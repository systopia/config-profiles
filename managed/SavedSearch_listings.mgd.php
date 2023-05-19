<?php
use CRM_Eck_ExtensionUtil as E;

// Auto-generate saved search and search display for each entity type
$searches = [];
foreach (\CRM_ConfigProfiles_BAO_ConfigProfile::getTypes() as $type) {
  $searches[] = [
    'name' => 'SavedSearch_listing:' . $type['name'],
    'entity' => 'SavedSearch',
    'cleanup' => 'always',
    'update' => 'unmodified',
    'params' => [
      'version' => 4,
      'values' => [
        'name' => 'ConfigProfiles_Listing_' . $type['name'],
        'label' => E::ts('Configuration Profiles: %1', [1 => $type['label']]),
        'form_values' => NULL,
        'search_custom_id' => NULL,
        'api_entity' => $type['entity_name'],
        'api_params' => [
          'version' => 4,
          'select' => [
            'name',
            'is_active',
            'is_default',
            'created_date',
            'modified_date',
          ],
          'orderBy' => [],
          'where' => [
            ['type', '=', $type['name']],
          ],
          'groupBy' => [],
          'join' => [],
          'having' => [],
        ],
        'expires_date' => NULL,
        'description' => NULL,
        'mapping_id' => NULL,
      ],
    ],
  ];
  $searches[] = [
    'name' => 'SavedSearch_listing_display:' . $type['name'],
    'entity' => 'SearchDisplay',
    'cleanup' => 'always',
    'update' => 'unmodified',
    'params' => [
      'version' => 4,
      'values' => [
        'name' => 'ConfigProfiles_Listing_Display_' . $type['name'],
        'label' => E::ts('Configuration Profiles Display: %1', [1 => $type['label']]),
        'saved_search_id.name' => 'ConfigProfiles_Listing_' . $type['name'],
        'type' => 'table',
        'settings' => [
          'actions' => TRUE,
          'limit' => 50,
          'classes' => [
            'table',
            'table-striped',
          ],
          'pager' => [
            'show_count' => TRUE,
            'expose_limit' => TRUE,
          ],
          'placeholder' => 5,
          'sort' => [],
          'columns' => [
            [
              'type' => 'field',
              'key' => 'name',
              'dataType' => 'String',
              'label' => E::ts('Name'),
              'sortable' => TRUE,
            ],
            [
              'type' => 'field',
              'key' => 'is_default',
              'dataType' => 'Boolean',
              'label' => 'Standard',
              'sortable' => TRUE,
            ],
            [
              'type' => 'field',
              'key' => 'is_active',
              'dataType' => 'Boolean',
              'label' => 'Aktiviert',
              'sortable' => TRUE,
            ],
            [
              'type' => 'field',
              'key' => 'created_date',
              'dataType' => 'Timestamp',
              'label' => E::ts('Created'),
              'sortable' => TRUE,
              'rewrite' => E::ts('[created_date] by [created_id.display_name]'),
            ],
            [
              'type' => 'field',
              'key' => 'modified_date',
              'dataType' => 'Timestamp',
              'label' => E::ts('Modified'),
              'sortable' => TRUE,
              'rewrite' => E::ts('[modified_date] by [modified_id.display_name]'),
            ],
            [
              'type' => 'buttons',
              'alignment' => 'text-right',
              'size' => 'btn-xs',
              'links' => [
                [
                  'entity' => $type['entity_name'],
                  'action' => 'update',
                  'join' => '',
                  'target' => 'crm-popup',
                  'icon' => 'fa-pencil',
                  'text' => E::ts('Edit'),
                  'style' => 'default',
                  'path' => '',
                  'condition' => [],
                ],
              ],
            ],
          ],
        ],
        'acl_bypass' => FALSE,
      ],
    ],
  ];
}
return $searches;
