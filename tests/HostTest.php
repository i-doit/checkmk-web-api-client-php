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

use bheisig\checkmkwebapi\Host;

class HostTest extends BaseTest {

    /**
     * @var \bheisig\checkmkwebapi\Host
     */
    protected $instance;

    public function setUp () {
        parent::setUp();

        $this->instance = new Host($this->api);
    }

    public function testGetExistingHost() {
        $hostname = $this->addHost();

        $result = $this->instance->get($hostname);

        $this->assertInternalType('array', $result);
        $this->assertNotCount(0, $result);

        $this->assertInternalType('array', $result);
        $this->assertCount(3, $result);

        $this->assertArrayHasKey('attributes', $result);
        $this->assertInternalType('array',$result['attributes']);

        $this->assertArrayHasKey('hostname', $result);
        $this->assertEquals($hostname, $result['hostname']);

        $this->assertArrayHasKey('path', $result);
        $this->assertInternalType('string', $result['path']);
    }

    /**
     * @expectedException \Exception
     */
    public function testGetNonExistingHost() {
        $this->instance->get('This is not the host you are looking for');
    }

    public function testGetAll() {
        // We need at minimum one host:
        $this->addHost();

        $result = $this->instance->getAll();

        $this->assertInternalType('array', $result);
        $this->assertNotCount(0, $result);

        foreach ($result as $hostname => $details) {
            $this->assertInternalType('string', $hostname);
            $this->assertInternalType('array', $details);
            $this->assertCount(3, $details);

            $this->assertArrayHasKey('attributes', $details);
            $this->assertInternalType('array',$details['attributes']);

            $this->assertArrayHasKey('hostname', $details);
            $this->assertEquals($hostname, $details['hostname']);

            $this->assertArrayHasKey('path', $details);
            $this->assertInternalType('string', $details['path']);
        }
    }

    public function testAdd() {
        $hostname = $this->generateRandomString();

        $result = $this->instance->add($hostname);

        $this->assertInstanceOf(Host::class, $result);

        $host = $this->instance->get($hostname);

        $this->assertEquals($hostname, $host['hostname']);
    }

    public function testAddWithAttributes() {
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

        $this->assertEquals($hostname, $host['hostname']);
        $this->assertArrayHasKey('alias', $host['attributes']);
        $this->assertEquals($alias, $host['attributes']['alias']);
        $this->assertArrayHasKey('ipaddress', $host['attributes']);
        $this->assertEquals($ip, $host['attributes']['ipaddress']);
    }

    public function testAddWithExistingFolder() {
        // @todo Implement me! Create new folder!
    }

    /**
     * @expectedException \Exception
     */
    public function testAddWithNonExistingFolder() {
        $this->instance->add(
            $this->generateRandomString(),
            // This folder does not exist:
            $this->generateRandomString(),
            [],
            false
        );
    }

    public function testAddAutoCreateFolder() {
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

        $this->assertEquals($hostname, $host['hostname']);
        $this->assertEquals($folder, $host['path']);
    }

    public function testEditWithNewAttributes() {
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

        $this->assertEquals($hostname, $host['hostname']);
        $this->assertArrayHasKey('alias', $host['attributes']);
        $this->assertEquals($alias, $host['attributes']['alias']);
        $this->assertArrayHasKey('ipaddress', $host['attributes']);
        $this->assertEquals($ip, $host['attributes']['ipaddress']);
    }

    public function testEditExistingAttributes() {
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

        $this->assertEquals($hostname, $host['hostname']);
        $this->assertArrayHasKey('alias', $host['attributes']);
        $this->assertEquals($updatedAlias, $host['attributes']['alias']);
        $this->assertArrayHasKey('ipaddress', $host['attributes']);
        $this->assertEquals($updatedIP, $host['attributes']['ipaddress']);
    }

    public function testEditResetAttributes() {
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

        $this->assertEquals($hostname, $host['hostname']);
        $this->assertArrayHasKey('ipaddress', $host['attributes']);
        $this->assertEmpty($host['attributes']['ipaddress']);
        $this->assertArrayHasKey('alias', $host['attributes']);
        $this->assertEmpty($host['attributes']['alias']);
    }

    /**
     * @expectedException \Exception
     */
    public function testEditNonExistingHost() {
        $this->instance->edit(
            $this->generateRandomString(),
            [
                'ipaddress' => $this->generateIPv4Address()
            ]
        );
    }

    public function testDelete() {
        $hostname = $this->addHost();

        $result = $this->instance->delete($hostname);

        $this->assertInstanceOf(Host::class, $result);
    }

    /**
     * @expectedException \Exception
     */
    public function testDeleteWithRetry() {
        $hostname = $this->addHost();

        $result = $this->instance->delete($hostname);

        $this->assertInstanceOf(Host::class, $result);

        $this->instance->get($hostname);
    }

    /**
     * @expectedException \Exception
     */
    public function testDeleteNonExistingHost() {
        $this->instance->delete($this->generateRandomString());
    }

    public function testDiscoverServices() {
        // @todo This is hardly to test, because host must exist
//        $hostname = $this->addHost();
//
//        $result = $this->instance->discoverServices($hostname);
//
//        $this->assertInternalType('string', $result);
//        $this->assertNotEmpty($result);
    }

    public function testDiscoverServicesWithModes() {
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
//            $this->assertInternalType('string', $result);
//            $this->assertNotEmpty($result);
//        }
    }

    /**
     * @expectedException \Exception
     */
    public function testDiscoverServicesForNonExistingHost() {
        $this->instance->discoverServices($this->generateRandomString());
    }

}
