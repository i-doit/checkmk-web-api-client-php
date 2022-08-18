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

class PythonTest extends BaseTest {

    /**
     * @return array
     * @throws Exception on error
     */
    public function provideValues(): array {
        return [
            'tupel with integers' => [
                <<<EOF
{
    'foo': (1, 2)
}
EOF
                ,
                $this->decodeJSON(
                    <<<EOF
{
    "foo": [
        1,
        2
    ]
}
EOF
                )
            ],
            'tupel with string plus integer' => [
                <<<EOF
{
    'bar': ('abc', 123)
}
EOF
                ,
                $this->decodeJSON(
                    <<<EOF
{
    "bar": [
        "abc",
        123
    ]
}
EOF
                )
            ],
            'tupel of tupels with integers' => [
                <<<EOF
{
    'baz': ((1, 2), (3, 4))
}
EOF
                ,
                $this->decodeJSON(
                    <<<EOF
{
    "baz": [
        [
            1,
            2
        ],
        [
            3,
            4
        ]
    ]
}
EOF
                )
            ],
            'tupel of string plus tupel of IPv4 addresses' => [
                <<<EOF
{
    'ip_ranges': ('ip_range', ('10.0.0.1', '10.0.0.254'))
}
EOF
                ,
                $this->decodeJSON(
                    <<<EOF
{
    "ip_ranges": [
        "ip_range",
        [
            "10.0.0.1",
            "10.0.0.254"
        ]
    ]
}
EOF
                )
            ],
            'tupel of tupels with integers including zeros' => [
                <<<EOF
{
    'time_allowed': ((0, 0), (24, 0))
}
EOF
                ,
                $this->decodeJSON(
                    <<<EOF
{
    "time_allowed": [
        [
            0,
            0
        ],
        [
            24,
            0
        ]
    ]
}
EOF
                )
            ],
            'tupel of boolean plus empty array' => [
                <<<EOF
{
    'contactgroups': (True, [])
}
EOF
                ,
                $this->decodeJSON(
                    <<<EOF
{
    "contactgroups": [
        true,
        []
    ]
}
EOF
                )
            ],
            'tupel of string plus integer' => [
                <<<EOF
{
    'socket': ('monitoring.example.com', 6559)
}
EOF
                ,
                $this->decodeJSON(
                    <<<EOF
{
    "socket": [
        "monitoring.example.com",
        6559
    ]
}
EOF
                )
            ],
            'tupel of integer plus float' => [
                <<<EOF
{
    'heartbeat': (5, 2.1)
}
EOF
                ,
                $this->decodeJSON(
                    <<<EOF
{
    "heartbeat": [
        5,
        2.1
    ]
}
EOF
                )
            ],
            'tupel of integer plus float (again)' => [
                <<<EOF
{
    'key': (123, 123.45)
}
EOF
                ,
                $this->decodeJSON(
                    <<<EOF
{
    "key": [
        123,
        123.45
    ]
}
EOF
                )
            ],
            'tupel of string and None (null)' => [
                <<<EOF
{
    'key': ('abc', None)
}
EOF
                ,
                $this->decodeJSON(
                    <<<EOF
{
    "key": [
        "abc",
        null
    ]
}
EOF
                )
            ],
            'tupel of another string plus None (null)' => [
                <<<EOF
{
    'key': ('abc_123.456-789', None)
}
EOF
                ,
                $this->decodeJSON(
                    <<<EOF
{
    "key": [
        "abc_123.456-789",
        null
    ]
}
EOF
                )
            ],
            'tupel of booleans' => [
                <<<EOF
{
    'key': (True, False)
}
EOF
                ,
                $this->decodeJSON(
                    <<<EOF
{
    "key": [
        true,
        false
    ]
}
EOF
                )
            ],
            'tupel of strings' => [
                <<<EOF
{
    'key': ('foo', 'bar')
}
EOF
                ,
                $this->decodeJSON(
                    <<<EOF
{
    "key": [
        "foo",
        "bar"
    ]
}
EOF
                )
            ],
            'tupel of tupel plus object' => [
                <<<EOF
{
    'key': ((123, 456), {'a': 'b'})
}
EOF
                ,
                $this->decodeJSON(
                    <<<EOF
{
    "key": [
        [
            123,
            456
        ],
        {
            "a": "b"
        }
    ]
}
EOF
                )
            ],
            'unicode' => [
                <<<EOF
{'result': {'site_id': u'sued', 'site_config': {'url_prefix':
'https://172.22.22.252/sued/', 'status_host': None, 'user_sync': None, 'socket': ('proxy',
{'params': None, 'socket': ('10.10.10.10', 6557)}), 'replication': 'slave', 'user_login': True,
'insecure': True, 'disable_wato': True, 'disabled': False, 'alias': u'S\xfcd',
'secret': 'abcdef0123456789', 'replicate_mkps': True, 'timeout': 10, 'persist': False,
'replicate_ec': True, 'multisiteurl': 'https://10.10.10.10/sued/check_mk/',
'customer': 'sued'}, 'configuration_hash': 'ba9fabee9f9b81c4135985291c0bd8fa'}, 'result_code': 0}
EOF
                ,
                $this->decodeJSON(
                    <<<EOF
{
    "result": {
        "site_id": "sued",
        "site_config": {
            "url_prefix": "https://172.22.22.252/sued/",
            "status_host": null,
            "user_sync": null,
            "socket": [
                "proxy",
                {
                    "params": null,
                    "socket": [
                        "10.10.10.10",
                        6557
                    ]
                }
            ],
            "replication": "slave",
            "user_login": true,
            "insecure": true,
            "disable_wato": true,
            "disabled": false,
            "alias": "Süd",
            "secret": "abcdef0123456789",
            "replicate_mkps": true,
            "timeout": 10,
            "persist": false,
            "replicate_ec": true,
            "multisiteurl": "https://10.10.10.10/sued/check_mk/",
            "customer": "sued"
        },
        "configuration_hash": "ba9fabee9f9b81c4135985291c0bd8fa"
    },
    "result_code": 0
}
EOF
                )
            ],
            'bigger example #1' => [
                <<<EOF
{'result': {'': {}, 'foo': {'network_scan_result': {'output': 'The network scan found 0 new hosts.',
'state': True, 'end': 1523271725.991344, 'start': 1523271721.830718}, 'network_scan': {'time_allowed':
((0, 0), (24, 0)), 'run_as': u'bar', 'tag_criticality': 'offline', 'ip_ranges': [('ip_range',
('10.0.0.1', '10.0.0.254'))], 'scan_interval': 3600, 'set_ipaddress': True, 'exclude_ranges': [],
'translate_names': {'case': 'lower', 'drop_domain': True}}, 'snmp_community': 'integrate'}},
'result_code': 0}
EOF
                ,
                $this->decodeJSON(
                    <<<EOF
{
    "result": {
        "": [],
        "foo": {
            "network_scan_result": {
                "output": "The network scan found 0 new hosts.",
                "state": true,
                "end": 1523271725.991344,
                "start": 1523271721.830718
            },
            "network_scan": {
                "time_allowed": [
                    [
                        0,
                        0
                    ],
                    [
                        24,
                        0
                    ]
                ],
                "run_as": "bar",
                "tag_criticality": "offline",
                "ip_ranges": [
                    [
                        "ip_range",
                        [
                            "10.0.0.1",
                            "10.0.0.254"
                        ]
                    ]
                ],
                "scan_interval": 3600,
                "set_ipaddress": true,
                "exclude_ranges": [],
                "translate_names": {
                    "case": "lower",
                    "drop_domain": true
                }
            },
            "snmp_community": "integrate"
        }
    },
    "result_code": 0
}
EOF
                )
            ],
            'bigger example #2' => [
                <<<EOF
{'result': {'attributes': {'network_scan': {'time_allowed': ((0, 0), (24, 0)), 'run_as': u'automation',
'tag_criticality': 'offline', 'ip_ranges': [], 'scan_interval': 86400, 'set_ipaddress': True,
'exclude_ranges': []}, 'tag_snmp': 'no-snmp', 'snmp_community': None, 'ipv6address': '', 'alias': '',
'management_protocol': None, 'site': 'apitest', 'tag_address_family': 'ip-v4-only',
'tag_criticality': 'prod', 'contactgroups': (True, []), 'network_scan_result': {'start': None,
'state': None, 'end': None, 'output': ''}, 'parents': [], 'management_address': '',
'tag_agent': 'cmk-agent', 'tag_networking': 'lan', 'ipaddress': '', 'management_snmp_community': None},
'configuration_hash': 'c6ddf7521153e6cc5ed6b8223e312421'}, 'result_code': 0}
EOF
                ,
                $this->decodeJSON(
                    <<<EOF
{
    "result": {
        "attributes": {
            "network_scan": {
                "time_allowed": [
                    [
                        0,
                        0
                    ],
                    [
                        24,
                        0
                    ]
                ],
                "run_as": "automation",
                "tag_criticality": "offline",
                "ip_ranges": [],
                "scan_interval": 86400,
                "set_ipaddress": true,
                "exclude_ranges": []
            },
            "tag_snmp": "no-snmp",
            "snmp_community": null,
            "ipv6address": "",
            "alias": "",
            "management_protocol": null,
            "site": "apitest",
            "tag_address_family": "ip-v4-only",
            "tag_criticality": "prod",
            "contactgroups": [
                true,
                []
            ],
            "network_scan_result": {
                "start": null,
                "state": null,
                "end": null,
                "output": ""
            },
            "parents": [],
            "management_address": "",
            "tag_agent": "cmk-agent",
            "tag_networking": "lan",
            "ipaddress": "",
            "management_snmp_community": null
        },
        "configuration_hash": "c6ddf7521153e6cc5ed6b8223e312421"
    },
    "result_code": 0
}
EOF
                )
            ],
            'bigger example #3' => [
                <<<EOF
{'result': {'attributes': {'network_scan': {'time_allowed': ((0, 0), (24, 0)), 'run_as': u'automation',
'tag_criticality': 'offline', 'ip_ranges': [], 'scan_interval': 86400, 'set_ipaddress': True,
'exclude_ranges': []}, 'tag_snmp': 'no-snmp', 'snmp_community': None,
'management_ipmi_credentials': None, 'ipv6address': '', 'alias': '', 'management_protocol': None,
'network_scan_result': {'start': None, 'state': None, 'end': None, 'output': ''}, 'site': 'apitest',
'tag_address_family': 'ip-v4-only', u'tag_criticality': u'prod', 'contactgroups': (True, []),
'additional_ipv6addresses': [], 'parents': [], 'management_address': '', u'tag_agent': u'cmk-agent',
'additional_ipv4addresses': [], u'tag_networking': u'lan', 'ipaddress': '',
'management_snmp_community': None}, 'configuration_hash': 'e22ba8d6831e023b771248bde84dd512'},
'result_code': 0}
EOF
                ,
                $this->decodeJSON(
                    <<<EOF
{
    "result": {
        "attributes": {
            "network_scan": {
                "time_allowed": [
                    [
                        0,
                        0
                    ],
                    [
                        24,
                        0
                    ]
                ],
                "run_as": "automation",
                "tag_criticality": "offline",
                "ip_ranges": [],
                "scan_interval": 86400,
                "set_ipaddress": true,
                "exclude_ranges": []
            },
            "tag_snmp": "no-snmp",
            "snmp_community": null,
            "management_ipmi_credentials": null,
            "ipv6address": "",
            "alias": "",
            "management_protocol": null,
            "network_scan_result": {
                "start": null,
                "state": null,
                "end": null,
                "output": ""
            },
            "site": "apitest",
            "tag_address_family": "ip-v4-only",
            "tag_criticality": "prod",
            "contactgroups": [
                true,
                []
            ],
            "additional_ipv6addresses": [],
            "parents": [],
            "management_address": "",
            "tag_agent": "cmk-agent",
            "additional_ipv4addresses": [],
            "tag_networking": "lan",
            "ipaddress": "",
            "management_snmp_community": null
        },
        "configuration_hash": "e22ba8d6831e023b771248bde84dd512"
    },
    "result_code": 0
}
EOF
                )
            ],
            'bigger example #4' => [
                <<<EOF
{'result': {'site_id': 'mysite', 'site_config': {'url_prefix': 'http://monitoring.example.com/mysite/',
'status_host': None, 'multisiteurl': 'http://monitoring.example.com/mysite/check_mk/',
'user_sync': 'all', 'socket': ('proxy', {'params': None, 'socket': ('monitoring.example.com', 6557)}),
'alias': u'slave', 'user_login': True, 'insecure': True, 'disable_wato': True, 'disabled': False,
'replication': 'slave', 'secret': 'abcdef0123456789', 'timeout': 10, 'persist': False,
'replicate_ec': False, 'replicate_mkps': True},
'configuration_hash': '00116115ff6b27cffda0944befe72240'}, 'result_code': 0}
EOF
                ,
                $this->decodeJSON(
                    <<<EOF
{
    "result": {
        "site_id": "mysite",
        "site_config": {
            "url_prefix": "http://monitoring.example.com/mysite/",
            "status_host": null,
            "multisiteurl": "http://monitoring.example.com/mysite/check_mk/",
            "user_sync": "all",
            "socket": [
                "proxy",
                {
                    "params": null,
                    "socket": [
                        "monitoring.example.com",
                        6557
                    ]
                }
            ],
            "alias": "slave",
            "user_login": true,
            "insecure": true,
            "disable_wato": true,
            "disabled": false,
            "replication": "slave",
            "secret": "abcdef0123456789",
            "timeout": 10,
            "persist": false,
            "replicate_ec": false,
            "replicate_mkps": true
        },
        "configuration_hash": "00116115ff6b27cffda0944befe72240"
    },
    "result_code": 0
}
EOF
                )
            ],
            'bigger example #5' => [
                <<<EOF
{'result': {'site_id': 'mysite', 'site_config': {'status_host': None,
'url_prefix': 'http://monitoring.example.com/mysite/', 'user_sync': None,
'socket': ('proxy', {'socket': ('monitoring.example.com', 6559), 'query_timeout': 120.0, 'cache': True,
'channels': 5, 'channel_timeout': 3.0, 'heartbeat': (5, 2.0), 'connect_retry': 4.0}),
'replication': 'slave', 'user_login': True, 'insecure': True, 'disable_wato': False, 'disabled': False,
'alias': u'My site', 'secret': 'abcdef0123456789',
'globals': {'cmc_cmk_helpers': 5, 'cmc_check_helpers': 2}, 'replicate_mkps': False, 'timeout': 10,
'persist': False, 'replicate_ec': False,
'multisiteurl': 'http://monitoring.example.com/mysite/check_mk/'},
'configuration_hash': '2f550c15bde70229bd02f20bc1bf3c5b'}, 'result_code': 0}
EOF
                ,
                $this->decodeJSON(
                    <<<EOF
{
    "result": {
        "site_id": "mysite",
        "site_config": {
            "status_host": null,
            "url_prefix": "http://monitoring.example.com/mysite/",
            "user_sync": null,
            "socket": [
                "proxy",
                {
                    "socket": [
                        "monitoring.example.com",
                        6559
                    ],
                    "query_timeout": 120.0,
                    "cache": true,
                    "channels": 5,
                    "channel_timeout": 3.0,
                    "heartbeat": [
                        5,
                        2.0
                    ],
                    "connect_retry": 4.0
                }
            ],
            "replication": "slave",
            "user_login": true,
            "insecure": true,
            "disable_wato": false,
            "disabled": false,
            "alias": "My site",
            "secret": "abcdef0123456789",
            "globals": {
                "cmc_cmk_helpers": 5,
                "cmc_check_helpers": 2
            },
            "replicate_mkps": false,
            "timeout": 10,
            "persist": false,
            "replicate_ec": false,
            "multisiteurl": "http://monitoring.example.com/mysite/check_mk/"
        },
        "configuration_hash": "2f550c15bde70229bd02f20bc1bf3c5b"
    },
    "result_code": 0
}
EOF
                )
            ],
            'Empty objects' => [
                <<<EOF
{'firstLevel': {}, 'someKey': {'secondLevel': {}}}
EOF
                ,
                $this->decodeJSON(
                    <<<EOF
{
    "firstLevel": {},
    "someKey": {
        "secondLevel": {}
    }
}
EOF
                )
            ]
        ];
    }

    /**
     * @dataProvider provideValues
     * @param string $value Value
     * @param array $expected Expected result
     */
    public function testDecode(string $value, array $expected): void {
        $result = Python::decode($value);
        $this->assertIsArray($result, $value);
        $this->assertSame($expected, $result);
    }

    /**
     * @param string $value
     * @return array
     * @throws Exception on error
     */
    protected function decodeJSON(string $value): array {
        $decoded = json_decode($value, true);

        if (!is_array($decoded)) {
            throw new Exception('Cannot handle expected JSON');
        }

        return $decoded;
    }

}
