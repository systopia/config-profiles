<?php

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
