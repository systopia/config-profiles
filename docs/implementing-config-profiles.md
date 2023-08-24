# Implementing Configuration Profiles

## Generic Implementation

You could use this extension for persisting your profiles without implementing a
specific configuration profile class by just defining a configuration profile
type and interacting with the generic API. Any additional configuration values
would need to be JSON-serialized and passed in the `data` property.

## Adding Configuration Profile Types

Configuration profile types are *OptionValue*s within the *Configuration Profile
Types*  (`config_profile_type`) *OptionGroup*. Each *OptionValue* needs those
attributes:

* `value` - the implementing class, for generic use this would be set to
  `\Civi\ConfigProfiles\GenericConfigProfile`
* `name` - the machine-readable name of the type
* `label` - the human-readable name of the type
* `description` (optional) - the description of the type, e.&nbsp;g. its purpose
  or structure

## Working with Configuration Profiles

This extension provides *SearchKit*- and *FormBuilder*-based listings and forms
for configuration profiles for each type. You might need to clear caches after
adding a configuration profile type for them to be created. Currently, there are
no navigation menu items for those listings, so you will have to access them by
manually heading to `/civicrm/admin/config-profile/[name]` or by looking them up
in the *FormBuilder* UI. You might want to add navigation menu items manually
for the listings of your manually defined configuration profile types.

Creating, retrieving, updating and deleting a configuration profile
programmatically works using the CiviCRM API (version 4) with the generic API
entity `ConfigProfile` and its actions `create`, `get`, `update`, `delete`, etc.
or the profile-type-specific API entity `ConfigProfile_[name]`.

!!!hint
    Mandatory attributes for creating configuration profiles are `type` and
    `name`.

!!!tip
    As the type's value is the implementing class, you might want to pass the
    type's machine-readable name with the `type:name` API parameter instead.

## Specific Implementations

The primarily-intended way of implementing *Configuration Profiles* with your
extension is to provide a configuration profile type programmatically and also
a distinct controller class. This allows you to define additional profile
properties as fields with a specific field type, widget, and special processing
if necessary.

### Defining Fields

Your `ConfigProfile` class should implement the
`\Civi\ConfigProfiles\ConfigProfileInterface`, especially the `getFields()`
method, which needs to return an array of `\Civi\Api4\Service\Spec\FieldSpec`
objects. Those fields will be considered by the extension's
`\Civi\Api4\Service\Spec\Provider\Generic\SpecProviderInterface` implementation.

Each API4 field specification will add a field to *FormBuilder* forms and save
its value as an array element in the generic `data` property of the
configuration profile.

You might want to transform the value prior to saving to the database with the
`processValues()` method. Currently, this is not possible the other way around
when fetching those values from the database.

You can also modify the entire field specification by implementing the
`modifyFieldSpec()` method, allowing you to add more fields that should not be
persisted in the `data` property, but might be needed for constructing
properties. Again, this has not yet been used and thus lacks some things to be
actually useful.

If you sub-class your profile type class from the
`\CRM_ConfigProfiles_BAO_ConfigProfile` class, you can add getter methods for
the additional properties of your profile for easy access to what is persisted
in the `dta` property.

An implementation might look like this example:

```php
namespace Civi\MyExtension;

class ConfigProfile extends \CRM_ConfigProfiles_BAO_ConfigProfile implements Civi\ConfigProfiles\ConfigProfileInterface {

  public static function getFields(): array {
    return [
      'api_base_uri' => (new FieldSpec('api_base_uri', 'ConfigProfile_' . self::NAME, 'String'))
        ->setTitle(ts('API Base URI'))
        ->setLabel(ts('API Base URI'))
        ->setDescription(ts('API base URI for this configuration profile.'))
        ->setRequired(TRUE)
        ->setInputType('Text'),
    ];
  }

  public static function modifyFieldSpec(RequestSpec $spec): void {}

  public static function processValues(array &profile): void {}

  public function getApiBaseUri(): string {
    $data = self::unSerializeField($this->data, self::SERIALIZE_JSON);
    return (string) $data['api_base_uri'];
  }

}
```
