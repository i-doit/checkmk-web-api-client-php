<?php

/**
 * Copyright (C) 2017 Benjamin Heisig
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
 * @copyright Copyright (C) 2017 Benjamin Heisig
 * @license http://www.gnu.org/licenses/agpl-3.0 GNU Affero General Public License (AGPL)
 * @link https://github.com/bheisig/check_mk-web-api
 */

namespace bheisig\checkmkwebapi;

/**
 * API client
 */
class API {

    /**
     * Configuration settings
     *
     * @var \bheisig\checkmkwebapi\Config
     */
    protected $config;

    /**
     * cURL resource
     *
     * @var resource
     */
    protected $resource;

    /**
     * Information about last client request
     *
     * @var array Associative array
     */
    protected $lastInfo = [];

    /**
     * HTTP headers of last server response
     *
     * @var string Multi-line string
     */
    protected $lastResponseHeaders;

    /**
     * Response for last request
     *
     * @var array Associative array
     */
    protected $lastResponse;

    /**
     * Last request content
     *
     * @var array Multi-dimensional associative array
     */
    protected $lastRequestContent;

    /**
     * cURL options
     *
     * @var array Associative array
     */
    protected $options = [];

    /**
     * Information about this project
     *
     * @var array
     */
    protected $project = [];

    /**
     * Counter for requests
     *
     * @var int
     */
    protected $counter = 0;

