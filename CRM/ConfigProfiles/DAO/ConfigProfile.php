<?php

/**
 * @package CRM
 * @copyright CiviCRM LLC https://civicrm.org/licensing
 *
 * Generated from config-profiles/xml/schema/CRM/ConfigProfiles/ConfigProfile.xml
 * DO NOT EDIT.  Generated by CRM_Core_CodeGen
 * (GenCodeChecksum:f0e2c546e2ea0a678f8266f4c2761402)
 */
use CRM_ConfigProfiles_ExtensionUtil as E;

/**
 * Database access object for the ConfigProfile entity.
 */
class CRM_ConfigProfiles_DAO_ConfigProfile extends CRM_Core_DAO {
  const EXT = E::LONG_NAME;
  const TABLE_ADDED = '';

  /**
   * Static instance to hold the table name.
   *
   * @var string
   */
  public static $_tableName = 'civicrm_config_profile';

  /**
   * Should CiviCRM log any modifications to this table in the civicrm_log table.
   *
   * @var bool
   */
  public static $_log = TRUE;

  /**
   * Unique ConfigProfile ID.
   *
   * @var int|string|null
   *   (SQL type: int unsigned)
   *   Note that values will be retrieved from the database as a string.
   */
  public $id;

  /**
   * Type of configuration profile.
   *
   * @var string
   *   (SQL type: varchar(255))
   *   Note that values will be retrieved from the database as a string.
   */
  public $type;

  /**
   * Name of configuration profile.
   *
   * @var string
   *   (SQL type: varchar(255))
   *   Note that values will be retrieved from the database as a string.
   */
  public $name;

  /**
   * Actual profile data as defined by the config profile type provider.
   *
   * @var string|null
   *   (SQL type: text)
   *   Note that values will be retrieved from the database as a string.
   */
  public $data;

  /**
   * A comma-separated list of selector values for identifying the profile without exposing its internal ID.
   *
   * @var string|null
   *   (SQL type: varchar(255))
   *   Note that values will be retrieved from the database as a string.
   */
  public $selector;

  /**
   * Whether this configuration profile is active.
   *
   * @var bool|string
   *   (SQL type: tinyint)
   *   Note that values will be retrieved from the database as a string.
   */
  public $is_active;

  /**
   * Whether this is the default configuration profile for this type.
   *
   * @var bool|string
   *   (SQL type: tinyint)
   *   Note that values will be retrieved from the database as a string.
   */
  public $is_default;

  /**
   * When the configuration profile was created.
   *
   * @var string
   *   (SQL type: timestamp)
   *   Note that values will be retrieved from the database as a string.
   */
  public $created_date;

  /**
   * When the configuration profile was created or last modified.
   *
   * @var string
   *   (SQL type: timestamp)
   *   Note that values will be retrieved from the database as a string.
   */
  public $modified_date;

  /**
   * When the configuration profile was last accessed.
   *
   * @var string
   *   (SQL type: timestamp)
   *   Note that values will be retrieved from the database as a string.
   */
  public $access_date;

  /**
   * Class constructor.
   */
  public function __construct() {
    $this->__table = 'civicrm_config_profile';
    parent::__construct();
  }

  /**
   * Returns localized title of this entity.
   *
   * @param bool $plural
   *   Whether to return the plural version of the title.
   */
  public static function getEntityTitle($plural = FALSE) {
    return $plural ? E::ts('Config Profiles') : E::ts('Config Profile');
  }

