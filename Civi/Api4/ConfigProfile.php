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
use Civi\Api4\Action\GetLinks;
use Civi\Api4\Generic\AutocompleteAction;
use Civi\Api4\Generic\BasicReplaceAction;
use Civi\Api4\Generic\CheckAccessAction;
use Civi\Api4\Generic\DAODeleteAction;
use Civi\ConfigProfiles\Api4\Action\CreateAction;
use Civi\ConfigProfiles\Api4\Action\GetAction;
use Civi\ConfigProfiles\Api4\Action\GetFieldsAction;
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

  public static function getFields(?string $profile_type = NULL, bool $checkPermissions = TRUE): GetFieldsAction {
    $action = (new GetFieldsAction('ConfigProfile', __FUNCTION__))
      ->setCheckPermissions($checkPermissions);
    if (isset($profile_type)) {
      $action
        ->addValue('type', $profile_type);
    }
    return $action;
  }

  public static function get(?string $profile_type = NULL, bool $checkPermissions = TRUE): GetAction {
    $action = (new GetAction('ConfigProfile', __FUNCTION__))
      ->setCheckPermissions($checkPermissions);
    if (isset($profile_type)) {
      $action->setType($profile_type);
      $action->addWhere('type', '=', $profile_type);
    }
    return $action;
  }

  public static function autocomplete(?string $profile_type = NULL, bool $checkPermissions = TRUE): AutocompleteAction {
    $action = (new \Civi\Api4\Generic\AutocompleteAction('ConfigProfile', __FUNCTION__))
      ->setCheckPermissions($checkPermissions);
    if (isset($profile_type)) {
      $action->addFilter('type', $profile_type);
    }
    return $action;
  }

  public static function save(?string $profile_type = NULL, bool $checkPermissions = TRUE): SaveAction {
    $action = (new SaveAction('ConfigProfile', __FUNCTION__))
      ->setCheckPermissions($checkPermissions);
    if (isset($profile_type)) {
      $action->addDefault('type', $profile_type);
    }
    return $action;
  }

  public static function create(?string $profile_type = NULL, bool $checkPermissions = TRUE): CreateAction {
    $action = (new CreateAction('ConfigProfile', __FUNCTION__))
      ->setCheckPermissions($checkPermissions);
    if (isset($profile_type)) {
      $action->addValue('type', $profile_type);
    }
    return $action;
  }

  public static function update(?string $profile_type = NULL, bool $checkPermissions = TRUE): UpdateAction {
    $action = (new UpdateAction('ConfigProfile', __FUNCTION__))
      ->setCheckPermissions($checkPermissions);
    if (isset($profile_type)) {
      $action
        ->addWhere('type', '=', $profile_type)
        ->addValue('type', $profile_type);
    }
    return $action;
  }

  public static function delete(?string $profile_type = NULL, bool $checkPermissions = TRUE): DAODeleteAction {
    $action = (new DAODeleteAction('ConfigProfile', __FUNCTION__))
      ->setCheckPermissions($checkPermissions);
    if (isset($profile_type)) {
      $action->addWhere('type', '=', $profile_type);
    }
    return $action;
  }

  public static function replace(?string $profile_type = NULL, bool $checkPermissions = TRUE): BasicReplaceAction {
    $action = (new BasicReplaceAction('ConfigProfile', __FUNCTION__))
      ->setCheckPermissions($checkPermissions);
    if (isset($profile_type)) {
      $action
        ->addWhere('type', '=', $profile_type)
        ->addDefault('type', $profile_type);
    }
    return $action;
  }

  public static function getActions(?string $profile_type = NULL, bool $checkPermissions = TRUE): GetActions {
    return (new GetActions('ConfigProfile', __FUNCTION__))
      ->setCheckPermissions($checkPermissions);
  }

  public static function getLinks(bool $checkPermissions = TRUE): GetLinks {
    return (new \Civi\Api4\Action\GetLinks('ConfigProfile', __FUNCTION__))
      ->setCheckPermissions($checkPermissions);
  }

  public static function checkAccess(?string $profile_type = NULL): CheckAccessAction {
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