    /**
     * Constructor
     *
     * @param \bheisig\checkmkwebapi\Config $config Configuration settings
     *
     * @throws \Exception on configuration errors
     */
    public function __construct(Config $config) {
        $this->config = $config;

        $this->config->validate();

        $projectFile = __DIR__ . '/../project.json';

        if (is_readable($projectFile)) {
            $this->project = json_decode(file_get_contents($projectFile), true);
        }

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
            CURLOPT_USERAGENT => $this->project['tag'] . ' ' . $this->project['version'],
// @todo "Content-Type: application/json" returns an HTTP 501 NOT IMPLEMENTED.
//            CURLOPT_HTTPHEADER => ['Content-Type: application/json'],
            CURLOPT_SSL_VERIFYPEER => true,
            CURLOPT_SSL_VERIFYHOST => 2,
            // TLS 1.2:
            CURLOPT_SSLVERSION => 6,
            // In seconds:
            CURLOPT_CONNECTTIMEOUT => 10
        ];
    }

    /**
     * Is client connected to API?
     *
     * @return bool
     */
    public function isConnected() {
        return is_resource($this->resource);
    }

    /**
     * Connect to API
     *
     * This method is optional and may be used for re-connects.
     *
     * @return self Returns itself
     *
     * @throws \Exception on error
     */
    public function connect() {
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
                    throw new \Exception(sprintf('Unknown proxy type "%s"', $this->config->getProxyType()));
            }
        }

        return $this;
    }

    /**
     * Disconnect from API
     *
     * This method is optional and may be used for reconnects.
     *
     * @return self Returns itself
     *
     * @throws \Exception on error
     */
    public function disconnect() {
        // Auto-connect:
        if ($this->isConnected() === false) {
            throw new \Exception('There is no connection.');
        }

        curl_close($this->resource);

        $this->resource = null;

        return $this;
    }

    /**
     * How many requests were already send?
     *
     * @return int Positive integer
     */
    public function countRequests() {
        return $this->counter;
    }

    /**
     * Send request to API
     *
     * @param string $action Action
     * @param array $data Optional POST payload
     * @param array $params Optional additional GET parameters
     *
     * @return mixed Result of request
     *
     * @throws \Exception on error
     */
    public function request($action, array $data = [], array $params = []) {
        $params['action'] = $action;
        $params['_username'] = $this->config->getUsername();
        $params['_secret'] = $this->config->getSecret();
// @todo GET parameter "request_format=json" resulted in an error:
//        $params['request_format'] = 'json';

        if (!array_key_exists('output_format', $params)) {
            $params['output_format'] = 'json';
        }

        $this->options[CURLOPT_URL] = sprintf(
            '%s?%s',
            $this->config->getURL(),
            http_build_query($params)
        );

        $response = $this->execute($data);

        $this->evaluateResponse($response);

        $this->counter++;

        return $response['result'];
    }

    /**
     * Send request to API with headers and receives response
     *
     * @param array $data Payload
     *
     * @return array Result of request
     *
     * @throws \Exception on error
     */
    protected function execute(array $data) {
        // Auto-connect:
        if ($this->isConnected() === false) {
            $this->connect();
        }

        $options = $this->options;

        $this->lastRequestContent = $data;

        if (count($data) > 0) {
            $dataAsString = 'request=' . json_encode($data);

            $options[CURLOPT_POSTFIELDS] = $dataAsString;

            $options[CURLOPT_CUSTOMREQUEST] = 'POST';
        } else {
            $options[CURLOPT_CUSTOMREQUEST] = 'GET';
        }

        curl_setopt_array($this->resource, $options);

        $responseString = curl_exec($this->resource);

        $this->lastInfo = curl_getinfo($this->resource);

        if ($responseString === false) {
            switch($this->lastInfo['http_code']) {
                case 0:
                    $message = curl_error($this->resource);

                    if (strlen($message) === 0) {
                        $message = 'Connection to Web server failed';
                    }

                    throw new \Exception($message);
                default:
                    throw new \Exception(sprintf(
                        'Web server responded with HTTP status code "%s"',
                        $this->lastInfo['http_code']
                    ));
            }
        }

        $responseLines = explode(PHP_EOL, $responseString);

        // Remove last line without content:
        if (strlen(end($responseLines)) < 2) {
            $responseLines = array_slice(
                $responseLines,
                0,
                (count($responseLines) - 1)
            );
        }

        $this->lastResponseHeaders = implode(PHP_EOL, array_slice($responseLines, 0, -1));

        $this->lastResponse = json_decode(end($responseLines), true);

        // Try to parse this creepy "python output format"…
        if (!is_array($this->lastResponse)) {
            $this->lastResponse = $this->convertPythonToJSON(end($responseLines));
        }

        if (!is_array($this->lastResponse)) {
            $message = end($responseLines);

            if (is_string($message) && strlen($message) > 0) {
                throw new \Exception(sprintf(
                    'Check_MK responded with an error message: %s',
                    $message
                ));
            } else {
                throw new \Exception('Check_MK responded with an invalid JSON string.');
            }
        }

        return $this->lastResponse;
    }

    /**
     * Convert python syntax into a JSON object
     *
     * @param string $python Python foo
     *
     * @return array|null Result as array, otherwise null
     */
    protected function convertPythonToJSON($python) {
        $python = str_replace(
            ['\'', 'True', 'False', 'None', '": u"'],
            ['"', 'true', 'false', 'null', '": "'],
            $python
        );

        // Convert nested tupels ((1, 2), (3, 4)) into arrays:
        $python = preg_replace(
            '/\(\((.+), (.+)\), \((.+), (.+)\)\)/',
            '[[$1, $2], [$3, $4]]',
            $python
        );

        // Convert tupels (1, 2) into arrays:
        $python = preg_replace(
            '/\((.+), (.+)\)/',
            '[$1, $2]',
            $python
        );

        return json_decode(
            $python,
            true
        );
    }

    /**
     * Evaluate server response
     *
     * @param array $response Server response
     *
     * @return self Returns itself
     *
     * @throws \Exception on error
     */
    protected function evaluateResponse(array $response) {
        $requiredKeys = ['result', 'result_code'];

        foreach ($requiredKeys as $requiredKey) {
            if (!array_key_exists($requiredKey, $response)) {
                throw new \Exception(sprintf(
                    'Response has no %s',
                    $requiredKey
                ));
            }
        }

        if (!is_int($response['result_code'])) {
            throw new \Exception('result_code is not an integer');
        }

        switch ($response['result_code']) {
            case 0:
                // Everything is fine.
                break;
            case 1:
                if (is_string($response['result']) && strlen($response['result']) !== 0) {
                    throw new \Exception(sprintf(
                        'Check_MK responded with an error message: %s',
                        $response['result']
                    ));
                } else {
                    throw new \Exception('Check_MK responded with an unspecific error');
                }
            default:
                throw new \Exception(sprintf(
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
     *
     * @return array Associative array
     */
    public function getLastInfo() {
        return $this->lastInfo;
    }

    /**
     * Get HTTP headers of last client request
     *
     * These headers may be very useful for debugging.
     *
     * @return string Multi-line string
     */
    public function getLastRequestHeaders() {
        if (array_key_exists('request_header', $this->lastInfo)) {
            return $this->lastInfo['request_header'];
        }

        return '';
    }

    /**
     * Get HTTP headers of last server response
     *
     * These headers may be very useful for debugging.
     *
     * @return string Multi-line string
     */
    public function getLastResponseHeaders() {
        return $this->lastResponseHeaders;
    }

    /**
     * Get last server response
     *
     * @return array Associative array
     */
    public function getLastResponse() {
        return $this->lastResponse;
    }

    /**
     * Get last request content
     *
     * This is the last content which was sent as a request. This may be very useful for debugging.
     *
     * @return array Multi-dimensional associative array
     */
    public function getLastRequestContent() {
        return $this->lastRequestContent;
    }

}
