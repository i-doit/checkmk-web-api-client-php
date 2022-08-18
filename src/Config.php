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

use \Exception;

/**
 * API configuration
 */
class Config {

    /**
     * Lowest allowed port number:
     */
    const PORT_MIN = 1;

    /**
     * Highest allowed port number
     */
    const PORT_MAX = 65535;

    /**
     * Standard HTTP port number
     */
    const HTTP_PORT = 80;

    /**
     * Standard HTTPS port number
     */
    const HTTPS_PORT = 443;

    /**
     * @var string
     */
    protected $url;

    /**
     * @var int|null
     */
    protected $port;

    /**
     * @var string
     */
    protected $username;

    /**
     * @var string
     */
    protected $secret;

    /**
     * @var bool Defaults to false
     */
    protected $proxyEnabled = false;

    /**
     * @var string
     */
    protected $proxyType;

    /**
     * @var string
     */
    protected $proxyHost;

    /**
     * @var int
     */
    protected $proxyPort;

    /**
     * @var string|null
     */
    protected $proxyUsername;

    /**
     * @var string|null
     */
    protected $proxyPassword;

    /**
     * @var bool Defaults to false
     */
    protected $bypassSecureConnection = false;

    /**
     * Set URL
     * @param string $url URL
     * @return self Returns itself
     * @throws Exception on error
     */
    public function setURL(string $url): self {
        $this->assertString('URL', $url);
        $this->url = $url;
        return $this;
    }

    /**
     * Get URL
     * @return string|null
     */
    public function getURL() {
        return $this->url;
    }

    /**
     * Set port number
     * @param int $port Port number
     * @return self Returns itself
     * @throws Exception on error
     */
    public function setPort(int $port): self {
        $this->assertPort('port', $port);
        $this->port = $port;
        return $this;
    }

    /**
     * Get port number
     * @return int|null
     */
    public function getPort() {
        return $this->port;
    }

    /**
     * Set username
     * @param string $username Username
     * @return self Returns itself
     * @throws Exception on error
     */
    public function setUsername(string $username): self {
        $this->assertString('username', $username);
        $this->username = $username;
        return $this;
    }

    /**
     * Get username
     * @return string|null
     */
    public function getUsername() {
        return $this->username;
    }

    /**
     * Set secret
     * @param string $secret Secret
     * @return self Returns itself
     * @throws Exception on error
     */
    public function setSecret(string $secret): self {
        $this->assertString('secret', $secret);
        $this->secret = $secret;
        return $this;
    }

    /**
     * Get secret
     * @return string|null
     */
    public function getSecret() {
        return $this->secret;
    }

    /**
     * Enable proxy settings
     * @return self Returns itself
     */
    public function enableProxy(): self {
        $this->proxyEnabled = true;
        return $this;
    }

    /**
     * Disable proxy settings
     * @return self Returns itself
     */
    public function disableProxy(): self {
        $this->proxyEnabled = false;
        return $this;
    }

    /**
     * Are proxy settings enabled?
     * @return bool Defaults to false
     */
    public function isProxyEnabled(): bool {
        return $this->proxyEnabled;
    }

    /**
     * Set proxy type to "HTTP"
     * @return self Returns itself
     */
    public function useHTTPProxy(): self {
        $this->proxyType = 'HTTP';
        return $this;
    }

    /**
     * Set to proxy type to "SOCKS5"
     * @return self Returns itself
     */
    public function useSOCKS5Proxy(): self {
        $this->proxyType = 'SOCKS5';
        return $this;
    }

    /**
     * Get proxy type
     * @return string|null
     */
    public function getProxyType() {
        return $this->proxyType;
    }

    /**
     * Set proxy host
     * @param string $host Hostname or IP address
     * @return self Returns itself
     * @throws Exception on error
     */
    public function setProxyHost(string $host): self {
        $this->assertString('proxy host', $host);
        $this->proxyHost = $host;
        return $this;
    }

    /**
     * Get proxy host
     * @return string|null
     */
    public function getProxyHost() {
        return $this->proxyHost;
    }

    /**
     * Set proxy port number
     * @param int $port Port number
     * @return self Returns itself
     * @throws Exception on error
     */
    public function setProxyPort(int $port): self {
        $this->assertPort('proxy port', $port);
        $this->proxyPort = $port;
        return $this;
    }

