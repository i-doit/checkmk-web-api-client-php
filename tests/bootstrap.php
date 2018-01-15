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
 * @link https://github.com/bheisig/check_mk-web-api
 */

try {
    require_once __DIR__ . '/../vendor/autoload.php';
    require_once __DIR__ . '/BaseTest.php';
    require_once __DIR__ . '/GroupTest.php';

    if (is_readable(__DIR__ . '/local.php')) {
        require_once __DIR__ . '/local.php';
    }

    $settings = [
        'url' => 'string',
        'username' => 'string',
        'secret' => 'string',
        'sites' => 'array'
    ];

    foreach ($settings as $setting => $dataType) {
        if (!array_key_exists($setting, $GLOBALS)) {
            throw new \Exception(sprintf(
                'Unable to perform unit tests because of configuration setting "%s" is missing',
                $setting
            ));
        }

        $valid = true;

        switch ($dataType) {
            case 'string':
                if (!is_string($GLOBALS[$setting]) ||
                    strlen($GLOBALS[$setting]) === 0) {
                    $valid = false;
                }
                break;
            case 'array':
                if (!is_array($GLOBALS[$setting]) ||
                    count($GLOBALS[$setting]) === 0) {
                    $valid = false;
                }
                break;
        }

        if ($valid === false) {
            throw new \Exception(sprintf(
                'Unable to perform unit tests because of configuration setting "%s" [%s] is invalid',
                $setting,
                $dataType
            ));
        }
    }
} catch (\Exception $e) {
    die('Ooops! ' . $e->getMessage());
}
