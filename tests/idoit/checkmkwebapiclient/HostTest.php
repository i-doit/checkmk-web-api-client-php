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

class HostTest extends BaseTest {

    /**
     * @var Host
     */
    protected $instance;

    /**
     * @throws Exception on error
     */
    public function setUp(): void {
        parent::setUp();

        $this->instance = new Host($this->api);
    }

    /**
     * @throws Exception on error
     */
    public function testGetExistingHost(): void {
        $hostname = $this->addHost();

        $result = $this->instance->get($hostname);

        $this->assertIsArray($result);
        $this->assertCount(3, $result);

        $this->assertArrayHasKey('attributes', $result);
        $this->assertIsArray($result['attributes']);

        $this->assertArrayHasKey('hostname', $result);
        $this->assertSame($hostname, $result['hostname']);

        $this->assertArrayHasKey('path', $result);
        $this->assertIsString($result['path']);
    }

    /**
     * @throws Exception on error
     */
    public function testGetNonExistingHost(): void {
        $this->expectException(Exception::class);
        $this->instance->get('This is not the host you are looking for');
    }

    /**
     * @throws Exception on error
     */
    public function testGetAll(): void {
        // At least we need one host:
        $this->addHost();

        $result = $this->instance->getAll();

        $this->assertIsArray($result);
        $this->assertNotCount(0, $result);

        foreach ($result as $hostname => $details) {
            $this->assertIsString($hostname);
            $this->assertIsArray($details);
            $this->assertCount(3, $details);

            $this->assertArrayHasKey('attributes', $details);
            $this->assertIsArray($details['attributes']);

            $this->assertArrayHasKey('hostname', $details);
            $this->assertSame($hostname, $details['hostname']);

            $this->assertArrayHasKey('path', $details);
            $this->assertIsString($details['path']);
        }
    }

    /**
     * @throws Exception on error
     */
    public function testAdd(): void {
        $hostname = $this->generateRandomString();

        $result = $this->instance->add($hostname);

        $this->assertInstanceOf(Host::class, $result);

        $host = $this->instance->get($hostname);

        $this->assertSame($hostname, $host['hostname']);
    }

    /**
     * @throws Exception on error
     */
    public function testAddWithAttributes(): void {
        $hostname = $this->generateRandomString();
        $ip = $this->generateIPv4Address();
        $alias = $this->generateRandomString();

        $result = $this->instance->add(
            $hostname,
            '',
            [
                'alias' => $alias,
                'ipaddress' => $ip
            ]
        );

        $this->assertInstanceOf(Host::class, $result);

        $host = $this->instance->get($hostname);

        $this->assertSame($hostname, $host['hostname']);
        $this->assertArrayHasKey('alias', $host['attributes']);
        $this->assertSame($alias, $host['attributes']['alias']);
        $this->assertArrayHasKey('ipaddress', $host['attributes']);
        $this->assertSame($ip, $host['attributes']['ipaddress']);
    }

    public function testAddWithExistingFolder(): void {
        // @todo Implement me! Create new folder!
    }

    /**
     * @throws Exception on error
     */
    public function testAddWithNonExistingFolder(): void {
        $this->expectException(Exception::class);
        $this->instance->add(
            $this->generateRandomString(),
            // This folder does not exist:
            $this->generateRandomString(),
            [],
            false
        );
    }

    /**
     * @throws Exception on error
     */
    public function testAddAutoCreateFolder(): void {
        $hostname = $this->generateRandomString();
        $folder = $this->generateRandomString();

        $result = $this->instance->add(
            $hostname,
            $folder,
            [],
            true
        );

        $this->assertInstanceOf(Host::class, $result);

        $host = $this->instance->get($hostname);

        $this->assertSame($hostname, $host['hostname']);
        $this->assertSame($folder, $host['path']);
    }

    /**
     * @throws Exception on error
     */
    public function testEditWithNewAttributes(): void {
        // Add "empty" host:
        $hostname = $this->addHost();

        $ip = $this->generateIPv4Address();
        $alias = $this->generateRandomString();

        $result = $this->instance->edit(
            $hostname,
            [
                'alias' => $alias,
                'ipaddress' => $ip
            ]
        );

        $this->assertInstanceOf(Host::class, $result);

        $host = $this->instance->get($hostname);

        $this->assertSame($hostname, $host['hostname']);
        $this->assertArrayHasKey('alias', $host['attributes']);
        $this->assertSame($alias, $host['attributes']['alias']);
        $this->assertArrayHasKey('ipaddress', $host['attributes']);
        $this->assertSame($ip, $host['attributes']['ipaddress']);
    }

