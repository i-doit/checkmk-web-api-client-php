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
use PHPUnit\Framework\TestCase;
use Symfony\Component\Dotenv\Dotenv;

abstract class BaseTest extends TestCase {

    /**
     * @var API
     */
    protected $api;

    /**
     * Makes API available
     * @throws Exception on error
     */
    public function setUp(): void {
        (new Dotenv())
            ->usePutenv(true)
            ->load(__DIR__ . '/../../../.env');

        $config = new Config();

        if (is_string(getenv('URL'))) {
            $config->setURL(getenv('URL'));
        }

        if (is_string(getenv('PORT'))) {
            $config->setPort((int) getenv('PORT'));
        }

        if (is_string(getenv('USERNAME'))) {
            $config->setUsername(getenv('USERNAME'));
        }

        if (is_string(getenv('SECRET'))) {
            $config->setSecret(getenv('SECRET'));
        }

        $this->api = new API($config);
    }

    /**
     * Creates a new host with random hostname
     * @return string Hostname
     * @throws Exception on error
     */
    protected function addHost(): string {
        $hostname = $this->generateRandomString();
        $host = new Host($this->api);
        $host->add($hostname);
        return $hostname;
    }

    /**
     * Creates a new user with a random username
     * @return string Username
     * @throws Exception on error
     */
    protected function addUser(): string {
        $username = $this->generateRandomString();
        $user = new User($this->api);
        $user->add($username, [
            'alias' => 'Alias '.$username
        ]);
        return $username;
    }

    /**
     * Generates random string
     * @return string
     */
    protected function generateRandomString(): string {
        return hash('sha256', (string) microtime(true));
    }

    /**
     * Generates random email address
     * @return string
     */
    protected function generateRandomEmail(): string {
        return $this->generateRandomString() . "@" . $this->generateRandomString() . ".com";
    }

    /**
     * Generates random IPv4 address
     * @return string
     */
    protected function generateIPv4Address(): string {
        return sprintf(
            '10.%s.%s.%s',
            mt_rand(2, 254),
            mt_rand(2, 254),
            mt_rand(2, 254)
        );
    }

}
