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

declare(strict_types=1);

namespace bheisig\checkmkwebapi\tests;

use bheisig\checkmkwebapi\API;

class APITest extends BaseTest {

    /**
     * @throws \Exception
     */
    public function testConnect() {
        $this->assertInstanceOf(API::class, $this->api->connect());
    }

    /**
     * @throws \Exception
     */
    public function testDisconnect() {
        $this->api->connect();

        $this->assertInstanceOf(API::class, $this->api->disconnect());
    }

    /**
     * @throws \Exception
     */
    public function testIsConnected() {
        $this->assertFalse($this->api->isConnected());

        $this->api->connect();

        $this->assertTrue($this->api->isConnected());

        $this->api->disconnect();

        $this->assertFalse($this->api->isConnected());
    }

    /**
     * @throws \Exception
     */
    public function testCountRequests() {
        $this->api->request('get_all_hosts');

        $count = $this->api->countRequests();

        $this->assertInternalType('integer', $count);
        $this->assertEquals(1, $count);

        $this->api->request('get_all_hosts');

        $count = $this->api->countRequests();

        $this->assertInternalType('integer', $count);
        $this->assertEquals(2, $count);
    }

    /**
     * @throws \Exception
     */
    public function testRequest() {
        $result = $this->api->request('get_all_hosts');

        $this->assertInternalType('array', $result);
        $this->assertNotCount(0, $result);
    }

    /**
     * @throws \Exception
     */
    public function testGetLastInfo() {
        $this->api->request('get_all_hosts');

        $this->assertInternalType('array', $this->api->getLastInfo());
        $this->assertNotCount(0, $this->api->getLastInfo());
    }

    /**
     * @throws \Exception
     */
    public function testGetLastResponse() {
        $this->api->request('get_all_hosts');

        $this->assertInternalType('array', $this->api->getLastResponse());
        $this->assertNotCount(0, $this->api->getLastResponse());
    }

    /**
     * @throws \Exception
     */
    public function testGetLastRequestContent() {
        // This produces an empty array (see below):
        $this->api->request('get_all_hosts');

        $this->assertInternalType('array', $this->api->getLastRequestContent());
        $this->assertCount(0, $this->api->getLastRequestContent());
    }

    /**
     * @throws \Exception
     */
    public function testGetLastResponseHeaders() {
        $this->api->request('get_all_hosts');

        $this->assertInternalType('string', $this->api->getLastResponseHeaders());
        $this->assertNotEmpty($this->api->getLastResponseHeaders());
    }

    /**
     * @throws \Exception
     */
    public function testGetLastRequestHeaders() {
        $this->api->request('get_all_hosts');

        $this->assertInternalType('string', $this->api->getLastRequestHeaders());
        $this->assertNotEmpty($this->api->getLastRequestHeaders());
    }

}
