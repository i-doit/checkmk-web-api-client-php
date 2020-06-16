<?php

/**
 * Copyright (C) 2018-20 Benjamin Heisig
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
 * @copyright Copyright (C) 2018-20 Benjamin Heisig
 * @license http://www.gnu.org/licenses/agpl-3.0 GNU Affero General Public License (AGPL)
 * @link https://github.com/bheisig/checkmkwebapi
 */

declare(strict_types=1);

namespace bheisig\checkmkwebapi;

use \Exception;

/**
 * Users
 */
class User extends Request {

    /**
     * Read information about an user by its identifier
     * @param string $id Identifier
     * @return array
     * @throws Exception on error
     */
    public function get(string $id): array {
        $users = $this->getAll();

        if (!array_key_exists($id, $users)) {
            throw new Exception(sprintf(
                'User with ID "%s" does not exist',
                $id
            ));
        }

        return $users[$id];
    }

    /**
     * Read information about all users
     * @return array
     * @throws Exception on error
     */
    public function getAll(): array {
        return $this->api->request(
            'get_all_users'
        );
    }

    /**
     * Create new user with some attributes
     * @param string $id Identifier
     * @param array $attributes Associative array of attributes like "alias", "password", "pager" and so on
     * @return self Returns itself
     * @throws Exception on error
     */
    public function add(string $id, array $attributes = []): self {
        return $this->batchAdd([
            $id => $attributes
        ]);
    }

    /**
     * Create new users with some attributes
     * @param array $users Associative array; id (key) with attributes (value)
     * @return self Returns itself
     * @throws Exception on error
     */
    public function batchAdd(array $users): self {
        $this->api->request(
            'add_users',
            [
                'users' => $users
            ]
        );

        return $this;
    }

    public function edit() {
        // @todo Implement me!
    }

    public function batchEdit() {
        // @todo Implement me!
    }

    /**
     * Delete a user by its identifier
     * @param string $id Identifier
     * @return self Returns itself
     * @throws Exception on error
     */
    public function delete(string $id): self {
        return $this->batchDelete([$id]);
    }

    /**
     * Delete users by their identifiers
     * @param array $ids Identifiers
     * @return self Returns itself
     * @throws Exception on error
     */
    public function batchDelete(array $ids): self {
        $this->api->request(
            'delete_users',
            [
                'users' => $ids
            ]
        );

        return $this;
    }

}