  /**
   * Returns all the column names of this table
   *
   * @return array
   */
  public static function &fields() {
    if (!isset(Civi::$statics[__CLASS__]['fields'])) {
      Civi::$statics[__CLASS__]['fields'] = [
        'id' => [
          'name' => 'id',
          'type' => CRM_Utils_Type::T_INT,
          'title' => E::ts('ID'),
          'description' => E::ts('Unique ConfigProfile ID.'),
          'required' => TRUE,
          'where' => 'civicrm_config_profile.id',
          'table_name' => 'civicrm_config_profile',
          'entity' => 'ConfigProfile',
          'bao' => 'CRM_ConfigProfiles_DAO_ConfigProfile',
          'localizable' => 0,
          'html' => [
            'type' => 'Number',
          ],
          'readonly' => TRUE,
          'add' => NULL,
        ],
        'type' => [
          'name' => 'type',
          'type' => CRM_Utils_Type::T_STRING,
          'title' => E::ts('Configuration Profile Type'),
          'description' => E::ts('Type of configuration profile.'),
          'required' => TRUE,
          'maxlength' => 255,
          'size' => CRM_Utils_Type::HUGE,
          'where' => 'civicrm_config_profile.type',
          'table_name' => 'civicrm_config_profile',
          'entity' => 'ConfigProfile',
          'bao' => 'CRM_ConfigProfiles_DAO_ConfigProfile',
          'localizable' => 0,
          'html' => [
            'type' => 'Select',
          ],
          'pseudoconstant' => [
            'optionGroupName' => 'config_profile_type',
            'optionEditPath' => 'civicrm/admin/options/config_profile_type',
          ],
          'add' => '1.0',
        ],
        'name' => [
          'name' => 'name',
          'type' => CRM_Utils_Type::T_STRING,
          'title' => E::ts('Config Profile Name'),
          'description' => E::ts('Name of configuration profile.'),
          'required' => TRUE,
          'maxlength' => 255,
          'size' => CRM_Utils_Type::HUGE,
          'where' => 'civicrm_config_profile.name',
          'table_name' => 'civicrm_config_profile',
          'entity' => 'ConfigProfile',
          'bao' => 'CRM_ConfigProfiles_DAO_ConfigProfile',
          'localizable' => 0,
          'html' => [
            'type' => 'Text',
          ],
          'add' => NULL,
        ],
        'data' => [
          'name' => 'data',
          'type' => CRM_Utils_Type::T_TEXT,
          'title' => E::ts('Profile Data'),
          'description' => E::ts('Actual profile data as defined by the config profile type provider.'),
          'where' => 'civicrm_config_profile.data',
          'table_name' => 'civicrm_config_profile',
          'entity' => 'ConfigProfile',
          'bao' => 'CRM_ConfigProfiles_DAO_ConfigProfile',
          'localizable' => 0,
          'serialize' => self::SERIALIZE_JSON,
          'add' => '1.0',
        ],
        'selector' => [
          'name' => 'selector',
          'type' => CRM_Utils_Type::T_STRING,
          'title' => E::ts('Selector'),
          'description' => E::ts('A comma-separated list of selector values for identifying the profile without exposing its internal ID.'),
          'maxlength' => 255,
          'size' => CRM_Utils_Type::HUGE,
          'where' => 'civicrm_config_profile.selector',
          'table_name' => 'civicrm_config_profile',
          'entity' => 'ConfigProfile',
          'bao' => 'CRM_ConfigProfiles_DAO_ConfigProfile',
          'localizable' => 0,
          'html' => [
            'type' => 'Text',
            'label' => E::ts("Selector"),
          ],
          'add' => '1.0',
        ],
        'is_active' => [
          'name' => 'is_active',
          'type' => CRM_Utils_Type::T_BOOLEAN,
          'title' => E::ts('Is active'),
          'description' => E::ts('Whether this configuration profile is active.'),
          'required' => TRUE,
          'where' => 'civicrm_config_profile.is_active',
          'default' => '1',
          'table_name' => 'civicrm_config_profile',
          'entity' => 'ConfigProfile',
          'bao' => 'CRM_ConfigProfiles_DAO_ConfigProfile',
          'localizable' => 0,
          'html' => [
            'type' => 'CheckBox',
            'label' => E::ts("Default"),
          ],
          'add' => '1.0',
        ],
        'is_default' => [
          'name' => 'is_default',
          'type' => CRM_Utils_Type::T_BOOLEAN,
          'title' => E::ts('Is Default Configuration Profile'),
          'description' => E::ts('Whether this is the default configuration profile for this type.'),
          'required' => TRUE,
          'where' => 'civicrm_config_profile.is_default',
          'default' => '0',
          'table_name' => 'civicrm_config_profile',
          'entity' => 'ConfigProfile',
          'bao' => 'CRM_ConfigProfiles_DAO_ConfigProfile',
          'localizable' => 0,
          'html' => [
            'type' => 'CheckBox',
            'label' => E::ts("Default"),
          ],
          'add' => '1.0',
        ],
        'created_date' => [
          'name' => 'created_date',
          'type' => CRM_Utils_Type::T_TIMESTAMP,
          'title' => E::ts('Created Date'),
          'description' => E::ts('When the configuration profile was created.'),
          'required' => FALSE,
          'where' => 'civicrm_config_profile.created_date',
          'default' => 'CURRENT_TIMESTAMP',
          'table_name' => 'civicrm_config_profile',
          'entity' => 'ConfigProfile',
          'bao' => 'CRM_ConfigProfiles_DAO_ConfigProfile',
          'localizable' => 0,
          'add' => '1.0',
        ],
        'modified_date' => [
          'name' => 'modified_date',
          'type' => CRM_Utils_Type::T_TIMESTAMP,
          'title' => E::ts('Modified Date'),
          'description' => E::ts('When the configuration profile was created or last modified.'),
          'required' => FALSE,
          'where' => 'civicrm_config_profile.modified_date',
          'default' => 'CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP',
          'table_name' => 'civicrm_config_profile',
          'entity' => 'ConfigProfile',
          'bao' => 'CRM_ConfigProfiles_DAO_ConfigProfile',
          'localizable' => 0,
          'add' => '1.0',
        ],
        'access_date' => [
          'name' => 'access_date',
          'type' => CRM_Utils_Type::T_TIMESTAMP,
          'title' => E::ts('Access Date'),
          'description' => E::ts('When the configuration profile was last accessed.'),
          'required' => FALSE,
          'where' => 'civicrm_config_profile.access_date',
          'table_name' => 'civicrm_config_profile',
          'entity' => 'ConfigProfile',
          'bao' => 'CRM_ConfigProfiles_DAO_ConfigProfile',
          'localizable' => 0,
          'add' => '1.0',
        ],
      ];
      CRM_Core_DAO_AllCoreTables::invoke(__CLASS__, 'fields_callback', Civi::$statics[__CLASS__]['fields']);
    }
    return Civi::$statics[__CLASS__]['fields'];
  }

