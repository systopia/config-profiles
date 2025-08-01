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

use CRM_ConfigProfiles_ExtensionUtil as E;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Civi\Core\Event\GenericHookEvent;

// phpcs:disable Generic.Files.LineLength.TooLong
class CRM_ConfigProfiles_BAO_ConfigProfile extends CRM_ConfigProfiles_DAO_ConfigProfile implements EventSubscriberInterface {
// phpcs:enable

  /**
   * @var array<string, string>
   *   A static cache of classes implementing specific profile types.
   */
  private static ?array $_types = NULL;

  /**
   * @inheritDoc
   */
  public static function getSubscribedEvents(): array {
    return [
      'civi.afform_admin.metadata' => 'afformEntityTypes',
      'civi.afform.get' => 'getConfigProfileAfforms',
    ];
  }

  /**
   * Create a new ConfigProfile based on array-data
   *
   * @param array{type: string, id?: string|int} $params
   *   Key-value pairs
   * @return CRM_ConfigProfiles_DAO_ConfigProfile|NULL
   */
  public static function create($params) {
    $className = self::getTypeClass($params['type']) ?? 'CRM_ConfigProfiles_DAO_ConfigProfile';
    $entityName = 'ConfigProfile';
    $params['id'] = isset($params['id']) ? (int) $params['id'] : NULL;
    $hook = NULL === $params['id'] ? 'create' : 'edit';

    CRM_Utils_Hook::pre($hook, $entityName, $params['id'], $params);
    /** @var CRM_ConfigProfiles_DAO_ConfigProfile $instance */
    $instance = new $className();
    $instance->copyValues($params);
    $instance->save();
    CRM_Utils_Hook::post($hook, $entityName, (int) $instance->id, $instance);
    /** @phpstan-var CRM_ConfigProfiles_DAO_ConfigProfile $instance */
    return $instance;
  }

  private static function getTypeClass(string $type): ?string {
    if (!isset(self::$_types)) {
      self::$_types = [];
      $event = GenericHookEvent::create(['types' => &self::$_types]);
      Civi::dispatcher()->dispatch('civi.config_profiles.types', $event);
    }

    return self::$_types[$type] ?? NULL;
  }

  /**
   * @param bool $includeFields
   *
   * @return array<string, array{
   *   name: string,
   *   label: string,
   *   description: string,
   *   class: string,
   *   icon: string,
   *   entity_name: string,
   *   }>
   * @throws \CRM_Core_Exception
   * @throws \Civi\Core\Exception\DBQueryException
   */
  public static function getTypes(bool $includeFields = FALSE): array {
    $types = Civi::cache('metadata')->get('ConfigProfileTypes');
    if (!is_array($types)) {
      // We can not use APIv4 for retrieving types (which are OptionValues) as
      // this causes an infinite loop when called inside
      // hook_civicrm_entityTypes().
      $query = CRM_Core_DAO::executeQuery(
        <<<SQL
        SELECT ov.`name`, ov.`label`, ov.`description`, ov.`value` AS 'class', ov.`icon`
        FROM `civicrm_option_value` ov
        INNER JOIN `civicrm_option_group`
          ON `civicrm_option_group`.`id` = ov.`option_group_id`
          AND `civicrm_option_group`.`name` = 'config_profile_type';
        SQL
      );
      if (is_a($query, CRM_Core_DAO::class)) {
        $types = $query->fetchAll();
        foreach ($types as &$type) {
          $type['entity_name'] = 'ConfigProfile_' . $type['name'];
          if (!is_string($type['icon']) || '' === $type['icon']) {
            $type['icon'] = 'fa-cogs';
          }
        }
        $types = array_combine(array_column($types, 'name'), $types);
        Civi::cache('metadata')->set('ConfigProfileTypes', $types);
      }
    }

    /** @phpstan-var array<string, array{
     * name: string,
     * label: string,
     * description: string,
     * class: string,
     * icon: string,
     * entity_name: string,
     * }> $types
     */
    return $types ?? [];
  }

  public static function getTypeFromApiEntityName(string $entityName): ?string {
    return strpos($entityName, 'ConfigProfile_') === 0 ? substr($entityName, strlen('ConfigProfile_')) : NULL;
  }

  public static function getClassFromTypeName(string $type_name): string {
    $types = self::getTypes();
    return $types[$type_name]['class'];
  }

