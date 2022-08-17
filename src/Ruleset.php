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
 * @link https://github.com/bheisig/checkmkwebapi
 */

declare(strict_types=1);

namespace bheisig\checkmkwebapi;

use \Exception;

/**
 * Rulesets
 */
class Ruleset extends Request {

    /**
     * Read information about a ruleset by its name
     * @param string $name Name
     * @return array
     * @throws Exception on error
     */
    public function get(string $name): array {
        return $this->api->request(
            'get_ruleset',
            [
                'ruleset_name' => $name
            ],
            [
                'output_format' => 'python'
            ]
        );
    }

    /**
     * Read information about all rulesets
     * @return array
     * @throws Exception on error
     */
    public function getAll(): array {
        return $this->api->request(
            'get_rulesets_info',
            [],
            [
                'output_format' => 'python'
            ]
        );
    }

    public function set(string $name, array $rules) {
        // @todo Implement me!
    }

}
