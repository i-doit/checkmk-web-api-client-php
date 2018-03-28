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

use bheisig\checkmkwebapi\Group;

abstract class GroupTest extends BaseTest {

    /**
     * @var \bheisig\checkmkwebapi\Group
     */
    protected $instance;

    /**
     * @throws \Exception on error
     */
    public function testGet() {
        $name = $this->generateRandomString();
        $alias = $this->generateRandomString();

        // We need a test group:
        $this->instance->add(
            $name,
            $alias
        );

        $result = $this->instance->get(
            $name
        );

        $this->assertInternalType('array', $result);
        $this->assertArrayHasKey('alias', $result);
        $this->assertEquals($alias, $result['alias']);
    }

    /**
     * @expectedException \Exception
     * @throws \Exception on error
     */
    public function testGetNonExistingGroup() {
        $this->instance->get($this->generateRandomString());
    }

    /**
     * @throws \Exception on error
     */
    public function testGetAll() {
        $result = $this->instance->getAll();

        $this->assertInternalType('array', $result);

        foreach ($result as $name => $details) {
            $this->assertInternalType('string', $name);

            $this->assertInternalType('array', $details);
            $this->assertCount(1, $details);
            $this->assertArrayHasKey('alias', $details);
            $this->assertInternalType('string', $details['alias']);
        }
    }

    /**
     * @throws \Exception on error
     */
    public function testAdd() {
        $name = $this->generateRandomString();
        $alias = $this->generateRandomString();

        $result = $this->instance->add(
            $name,
            $alias
        );

        $this->assertInstanceOf(Group::class, $result);

        $group = $this->instance->get($name);

        $this->assertEquals($alias, $group['alias']);
    }

    /**
     * @throws \Exception on error
     */
    public function testEdit() {
        $name = $this->generateRandomString();
        $alias = $this->generateRandomString();

        $this->instance->add(
            $name,
            $alias
        );

        $alias = $this->generateRandomString();

        $result = $this->instance->edit(
            $name,
            $alias
        );

        $this->assertInstanceOf(Group::class, $result);

        $group = $this->instance->get($name);

        $this->assertArrayHasKey('alias', $group);
        $this->assertEquals($alias, $group['alias']);
    }

    /**
     * @throws \Exception on error
     */
    public function testDelete() {
        $name = $this->generateRandomString();
        $alias = $this->generateRandomString();

        $this->instance->add(
            $name,
            $alias
        );

        $result = $this->instance->delete($name);

        $this->assertInstanceOf(Group::class, $result);
    }

}
