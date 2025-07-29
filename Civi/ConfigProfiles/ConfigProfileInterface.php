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

namespace Civi\ConfigProfiles;

use Civi\Api4\Service\Spec\RequestSpec;

interface ConfigProfileInterface {

  /**
   * @return array<string, \Civi\Api4\Service\Spec\FieldSpec>
   */
  public static function getFields(): array;

  public static function modifyFieldSpec(RequestSpec $spec): void;

  /**
   * @param array<string, mixed> $profile
   *
   * @return void
   */
  public static function processValues(array &$profile): void;

}
