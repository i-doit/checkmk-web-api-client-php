<?php

/**
 * Copyright (C) 2022 synetics GmbH
 * Copyright (C) 2018-2022 Benjamin Heisig
 *
 * This program is free software: you can redistribute it and/or modify
 * it under the terms of the GNU Affero General Public License as published by
 * the Free Software Foundation, either version 3 of the License, or
 * (at your option) any later version.
 *
 * This program is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE. See the
 * GNU Affero General Public License for more details.
 *
 * You should have received a copy of the GNU Affero General Public License
 * along with this program. If not, see <http://www.gnu.org/licenses/>.
 *
 * @author Benjamin Heisig <https://benjamin.heisig.name/>
 * @copyright Copyright (C) 2022 synetics GmbH
 * @copyright Copyright (C) 2018-2022 Benjamin Heisig
 * @license http://www.gnu.org/licenses/agpl-3.0 GNU Affero General Public License (AGPL)
 * @link https://github.com/i-doit/checkmk-web-api-client-php
 */

declare(strict_types=1);

namespace Idoit\CheckmkWebAPIClient;

use CurlHandle;
use \Exception;
use \RuntimeException;

/**
 * API client
 */
class API {

    /**
     * @var Config Configuration settings
     */
    protected $config;

    /**
     * @var CurlHandle|resource|false|null
     */
    protected $resource;

    /**
     * @var array Information about last client request (associative array)
     */
    protected $lastInfo = [];

    /**
     * @var string HTTP headers of last server response (multi-line string)
     */
    protected $lastResponseHeaders;

    /**
     * @var array Response for last request (associative array)
     */
    protected $lastResponse;

    /**
     * @var array Last request content (multi-dimensional associative array)
     */
    protected $lastRequestContent;

    /**
     * @var array cURL options (associative array)
     */
    protected $options = [];

    /**
     * @var int Counter for requests
     */
    protected $counter = 0;

    /**
     * Constructor
     * @param Config $config Configuration settings
     * @throws Exception on configuration errors
     */
    public function __construct(Config $config) {
        $this->config = $config;

        $this->config->validate();

        $this->options = [
            CURLOPT_FAILONERROR => true,
            // Follow (only) 301s and 302s:
            CURLOPT_FOLLOWLOCATION => true,
            CURLOPT_POSTREDIR => (1 | 2),
            CURLOPT_FRESH_CONNECT => true,
            CURLOPT_HEADER => true,
            CURLINFO_HEADER_OUT => true,
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_PORT => $this->config->getPort(),
            CURLOPT_REDIR_PROTOCOLS => (CURLPROTO_HTTP | CURLPROTO_HTTPS),
            CURLOPT_ENCODING => 'application/json',
            CURLOPT_USERAGENT => $this->getUserAgent(),
// @todo "Content-Type: application/json" returns an HTTP 501 NOT IMPLEMENTED.
//            CURLOPT_HTTPHEADER => ['Content-Type: application/json'],
            // In seconds:
            CURLOPT_CONNECTTIMEOUT => 10
        ];
    }

    /**
     * Get user agent string
     * @return string
     */
    protected function getUserAgent(): string {
        $userAgent = 'idoit/checkmkwebapiclient';

        $composerFile = __DIR__ . '/../composer.json';

        if (is_readable($composerFile)) {
            $composerFileContent = file_get_contents($composerFile);

            if (is_string($composerFileContent)) {
                $composer = json_decode($composerFileContent, true);
                $userAgent = $composer['name'] . ' ' . $composer['extra']['version'];
            }
        }

        return $userAgent;
    }

    /**
     * Is client connected to API?
     * @return bool
     */
    public function isConnected(): bool {
        return is_resource($this->resource) || $this->resource instanceof CurlHandle;
    }

