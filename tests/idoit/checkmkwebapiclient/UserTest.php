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

class UserTest extends BaseTest
{

    /**
     * @var User
     */
    protected $instance;

    /**
     * @throws Exception on error
     */
    public function setUp(): void
    {
        parent::setUp();

        $this->instance = new User($this->api);
    }

    /**
     * @throws Exception on error
     */
    public function testGetExistingUser(): void
    {
        $username = $this->addUser();

        $result = $this->instance->get($username);
        $this->assertIsArray($result);
        $this->assertCount(10, $result);

        $this->assertArrayHasKey('alias', $result);
        $this->assertSame('Alias ' . $username, $result['alias']);
    }

    /**
     * @throws Exception on error
     */
    public function testGetNonExistingUser(): void
    {
        $this->expectException(Exception::class);
        $this->instance->get('This is not the user you are looking for');
    }

    /**
     * @throws Exception on error
     */
    public function testGetAll(): void
    {
        // At least we need one host:
        $this->addUser();

        $result = $this->instance->getAll();

        $this->assertIsArray($result);
        $this->assertNotCount(0, $result);


        foreach ($result as $username => $details) {
            $this->assertIsString($username);
            $this->assertIsArray($details);

            $this->assertArrayHasKey('alias', $details);
        }
    }

    /**
     * @throws Exception on error
     */
    public function testAdd(): void
    {
        $username = $this->generateRandomString();

        $result = $this->instance->add($username, [
            'alias' => 'Alias ' . $username,
        ]);

        $this->assertInstanceOf(User::class, $result);

        $user = $this->instance->get($username);

        $this->assertSame('Alias ' . $username, $user['alias']);
    }

    /**
     * @throws Exception on error
     */
    public function testEditWithNewAttributes(): void
    {
        // Add "empty" host:
        $username = $this->addUser();
        $email = $this->generateRandomEmail();

        $result = $this->instance->edit(
            $username,
            [
                'email' => $email
            ]
        );

        $this->assertInstanceOf(User::class, $result);

        $user = $this->instance->get($username);
        $this->assertArrayHasKey('email', $user);
        $this->assertSame($email, $user['email']);
    }

    /**
     * @throws Exception on error
     */
    public function testEditExistingAttributes(): void
    {
        $username = $this->generateRandomString();
        $alias = $this->generateRandomString();
        $email = $this->generateRandomEmail();

        $this->instance->add(
            $username,
            [
                'alias' => $alias,
                'email' => $email,
            ]
        );

        $updatedEmail = $this->generateRandomEmail();
        $updatedAlias = $this->generateRandomString();

        $result = $this->instance->edit(
            $username,
            [
                'alias' => $updatedAlias,
                'email' => $updatedEmail
            ]
        );

        $this->assertInstanceOf(User::class, $result);

        $username = $this->instance->get($username);

        $this->assertArrayHasKey('alias', $username);
        $this->assertSame($updatedAlias, $username['alias']);
        $this->assertArrayHasKey('email', $username);
        $this->assertSame($updatedEmail, $username['email']);
    }

    /**
     * @throws Exception on error
     */
    public function testEditResetAttributes(): void
    {
        $username = $this->generateRandomString();
        $alias = $this->generateRandomString();
        $email = $this->generateRandomEmail();

        $this->instance->add(
            $username,
            [
                'alias' => $alias,
                'email' => $email,
            ]
        );

        $result = $this->instance->edit(
            $username,
            [
                'email' => null,
            ]
        );

        $this->assertInstanceOf(User::class, $result);

        $user = $this->instance->get($username);

        $this->assertArrayNotHasKey('email', $user);
        $this->assertArrayHasKey('alias', $user);
    }

    /**
     * @throws Exception on error
     */
    public function testEditNonExistingUser(): void
    {
        $this->expectException(Exception::class);
        $this->instance->edit(
            $this->generateRandomString(),
            [
                'email' => $this->generateRandomEmail()
            ]
        );
    }

    /**
     * @throws Exception on error
     */
    public function testDelete(): void
    {
        $username = $this->addUser();

        $result = $this->instance->delete($username);

        $this->assertInstanceOf(User::class, $result);
    }

    /**
     * @throws Exception on error
     */
    public function testDeleteWithRetry(): void
    {
        $username = $this->addUser();

        $result = $this->instance->delete($username);

        $this->assertInstanceOf(User::class, $result);

        $this->expectException(Exception::class);
        $this->instance->get($username);
    }

    /**
     * @throws Exception on error
     */
    public function testDeleteNonExistingUser(): void
    {
        $this->expectException(Exception::class);
        $this->instance->delete($this->generateRandomString());
    }
}
