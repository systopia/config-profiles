<af-form ctrl="afform">
  <af-entity type="{$profileType.entity_name}" name="{$profileType.entity_name}" label="{$profileType.label}" actions="{ldelim}create: true, update: true{rdelim}" security="RBAC" url-autofill="1" data="{ldelim}type: '{$profileType.name}'{rdelim}" ></af-entity>
  <fieldset af-fieldset="{$profileType.entity_name}" class="af-container" af-title="{$profileType.label}">
  {foreach item="field" from=$fields}
    <af-field name="{$field.name}"></af-field>
  {/foreach}
  </fieldset>
  <button class="af-button btn btn-primary" crm-icon="fa-check" ng-click="afform.submit()">{ts}Submit{/ts}</button>
</af-form>
