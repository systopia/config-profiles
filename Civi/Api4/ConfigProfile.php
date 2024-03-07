<?php

namespace Civi\Api4;

use Civi\Api4\Action\GetActions;
use Civi\Api4\Generic\BasicReplaceAction;
use Civi\Api4\Generic\CheckAccessAction;
use Civi\Api4\Generic\DAODeleteAction;
use Civi\Api4\Generic\DAOEntity;
use Civi\Api4\Generic\DAOGetAction;
use Civi\Api4\Generic\DAOGetFieldsAction;
use Civi\Api4\Generic\Result;
use Civi\ConfigProfiles\Api4\Action\CreateAction;
use Civi\ConfigProfiles\Api4\Action\GetAction;
use Civi\ConfigProfiles\Api4\Action\SaveAction;
use Civi\ConfigProfiles\Api4\Action\UpdateAction;

/**
 * ConfigProfile entity.
 *
 * Provided by the Configuration Profiles extension.
 *
 * @package Civi\Api4
 */
class ConfigProfile extends DAOEntity {

  /**
   * @param string $profile_type
   * @param bool $checkPermissions
   *
   * @return DAOGetFieldsAction
   */
  public static function getFields(string $profile_type = NULL, $checkPermissions = TRUE) {
    $action = (new DAOGetFieldsAction('ConfigProfile', __FUNCTION__))
      ->setCheckPermissions($checkPermissions);
    if (isset($profile_type)) {
      $action
        ->addValue('type', $profile_type);
    }
    return $action;
  }

  /**
   * @param string $profile_type
   * @param bool $checkPermissions
   *
   * @return DAOGetAction
   * @throws \API_Exception
   */
  public static function get(string $profile_type = NULL, $checkPermissions = TRUE) {
    $action = (new GetAction('ConfigProfile', __FUNCTION__))
      ->setCheckPermissions($checkPermissions);
    if (isset($profile_type)) {
      $action->setType($profile_type);
      $action->addWhere('type', '=', $profile_type);
    }
    return $action;
  }

  /**
   * @param string $profile_type
   * @param bool $checkPermissions
   *
   * @return \Civi\Api4\Generic\AutocompleteAction
   * @throws \API_Exception
   */
  public static function autocomplete(string $profile_type = NULL, $checkPermissions = TRUE) {
    $action = (new \Civi\Api4\Generic\AutocompleteAction('ConfigProfile', __FUNCTION__))
      ->setCheckPermissions($checkPermissions);
    if (isset($profile_type)) {
      $action->addFilter('type', $profile_type);
    }
    return $action;
  }

  /**
   * @param string $profile_type
   * @param bool $checkPermissions
   *
   * @return EckDAOSaveAction
   * @throws \API_Exception
   */
  public static function save(string $profile_type = NULL, $checkPermissions = TRUE) {
    $action = (new SaveAction('ConfigProfile', __FUNCTION__))
      ->setCheckPermissions($checkPermissions);
    if (isset($profile_type)) {
      $action->addDefault('type', $profile_type);
    }
    return $action;
  }

  /**
   * @param string $profile_type
   * @param bool $checkPermissions
   *
   * @return EckDAOCreateAction
   * @throws \API_Exception
   */
  public static function create(string $profile_type = NULL, $checkPermissions = TRUE) {
    $action = (new CreateAction('ConfigProfile', __FUNCTION__))
      ->setCheckPermissions($checkPermissions);
    if (isset($profile_type)) {
      $action->addValue('type', $profile_type);
    }
    return $action;
  }

  /**
   * @param string $profile_type
   * @param bool $checkPermissions
   *
   * @return EckDAOUpdateAction
   * @throws \API_Exception
   */
  public static function update(string $profile_type = NULL, $checkPermissions = TRUE) {
    $action = (new UpdateAction('ConfigProfile', __FUNCTION__))
      ->setCheckPermissions($checkPermissions);
    if (isset($profile_type)) {
      $action
        ->addWhere('type', '=', $profile_type)
        ->addValue('type', $profile_type);
    }
    return $action;
  }

  /**
   * @param string $profile_type
   * @param bool $checkPermissions
   *
   * @return EckDAODeleteAction
   * @throws \API_Exception
   */
  public static function delete(string $profile_type = NULL, $checkPermissions = TRUE) {
    $action = (new DAODeleteAction('ConfigProfile', __FUNCTION__))
      ->setCheckPermissions($checkPermissions);
    if (isset($profile_type)) {
      $action->addWhere('type', '=', $profile_type);
    }
    return $action;
  }

  /**
   * @param string $profile_type
   * @param bool $checkPermissions
   *
   * @return BasicReplaceAction
   * @throws \API_Exception
   */
  public static function replace(string $profile_type = NULL, $checkPermissions = TRUE) {
    $action = (new BasicReplaceAction('ConfigProfile', __FUNCTION__))
      ->setCheckPermissions($checkPermissions);
    if (isset($profile_type)) {
      $action
        ->addWhere('type', '=', $profile_type)
        ->addDefault('type', $profile_type);
    }
    return $action;
  }

  /**
   * @param string $profile_type
   * @param bool $checkPermissions
   *
   * @return GetActions
   */
  public static function getActions(string $profile_type = NULL, $checkPermissions = TRUE) {
    return (new GetActions('ConfigProfile', __FUNCTION__))
      ->setCheckPermissions($checkPermissions);
  }

  /**
   * @param string $profile_type
   *
   * @return CheckAccessAction
   * @throws \API_Exception
   */
  public static function checkAccess(string $profile_type = NULL) {
    $action = (new CheckAccessAction('ConfigProfile', __FUNCTION__));
    if (isset($profile_type)) {
      $action->addValue('type', $profile_type);
    }
    return $action;
  }

  /**
   * @return array
   */
  public static function permissions(): array {
    // TODO: Add per-profile-type permissions.
    return [];
  }

}
