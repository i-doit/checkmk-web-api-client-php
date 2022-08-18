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

class FolderTest extends BaseTest {

    /**
     * @var Folder
     */
    protected $instance;

    /**
     * @throws Exception on error
     */
    public function setUp(): void {
        parent::setUp();

        $this->instance = new Folder($this->api);
    }

    /**
     * @throws Exception on error
     */
    public function testGetExistingFolder() : void{
        $folderPath = $this->generateRandomString();
        $this->instance->add($folderPath);

        $folder = $this->instance->get($folderPath);

        $this->checkFolder($folder);
    }

    /**
     * @throws Exception on error
     */
    public function testGetNonExistingFolder(): void {
        $this->expectException(Exception::class);
        $this->instance->get('This is not the folder you are looking for');
    }

    /**
     * @throws Exception on error
     */
    public function testGetAll(): void {
        // At least we need one folder:
        $this->instance->add($this->generateRandomString());

        $folders = $this->instance->getAll();

        // We only get the folder paths without any attributes and configuration hashes:
        $this->assertIsArray($folders);
        $this->assertNotCount(0, $folders);

        $counter = 0;
        foreach ($folders as $folderPath => $folder) {
            $this->assertIsString($folderPath);

            $this->assertIsArray($folder);

            // First entry is always the root folder:
            if ($counter === 0) {
                $this->assertSame('', $folderPath);
            }

            $counter++;
        }
    }

    /**
     * @throws Exception on error
     */
    public function testAddWithoutAttributes(): void {
        $folderPath = $this->generateRandomString();

        $result = $this->instance->add($folderPath);

        $this->assertInstanceOf(Folder::class, $result);

        // Must not throw exception:
        $this->instance->get($folderPath);
    }

    /**
     * @throws Exception on error
     */
    public function testAddWithAttributes(): void {
        $folderPath = $this->generateRandomString();
        $alias = $this->generateRandomString();
        $attributes = [
            'alias' => $alias
        ];

        $result = $this->instance->add($folderPath, $attributes);

        $this->assertInstanceOf(Folder::class, $result);

        // Must not throw exception:
        $folder = $this->instance->get($folderPath);

        $this->assertIsArray($folder);
        $this->assertArrayHasKey('attributes', $folder);
        $this->assertArrayHasKey('alias', $folder['attributes']);
        // @todo 'alias' seems to be a bad example, but I don't know any better:
//        $this->assertSame($alias, $folder['attributes']['alias']);
    }

    /**
     * @throws Exception on error
     */
    public function testAddExistingFolder(): void {
        $folderPath = $this->generateRandomString();
        $this->instance->add($folderPath);

        // Checkmk only says "Edited properties of folder XY":
        $result = $this->instance->add($folderPath);

        $this->assertInstanceOf(Folder::class, $result);
    }

    public function testEditWithNewAttributes(): void {
        // @todo Implement me!
    }

    public function testEditWithChangedAttributes(): void {
        // @todo Implement me!
    }

    public function testEditWithConfigurationHash(): void {
        // @todo Implement me!
    }

    /**
     * @throws Exception on error
     */
    public function testEditWithWrongConfigurationHash(): void {
        // @todo Implement me!
    }

    /**
     * @throws Exception on error
     */
    public function testEditWithoutRequiredConfigurationHash(): void {
        // @todo Implement me!
    }

    /**
     * @throws Exception on error
     */
    public function testEditNonExistingFolder(): void {
        $this->expectException(Exception::class);
        $this->instance->edit(
            $this->generateRandomString(),
            [
                'alias' => $this->generateRandomString()
            ]
        );
    }

    /**
     * @throws Exception on error
     */
    public function testDeleteExistingFolder(): void {
        $folderPath = $this->generateRandomString();
        $this->instance->add($folderPath);

        $result = $this->instance->delete($folderPath);

        $this->assertInstanceOf(Folder::class, $result);
    }

    /**
     * @throws Exception on error
     */
    public function testDeleteNonExistingFolder(): void {
        $this->expectException(Exception::class);
        $this->instance->delete($this->generateRandomString());
    }

    /**
     * @throws Exception on error
     */
    public function testDeleteWithRetry(): void {
        $folderPath = $this->generateRandomString();
        $this->instance->add($folderPath);

        $result = $this->instance->delete($folderPath);

        $this->assertInstanceOf(Folder::class, $result);

        $this->expectException(Exception::class);
        $this->instance->get($folderPath);
    }

    protected function checkFolder($folder): void {
        $this->assertIsArray($folder);
        $this->assertCount(2, $folder);

        $this->assertArrayHasKey('attributes', $folder);
        $this->assertIsArray($folder['attributes']);

        $this->assertArrayHasKey('network_scan', $folder['attributes']);
        $this->assertIsArray($folder['attributes']['network_scan']);

        $this->assertArrayHasKey('tag_agent', $folder['attributes']);
        $this->assertIsString($folder['attributes']['tag_agent']);

        $this->assertArrayHasKey('snmp_community', $folder['attributes']);
        // May be null…

        $this->assertArrayHasKey('ipv6address', $folder['attributes']);
        $this->assertIsString($folder['attributes']['ipv6address']);

        $this->assertArrayHasKey('alias', $folder['attributes']);
        $this->assertIsString($folder['attributes']['alias']);

        $this->assertArrayHasKey('management_protocol', $folder['attributes']);
        // May be null…

        $this->assertArrayHasKey('site', $folder['attributes']);
        $this->assertIsString($folder['attributes']['site']);

        $this->assertArrayHasKey('tag_address_family', $folder['attributes']);
        $this->assertIsString($folder['attributes']['tag_address_family']);

        $this->assertArrayHasKey('tag_criticality', $folder['attributes']);
        $this->assertIsString($folder['attributes']['tag_criticality']);

        $this->assertArrayHasKey('network_scan_result', $folder['attributes']);
        $this->assertIsArray($folder['attributes']['network_scan_result']);

        $this->assertArrayHasKey('parents', $folder['attributes']);
        $this->assertIsArray($folder['attributes']['parents']);

        $this->assertArrayHasKey('management_address', $folder['attributes']);
        $this->assertIsString($folder['attributes']['management_address']);

        $this->assertArrayHasKey('tag_networking', $folder['attributes']);
        $this->assertIsString($folder['attributes']['tag_networking']);

        $this->assertArrayHasKey('ipaddress', $folder['attributes']);
        $this->assertIsString($folder['attributes']['ipaddress']);

        $this->assertArrayHasKey('management_snmp_community', $folder['attributes']);
        // May be null…

        $this->assertArrayHasKey('configuration_hash', $folder);
        $this->assertIsString($folder['configuration_hash']);
    }

}
