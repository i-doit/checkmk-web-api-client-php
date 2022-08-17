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
use \ReflectionClass;
use \ReflectionException;

/**
 * Groups
 */
abstract class Group extends Request {

    protected $prefix;

    /**
     * Constructor
     * @param API $api API client
     * @throws ReflectionException on error
     */
    public function __construct(API $api) {
        parent::__construct($api);

        $this->prefix = strtolower((new ReflectionClass($this))->getShortName());
    }

    /**
     * Read information about a group by its name
     * @param string $name Name
     * @return array
     * @throws Exception
     */
    public function get(string $name): array {
        $groups = $this->getAll();

        if (!array_key_exists($name, $groups)) {
            throw new Exception(sprintf(
                'Group "%s" does not exist',
                $name
            ));
        }

        return $groups[$name];
    }

    /**
     * Read information about all groups
     * @return array
     * @throws Exception on error
     */
    public function getAll(): array {
        return $this->api->request(
            sprintf('get_all_%ss', $this->prefix)
        );
    }

    /**
     * Create new group with name and alias
     * @param string $name Name
     * @param string $alias Alias
     * @return self Returns itself
     * @throws Exception on error
     */
    public function add(string $name, string $alias): self {
        $this->api->request(
            sprintf('add_%s', $this->prefix),
            [
                'groupname' => $name,
                'alias' => $alias
            ]
        );

        return $this;
    }

    /**
     * Change the alias of a group
     * @param string $name Name
     * @param string $alias Alias
     * @return self Returns itself
     * @throws Exception on error
     */
    public function edit(string $name, string $alias): self {
        $this->api->request(
            sprintf('edit_%s', $this->prefix),
            [
                'groupname' => $name,
                'alias' => $alias
            ]
        );

        return $this;
    }

    /**
     * Delete contact group by its name
     * @param string $name Name
     * @return self Returns itself
     * @throws Exception on error
     */
    public function delete(string $name): self {
        $this->api->request(
            sprintf('delete_%s', $this->prefix),
            [
                'groupname' => $name
            ]
        );

        return $this;
    }

}
