<?php
/*
 * Copyright (C) 2025 SYSTOPIA GmbH
 *
 *  This program is free software: you can redistribute it and/or modify
 *  it under the terms of the GNU Affero General Public License as published by
 *  the Free Software Foundation in version 3.
 *
 *  This program is distributed in the hope that it will be useful,
 *  but WITHOUT ANY WARRANTY; without even the implied warranty of
 *  MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 *  GNU Affero General Public License for more details.
 *
 *  You should have received a copy of the GNU Affero General Public License
 *  along with this program.  If not, see <http://www.gnu.org/licenses/>.
 */

declare(strict_types = 1);

// phpcs:disable PSR1.Files.SideEffects
require_once 'config_profiles.civix.php';
// phpcs:enable

use Symfony\Component\DependencyInjection\ContainerBuilder;

// phpcs:disable
use CRM_ConfigProfiles_ExtensionUtil as E;

// phpcs:enable

/**
 * Implements hook_civicrm_config().
 *
 * @link https://docs.civicrm.org/dev/en/latest/hooks/hook_civicrm_config/
 */
function config_profiles_civicrm_config(\CRM_Core_Config &$config): void {
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

function config_profiles_civicrm_container(ContainerBuilder $container): void {
  $container->register(
    \Civi\ConfigProfiles\Api\ConfigProfile::class,
    \Civi\ConfigProfiles\Api\ConfigProfile::class
  )
    ->addTag('kernel.event_subscriber');
}
