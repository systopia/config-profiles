<?php

namespace Civi\ConfigProfiles;

use Civi\Api4\Service\Spec\RequestSpec;

interface ConfigProfileInterface {

  public static function getFields(): array;

  public static function modifyFieldSpec(RequestSpec $spec): void;

  public static function processValues(array &$profile): void;

}
