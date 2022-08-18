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
 * Changes
 */
class Change extends Request {

    /**
     * Activate changes on specific sites
     * @param string[] $sites List of sites
     * @param bool $allowForeignChanges Optional activate changes made by other users; defaults to false ("no")
     * @return array Results for every site
     * @throws Exception on error
     */
    public function activate(array $sites, bool $allowForeignChanges = false): array {
        $result = $this->api->request(
            'activate_changes',
            [
                'sites' => $sites,
                'allow_foreign_changes' => $allowForeignChanges ? '1' : '0'
            ]
        );

        if (!array_key_exists('sites', $result) ||
            !is_array($result['sites']) ||
            count($result['sites']) !== count($sites)) {
            throw new Exception('Invalid server response');
        }

        return $result['sites'];
    }

    /**
     * Activate changes on all sites
     * @param bool $allowForeignChanges Optional activate changes made by other users; defaults to false ("no")
     * @return array Results for every site
     * @throws Exception on error
     */
    public function activateEverywhere(bool $allowForeignChanges = false): array {
        $sites = (new Site($this->api))->getAll();
        $affectedSites = [];

        foreach ($sites as $site) {
            if (!array_key_exists('site_id', $site)) {
                throw new Exception('Site identifier missing');
            }

            $affectedSites[] = $site['site_id'];
        }

        return $this->activate($affectedSites, $allowForeignChanges);
    }

}
