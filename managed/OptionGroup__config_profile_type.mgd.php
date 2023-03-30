<?php

use CRM_ConfigProfiles_ExtensionUtil as E;

return [
  [
    'name' => 'OptionGroup__config_profile_type',
    'entity' => 'OptionGroup',
    'cleanup' => 'always',
    'update' => 'unmodified',
    'params' => [
      'version' => 4,
      'values' => [
        'name' => 'config_profile_type',
        'title' => E::ts('Configuration Profile Types'),
        'description' => E::ts('Types of configuration profiles defined and used by extensions implementing the "Configuration Profiles" extension.'),
        'data_type' => 'String',
        'is_reserved' => TRUE,
        'is_active' => TRUE,
        'is_locked' => TRUE,
        'option_value_fields' => [
          'name',
          'label',
          'description',
        ],
      ],
      'match' => [
        'name',
      ],
    ],
  ],
];