    /**
     * @throws Exception on error
     */
    public function testEditExistingAttributes(): void {
        $hostname = $this->generateRandomString();
        $ip = $this->generateIPv4Address();
        $alias = $this->generateRandomString();

        $this->instance->add(
            $hostname,
            '',
            [
                'alias' => $alias,
                'ipaddress' => $ip
            ]
        );

        $updatedIP = $this->generateIPv4Address();
        $updatedAlias = $this->generateRandomString();

        $result = $this->instance->edit(
            $hostname,
            [
                'alias' => $updatedAlias,
                'ipaddress' => $updatedIP
            ]
        );

        $this->assertInstanceOf(Host::class, $result);

        $host = $this->instance->get($hostname);

        $this->assertSame($hostname, $host['hostname']);
        $this->assertArrayHasKey('alias', $host['attributes']);
        $this->assertSame($updatedAlias, $host['attributes']['alias']);
        $this->assertArrayHasKey('ipaddress', $host['attributes']);
        $this->assertSame($updatedIP, $host['attributes']['ipaddress']);
    }

    /**
     * @throws Exception on error
     */
    public function testEditResetAttributes(): void {
        $hostname = $this->generateRandomString();
        $ip = $this->generateIPv4Address();
        $alias = $this->generateRandomString();

        $this->instance->add(
            $hostname,
            '',
            [
                'alias' => $alias,
                'ipaddress' => $ip
            ]
        );

        $result = $this->instance->edit(
            $hostname,
            [
                'tag_criticality' => 'prod'
            ],
            [
                'ipaddress',
                'alias'
            ]
        );

        $this->assertInstanceOf(Host::class, $result);

        $host = $this->instance->get($hostname);

        $this->assertSame($hostname, $host['hostname']);
        $this->assertArrayHasKey('ipaddress', $host['attributes']);
        $this->assertEmpty($host['attributes']['ipaddress']);
        $this->assertArrayHasKey('alias', $host['attributes']);
        $this->assertEmpty($host['attributes']['alias']);
    }

    /**
     * @throws Exception on error
     */
    public function testEditNonExistingHost(): void {
        $this->expectException(Exception::class);
        $this->instance->edit(
            $this->generateRandomString(),
            [
                'ipaddress' => $this->generateIPv4Address()
            ]
        );
    }

    /**
     * @throws Exception on error
     */
    public function testDelete(): void {
        $hostname = $this->addHost();

        $result = $this->instance->delete($hostname);

        $this->assertInstanceOf(Host::class, $result);
    }

    /**
     * @throws Exception on error
     */
    public function testDeleteWithRetry(): void {
        $hostname = $this->addHost();

        $result = $this->instance->delete($hostname);

        $this->assertInstanceOf(Host::class, $result);

        $this->expectException(Exception::class);
        $this->instance->get($hostname);
    }

    /**
     * @throws Exception on error
     */
    public function testDeleteNonExistingHost(): void {
        $this->expectException(Exception::class);
        $this->instance->delete($this->generateRandomString());
    }

    public function testDiscoverServices(): void {
        // @todo This is hardly to test, because host must exist
//        $hostname = $this->addHost();
//
//        $result = $this->instance->discoverServices($hostname);
//
//        $this->assertIsString($result);
//        $this->assertNotEmpty($result);
    }

    public function testDiscoverServicesWithModes(): void {
        // @todo This is hardly to test, because hosts must exist
//        $modes = [
//            'new', 'remove', 'fixall', 'refresh'
//        ];
//
//        foreach ($modes as $mode) {
//            $hostname = $this->addHost();
//
//            $result = $this->instance->discoverServices($hostname, $mode);
//
//            $this->assertIsString($result);
//            $this->assertNotEmpty($result);
//        }
    }

    /**
     * @throws Exception on error
     */
    public function testDiscoverServicesForNonExistingHost(): void {
        $this->expectException(Exception::class);
        $this->instance->discoverServices($this->generateRandomString());
    }

}
