<?php

namespace Civi\ConfigProfiles\Api4\Action;

use \Civi\Api4\Generic\DAOGetAction;
use Civi\Api4\Generic\Result;
use CRM_ConfigProfiles_ExtensionUtil as E;

class GetAction extends DAOGetAction {

  protected array $selectFields = [];

  protected ?string $type = NULL;

  public function setType($type) {
    $this->type = $type;
  }

  public function addWhere(string $fieldName, string $op, $value = NULL, bool $isExpression = FALSE) {
    // For pseudo data fields, add conditions for the "data" column with the
    // "CONTAINS" operator.
    $fields = $this->entityFields();
    if (!empty($fields[$fieldName]['column_name'])) {
      return parent::addWhere($fieldName, $op, $value, $isExpression);
    }
    return parent::addWhere('data', 'CONTAINS', '"' . $fieldName . '":"' . $value . '"');
  }

  public function getObjects(Result $result) {
    if (isset($this->type)) {
      /* @var \Civi\ConfigProfiles\ConfigProfileInterface $class */
      $class = \CRM_ConfigProfiles_BAO_ConfigProfile::getClassFromTypeName($this->type);
      foreach (array_keys($class::getFields()) as $field_name) {
        // Store pseudo "data" fields in the SELECT clause.
        if (in_array($field_name, $this->select)) {
          $this->selectFields[] = $field_name;
          unset($this->select[array_search($field_name, $this->select)]);
        }
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
        // TODO: Let configuration type classes transform the values.
      }
    }
    // TODO: Is this correct?
    if (isset($row)) {
      unset($row['data']);
    }
  }

  /**
   * {@inheritDoc}
   */
  public function entityFields() {
    // Add type to getFields parameters if it is in the WHERE clause.
    if (isset($this->type)) {
      $allowedTypes = ['Field', 'Filter', 'Extra'];
      $getFieldsParams = [
        'version' => 4,
        'checkPermissions' => FALSE,
        'action' => $this->getActionName(),
        'where' => [['type', 'IN', $allowedTypes]],
      ];

      // Rebuild API entity cache if ConfigProfile_$type API has not been
      // registered yet.
      // TODO: This load-order issue should be solved differently.
      $knownEntities = \Civi::service('action_object_provider')->getEntities();
      $entity = $this->getEntityName() . '_' . $this->type;
      if (!array_key_exists($entity, $knownEntities)) {
        \Civi::cache('metadata')->clear();
      }
      $getFields = \Civi\API\Request::create($this->getEntityName() . '_' . $this->type, 'getFields', $getFieldsParams);
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
