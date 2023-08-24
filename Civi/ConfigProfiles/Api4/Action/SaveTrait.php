<?php

namespace Civi\ConfigProfiles\Api4\Action;

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
      $type = $item['type'];
      /* @var \Civi\ConfigProfiles\ConfigProfileInterface $class */
      $class = $types[$type]['class'];
      foreach (array_keys($class::getFields()) as $field_name) {
        $item['data'][$field_name] = $item[$field_name];
        unset($item[$field_name]);
      }
      $class::processValues($item);
    }

    return \CRM_ConfigProfiles_BAO_ConfigProfile::writeRecords($items);
  }

}