    /**
     * Connect to API
     *
     * This method is optional and may be used for re-connects.
     * @return self Returns itself
     * @throws Exception on error
     */
    public function connect(): self {
        $this->resource = curl_init();

        if ($this->config->isProxyEnabled()) {
            $this->options[CURLOPT_PROXY] = $this->config->getProxyHost();
            $this->options[CURLOPT_PROXYPORT] = $this->config->getProxyPort();

            if ($this->config->getProxyUsername()) {
                $this->options[CURLOPT_PROXYUSERPWD] = $this->config->getProxyUsername() .
                    ':' . $this->config->getProxyPassword();
            }

            switch ($this->config->getProxyType()) {
                case 'HTTP':
                    $this->options[CURLOPT_PROXYTYPE] = CURLPROXY_HTTP;
                    break;
                case 'SOCKS5':
                    $this->options[CURLOPT_PROXYTYPE] = CURLPROXY_SOCKS5;
                    break;
                default:
                    throw new Exception(sprintf('Unknown proxy type "%s"', $this->config->getProxyType()));
            }
        }

        if ($this->config->isSecureConnectionBypassed() === true) {
            $this->options[CURLOPT_SSL_VERIFYPEER] = false;
            $this->options[CURLOPT_SSL_VERIFYHOST] = 0;
            $this->options[CURLOPT_SSLVERSION] = CURL_SSLVERSION_DEFAULT;
        } else {
            $this->options[CURLOPT_SSL_VERIFYPEER] = true;
            $this->options[CURLOPT_SSL_VERIFYHOST] = 2;
            $this->options[CURLOPT_SSLVERSION] = CURL_SSLVERSION_TLSv1_2;
        }

        return $this;
    }

    /**
     * Disconnect from API
     *
     * This method is optional and may be used for reconnects.
     * @return self Returns itself
     * @throws Exception on error
     */
    public function disconnect(): self {
        if ($this->isConnected() === false) {
            throw new Exception('There is no connection.');
        }

        curl_close($this->resource);

        return $this;
    }

    /**
     * How many requests were already send?
     * @return int Positive integer
     */
    public function countRequests(): int {
        return $this->counter;
    }

    /**
     * Send request to API
     * @param string $action Action
     * @param array $data Optional POST payload
     * @param array $params Optional additional GET parameters
     * @param string $entryPoint Entry point; defaults to "webapi.py"
     * @return mixed Result of request
     * @throws Exception on error
     */
    public function request(string $action, array $data = [], array $params = [], string $entryPoint = 'webapi.py') {
        $params['action'] = $action;
        $params['_username'] = $this->config->getUsername();
        $params['_secret'] = $this->config->getSecret();
// @todo GET parameter "request_format=json" resulted in an error:
//        $params['request_format'] = 'json';

        if (!array_key_exists('output_format', $params)) {
            $params['output_format'] = 'json';
        }

        $url = $this->config->getURL();

        if (!is_string($url)) {
            throw new Exception('Base URL to Checkmk not set');
        }

        // Combine base URL with entry point:
        if (substr($url, -1) !== '/' &&
            substr($entryPoint, 0, 1) !== '/') {
            $url .= '/' . $entryPoint;
        } elseif (substr($url, -1) !== '/' &&
            substr($entryPoint, 0, 1) === '/') {
            $url .= $entryPoint;
        } elseif (substr($url, -1) === '/' &&
            substr($entryPoint, 0, 1) !== '/') {
            $url .= $entryPoint;
        } else {
            $url .= substr($entryPoint, 0, -1);
        }

        $this->options[CURLOPT_URL] = sprintf(
            '%s?%s',
            $url,
            http_build_query($params)
        );

        $response = $this->execute($data);

        $this->evaluateResponse($response);

        $this->counter++;

        return $response['result'];
    }

