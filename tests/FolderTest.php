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

namespace bheisig\checkmkwebapi\tests;

use bheisig\checkmkwebapi\Folder;

class FolderTest extends BaseTest {

    /**
     * @var \bheisig\checkmkwebapi\Folder
     */
    protected $instance;

    /**
     * @throws \Exception on error
     */
    public function setUp() {
        parent::setUp();

        $this->instance = new Folder($this->api);
    }

    /**
     * @throws \Exception on error
     */
    public function testGetExistingFolder() {
        $folderPath = $this->generateRandomString();
        $this->instance->add($folderPath);

        $folder = $this->instance->get($folderPath);

        $this->checkFolder($folder);
    }

    /**
     * @expectedException \Exception
     * @throws \Exception on error
     */
    public function testGetNonExistingFolder() {
        $this->instance->get('This is not the folder you are looking for');
    }

    /**
     * @throws \Exception on error
     */
    public function testGetAll() {
        // At least we need one folder:
        $this->instance->add($this->generateRandomString());

        $folders = $this->instance->getAll();

        // We only get the folder paths without any attributes and configuration hashes:
        $this->assertInternalType('array', $folders);
        $this->assertNotCount(0, $folders);

        $counter = 0;
        foreach ($folders as $folderPath => $folder) {
            $this->assertInternalType('string', $folderPath);

            $this->assertInternalType('array', $folder);

            // First entry is the root folder:
            if ($counter === 0) {
                $this->assertEquals('', $folderPath);
            }

            $counter++;
        }
    }

    /**
     * @throws \Exception on error
     */
    public function testAddWithoutAttributes() {
        $folderPath = $this->generateRandomString();

        $result = $this->instance->add($folderPath);

        $this->assertInstanceOf(Folder::class, $result);

        // Must not throw exception:
        $this->instance->get($folderPath);
    }

    /**
     * @throws \Exception on error
     */
    public function testAddWithAttributes() {
        $folderPath = $this->generateRandomString();
        $alias = $this->generateRandomString();
        $attributes = [
            'alias' => $alias
        ];

        $result = $this->instance->add($folderPath, $attributes);

        $this->assertInstanceOf(Folder::class, $result);

        // Must not throw exception:
        $folder = $this->instance->get($folderPath);

        $this->assertInternalType('array', $folder);
        $this->assertArrayHasKey('attributes', $folder);
        $this->assertArrayHasKey('alias', $folder['attributes']);
        // @todo 'alias' seems to be a bad example, but I don't know any better:
//        $this->assertEquals($alias, $folder['attributes']['alias']);
    }

    /**
     * @throws \Exception on error
     */
    public function testAddExistingFolder() {
        $folderPath = $this->generateRandomString();
        $this->instance->add($folderPath);

        // Check_MK only says "Edited properties of folder XY":
        $result = $this->instance->add($folderPath);

        $this->assertInstanceOf(Folder::class, $result);
    }

    public function testEditWithNewAttributes() {
        // @todo Implement me!
    }

    public function testEditWithChangedAttributes() {
        // @todo Implement me!
    }

    public function testEditWithConfigurationHash() {
        // @todo Implement me!
    }

    /**
     * @expectedException \Exception
     * @throws \Exception on error
     */
    public function testEditWithWrongConfigurationHash() {
        // @todo Implement me!
        throw new \Exception('Implement me!');
    }

    /**
     * @expectedException \Exception
     * @throws \Exception on error
     */
    public function testEditWithoutRequiredConfigurationHash() {
        // @todo Implement me!
        throw new \Exception('Implement me!');
    }

    /**
     * @expectedException \Exception
     * @throws \Exception on error
     */
    public function testEditNonExistingFolder() {
        $this->instance->edit(
            $this->generateRandomString(),
            [
                'alias' => $this->generateRandomString()
            ]
        );
    }

    /**
     * @throws \Exception on error
     */
    public function testDeleteExistingFolder() {
        $folderPath = $this->generateRandomString();
        $this->instance->add($folderPath);

        $result = $this->instance->delete($folderPath);

        $this->assertInstanceOf(Folder::class, $result);
    }

    /**
     * @expectedException \Exception
     * @throws \Exception on error
     */
    public function testDeleteNonExistingFolder() {
        $this->instance->delete($this->generateRandomString());
    }

    /**
     * @expectedException \Exception
     * @throws \Exception on error
     */
    public function testDeleteWithRetry() {
        $folderPath = $this->generateRandomString();
        $this->instance->add($folderPath);

        $result = $this->instance->delete($folderPath);

        $this->assertInstanceOf(Folder::class, $result);

        // Must throw exception:
        $this->instance->get($folderPath);
    }

    protected function checkFolder($folder) {
        $this->assertInternalType('array', $folder);
        $this->assertCount(2, $folder);

        $this->assertArrayHasKey('attributes', $folder);
        $this->assertInternalType('array', $folder['attributes']);

        $this->assertArrayHasKey('network_scan', $folder['attributes']);
        $this->assertInternalType('array', $folder['attributes']['network_scan']);

        $this->assertArrayHasKey('tag_agent', $folder['attributes']);
        $this->assertInternalType('string', $folder['attributes']['tag_agent']);

        $this->assertArrayHasKey('snmp_community', $folder['attributes']);
        // May be null…

        $this->assertArrayHasKey('ipv6address', $folder['attributes']);
        $this->assertInternalType('string', $folder['attributes']['ipv6address']);

        $this->assertArrayHasKey('alias', $folder['attributes']);
        $this->assertInternalType('string', $folder['attributes']['alias']);

        $this->assertArrayHasKey('management_protocol', $folder['attributes']);
        // May be null…

        $this->assertArrayHasKey('site', $folder['attributes']);
        $this->assertInternalType('string', $folder['attributes']['site']);

        $this->assertArrayHasKey('tag_address_family', $folder['attributes']);
        $this->assertInternalType('string', $folder['attributes']['tag_address_family']);

        $this->assertArrayHasKey('tag_criticality', $folder['attributes']);
        $this->assertInternalType('string', $folder['attributes']['tag_criticality']);

        $this->assertArrayHasKey('network_scan_result', $folder['attributes']);
        $this->assertInternalType('array', $folder['attributes']['network_scan_result']);

        $this->assertArrayHasKey('parents', $folder['attributes']);
        $this->assertInternalType('array', $folder['attributes']['parents']);

        $this->assertArrayHasKey('management_address', $folder['attributes']);
        $this->assertInternalType('string', $folder['attributes']['management_address']);

        $this->assertArrayHasKey('tag_networking', $folder['attributes']);
        $this->assertInternalType('string', $folder['attributes']['tag_networking']);

        $this->assertArrayHasKey('ipaddress', $folder['attributes']);
        $this->assertInternalType('string', $folder['attributes']['ipaddress']);

        $this->assertArrayHasKey('management_snmp_community', $folder['attributes']);
        // May be null…

        $this->assertArrayHasKey('configuration_hash', $folder);
        $this->assertInternalType('string', $folder['configuration_hash']);
    }

}
