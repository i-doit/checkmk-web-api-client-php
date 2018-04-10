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

namespace bheisig\checkmkwebapi\tests;

use bheisig\checkmkwebapi\Python;

class PythonTest extends BaseTest {

    public function testDecode() {
        $values = [
            "{'foo': (1, 2)}",
            "{'bar': ('abc', 123)}",
            "{'baz': ((1, 2), (3, 4))}",
            "{'ip_ranges': ('ip_range', ('10.0.0.1', '10.0.0.254'))}",
            "{'time_allowed': ((0, 0), (24, 0))}",
            "{'result': {'': {}, 'foo': {'network_scan_result': {'output': 'The network scan found 0 new hosts.', 'state': True, 'end': 1523271725.991344, 'start': 1523271721.830718}, 'network_scan': {'time_allowed': ((0, 0), (24, 0)), 'run_as': u'bar', 'tag_criticality': 'offline', 'ip_ranges': [('ip_range', ('10.0.0.1', '10.0.0.254'))], 'scan_interval': 3600, 'set_ipaddress': True, 'exclude_ranges': [], 'translate_names': {'case': 'lower', 'drop_domain': True}}, 'snmp_community': 'integrate'}}, 'result_code': 0}",
            "{'result': {'attributes': {'network_scan': {'time_allowed': ((0, 0), (24, 0)), 'run_as': u'automation', 'tag_criticality': 'offline', 'ip_ranges': [], 'scan_interval': 86400, 'set_ipaddress': True, 'exclude_ranges': []}, 'tag_snmp': 'no-snmp', 'snmp_community': None, 'ipv6address': '', 'alias': '', 'management_protocol': None, 'site': 'apitest', 'tag_address_family': 'ip-v4-only', 'tag_criticality': 'prod', 'contactgroups': (True, []), 'network_scan_result': {'start': None, 'state': None, 'end': None, 'output': ''}, 'parents': [], 'management_address': '', 'tag_agent': 'cmk-agent', 'tag_networking': 'lan', 'ipaddress': '', 'management_snmp_community': None}, 'configuration_hash': 'c6ddf7521153e6cc5ed6b8223e312421'}, 'result_code': 0}",
            "{'result': {'site_id': 'apitestslave', 'site_config': {'url_prefix': 'http://10.10.30.230/apitestslave/', 'status_host': None, 'multisiteurl': 'http://10.10.30.230/apitestslave/check_mk/', 'user_sync': 'all', 'socket': ('proxy', {'params': None, 'socket': ('10.10.30.230', 6557)}), 'alias': u'slave', 'user_login': True, 'insecure': True, 'disable_wato': True, 'disabled': False, 'replication': 'slave', 'secret': 'ZR5=3<MG=KT04ZK853NBZ<9R4KNVE@WG', 'timeout': 10, 'persist': False, 'replicate_ec': False, 'replicate_mkps': True}, 'configuration_hash': '00116115ff6b27cffda0944befe72240'}, 'result_code': 0}"
        ];

        foreach ($values as $value) {
            $result = Python::decode($value);

            $this->assertInternalType('array', $result, $value);
        }
    }

}
