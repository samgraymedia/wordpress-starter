<?php

/**
 * Tabs Block
 * ACF Field Configuration
 */

if( function_exists('acf_add_local_field_group') ):

acf_add_local_field_group(array(
    'key' => 'group_tabs_block',
    'title' => 'Tabs Block Fields',
    'fields' => array(
        array(
            'key' => 'field_tabs_title',
            'label' => 'Block Title',
            'name' => 'title',
            'type' => 'text',
            'instructions' => 'Optional title to display above the tabs',
            'required' => 0,
            'placeholder' => 'Features',
        ),
        array(
            'key' => 'field_tabs_items',
            'label' => 'Tabs',
            'name' => 'tabs',
            'type' => 'repeater',
            'instructions' => 'Add tab items with titles and content',
            'required' => 1,
            'layout' => 'block',
            'collapsed' => 'field_tab_title',
            'sub_fields' => array(
                array(
                    'key' => 'field_tab_title',
                    'label' => 'Tab Title',
                    'name' => 'tab_title',
                    'type' => 'text',
                    'instructions' => 'The text displayed in the tab button',
                    'required' => 1,
                    'placeholder' => 'Tab Title',
                ),
                array(
                    'key' => 'field_tab_content',
                    'label' => 'Tab Content',
                    'name' => 'tab_content',
                    'type' => 'textarea',
                    'instructions' => 'The content displayed when this tab is active',
                    'required' => 1,
                    'rows' => 5,
                ),
            ),
        ),
        array(
            'key' => 'field_tabs_default',
            'label' => 'Default Active Tab',
            'name' => 'default_active_tab',
            'type' => 'number',
            'instructions' => 'Which tab should be active by default? (1-based index)',
            'required' => 0,
            'default_value' => 1,
            'min' => 1,
        ),
    ),
    'location' => array(
        array(
            array(
                'param' => 'block',
                'operator' => '==',
                'value' => 'acf/tabs',
            ),
        ),
    ),
    'menu_order' => 0,
    'position' => 'normal',
    'style' => 'default',
    'label_placement' => 'top',
    'instruction_placement' => 'label',
    'hide_on_screen' => '',
    'active' => true,
    'description' => '',
    'show_in_rest' => 0,
));

endif;
?>
