<?php

namespace Civi\ConfigProfiles\Api4\Action;

use Civi\Api4\Generic\DAOGetAction;
use Civi\Api4\Generic\Result;
use CRM_ConfigProfiles_ExtensionUtil as E;

class GetAction extends DAOGetAction {

  protected array $selectFields = [];

  protected ?string $type = NULL;

  public function setType(string $type): void {
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

  /**
   * {@inheritDoc}
   */
  public function getObjects(Result $result): void {
    if (isset($this->type)) {
      /**
       * @phpcs:disable
       * @var \Civi\ConfigProfiles\ConfigProfileInterface $class
       * @phpcs:enable
       */
      $class = \CRM_ConfigProfiles_BAO_ConfigProfile::getClassFromTypeName($this->type);
      foreach (array_keys($class::getFields()) as $field_name) {
        // Store pseudo "data" fields in the SELECT clause.
        if (in_array($field_name, $this->select, TRUE)) {
          $this->selectFields[] = $field_name;
          unset($this->select[array_search($field_name, $this->select, TRUE)]);
        }
      }
      if ([] !== $this->selectFields) {
        $this->addSelect('data');
      }
    }
    parent::getObjects($result);
    // Set selected pseudo "data" fields as result fields.
    foreach ($this->selectFields as $field_name) {
      /**
       * @var array{data: array<string, mixed>} $row
       */
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
      /**
       * @var \Civi\Api4\Provider\ActionObjectProvider $actionObjectProvider
       */
      $actionObjectProvider = \Civi::service('action_object_provider');
      $knownEntities = $actionObjectProvider->getEntities();
      $entity = $this->getEntityName() . '_' . $this->type;
      if (!array_key_exists($entity, $knownEntities)) {
        \Civi::cache('metadata')->clear();
      }
      /**
       * @var \Civi\Api4\Generic\DAOGetFieldsAction $getFields
       */
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
