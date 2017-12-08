<?php

/**
 * Copyright (C) 2017 Benjamin Heisig
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
 * @copyright Copyright (C) 2017 Benjamin Heisig
 * @license http://www.gnu.org/licenses/agpl-3.0 GNU Affero General Public License (AGPL)
 * @link https://github.com/bheisig/check_mk-web-api
 */

use bheisig\checkmkwebapi\Site;

class SiteTest extends BaseTest {

    /**
     * @var \bheisig\checkmkwebapi\Site
     */
    protected $instance;

    /**
     * @throws \Exception on error
     */
    public function setUp () {
        parent::setUp();

        $this->instance = new Site($this->api);
    }

    /**
     * @throws \Exception on error
     */
    public function testGet() {
        foreach ($GLOBALS['sites'] as $id) {
            $site = $this->instance->get($id);

            $this->assertInternalType('array', $site);
            $this->assertCount(3, $site);

            $this->assertArrayHasKey('site_id', $site);
            $this->assertInternalType('string', $site['site_id']);
            $this->assertEquals($id, $site['site_id']);

            $this->evaluateSite($site);
        }
    }

    /**
     * @expectedException \Exception
     * @throws \Exception on error
     */
    public function testGetNonExistingSite() {
        $this->instance->get($this->generateRandomString());
    }

    /**
     * @throws \Exception on error
     */
    public function testGetAll() {
        $sites = $this->instance->getAll();

        $expectedSites = $GLOBALS['sites'];

        $this->assertInternalType('array', $sites);
        // @todo $sites contains only sites which monitor hosts, so unused sites cannot be fetched:
//        $this->assertCount(count($expectedSites), $sites);

        foreach ($sites as $site) {
            $this->assertInternalType('array', $site);
            $this->assertCount(3, $site);

            $this->assertArrayHasKey('site_id', $site);
            $this->assertInternalType('string', $site['site_id']);
            $this->assertContains($site['site_id'], $expectedSites);

            $this->evaluateSite($site);
        }
    }

    protected function evaluateSite($site) {
        $this->assertArrayHasKey('site_config', $site);
        $this->assertInternalType('array', $site['site_config']);
        $this->assertNotCount(0, $site['site_config']);

        $this->assertArrayHasKey('disabled', $site['site_config']);
        $this->assertInternalType('boolean', $site['site_config']['disabled']);

        $this->assertArrayHasKey('alias', $site['site_config']);
        $this->assertInternalType('string', $site['site_config']['alias']);

        $this->assertArrayHasKey('user_login', $site['site_config']);
        $this->assertInternalType('boolean', $site['site_config']['user_login']);

        $this->assertArrayHasKey('timeout', $site['site_config']);
        $this->assertInternalType('integer', $site['site_config']['timeout']);

        $this->assertArrayHasKey('replication', $site['site_config']);
        $this->assertInternalType('string', $site['site_config']['replication']);

        $this->assertArrayHasKey('replicate_ec', $site['site_config']);
        $this->assertInternalType('boolean', $site['site_config']['replicate_ec']);

        $this->assertArrayHasKey('multisiteurl', $site['site_config']);
        $this->assertInternalType('string', $site['site_config']['multisiteurl']);

        $this->assertArrayHasKey('insecure', $site['site_config']);
        $this->assertInternalType('boolean', $site['site_config']['insecure']);

        $this->assertArrayHasKey('persist', $site['site_config']);
        $this->assertInternalType('boolean', $site['site_config']['persist']);

        $this->assertArrayHasKey('disable_wato', $site['site_config']);
        $this->assertInternalType('boolean', $site['site_config']['disable_wato']);

        $this->assertArrayHasKey('configuration_hash', $site);
        $this->assertInternalType('string', $site['configuration_hash']);
        $this->assertNotEmpty($site['configuration_hash']);

        // Optional keys:
//            if (array_key_exists('status_host', $site['site_config'])) {
//                // @todo Dunno…
//            }

        if (array_key_exists('socket', $site['site_config'])) {
            $this->assertInternalType('string', $site['site_config']['socket']);
        }

        if (array_key_exists('url_prefix', $site['site_config'])) {
            $this->assertInternalType('string', $site['site_config']['url_prefix']);
        }

        if (array_key_exists('user_sync', $site['site_config'])) {
            $this->assertInternalType('string', $site['site_config']['user_sync']);
        }

        if (array_key_exists('secret', $site['site_config'])) {
            $this->assertInternalType('string', $site['site_config']['secret']);
        }

        if (array_key_exists('relicate_mkps', $site['site_config'])) {
            $this->assertInternalType('boolean', $site['site_config']['relicate_mkps']);
        }
    }

}
