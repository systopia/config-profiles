<?php

namespace Civi\ConfigProfiles\Api4\Action;

use Civi\Api4\ConfigProfile;

trait SaveTrait {

  /**
   * Override core function to save items using the appropriate profile type and
   * transforming pseudo fields into the JSON structure of the "data" field.
   *
   * @param array[] $items
   *   Items already formatted by self::writeObjects
   * @return \CRM_Core_DAO[]
   *   Array of saved DAO records
   */
  protected function write(array $items) {
    $types = \CRM_ConfigProfiles_BAO_ConfigProfile::getTypes();
    foreach ($items as &$item) {
      $type = $item['type'] ?? self::getType($item['id'] ?? NULL);
      if (!isset($type)) {
        throw new \Exception('Unable to determine ConfigProfile type.');
      }

      /**
       * @phpcs:disable
       * @var \Civi\ConfigProfiles\ConfigProfileInterface $class
       * @phpcs:enable
       */
      $class = $types[$type]['class'];
      foreach (array_keys($class::getFields()) as $field_name) {
        if (isset($item[$field_name])) {
          $item['data'][$field_name] = $item[$field_name];
        }
        unset($item[$field_name]);
      }
      $class::processValues($item);
    }

    return \CRM_ConfigProfiles_BAO_ConfigProfile::writeRecords($items);
  }

  public static function getType(int $id = NULL): ?string {
    return isset($id)
      ? ConfigProfile::get(NULL, FALSE)
        ->addSelect('type')
        ->addWhere('id', '=', $id)
        ->execute()
        ->single()['type']
      : NULL;
  }

}
