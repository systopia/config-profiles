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

namespace Civi\Api4\Service\Spec\Provider;

use Civi\Api4\Service\Spec\Provider\Generic\SpecProviderInterface;
use Civi\Api4\Service\Spec\RequestSpec;
use Civi\ConfigProfiles\ConfigProfileInterface;

class ConfigProfileSpecProvider implements SpecProviderInterface {

  /**
   * This adds "pseudo" fields to the API specification depending on the value
   * for "type".
   *
   * @param \Civi\Api4\Service\Spec\RequestSpec $spec
   */
  public function modifySpec(RequestSpec $spec) {
    if (
      is_string($type = $spec->getValue('type'))
      && is_string($class = \CRM_ConfigProfiles_BAO_ConfigProfile::getClassFromTypeName($type))
      && is_a($class, ConfigProfileInterface::class, TRUE)
    ) {
      // Add pseudo data fields to the API sepcification.
      foreach ($class::getFields() as $field_name => $field) {
        $spec->addFieldSpec($field);
      }

      // Allow custom modifications.
      $class::modifyFieldSpec($spec);
    }
  }

  /**
   * @param string $entity
   * @param string $action
   *
   * @return bool
   */
  public function applies($entity, $action) {
    return $entity === 'ConfigProfile';
  }

}
