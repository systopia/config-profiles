<?php
/*-------------------------------------------------------+
| Configuration Profiles                                 |
| Copyright (C) 2023 SYSTOPIA                            |
| Author: J. Schuppe (schuppe@systopia.de)               |
+--------------------------------------------------------+
| This program is released as free software under the    |
| Affero GPL license. You can redistribute it and/or     |
| modify it under the terms of this license which you    |
| can read by viewing the included agpl.txt or online    |
| at www.gnu.org/licenses/agpl.html. Removal of this     |
| copyright header is strictly prohibited without        |
| written permission from the original author(s).        |
+--------------------------------------------------------*/

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
