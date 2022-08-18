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
 * Hosts
 */
class Host extends Request {

    const MODE_NEW = 'new';
    const MODE_REMOVE = 'remove';
    const MODE_FIXALL = 'fixall';
    const MODE_REFRESH = 'refresh';

    /**
     * Read information about a host by its hostname
     * @param string $hostname Hostname
     * @param bool $effectiveAttributes Fetch inherited settings from rulesets, folders, etc.; defaults to true
     * @return array
     * @throws Exception on error
     */
    public function get(string $hostname, bool $effectiveAttributes = true): array {
        return $this->api->request(
            'get_host',
            [
                'hostname' => $hostname
            ],
            [
                'effective_attributes' => $effectiveAttributes ? 1 : 0
            ]
        );
    }

    /**
     * Read information about all hosts
     * @param bool $effectiveAttributes Fetch inherited settings from rulesets, folders, etc.; defaults to true
     * @return array
     * @throws Exception on error
     */
    public function getAll(bool $effectiveAttributes = true): array {
        return $this->api->request(
            'get_all_hosts',
            [],
            [
                'effective_attributes' => $effectiveAttributes ? 1 : 0
            ]
        );
    }

    /**
     * Create new host with some attributes and tags
     * @param string $hostname Hostname
     * @param string $folder Optional folder; leave empty for root folder
     * @param array $attributes Associative array of attributes like "ipaddress", "site", "tag_agents" and so on
     * @param bool $createFolders Create folder structure if needed; defaults to false
     * @return self Returns itself
     * @throws Exception on error
     */
    public function add(
        string $hostname,
        string $folder = '',
        array $attributes = [],
        bool $createFolders = false
    ): self {
        $parameters = [
            'hostname' => $hostname,
            'folder' => $folder,
            'create_folders' => $createFolders ? '1' : '0'
        ];

        if (count($attributes) > 0) {
            $parameters['attributes'] = $attributes;
        }

        $this->api->request(
            'add_host',
            $parameters
        );

        return $this;
    }

    /**
     * Edit host, adds new attributes, changes attributes, or unsets attributes
     * @param string $hostname Hostname
     * @param array $attributes Optional attributes to create/update
     * @param array $unsetAttributes Optional attributes to unset (reset to default); works only with other attributes
     * to create/update
     * @return self Returns itself
     * @throws Exception on error
     */
    public function edit(string $hostname, array $attributes = [], array $unsetAttributes = []): self {
        $data = [
            'hostname' => $hostname
        ];

        if (count($attributes) > 0) {
            $data['attributes'] = $attributes;
        }

        if (count($unsetAttributes) > 0) {
            $data['unset_attributes'] = $unsetAttributes;
        }

        $this->api->request(
            'edit_host',
            $data
        );

        return $this;
    }

    /**
     * Delete a host by its hostname
     * @param string $hostname Hostname
     * @return self Returns itself
     * @throws Exception on error
     */
    public function delete(string $hostname): self {
        $this->api->request(
            'delete_host',
            [
                'hostname' => $hostname
            ]
        );

        return $this;
    }

    /**
     * Discover services of a host
     * @param string $hostname Hostname
     * @param string $mode Modes: "new", "remove", "fixall", "refresh"; defaults to "new"; use class constants
     * @return string Result message
     * @throws Exception on error
     */
    public function discoverServices(string $hostname, string $mode = self::MODE_NEW): string {
        return $this->api->request(
            'discover_services',
            [
                'hostname' => $hostname,
                'mode' => $mode
            ]
        );
    }

}
