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
use \stdClass;

/**
 * Folders
 */
class Folder extends Request {

    /**
     * Read information about a folder by its path
     * @param string $folder Folder path
     * @return array
     * @throws Exception on error
     */
    public function get(string $folder): array {
        return $this->api->request(
            'get_folder',
            [
                'folder' => $folder
            ],
            [
                'effective_attributes' => 1,
                'output_format' => 'json'
            ]
        );
    }

    /**
     * Read information about all folders
     * @return array
     * @throws Exception on error
     */
    public function getAll(): array {
        return $this->api->request(
            'get_all_folders',
            [],
            [
                'output_format' => 'json'
            ]
        );
    }

    /**
     * Create new folder with some attributes
     * @param string $folder Folder path
     * @param array $attributes Associative array of attributes like "parents", "site", "tag_agent" and so on
     * @return self Returns itself
     * @throws Exception on error
     */
    public function add(string $folder, array $attributes = []): self {
        $data = [
            'folder' => $folder
        ];

        if (count($attributes) > 0) {
            $data['attributes'] = $attributes;
        } else {
            $data['attributes'] = new stdClass();
        }

        $this->api->request(
            'add_folder',
            $data
        );

        return $this;
    }

    /**
     * Edit a folder's attributes
     * @param string $folder Folder path
     * @param array $attributes Attributes to create/update
     * @param string $configurationHash Configuration hash
     * @return self Returns itself
     * @throws Exception on error
     */
    public function edit(string $folder, array $attributes, string $configurationHash = null): self {
        $data = [
            'folder' => $folder,
            'attributes' => $attributes
        ];

        if (isset($configurationHash)) {
            $data['configuration_hash'] = $configurationHash;
        }

        $this->api->request(
            'edit_folder',
            $data,
            [
                'request_format' => 'python'
            ]
        );

        return $this;
    }

    /**
     * Delete a folder by its path
     * @param string $folder Folder path
     * @return self Returns itself
     * @throws Exception on error
     */
    public function delete(string $folder): self {
        $this->api->request(
            'delete_folder',
            [
                'folder' => $folder
            ]
        );

        return $this;
    }

}