  /**
   * Make configuration profiles available to FormBuilder.
   *
   * @param \Civi\Core\Event\GenericHookEvent $event
   */
  public static function afformEntityTypes(GenericHookEvent $event): void {
    $event->entities['ConfigProfile'] = [
      'entity' => 'ConfigProfile',
      'label' => E::ts('Configuration Profile (generic)'),
      'icon' => 'fa-cogs',
      'type' => 'primary',
      'defaults' => '{}',
    ];

    foreach (\CRM_ConfigProfiles_BAO_ConfigProfile::getTypes() as $profile_type) {
      $event->entities[$profile_type['entity_name']] = [
        'entity' => $profile_type['entity_name'],
        'label' => E::ts('%1 Configuration Profile', [1 => $profile_type['label']]),
        'icon' => $profile_type['icon'],
        'type' => 'primary',
        'defaults' => '{}',
      ];
    }
  }

  /**
   * Generates Afform forms for each configuration profile type.
   *
   * @param \Civi\Core\Event\GenericHookEvent $event
   * @throws \CRM_Core_Exception
   */
  public static function getConfigProfileAfforms($event): void {
    // Early return if forms are not requested.
    if ($event->getTypes && !in_array('form', $event->getTypes, TRUE)) {
      return;
    }

    $afforms =& $event->afforms;
    $getNames = $event->getNames;

    // Early return if this API call is fetching afforms by name and those names are not related to ConfigProfiles.
    if (
      (
        isset($getNames['name'])
        && FALSE === strstr(implode(' ', $getNames['name']), 'ConfigProfile_')
      )
      || (
        isset($getNames['module_name'])
        && FALSE === strstr(implode(' ', $getNames['module_name']), 'ConfigProfile')
      )
      || (
        isset($getNames['directive_name'])
        && FALSE === strstr(implode(' ', $getNames['directive_name']), 'config_profile')
      )
    ) {
      return;
    }

    foreach (\CRM_ConfigProfiles_BAO_ConfigProfile::getTypes() as $profile_type) {
      // Submission form to create/edit profiles of each profile type.
      $name = 'afform' . $profile_type['entity_name'];
      $item = [
        'name' => $name,
        'type' => 'form',
        'title' => E::ts('%1 Configuration Profile', [1 => $profile_type['label']]),
        'description' => '',
        'base_module' => E::LONG_NAME,
        'is_dashlet' => FALSE,
        'is_public' => FALSE,
        'is_token' => FALSE,
        'permission' => 'administer CiviCRM',
        'server_route' => "civicrm/admin/config-profile/{$profile_type['name']}/edit",
      ];
      if ($event->getLayout) {
        $fields = \Civi\Api4\ConfigProfile::getFields($profile_type['name'], FALSE)
          ->addSelect('name')
          ->addWhere('readonly', 'IS EMPTY')
          ->addWhere('input_type', 'IS NOT EMPTY')
          // Don't allow type to be changed on the form, since this form is specific to type.
          ->addWhere('name', '!=', 'type')
          // Don't include the serialized "data" field in the form, as individual data properties will have their own
          // fields per type.
          ->addWhere('name', '!=', 'data')
          ->execute();
        $item['layout'] = \CRM_Core_Smarty::singleton()->fetchWith('ang/afformConfigProfile.tpl', [
          'profileType' => $profile_type,
          'fields' => $fields,
        ]);
      }
      $afforms[$name] = $item;

      // Search listing for for each profile type.
      $name = 'afsearch' . $profile_type['entity_name'] . '_listing';
      $item = [
        'name' => $name,
        'type' => 'search',
        'title' => E::ts('%1 Configuration Profile', [1 => $profile_type['label']]),
        'description' => E::ts('Search listing for %1 configuration profiles', [1 => $profile_type['label']]),
        'base_module' => E::LONG_NAME,
        'is_dashlet' => FALSE,
        'is_public' => FALSE,
        'is_token' => FALSE,
        'permission' => 'administer CiviCRM',
        'server_route' => "civicrm/admin/config-profile/{$profile_type['name']}",
        'requires' => ['crmSearchDisplayTable'],
      ];
      $item['layout'] = \CRM_Core_Smarty::singleton()->fetchWith('ang/afsearch_config-profiles_listing.tpl', [
        'profileType' => $profile_type,
      ]);
      $afforms[$name] = $item;
    }
  }

}
