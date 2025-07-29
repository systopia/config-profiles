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

namespace Civi\ConfigProfiles\Api;

use Civi\API\Events;
use Civi\Core\Event\GenericHookEvent;
use Civi\Core\Service\AutoSubscriber;
use CRM_ConfigProfiles_ExtensionUtil as E;

class ConfigProfile extends AutoSubscriber {

  /**
   * @inheritDoc
   */
  public static function getSubscribedEvents() {
    return [
      'civi.api4.entityTypes' => ['onApi4EntityTypes', Events::W_EARLY],
    ];
  }

  /**
   * Register each configuration profile type as an APIv4 entity.
   *
   * Callback for `civi.api4.entityTypes` event.
   *
   * @param \Civi\Core\Event\GenericHookEvent $event
   */
  public function onApi4EntityTypes(GenericHookEvent $event): void {
    foreach (\CRM_ConfigProfiles_BAO_ConfigProfile::getTypes() as $profile_type) {
      $event->entities[$profile_type['entity_name']] = [
        'name' => $profile_type['entity_name'],
        'title' => E::ts('%1 Configuration Profile', [1 => $profile_type['label']]),
        'title_plural' => E::ts('%1 Configuration Profiles', [1 => $profile_type['label']]),
        'description' => ts('Configuration Profile %1', [1 => $profile_type['label']]),
        'primary_key' => ['id'],
        'type' => ['ConfigProfile'],
        'table_name' => 'civicrm_config_profile',
        'class_args' => [$profile_type['name']],
        'label_field' => 'name',
        'icon_field' => ['icon'],
        'searchable' => 'secondary',
        'paths' => [
          'browse' => "civicrm/admin/config-profile/{$profile_type['name']}",
          'view' => "civicrm/admin/config-profile/{$profile_type['name']}/edit#?{$profile_type['entity_name']}=[id]",
          'update' => "civicrm/admin/config-profile/{$profile_type['name']}/edit#?{$profile_type['entity_name']}=[id]",
          'add' => "civicrm/admin/config-profile/{$profile_type['name']}/edit",
        ],
        'class' => '\Civi\Api4\ConfigProfile',
        'icon' => $profile_type['icon'],
      ];
    }
    $event->entities['ConfigProfile'] = [
      'name' => 'ConfigProfile',
      'title' => E::ts('Configuration Profile (generic)'),
      'title_plural' => E::ts('Configuration Profiles (generic)'),
      'description' => ts('Configuration Profiles (generic)'),
      'primary_key' => ['id'],
      'type' => ['ConfigProfile'],
      'table_name' => 'civicrm_config_profile',
      'class_args' => [],
      'label_field' => 'name',
      'icon_field' => ['icon'],
      'searchable' => 'secondary',
      'class' => '\Civi\Api4\ConfigProfile',
      'icon' => 'fa-cogs',
    ];
  }

}
