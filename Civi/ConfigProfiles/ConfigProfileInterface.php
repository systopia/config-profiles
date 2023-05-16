<?php

namespace Civi\ConfigProfiles;

use Civi\Api4\Service\Spec\RequestSpec;

interface ConfigProfileInterface {

  public static function modifyFieldSpec(RequestSpec $spec): void;

}
