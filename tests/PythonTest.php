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

declare(strict_types=1);

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
            "{'contactgroups': (True, [])}",
            "{'socket': ('monitoring.example.com', 6559)}",
            "{'heartbeat': (5, 2.1)}",
            "{'key': ('abc', None)}",
            "{'key': (True, False)}",
            "{'key': (123, 123.45)}",
            "{'key': ('abc_123.456-789', None)}",
            "{'key': ((123, 456), {'a': 'b'})}",
            "{'result': {'': {}, 'foo': {'network_scan_result': {'output': 'The network scan found 0 new hosts.',
                'state': True, 'end': 1523271725.991344, 'start': 1523271721.830718}, 'network_scan': {'time_allowed':
                ((0, 0), (24, 0)), 'run_as': u'bar', 'tag_criticality': 'offline', 'ip_ranges': [('ip_range',
                ('10.0.0.1', '10.0.0.254'))], 'scan_interval': 3600, 'set_ipaddress': True, 'exclude_ranges': [],
                'translate_names': {'case': 'lower', 'drop_domain': True}}, 'snmp_community': 'integrate'}},
                'result_code': 0}",
            "{'result': {'attributes': {'network_scan': {'time_allowed': ((0, 0), (24, 0)), 'run_as': u'automation',
                'tag_criticality': 'offline', 'ip_ranges': [], 'scan_interval': 86400, 'set_ipaddress': True,
                'exclude_ranges': []}, 'tag_snmp': 'no-snmp', 'snmp_community': None, 'ipv6address': '', 'alias': '',
                'management_protocol': None, 'site': 'apitest', 'tag_address_family': 'ip-v4-only',
                'tag_criticality': 'prod', 'contactgroups': (True, []), 'network_scan_result': {'start': None,
                'state': None, 'end': None, 'output': ''}, 'parents': [], 'management_address': '',
                'tag_agent': 'cmk-agent', 'tag_networking': 'lan', 'ipaddress': '', 'management_snmp_community': None},
                'configuration_hash': 'c6ddf7521153e6cc5ed6b8223e312421'}, 'result_code': 0}",
            "{'result': {'attributes': {'network_scan': {'time_allowed': ((0, 0), (24, 0)), 'run_as': u'automation',
                'tag_criticality': 'offline', 'ip_ranges': [], 'scan_interval': 86400, 'set_ipaddress': True,
                'exclude_ranges': []}, 'tag_snmp': 'no-snmp', 'snmp_community': None,
                'management_ipmi_credentials': None, 'ipv6address': '', 'alias': '', 'management_protocol': None,
                'network_scan_result': {'start': None, 'state': None, 'end': None, 'output': ''}, 'site': 'apitest',
                'tag_address_family': 'ip-v4-only', u'tag_criticality': u'prod', 'contactgroups': (True, []),
                'additional_ipv6addresses': [], 'parents': [], 'management_address': '', u'tag_agent': u'cmk-agent',
                'additional_ipv4addresses': [], u'tag_networking': u'lan', 'ipaddress': '',
                'management_snmp_community': None}, 'configuration_hash': 'e22ba8d6831e023b771248bde84dd512'},
                'result_code': 0}",
            "{'result': {'site_id': 'mysite', 'site_config': {'url_prefix': 'http://monitoring.example.com/mysite/',
                'status_host': None, 'multisiteurl': 'http://monitoring.example.com/mysite/check_mk/',
                'user_sync': 'all', 'socket': ('proxy', {'params': None, 'socket': ('monitoring.example.com', 6557)}),
                'alias': u'slave', 'user_login': True, 'insecure': True, 'disable_wato': True, 'disabled': False,
                'replication': 'slave', 'secret': 'abcdef0123456789', 'timeout': 10, 'persist': False,
                'replicate_ec': False, 'replicate_mkps': True},
                'configuration_hash': '00116115ff6b27cffda0944befe72240'}, 'result_code': 0}",
            "{'result': {'site_id': 'mysite', 'site_config': {'status_host': None,
                'url_prefix': 'http://monitoring.example.com/mysite/', 'user_sync': None,
                'socket': ('proxy', {'socket': ('monitoring.example.com', 6559), 'query_timeout': 120.0, 'cache': True, 
                'channels': 5, 'channel_timeout': 3.0, 'heartbeat': (5, 2.0), 'connect_retry': 4.0}),
                'replication': 'slave', 'user_login': True, 'insecure': True, 'disable_wato': False, 'disabled': False,
                'alias': u'My site', 'secret': 'abcdef0123456789',
                'globals': {'cmc_cmk_helpers': 5, 'cmc_check_helpers': 2}, 'replicate_mkps': False, 'timeout': 10,
                'persist': False, 'replicate_ec': False,
                'multisiteurl': 'http://monitoring.example.com/mysite/check_mk/'},
                'configuration_hash': '2f550c15bde70229bd02f20bc1bf3c5b'}, 'result_code': 0}"
        ];

        foreach ($values as $value) {
            $result = Python::decode($value);

            $this->assertIsArray($result, $value);
        }
    }

}
