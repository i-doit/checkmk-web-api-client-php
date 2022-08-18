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
 * Hardware/software inventory
 */
class Inventory extends Request {

    /**
     * Read hardware/software inventory data for a specific host
     * @param string $hostname Hostname
     * @return array
     * @throws Exception on error
     */
    public function getHost(string $hostname): array {
        $result = $this->getHosts([$hostname]);

        return $result[$hostname];
    }

    /**
     * Read hardware/software inventory data for one or more hosts
     * @param string[] $hostnames List of hostnames
     * @return array
     * @throws Exception on error
     */
    public function getHosts(array $hostnames): array {
        $hosts = implode(', ', array_map(function ($hostname) {
            return '"' . $hostname . '"';
        }, $hostnames));

        return $this->api->request(
            '',
            [],
            [
                'request' => '{"hosts": [' . $hosts . ']}'
            ],
            'host_inv_api.py'
        );
    }

}
