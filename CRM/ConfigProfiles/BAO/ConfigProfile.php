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
  static private array $_types;

  /**
   * @inheritDoc
   */
  public static function getSubscribedEvents(): array {
    return [
      'civi.afform_admin.metadata' => 'afformAdminMetadata',
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

  private function getTypeClass($type) {
    if (!isset(self::$_types)) {
      $event = GenericHookEvent::create(['types' => &self::$_types]);
      Civi::dispatcher()->dispatch('civi.config_profiles.types', $event);
    }

    return self::$_types[$type];
  }

  /**
   * Provides Afform metadata about this entity.
   *
   * @see \Civi\AfformAdmin\AfformAdminMeta::getMetadata().
   */
  public static function afformAdminMetadata(GenericHookEvent $event): void {
    $entity = 'ConfigProfile';
    $event->entities[$entity] = [
      'entity' => $entity,
      'label' => E::ts('Configuration Profile'),
      'icon' => 'fa-cogs',
      'type' => 'primary',
      'defaults' => '{}',
    ];
  }

}
