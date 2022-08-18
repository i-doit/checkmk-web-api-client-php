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

    /**
     * @param string $id Identifier
     * @param array $attributes Associative array of attributes like "alias", "password", "pager" and so on
     * @return self Returns itself
     * @throws \Exception on error
     */
    public function edit(string $id, array $attributes): self {
        return $this->batchEdit([
            $id => $attributes
        ]);
    }

    /**
     * Edit a batch of users
     * @param array $users Associative array; id (key) with attributes (value)
     * @return self Returns itself
     * @throws \Exception on error
     */
    public function batchEdit(array $users): self {
        // We first need to detect the edited and/or removed attributes
        // because the CheckMK webapi expects a `set_attributes` and `unset_attributes`
        // So, we need to extract the attributes that have a value of `null`. Those will unset,
        // and will be set to the default value.

        $edit = [];
        $_users = $this->getAll();
        // Then loop over each user
        foreach ($users as $user => $attributes) {
            $set = [];
            $unset = [];
            // First check if the user exists
            if (!array_key_exists($user, $_users)) {
                throw new Exception(sprintf(
                    'User with ID "%s" does not exist',
                    $user
                ));
            }
            // and each attribute of that user
            foreach ($attributes as $attribute => $value) {
                if (is_null($value)) {
                    $unset[] = $attribute;
                } else {
                    $set[$attribute] = $value;
                }
            }
            // so we can set the `set` and `unset` based on the `null` value of attributes.
            if (count($set) > 0) {
                $edit[$user] = [
                    'set_attributes'   => $set,
                ];
            }
            if (count($unset) > 0) {
                $edit[$user] = [
                    'unset_attributes' => $unset,
                ];
            }
        }
        $this->api->request(
            'edit_users',
            [
                'users' => $edit
            ]
        );
        return $this;
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
