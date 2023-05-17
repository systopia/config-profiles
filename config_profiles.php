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

require_once 'config_profiles.civix.php';

use Symfony\Component\DependencyInjection\ContainerBuilder;
use \Symfony\Component\DependencyInjection\Definition;

// phpcs:disable
use CRM_ConfigProfiles_ExtensionUtil as E;

// phpcs:enable

/**
 * Implements hook_civicrm_config().
 *
 * @link https://docs.civicrm.org/dev/en/latest/hooks/hook_civicrm_config/
 */
function config_profiles_civicrm_config(&$config): void {
  _config_profiles_civix_civicrm_config($config);
}

/**
 * Implements hook_civicrm_install().
 *
 * @link https://docs.civicrm.org/dev/en/latest/hooks/hook_civicrm_install
 */
function config_profiles_civicrm_install(): void {
  _config_profiles_civix_civicrm_install();
}

/**
 * Implements hook_civicrm_enable().
 *
 * @link https://docs.civicrm.org/dev/en/latest/hooks/hook_civicrm_enable
 */
function config_profiles_civicrm_enable(): void {
  _config_profiles_civix_civicrm_enable();
}

function config_profiles_civicrm_container(ContainerBuilder $container) {
  // Register API Provider.
  $apiKernelDefinition = $container->getDefinition('civi_api_kernel');
  $apiProviderDefinition = new Definition('Civi\ConfigProfiles\Api\ConfigProfile');
  $apiKernelDefinition->addMethodCall('registerApiProvider', [$apiProviderDefinition]);
}
