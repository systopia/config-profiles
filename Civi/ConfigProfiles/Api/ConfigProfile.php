<?php

namespace Civi\ConfigProfiles\Api;

use Civi\API\Events;
use Civi\Core\Event\GenericHookEvent;
use \Symfony\Component\EventDispatcher\EventSubscriberInterface;
use \Civi\API\Provider\ProviderInterface as ApiProviderInterface;
use CRM_ConfigProfiles_ExtensionUtil as E;

class ConfigProfile implements EventSubscriberInterface, ApiProviderInterface {

  /**
   * @inheritDoc
   */
  public static function getSubscribedEvents() {
    return [
      'civi.api4.entityTypes' => ['onApi4EntityTypes', Events::W_EARLY],
      'civi.afform_admin.metadata' => 'afformEntityTypes',
      'civi.afform.get' => 'getConfigProfileAfforms',
    ];
  }

  /**
   * {@inheritDoc}
   */
  public function getEntityNames($version) {
    return [];
  }

  /**
   * {@inheritDoc}
   */
  public function getActionNames($version, $entity) {
    return [];
  }

  /**
   * Register each configuration profile type as an APIv4 entity.
   *
   * Callback for `civi.api4.entityTypes` event.
   *
   * @param GenericHookEvent $event
   */
  public function onApi4EntityTypes(GenericHookEvent $event) {
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
      'title_plural' => E::ts('Configuration Profile (generic)'),
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

  /**
   * Make configuration profiles available to FormBuilder.
   *
   * @param GenericHookEvent $e
   */
  public static function afformEntityTypes(GenericHookEvent $e) {
    foreach (\CRM_ConfigProfiles_BAO_ConfigProfile::getTypes() as $profile_type) {
      $e->entities[$profile_type['entity_name']] = [
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

  /**
   * {@inheritDoc}
   */
  public function invoke($apiRequest) {
  }

}
