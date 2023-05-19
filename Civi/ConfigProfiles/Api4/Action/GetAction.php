<?php

namespace Civi\ConfigProfiles\Api4\Action;

use Civi\Api4\Generic\Result;
use CRM_ConfigProfiles_ExtensionUtil as E;

class GetAction extends \Civi\Api4\Generic\DAOGetAction {

  /**
   * {@inheritDoc}
   */
  public function entityFields() {
    // Add type to getFields parameters if it is in the WHERE clause.
    foreach ($this->where as $where) {
      if ($where[0] == 'type') {
        $type = $where[2];
        break;
      }
    }
    if (isset($type)) {
      $allowedTypes = ['Field', 'Filter', 'Extra'];
      $getFieldsParams = [
        'version' => 4,
        'checkPermissions' => FALSE,
        'action' => $this->getActionName(),
        'where' => [['type', 'IN', $allowedTypes]],
      ];
      $getFields = \Civi\API\Request::create($this->getEntityName() . '_' . $type, 'getFields', $getFieldsParams);
      $result = new Result();
      // Pass TRUE for the private $isInternal param
      $getFields->_run($result, TRUE);
      return (array) $result->indexBy('name');
    }
    else {
      return parent::entityFields();
    }
  }

}
