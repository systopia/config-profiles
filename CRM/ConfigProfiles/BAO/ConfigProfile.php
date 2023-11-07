<?php
// phpcs:disable
use CRM_ConfigProfiles_ExtensionUtil as E;
use \Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Civi\Core\Event\GenericHookEvent;
// phpcs:enable

class CRM_ConfigProfiles_BAO_ConfigProfile extends CRM_ConfigProfiles_DAO_ConfigProfile implements EventSubscriberInterface {

  /**
   * @var array $_types
   *   A static cache of classes implementing specific profile types.
   */
  private static ?array $_types;

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
   * @param array $params key-value pairs
   * @return CRM_ConfigProfiles_DAO_ConfigProfile|NULL
   */
  public static function create($params) {
    $className = self::getTypeClass($params['type']) ?? 'CRM_ConfigProfiles_DAO_ConfigProfile';
    $entityName = 'ConfigProfile';
    $hook = empty($params['id']) ? 'create' : 'edit';

    CRM_Utils_Hook::pre($hook, $entityName, CRM_Utils_Array::value('id', $params), $params);
    $instance = new $className();
    $instance->copyValues($params);
    $instance->save();
    CRM_Utils_Hook::post($hook, $entityName, $instance->id, $instance);

    return $instance;
  }

  private static function getTypeClass($type) {
    if (!isset(self::$_types)) {
      self::$_types = [];
      $event = GenericHookEvent::create(['types' => &self::$_types]);
      Civi::dispatcher()->dispatch('civi.config_profiles.types', $event);
    }

    return self::$_types[$type];
  }

  public static function getTypes($includeFields = FALSE) {
    $types = Civi::cache('metadata')->get('ConfigProfileTypes');
    if (!is_array($types)) {
      // We can not use APIv4 for retrieving types (which are OptionValues) as
      // this causes an infinite loop when called inside
      // hook_civicrm_entityTypes().
      $optionGroupId = \CRM_Core_DAO::getFieldValue(
        'CRM_Core_DAO_OptionGroup',
        'config_profile_type',
        'id',
        'name'
      );
      // The OptionGroup might not yet exist when installing.
      if ($optionGroupId) {
        $types = CRM_Core_DAO::executeQuery(
          "SELECT `name`, `label`, `description`, `value` AS 'class', `icon`
      FROM `civicrm_option_value`
      WHERE `civicrm_option_value`.`option_group_id` = $optionGroupId"
        )->fetchAll();
        foreach ($types as &$type) {
          $type['entity_name'] = 'ConfigProfile_' . $type['name'];
          if (empty($type['icon'])) {
            $type['icon'] = 'fa-cogs';
          }
        }
        $types = array_combine(array_column($types, 'name'), $types);
        Civi::cache('metadata')->set('ConfigProfileTypes', $types);
      }
    }
    return $types ?? [];
  }

  public static function getTypeFromApiEntityName($entityName) {
    return strpos($entityName, 'ConfigProfile_') === 0 ? substr($entityName, strlen('ConfigProfile_')) : NULL;
  }

  public static function getClassFromTypeName($type_name) {
    $types = self::getTypes();
    return $types[$type_name]['class'];
  }

  /**
   * Make configuration profiles available to FormBuilder.
   *
   * @param GenericHookEvent $e
   */
  public static function afformEntityTypes(GenericHookEvent $event) {
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
   * @throws \API_Exception
   */
  public static function getConfigProfileAfforms($event) {
    // Early return if forms are not requested.
    if ($event->getTypes && !in_array('form', $event->getTypes, TRUE)) {
      return;
    }

    $afforms =& $event->afforms;
    $getNames = $event->getNames;

    // Early return if this API call is fetching afforms by name and those names are not related to ConfigProfiles.
    if (
      (!empty($getNames['name']) && !strstr(implode(' ', $getNames['name']), 'ConfigProfile_'))
      || (!empty($getNames['module_name']) && !strstr(implode(' ', $getNames['module_name']), 'ConfigProfile'))
      || (!empty($getNames['directive_name']) && !strstr(implode(' ', $getNames['directive_name']), 'config_profile'))
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
        $fields = \Civi\Api4\ConfigProfile::getFields()
          ->addValue('type', $profile_type['name'])
          ->addSelect('name')
          ->addWhere('readonly', 'IS EMPTY')
          ->addWhere('input_type', 'IS NOT EMPTY')
          // Don't allow type to be changed on the form, since this form is specific to type.
          ->addWhere('name', '!=', 'type')
          // Don't include the serialized "data" field in the form, as individual data properties will have their own fields per type.
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