  /**
   * Return a mapping from field-name to the corresponding key (as used in fields()).
   *
   * @return array
   *   Array(string $name => string $uniqueName).
   */
  public static function &fieldKeys() {
    if (!isset(Civi::$statics[__CLASS__]['fieldKeys'])) {
      Civi::$statics[__CLASS__]['fieldKeys'] = array_flip(CRM_Utils_Array::collect('name', self::fields()));
    }
    return Civi::$statics[__CLASS__]['fieldKeys'];
  }

  /**
   * Returns the names of this table
   *
   * @return string
   */
  public static function getTableName() {
    return self::$_tableName;
  }

  /**
   * Returns if this table needs to be logged
   *
   * @return bool
   */
  public function getLog() {
    return self::$_log;
  }

  /**
   * Returns the list of fields that can be imported
   *
   * @param bool $prefix
   *
   * @return array
   */
  public static function &import($prefix = FALSE) {
    $r = CRM_Core_DAO_AllCoreTables::getImports(__CLASS__, 'config_profile', $prefix, []);
    return $r;
  }

  /**
   * Returns the list of fields that can be exported
   *
   * @param bool $prefix
   *
   * @return array
   */
  public static function &export($prefix = FALSE) {
    $r = CRM_Core_DAO_AllCoreTables::getExports(__CLASS__, 'config_profile', $prefix, []);
    return $r;
  }

  /**
   * Returns the list of indices
   *
   * @param bool $localize
   *
   * @return array
   */
  public static function indices($localize = TRUE) {
    $indices = [];
    return ($localize && !empty($indices)) ? CRM_Core_DAO_AllCoreTables::multilingualize(__CLASS__, $indices) : $indices;
  }

}