    /**
     * Get proxy port number
     * @return int|null
     */
    public function getProxyPort() {
        return $this->proxyPort;
    }

    /**
     * Set proxy username
     * @param string $username Username
     * @return self Returns itself
     * @throws Exception on error
     */
    public function setProxyUsername(string $username): self {
        $this->assertString('proxy username', $username);
        $this->proxyUsername = $username;
        return $this;
    }

    /**
     * Get proxy username
     * @return string|null
     */
    public function getProxyUsername() {
        return $this->proxyUsername;
    }

    /**
     * Set proxy password
     * @param string $password Password
     * @return self Returns itself
     * @throws Exception on error
     */
    public function setProxyPassword(string $password): self {
        $this->assertString('proxy password', $password);
        $this->proxyPassword = $password;
        return $this;
    }

    /**
     * Get proxy password
     * @return string|null
     */
    public function getProxyPassword() {
        return $this->proxyPassword;
    }

    /**
     * Bypass secure connection
     * @return self Returns itself
     */
    public function bypassSecureConnection(): self {
        $this->bypassSecureConnection = true;
        return $this;
    }

    /**
     * Is secure connection bypassed?
     * @return bool Defaults to false
     */
    public function isSecureConnectionBypassed(): bool {
        return $this->bypassSecureConnection;
    }

    /**
     * Run tests on configuration settings
     * @return self Returns itself
     * @throws Exception on any misconfigured setting
     */
    public function validate(): self {
        /**
         * Mandatory settings
         */

        $mandatorySettings = [
            'URL' => $this->url,
            'username' => $this->username,
            'secret' => $this->secret
        ];

        $this->assertIsMandatory($mandatorySettings);

        /**
         * URL
         */

        if (strpos($this->url, 'https://') === false &&
            strpos($this->url, 'http://') === false) {
            throw new Exception(sprintf(
                'Unsupported protocol in URL "%s"',
                $this->url
            ));
        }

        /**
         * Port
         */

        if (isset($this->port)) {
            $this->assertPort('port', $this->port);
        } elseif (strpos($this->url, 'https://') === 0) {
            $this->port = self::HTTPS_PORT;
        } elseif (strpos($this->url, 'http://') === 0) {
            $this->port = self::HTTP_PORT;
        }

        /**
         * Username
         */

        $this->assertString('username', $this->username);

        /**
         * Secret
         */

        $this->assertString('secret', $this->secret);

        /**
         * Proxy
         */

        if ($this->proxyEnabled) {
            $mandatorySettings = [
                'proxy type' => $this->proxyType,
                'proxy host' => $this->proxyHost,
                'proxy port' => $this->proxyPort
            ];

            $this->assertIsMandatory($mandatorySettings);

            $this->assertString('proxy host', $this->proxyHost);
            $this->assertPort('proxy port', $this->proxyPort);

            if (isset($this->proxyUsername)) {
                $this->assertString('proxy username', $this->username);
            }

            if (isset($this->proxyPassword)) {
                $this->assertString('proxy password', $this->proxyPassword);
            }
        }

        return $this;
    }

    /**
     * @param array $settings
     * @throws Exception on error
     */
    protected function assertIsMandatory(array $settings) {
        foreach ($settings as $key => $value) {
            if (!isset($value)) {
                throw new Exception(sprintf(
                    'Configuration setting "%s" is mandatory.',
                    $key
                ));
            }
        }
    }

    /**
     * @param string $key
     * @param string $value
     * @throws Exception on error
     */
    protected function assertString(string $key, string $value) {
        if (!is_string($value) || empty($value)) {
            throw new Exception(sprintf(
                'Configuration setting "%s" is invalid.',
                $key
            ));
        }
    }

    /**
     * @param string $key
     * @param int $value
     * @throws Exception on error
     */
    protected function assertPort(string $key, int $value) {
        if (!is_int($value) || $value < self::PORT_MIN || $value > self::PORT_MAX) {
            throw new Exception(sprintf(
                'Configuration setting "%s" is not a valid port number between %s and %s.',
                $key,
                self::PORT_MIN,
                self::PORT_MAX
            ));
        }
    }

}
