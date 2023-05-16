<?php

namespace Civi\Api4\Service\Spec\Provider;

use Civi\Api4\Service\Spec\Provider\Generic\SpecProviderInterface;
use Civi\Api4\Service\Spec\RequestSpec;
use Civi\ConfigProfiles\ConfigProfileInterface;

class ConfigProfileSpecProvider implements SpecProviderInterface {

  /**
   * This adds "pseudo" fields to the API specification depending on the value
   * for "type".
   *
   * TODO: Afform does not add the value for "type" when setting a value for
   *       "type", so the form does not know about "pseudo" fields added to the
   *       specification here. So we will have to provide our own API entities
   *       per config-profile type.
   *
   * @param \Civi\Api4\Service\Spec\RequestSpec $spec
   */
  public function modifySpec(RequestSpec $spec) {
    if (
      ($type = $spec->getValue('type'))
      && in_array(ConfigProfileInterface::class, class_implements($type))
    ) {
      /* @var ConfigProfileInterface $type */
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
