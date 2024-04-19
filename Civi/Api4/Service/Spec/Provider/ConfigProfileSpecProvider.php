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
   * @param \Civi\Api4\Service\Spec\RequestSpec $spec
   */
  public function modifySpec(RequestSpec $spec) {
    if (
      ($type = $spec->getValue('type'))
      && ($class = \CRM_ConfigProfiles_BAO_ConfigProfile::getClassFromTypeName($type))
      && in_array(ConfigProfileInterface::class, class_implements($class))
    ) {
      /**
       * @phpcs:disable
       * @var ConfigProfileInterface $class
       * @phpcs:enable
       */

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
