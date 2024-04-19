<?php
/*
 * Copyright (C) 2022 SYSTOPIA GmbH
 *
 *  This program is free software: you can redistribute it and/or modify
 *  it under the terms of the GNU Affero General Public License as published by
 *  the Free Software Foundation in version 3.
 *
 *  This program is distributed in the hope that it will be useful,
 *  but WITHOUT ANY WARRANTY; without even the implied warranty of
 *  MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 *  GNU Affero General Public License for more details.
 *
 *  You should have received a copy of the GNU Affero General Public License
 *  along with this program.  If not, see <http://www.gnu.org/licenses/>.
 */

declare(strict_types = 1);

namespace Civi\Api4;

use Civi\Api4\Action\GetActions;
use Civi\Api4\Generic\BasicReplaceAction;
use Civi\Api4\Generic\CheckAccessAction;
use Civi\Api4\Generic\DAODeleteAction;
use Civi\Api4\Generic\DAOGetFieldsAction;
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
class ConfigProfile {

  /**
   * @param string $profile_type
   * @param bool $checkPermissions
   *
   * @return \Civi\Api4\Generic\DAOGetFieldsAction
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
   * @return \Civi\ConfigProfiles\Api4\Action\GetAction
   * @throws \CRM_Core_Exception
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
   * @return \Civi\ConfigProfiles\Api4\Action\SaveAction
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
   * @return \Civi\ConfigProfiles\Api4\Action\CreateAction
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
   * @return \Civi\ConfigProfiles\Api4\Action\UpdateAction
   * @throws \CRM_Core_Exception
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
   * @return \Civi\Api4\Generic\DAODeleteAction
   * @throws \CRM_Core_Exception
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
   * @return \Civi\Api4\Generic\BasicReplaceAction
   * @throws \CRM_Core_Exception
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
   * @return \Civi\Api4\Action\GetActions
   */
  public static function getActions(string $profile_type = NULL, $checkPermissions = TRUE) {
    return (new GetActions('ConfigProfile', __FUNCTION__))
      ->setCheckPermissions($checkPermissions);
  }

  /**
   * @return \Civi\Api4\Action\GetLinks
   */
  public static function getLinks(bool $checkPermissions = TRUE) {
    // CiviCRM 5.70+
    if (class_exists('Civi\Api4\Action\GetLinks')) {
      return (new \Civi\Api4\Action\GetLinks('ConfigProfile', __FUNCTION__))
        ->setCheckPermissions($checkPermissions);
    }
    // Older versions do not support this action so just return a placeholder
    else {
      // @phpstan-ignore-next-line CiviCRM <5.70 does not have class \Civi\Api4\Generic\BasicGetAction.
      return (new \Civi\Api4\Generic\BasicGetAction('ConfigProfile', __FUNCTION__, fn() => []))
        ->setCheckPermissions($checkPermissions);
    }
  }

  /**
   * @param string $profile_type
   *
   * @return \Civi\Api4\Generic\CheckAccessAction
   */
  public static function checkAccess(string $profile_type = NULL) {
    $action = (new CheckAccessAction('ConfigProfile', __FUNCTION__));
    if (isset($profile_type)) {
      $action->addValue('type', $profile_type);
    }
    return $action;
  }

  /**
   * @return array<string, array{label: string, description?: string}>
   */
  public static function permissions(): array {
    // TODO: Add per-profile-type permissions.
    return [];
  }

}
