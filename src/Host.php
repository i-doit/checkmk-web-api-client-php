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

namespace bheisig\checkmkwebapi;

/**
 * Hosts
 */
class Host extends Request {

    /**
     * Read information about a host by its hostname
     *
     * @param string $hostname Hostname
     *
     * @return array
     *
     * @throws \Exception on error
     */
    public function get($hostname) {
        return $this->api->request(
            'get_host',
            [
                'hostname' => $hostname
            ],
            [
                'effective_attributes' => 1
            ]
        );
    }

    /**
     * Read information about all hosts
     *
     * @return array
     *
     * @throws \Exception on error
     */
    public function getAll() {
        return $this->api->request(
            'get_all_hosts'
        );
    }

    /**
     * Create new host with some attributes and tags
     *
     * @param string $hostname Hostname
     * @param string $folder Optional folder; leave empty for root folder
     * @param array $attributes Associative array of attributes like "ipaddress", "site", "tag_agents" and so on
     * @param bool $createFolders Create folder structure if needed; defaults to false
     *
     * @return self Returns itself
     *
     * @throws \Exception on error
     */
    public function add($hostname, $folder = '', array $attributes = [], $createFolders = false) {
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
     *
     * @param string $hostname Hostname
     * @param array $attributes Optional attributes to create/update
     * @param array $unsetAttributes Optional attributes to unset (reset to default); works only with other attributes to create/update
     *
     * @return self Returns itself
     *
     * @throws \Exception on error
     */
    public function edit($hostname, array $attributes = [], array $unsetAttributes = []) {
        $this->api->request(
            'edit_host',
            [
                'hostname' => $hostname,
                'unset_attributes' => $unsetAttributes,
                'attributes' => $attributes
            ]
        );

        return $this;
    }

    /**
     * Delete a host by its hostname
     *
     * @param string $hostname Hostname
     *
     * @return self Returns itself
     *
     * @throws \Exception on error
     */
    public function delete($hostname) {
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
     *
     * @param string $hostname Hostname
     * @param string $mode Modes: "new", "remove", "fixall", "refresh"; defaults to "new"
     *
     * @return string Result message
     *
     * @throws \Exception on error
     */
    public function discoverServices($hostname, $mode = 'new') {
        return $this->api->request(
            'discover_services',
            [
                'hostname' => $hostname,
                'mode' => $mode
            ]
        );
    }

}