    /**
     * Send request to API with headers and receives response
     * @param array $data Payload
     * @return array Result of request
     * @throws Exception on error
     */
    protected function execute(array $data): array {
        // Auto-connect:
        if ($this->isConnected() === false) {
            $this->connect();
        }

        $options = $this->options;

        $this->lastRequestContent = $data;

        $options[CURLOPT_POSTFIELDS] = null;
        $options[CURLOPT_CUSTOMREQUEST] = 'GET';

        if (count($data) > 0) {
            $dataAsString = 'request=' . json_encode($data);

            $options[CURLOPT_POSTFIELDS] = $dataAsString;
            $options[CURLOPT_CUSTOMREQUEST] = 'POST';
        }

        curl_setopt_array($this->resource, $options);

        $responseString = curl_exec($this->resource);

        $this->lastInfo = curl_getinfo($this->resource);

        if ($responseString === false) {
            switch ($this->lastInfo['http_code']) {
                case 0:
                    $message = curl_error($this->resource);

                    if (strlen($message) === 0) {
                        $message = 'Connection to Web server failed';
                    }

                    throw new Exception($message);
                default:
                    throw new Exception(sprintf(
                        'Web server responded with HTTP status code "%s"',
                        $this->lastInfo['http_code']
                    ));
            }
        } elseif (!is_string($responseString)) {
            throw new RuntimeException('No content from Web server');
        }

        $headerLength = curl_getinfo($this->resource, CURLINFO_HEADER_SIZE);
        $this->lastResponseHeaders = substr($responseString, 0, $headerLength);

        $body = substr($responseString, $headerLength);

        $lastResponse = json_decode(trim($body), true);

        // Try to parse this creepy "python output format"…
        if (!is_array($lastResponse)) {
            $lastResponse = Python::decode($body);

            if (!is_array($lastResponse) && strpos("'result_code': 0", $body) !== false) {
                throw new Exception(sprintf(
                    'Unable to parse this response from Checkmk: %s',
                    $body
                ));
            }
        }

        if (!is_array($lastResponse)) {
            if (is_string($body) && strlen($body) > 0) {
                throw new Exception(sprintf(
                    'Checkmk responded with an error message: %s',
                    $body
                ));
            } else {
                throw new Exception('Checkmk responded with an invalid JSON string.');
            }
        }

        $this->lastResponse = $lastResponse;

        return $this->lastResponse;
    }

    /**
     * Evaluate server response
     * @param array $response Server response
     * @return self Returns itself
     * @throws Exception on error
     */
    protected function evaluateResponse(array $response): self {
        $requiredKeys = ['result', 'result_code'];

        foreach ($requiredKeys as $requiredKey) {
            if (!array_key_exists($requiredKey, $response)) {
                throw new Exception(sprintf(
                    'Response has no %s',
                    $requiredKey
                ));
            }
        }

        if (!is_int($response['result_code'])) {
            throw new Exception('result_code is not an integer');
        }

        switch ($response['result_code']) {
            case 0:
                // Everything is fine.
                break;
            case 1:
                if (is_string($response['result']) && strlen($response['result']) !== 0) {
                    throw new Exception(sprintf(
                        'Checkmk responded with an error message: %s',
                        $response['result']
                    ));
                } else {
                    throw new Exception('Checkmk responded with an unspecific error');
                }
            default:
                throw new Exception(sprintf(
                    'Unknown result code: %s',
                    $response['result_code']
                ));
        }

        return $this;
    }

    /**
     * Get information about last client request
     *
     * These information may be very useful for debugging.
     * @return array Associative array
     */
    public function getLastInfo(): array {
        return $this->lastInfo;
    }

    /**
     * Get HTTP headers of last client request
     *
     * These headers may be very useful for debugging.
     * @return string Multi-line string
     */
    public function getLastRequestHeaders(): string {
        if (array_key_exists('request_header', $this->lastInfo)) {
            return $this->lastInfo['request_header'];
        }

        return '';
    }

    /**
     * Get HTTP headers of last server response
     *
     * These headers may be very useful for debugging.
     * @return string Multi-line string
     */
    public function getLastResponseHeaders(): string {
        return $this->lastResponseHeaders;
    }

    /**
     * Get last server response
     * @return array Associative array
     */
    public function getLastResponse(): array {
        return $this->lastResponse;
    }

    /**
     * Get last request content
     *
     * This is the last content which was sent as a request. This may be very useful for debugging.
     * @return array Multi-dimensional associative array
     */
    public function getLastRequestContent(): array {
        return $this->lastRequestContent;
    }

}
