<div af-fieldset>
  <div class="pull-right btn-group">
    <a class="crm-popup" href="{literal}{{{/literal}:: crmUrl('civicrm/admin/config-profile/{$profileType.name}/edit') {literal}}}{/literal}">
      <i class="fa-fw{if $profileType.icon} crm-i {$profileType.icon}{/if}"></i>
        {ts 1=$profileType.label}Add %1{/ts}
    </a>
  </div>
  <div class="af-container af-layout-inline">
    <af-field name="name" defn="{ldelim}required: false, input_attrs: {ldelim}placeholder: '{ts}Filter by Name{/ts}'{rdelim}, label: false{rdelim}" ></af-field>
  </div>
  <crm-search-display-table search-name="ConfigProfiles_Listing_{$profileType.name}" display-name="ConfigProfiles_Listing_Display_{$profileType.name}"></crm-search-display-table>
</div>
