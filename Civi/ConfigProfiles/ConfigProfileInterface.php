<?php

namespace Civi\ConfigProfiles;

use Civi\Api4\Service\Spec\RequestSpec;

interface ConfigProfileInterface {

  public static function getMetadata(): array;

  public static function modifyFieldSpec(RequestSpec $spec): void;

  public static function processValues(array $item, array &$data): void;

}
