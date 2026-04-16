<?php

namespace App\Services;

class PublicApiCatalog
{
    public static function groups(): array
    {
        return [
            [
                'key' => 'profile',
                'title_key' => 'ui.public_api.groups.profile',
                'description_key' => 'ui.public_api.groups.profile_description',
                'abilities' => [
                    [
                        'key' => 'public-api.profile.read',
                        'title_key' => 'ui.public_api.abilities.profile_read',
                        'description_key' => 'ui.public_api.abilities.profile_read_description',
                        'routes' => [
                            ['method' => 'GET', 'uri' => '/api/public/me'],
                        ],
                    ],
                ],
            ],
            [
                'key' => 'devices',
                'title_key' => 'ui.public_api.groups.devices',
                'description_key' => 'ui.public_api.groups.devices_description',
                'abilities' => [
                    [
                        'key' => 'public-api.devices.read',
                        'title_key' => 'ui.public_api.abilities.devices_read',
                        'description_key' => 'ui.public_api.abilities.devices_read_description',
                        'routes' => [
                            ['method' => 'GET', 'uri' => '/api/public/devices'],
                            ['method' => 'GET', 'uri' => '/api/public/devices/{device}'],
                        ],
                    ],
                    [
                        'key' => 'public-api.devices.write',
                        'title_key' => 'ui.public_api.abilities.devices_write',
                        'description_key' => 'ui.public_api.abilities.devices_write_description',
                        'routes' => [
                            ['method' => 'POST', 'uri' => '/api/public/devices'],
                            ['method' => 'PUT', 'uri' => '/api/public/devices/{device}'],
                            ['method' => 'DELETE', 'uri' => '/api/public/devices/{device}'],
                        ],
                    ],
                    [
                        'key' => 'public-api.devices.command',
                        'title_key' => 'ui.public_api.abilities.devices_command',
                        'description_key' => 'ui.public_api.abilities.devices_command_description',
                        'routes' => [
                            ['method' => 'POST', 'uri' => '/api/public/devices/{device}/commands'],
                        ],
                    ],
                    [
                        'key' => 'public-api.devices.configuration.read',
                        'title_key' => 'ui.public_api.abilities.devices_configuration_read',
                        'description_key' => 'ui.public_api.abilities.devices_configuration_read_description',
                        'routes' => [
                            ['method' => 'GET', 'uri' => '/api/public/devices/{device}/configuration'],
                        ],
                    ],
                    [
                        'key' => 'public-api.devices.configuration.write',
                        'title_key' => 'ui.public_api.abilities.devices_configuration_write',
                        'description_key' => 'ui.public_api.abilities.devices_configuration_write_description',
                        'routes' => [
                            ['method' => 'PUT', 'uri' => '/api/public/devices/{device}/configuration'],
                        ],
                    ],
                    [
                        'key' => 'public-api.devices.firmware',
                        'title_key' => 'ui.public_api.abilities.devices_firmware',
                        'description_key' => 'ui.public_api.abilities.devices_firmware_description',
                        'routes' => [
                            ['method' => 'POST', 'uri' => '/api/public/devices/{device}/firmware'],
                        ],
                    ],
                ],
            ],
            [
                'key' => 'voice',
                'title_key' => 'ui.public_api.groups.voice',
                'description_key' => 'ui.public_api.groups.voice_description',
                'abilities' => [
                    [
                        'key' => 'public-api.voice.read',
                        'title_key' => 'ui.public_api.abilities.voice_read',
                        'description_key' => 'ui.public_api.abilities.voice_read_description',
                        'routes' => [
                            ['method' => 'GET', 'uri' => '/api/public/voice-commands'],
                            ['method' => 'GET', 'uri' => '/api/public/voice-commands/{voiceCommand}'],
                        ],
                    ],
                    [
                        'key' => 'public-api.voice.write',
                        'title_key' => 'ui.public_api.abilities.voice_write',
                        'description_key' => 'ui.public_api.abilities.voice_write_description',
                        'routes' => [
                            ['method' => 'POST', 'uri' => '/api/public/voice-commands'],
                            ['method' => 'PUT', 'uri' => '/api/public/voice-commands/{voiceCommand}'],
                            ['method' => 'DELETE', 'uri' => '/api/public/voice-commands/{voiceCommand}'],
                        ],
                    ],
                    [
                        'key' => 'public-api.voice.execute',
                        'title_key' => 'ui.public_api.abilities.voice_execute',
                        'description_key' => 'ui.public_api.abilities.voice_execute_description',
                        'routes' => [
                            ['method' => 'POST', 'uri' => '/api/public/voice-commands/execute'],
                        ],
                    ],
                ],
            ],
            [
                'key' => 'scenarios',
                'title_key' => 'ui.public_api.groups.scenarios',
                'description_key' => 'ui.public_api.groups.scenarios_description',
                'abilities' => [
                    [
                        'key' => 'public-api.scenarios.read',
                        'title_key' => 'ui.public_api.abilities.scenarios_read',
                        'description_key' => 'ui.public_api.abilities.scenarios_read_description',
                        'routes' => [
                            ['method' => 'GET', 'uri' => '/api/public/scenarios'],
                            ['method' => 'GET', 'uri' => '/api/public/scenarios/{scenario}'],
                        ],
                    ],
                    [
                        'key' => 'public-api.scenarios.write',
                        'title_key' => 'ui.public_api.abilities.scenarios_write',
                        'description_key' => 'ui.public_api.abilities.scenarios_write_description',
                        'routes' => [
                            ['method' => 'POST', 'uri' => '/api/public/scenarios'],
                            ['method' => 'PUT', 'uri' => '/api/public/scenarios/{scenario}'],
                            ['method' => 'DELETE', 'uri' => '/api/public/scenarios/{scenario}'],
                        ],
                    ],
                ],
            ],
            [
                'key' => 'dashboard',
                'title_key' => 'ui.public_api.groups.dashboard',
                'description_key' => 'ui.public_api.groups.dashboard_description',
                'abilities' => [
                    [
                        'key' => 'public-api.dashboard.read',
                        'title_key' => 'ui.public_api.abilities.dashboard_read',
                        'description_key' => 'ui.public_api.abilities.dashboard_read_description',
                        'routes' => [
                            ['method' => 'GET', 'uri' => '/api/public/dashboard'],
                            ['method' => 'GET', 'uri' => '/api/public/dashboard/{dashboard}'],
                        ],
                    ],
                    [
                        'key' => 'public-api.dashboard.write',
                        'title_key' => 'ui.public_api.abilities.dashboard_write',
                        'description_key' => 'ui.public_api.abilities.dashboard_write_description',
                        'routes' => [
                            ['method' => 'POST', 'uri' => '/api/public/dashboard'],
                            ['method' => 'PUT', 'uri' => '/api/public/dashboard/{dashboard}'],
                            ['method' => 'DELETE', 'uri' => '/api/public/dashboard/{dashboard}'],
                        ],
                    ],
                ],
            ],
            [
                'key' => 'widgets',
                'title_key' => 'ui.public_api.groups.widgets',
                'description_key' => 'ui.public_api.groups.widgets_description',
                'abilities' => [
                    [
                        'key' => 'public-api.widgets.read',
                        'title_key' => 'ui.public_api.abilities.widgets_read',
                        'description_key' => 'ui.public_api.abilities.widgets_read_description',
                        'routes' => [
                            ['method' => 'GET', 'uri' => '/api/public/widgets'],
                        ],
                    ],
                ],
            ],
        ];
    }

    public static function abilities(): array
    {
        $abilities = [];

        foreach (self::groups() as $group) {
            foreach ($group['abilities'] as $ability) {
                $abilities[$ability['key']] = $ability;
            }
        }

        return $abilities;
    }

    public static function keys(): array
    {
        return array_keys(self::abilities());
    }

    public static function routeRows(): array
    {
        $rows = [];

        foreach (self::groups() as $group) {
            foreach ($group['abilities'] as $ability) {
                foreach ($ability['routes'] as $route) {
                    $rows[] = [
                        'ability_key' => $ability['key'],
                        'ability_title_key' => $ability['title_key'],
                        'method' => $route['method'],
                        'uri' => $route['uri'],
                    ];
                }
            }
        }

        return $rows;
    }
}
