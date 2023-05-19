<?php

namespace Civi\ConfigProfiles\Api4\Action;

use Civi\Api4\Generic\DAOSaveAction;
use CRM_ConfigProfiles_ExtensionUtil as E;

class SaveAction extends DAOSaveAction {

  use SaveTrait;

}
