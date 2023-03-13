<?php
// This file declares a new entity type. For more details, see "hook_civicrm_entityTypes" at:
// https://docs.civicrm.org/dev/en/latest/hooks/hook_civicrm_entityTypes
return [
  [
    'name' => 'ConfigProfile',
    'class' => 'CRM_ConfigProfiles_DAO_ConfigProfile',
    'table' => 'civicrm_config_profile',
  ],
];
