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
        throw new \CRM_Core_Exception('Unable to determine ConfigProfile type.');
      }

      /** @phpstan-var class-string<\Civi\ConfigProfiles\ConfigProfileInterface> $class */
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

  public static function getType(?int $id = NULL): ?string {
    return isset($id)
      ? ConfigProfile::get(NULL, FALSE)
        ->addSelect('type')
        ->addWhere('id', '=', $id)
        ->execute()
        ->single()['type']
      : NULL;
  }

}
