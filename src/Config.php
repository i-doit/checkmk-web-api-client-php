<?php

/**
 * Copyright (C) 2018 Benjamin Heisig
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
 * @copyright Copyright (C) 2018 Benjamin Heisig
 * @license http://www.gnu.org/licenses/agpl-3.0 GNU Affero General Public License (AGPL)
 * @link https://github.com/bheisig/checkmkwebapi
 */

namespace bheisig\checkmkwebapi;

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
     * @var int
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
     * @var string
     */
    protected $proxyUsername;

    /**
     * @var string
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
     * @throws \Exception on error
     */
    public function setURL($url) {
        $this->assertString('URL', $url);
        $this->url = $url;
        return $this;
    }

    /**
     * Get URL
     * @return string
     */
    public function getURL() {
        return $this->url;
    }

    /**
     * Set port number
     * @param int $port Port number
     * @return self Returns itself
     * @throws \Exception on error
     */
    public function setPort($port) {
        $this->assertPort('port', $port);
        $this->port = $port;
        return $this;
    }

    /**
     * Get port number
     * @return int
     */
    public function getPort() {
        return $this->port;
    }

    /**
     * Set username
     * @param string $username Username
     * @return self Returns itself
     * @throws \Exception on error
     */
    public function setUsername($username) {
        $this->assertString('username', $username);
        $this->username = $username;
        return $this;
    }

    /**
     * Get username
     * @return string
     */
    public function getUsername() {
        return $this->username;
    }

    /**
     * Set secret
     * @param string $secret Secret
     * @return self Returns itself
     * @throws \Exception on error
     */
    public function setSecret($secret) {
        $this->assertString('secret', $secret);
        $this->secret = $secret;
        return $this;
    }

    /**
     * Get secret
     * @return string
     */
    public function getSecret() {
        return $this->secret;
    }

    /**
     * Enable proxy settings
     * @return self
     */
    public function enableProxy() {
        $this->proxyEnabled = true;
        return $this;
    }

    /**
     * Disable proxy settings
     * @return self
     */
    public function disableProxy() {
        $this->proxyEnabled = false;
        return $this;
    }

    /**
     * Are proxy settings enabled?
     * @return bool Defaults to false
     */
    public function isProxyEnabled() {
        return $this->proxyEnabled;
    }

    /**
     * Set proxy type to "HTTP"
     * @return self Returns itself
     */
    public function useHTTPProxy() {
        $this->proxyType = 'HTTP';
        return $this;
    }

    /**
     * Set to proxy type to "SOCKS5"
     * @return self Returns itself
     */
    public function useSOCKS5Proxy() {
        $this->proxyType = 'SOCKS5';
        return $this;
    }

    /**
     * Get proxy type
     * @return string
     */
    public function getProxyType() {
        return $this->proxyType;
    }

    /**
     * Set proxy host
     * @param string $host Hostname or IP address
     * @return self Returns itself
     * @throws \Exception on error
     */
    public function setProxyHost($host) {
        $this->assertString('proxy host', $host);
        $this->proxyHost = $host;
        return $this;
    }

    /**
     * Get proxy host
     * @return string
     */
    public function getProxyHost() {
        return $this->proxyHost;
    }

    /**
     * Set proxy port number
     * @param int $port Port number
     * @return self Returns itself
     * @throws \Exception on error
     */
    public function setProxyPort($port) {
        $this->assertPort('proxy port', $port);
        $this->proxyPort = $port;
        return $this;
    }

    /**
     * Get proxy port number
     * @return int
     */
    public function getProxyPort() {
        return $this->proxyPort;
    }

    /**
     * Set proxy username
     * @param string $username Username
     * @return self Returns itself
     * @throws \Exception on error
     */
    public function setProxyUsername($username) {
        $this->assertString('proxy username', $username);
        $this->proxyUsername = $username;
        return $this;
    }

    /**
     * Get proxy username
     * @return string
     */
    public function getProxyUsername() {
        return $this->proxyUsername;
    }

    /**
     * Set proxy password
     * @param string $password Password
     * @return self Returns itself
     * @throws \Exception on error
     */
    public function setProxyPassword($password) {
        $this->assertString('proxy password', $password);
        $this->proxyPassword = $password;
        return $this;
    }

    /**
     * Get proxy password
     * @return string
     */
    public function getProxyPassword() {
        return $this->proxyPassword;
    }

    /**
     * Bypass secure connection
     * @return self Returns itself
     */
    public function bypassSecureConnection() {
        $this->bypassSecureConnection = true;
        return $this;
    }

    /**
     * Is secure connection bypassed?
     * @return bool Defaults to false
     */
    public function isSecureConnectionBypassed() {
        return $this->bypassSecureConnection;
    }

    /**
     * Run tests on configuration settings
     * @return self Returns itself
     * @throws \Exception on any misconfigured setting
     */
    public function validate() {
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
            throw new \Exception(sprintf(
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
     * @throws \Exception on error
     */
    protected function assertIsMandatory(array $settings) {
        foreach ($settings as $key => $value) {
            if (!isset($value)) {
                throw new \Exception(sprintf(
                    'Configuration setting "%s" is mandatory.',
                    $key
                ));
            }
        }
    }

    /**
     * @param string $key
     * @param string $value
     * @throws \Exception on error
     */
    protected function assertString($key, $value) {
        if (!is_string($value) || empty($value)) {
            throw new \Exception(sprintf(
                'Configuration setting "%s" is invalid.',
                $key
            ));
        }
    }

    /**
     * @param string $key
     * @param int $value
     * @throws \Exception on error
     */
    protected function assertPort($key, $value) {
        if (!is_int($value) || $value < self::PORT_MIN || $value > self::PORT_MAX) {
            throw new \Exception(sprintf(
                'Configuration setting "%s" is not a valid port number between %s and %s.',
                $key,
                self::PORT_MIN,
                self::PORT_MAX
            ));
        }
    }

}
