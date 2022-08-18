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

class ChangeTest extends BaseTest {

    /**
     * @var Change
     */
    protected $instance;

    protected $sites = [];

    /**
     * @throws Exception on error
     */
    public function setUp(): void {
        parent::setUp();

        $this->instance = new Change($this->api);

        if (is_string(getenv('SITES'))) {
            $this->sites = explode(',', getenv('SITES'));
        }
    }

    /**
     * @throws Exception on error
     */
    public function testActivate(): void {
        // We need at least one change:
        (new Host($this->api))->add(
            $this->generateRandomString(),
            '',
            [
                'site' => $this->sites[0],
                'ipaddress' => '127.0.0.1'
            ]
        );

        $result = $this->instance->activate($this->sites);

        $this->assertIsArray($result);
        $this->assertCount(count($this->sites), $result);

        foreach ($result as $site => $details) {
            $this->assertIsString($site);
            $this->assertContains($site, $this->sites);

            $this->assertIsArray($details);
            $this->assertNotCount(0, $details);

            $this->assertArrayHasKey('_time_updated', $details);
            $this->assertIsFloat($details['_time_updated']);

            $this->assertArrayHasKey('_status_details', $details);
            $this->assertIsString($details['_status_details']);

            $this->assertArrayHasKey('_phase', $details);
            $this->assertIsString($details['_phase']);

            $this->assertArrayHasKey('_status_text', $details);
            $this->assertIsString($details['_status_text']);

            $this->assertArrayHasKey('_pid', $details);
            $this->assertIsInt($details['_pid']);

            $this->assertArrayHasKey('_state', $details);
            $this->assertIsString($details['_state']);

            $this->assertArrayHasKey('_time_ended', $details);
            $this->assertIsFloat($details['_time_ended']);

            $this->assertArrayHasKey('_expected_duration', $details);
            $this->assertIsFloat($details['_expected_duration']);

            $this->assertArrayHasKey('_time_started', $details);
            $this->assertIsFloat($details['_time_started']);

            $this->assertArrayHasKey('_site_id', $details);
            $this->assertIsString($details['_site_id']);

            $this->assertArrayHasKey('_warnings', $details);
            $this->assertIsArray($details['_warnings']);

            $this->assertSame('done', $details['_phase']);
            $this->assertContains($details['_status_text'], ['Activated', 'Success', 'Failed']);
            $this->assertContains($details['_state'], ['success', 'warning', 'error']);
            $this->assertSame($site, $details['_site_id']);
        }
    }

    public function testActivateForeignChanges(): void {
        // @todo Simulate foreign changes!
    }

    public function testActivateEverywhere(): void {
        // @todo Implement me!
    }

}
