<?php

namespace Civi\ConfigProfiles\Api4\Action;

use Civi\Api4\Generic\Result;
use CRM_ConfigProfiles_ExtensionUtil as E;

class GetAction extends \Civi\Api4\Generic\DAOGetAction {

  protected array $selectFields = [];

  public function getObjects(Result $result) {
    foreach ($this->where as $where) {
      if ($where[0] == 'type') {
        $type = $where[2];
        break;
      }
    }
    if (isset($type)) {
      /* @var \Civi\ConfigProfiles\ConfigProfileInterface $class */
      $class = \CRM_ConfigProfiles_BAO_ConfigProfile::getClassFromTypeName($type);
      foreach (array_keys($class::getMetadata()['fields']) as $field_name) {
        // Store pseudo "data" fields in the SELECT clause.
        if (in_array($field_name, $this->select)) {
          $this->selectFields[] = $field_name;
          unset($this->select[array_search($field_name, $this->select)]);
        }

        // TODO: Transform filters for pseudo data fields.
      }
      if (!empty($this->selectFields)) {
        $this->addSelect('data');
      }
    }
    parent::getObjects($result);
    // Set selected pseudo "data" fields as result fields.
    foreach ($this->selectFields as $field_name) {
      foreach ($result as &$row) {
        $row[$field_name] = $row['data'][$field_name];
      }
    }
    unset($row['data']);
  }

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
