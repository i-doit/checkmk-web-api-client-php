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

use bheisig\checkmkwebapi\Change;
use bheisig\checkmkwebapi\Host;

class ChangeTest extends BaseTest {

    /**
     * @var \bheisig\checkmkwebapi\Change
     */
    protected $instance;

    protected $sites = [];

    /**
     * @throws \Exception on error
     */
    public function setUp () {
        parent::setUp();

        $this->instance = new Change($this->api);

        $this->sites = $GLOBALS['sites'];
    }

    /**
     * @throws \Exception on error
     */
    public function testActivate() {
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

        $this->assertInternalType('array', $result);
        $this->assertCount(count($this->sites), $result);

        foreach ($result as $site => $details) {
            $this->assertInternalType('string', $site);
            $this->assertContains($site, $this->sites);

            $this->assertInternalType('array', $details);
            $this->assertNotCount(0, $details);

            $keys = [
                '_time_updated' => 'double',
                '_status_details' => 'string',
                '_phase' => 'string',
                '_status_text' => 'string',
                '_pid' => 'int',
                '_state' => 'string',
                '_time_ended' => 'double',
                '_expected_duration' => 'double',
                '_time_started' => 'double',
                '_site_id' => 'string',
                '_warnings' => 'array'
            ];

            foreach ($keys as $key => $type) {
                $this->assertArrayHasKey($key, $details);
                $this->assertInternalType($type, $details[$key]);
            }

            $this->assertEquals('done', $details['_phase']);
            $this->assertContains($details['_status_text'], ['Activated', 'Success']);
            $this->assertContains($details['_state'], ['success', 'warning']);
            $this->assertEquals($site, $details['_site_id']);
        }
    }

    public function testActivateForeignChanges() {
        // @todo Simulate foreign changes!
    }

    public function testActivateEverwhere() {
        // @todo Implement me!
    }

}
