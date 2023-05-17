<?php

namespace Civi\Api4;

use Civi\Api4\Action\GetActions;
use Civi\Api4\Generic\BasicReplaceAction;
use Civi\Api4\Generic\CheckAccessAction;
use Civi\Api4\Generic\DAOCreateAction;
use Civi\Api4\Generic\DAODeleteAction;
use Civi\Api4\Generic\DAOGetAction;
use Civi\Api4\Generic\DAOGetFieldsAction;
use Civi\Api4\Generic\DAOSaveAction;
use Civi\Api4\Generic\DAOUpdateAction;

/**
 * ConfigProfile entity.
 *
 * Provided by the Configuration Profiles extension.
 *
 * @package Civi\Api4
 */
class ConfigProfile {

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
      $action->addValue('type', $profile_type);
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
    $action = (new DAOGetAction('ConfigProfile', __FUNCTION__))
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
   * @return \Civi\Api4\Generic\AutocompleteAction
   * @throws \API_Exception
   */
  public static function autocomplete(string $profile_type = NULL, $checkPermissions = TRUE) {
    $action = (new \Civi\Api4\Generic\AutocompleteAction('ConfigProfile', __FUNCTION__))
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
   * @return EckDAOSaveAction
   * @throws \API_Exception
   */
  public static function save(string $profile_type = NULL, $checkPermissions = TRUE) {
    $action = (new DAOSaveAction('ConfigProfile', __FUNCTION__))
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
   * @return EckDAOCreateAction
   * @throws \API_Exception
   */
  public static function create(string $profile_type = NULL, $checkPermissions = TRUE) {
    $action = (new DAOCreateAction('ConfigProfile', __FUNCTION__))
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
    $action = (new DAOUpdateAction('ConfigProfile', __FUNCTION__))
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
   * @return EckDAODeleteAction
   * @throws \API_Exception
   */
  public static function delete(string $profile_type = NULL, $checkPermissions = TRUE) {
    $action = (new DAODeleteAction('ConfigProfile', __FUNCTION__))
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
   * @return BasicReplaceAction
   * @throws \API_Exception
   */
  public static function replace(string $profile_type = NULL, $checkPermissions = TRUE) {
    $action = (new BasicReplaceAction('ConfigProfile', __FUNCTION__))
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
