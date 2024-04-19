<?php

namespace Civi\ConfigProfiles;

use Civi\Api4\Service\Spec\RequestSpec;

class GenericConfigProfile extends \CRM_ConfigProfiles_BAO_ConfigProfile implements ConfigProfileInterface {

  public static function getFields(): array {
    return [];
  }

  public static function modifyFieldSpec(RequestSpec $spec): void {}

  public static function processValues(array $item, array &$data): void {}

}
