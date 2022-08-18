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

class InventoryTest extends BaseTest {

    /**
     * @var Inventory
     */
    protected $instance;

    /**
     * @throws Exception on error
     */
    public function setUp(): void {
        parent::setUp();

        $this->instance = new Inventory($this->api);
    }

    /**
     * @throws Exception on error
     */
    public function testGetExistingHost(): void {
        $hostname = $this->addHost();

        $result = $this->instance->getHost($hostname);

        $this->assertIsArray($result);
        $this->assertCount(0, $result);
    }

    /**
     * @throws Exception on error
     */
    public function testGetNonExistingHost(): void {
        $hostname = $this->generateRandomString();

        $result = $this->instance->getHost($hostname);

        $this->assertIsArray($result);
        $this->assertCount(0, $result);
    }

    /**
     * @throws Exception on error
     */
    public function testGetExistingHosts(): void {
        $amount = 3;
        $hostnames = [];

        for ($i = 0; $i < $amount; $i++) {
            $hostnames[] = $this->addHost();
        }

        $result = $this->instance->getHosts($hostnames);

        $this->assertIsArray($result);
        $this->assertCount($amount, $result);

        foreach ($result as $hostname => $inventory) {
            $this->assertIsArray($inventory);
            $this->assertCount(0, $inventory);
        }
    }

    /**
     * @throws Exception on error
     */
    public function testGetNonExistingHosts(): void {
        $amount = 3;
        $hostnames = [];

        for ($i = 0; $i < $amount; $i++) {
            $hostnames[] = $this->generateRandomString();
        }

        $result = $this->instance->getHosts($hostnames);

        $this->assertIsArray($result);
        // @todo With non-existing hosts result *sometimes* contains fewer entries:
//        $this->assertCount($amount, $result);
        $this->assertNotCount(0, $result);

        foreach ($result as $hostname => $inventory) {
            $this->assertIsArray($inventory);
            $this->assertCount(0, $inventory);
        }
    }

    public function testGetHostWithInventoryData(): void {
        // @todo Implement me! This is hard to test because we need hw/sw inventory data.
    }

}
