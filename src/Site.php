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
 * Sites
 */
class Site extends Request {

    /**
     * Read information about a site by its identifier
     * @param string $id Identifier
     * @return array
     * @throws Exception on error
     */
    public function get(string $id): array {
        return $this->api->request(
            'get_site',
            [
                'site_id' => $id
            ],
            [
                'output_format' => 'python'
            ]
        );
    }

    public function set() {
        // @todo Implement me!
    }

    public function delete() {
        // @todo Implement me!
    }

    public function login() {
        // @todo Implement me!
    }

    public function logout() {
        // @todo Implement me!
    }

    /**
     * Read information about all sites
     * @return array
     * @throws Exception on error
     */
    public function getAll(): array {
        // @todo There is no dedicated API call for this.

        $siteIDs = [];
        $sites = [];

        $hostAPI = new Host($this->api);

        $hosts = $hostAPI->getAll();

        foreach ($hosts as $host) {
            if (!array_key_exists('attributes', $host)) {
                continue;
            }

            if (!is_array($host['attributes'])) {
                continue;
            }

            if (!array_key_exists('site', $host['attributes'])) {
                continue;
            }

            if (!is_string($host['attributes']['site']) ||
                strlen($host['attributes']['site']) === 0) {
                continue;
            }

            $siteIDs[] = $host['attributes']['site'];
        }

        $siteIDs = array_unique($siteIDs);

        foreach ($siteIDs as $siteID) {
            $sites[] = $this->get($siteID);
        }

        return $sites;
    }

}
