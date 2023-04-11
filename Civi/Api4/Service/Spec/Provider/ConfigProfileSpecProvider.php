<?php

namespace Civi\Api4\Service\Spec\Provider;

use Civi\Api4\Service\Spec\Provider\Generic\SpecProviderInterface;
use Civi\Api4\Service\Spec\RequestSpec;
use Civi\ConfigProfiles\ConfigProfileInterface;

class ConfigProfileSpecProvider implements SpecProviderInterface {

  /**
   * @param \Civi\Api4\Service\Spec\RequestSpec $spec
   */
  public function modifySpec(RequestSpec $spec) {
    if (
      ($type = $spec->getValue('type'))
      && in_array(ConfigProfileInterface::class, class_implements($type))
    ) {
      $type::modifyFieldSpec($spec);
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
