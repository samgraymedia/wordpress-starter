<?php

/**
 * Register Hero Block Fields
 */
if( function_exists('acf_add_local_field_group') ):

acf_add_local_field_group(array(
    'key' => 'group_hero_block',
    'title' => 'Hero Block Fields',
    'fields' => array(
        // Title Lead
        array(
            'key' => 'field_hero_title_lead',
            'label' => 'Title Lead',
            'name' => 'title_lead',
            'type' => 'text',
            'instructions' => 'Optional eyebrow text above the main title',
            'required' => 0,
        ),
        // Title (WYSIWYG)
        array(
            'key' => 'field_hero_title',
            'label' => 'Title',
            'name' => 'title',
            'type' => 'wysiwyg',
            'instructions' => 'Main hero title with formatting support',
            'required' => 1,
            'tabs' => 'all',
            'toolbar' => 'full',
            'media_upload' => 0,
        ),
        // Intro
        array(
            'key' => 'field_hero_intro',
            'label' => 'Intro',
            'name' => 'intro',
            'type' => 'textarea',
            'instructions' => 'Brief introduction text',
            'required' => 0,
            'rows' => 4,
        ),
        // Buttons Repeater
        array(
            'key' => 'field_hero_buttons',
            'label' => 'Buttons',
            'name' => 'buttons',
            'type' => 'repeater',
            'instructions' => 'Add action buttons',
            'required' => 0,
            'min' => 0,
            'max' => 3,
            'layout' => 'table',
            'button_label' => 'Add Button',
            'sub_fields' => array(
                array(
                    'key' => 'field_hero_button_label',
                    'label' => 'Button Label',
                    'name' => 'label',
                    'type' => 'text',
                    'required' => 1,
                ),
                array(
                    'key' => 'field_hero_button_url',
                    'label' => 'Button URL',
                    'name' => 'url',
                    'type' => 'url',
                    'required' => 1,
                ),
                array(
                    'key' => 'field_hero_button_style',
                    'label' => 'Button Style',
                    'name' => 'style',
                    'type' => 'select',
                    'choices' => array(
                        'primary' => 'Primary',
                        'secondary' => 'Secondary',
                    ),
                    'default_value' => 'primary',
                    'allow_null' => 0,
                ),
            ),
        ),
        // Content Type Choice
        array(
            'key' => 'field_hero_content_type',
            'label' => 'Content Type',
            'name' => 'content_type',
            'type' => 'select',
            'instructions' => 'Choose between an image or stats block',
            'required' => 0,
            'choices' => array(
                'image' => 'Image',
                'stats' => 'Stats Block',
            ),
            'default_value' => 'image',
            'allow_null' => 0,
        ),
        // Image (conditional)
        array(
            'key' => 'field_hero_image',
            'label' => 'Image',
            'name' => 'image',
            'type' => 'image',
            'instructions' => 'Hero section background or featured image',
            'required' => 0,
            'return_format' => 'array',
            'conditional_logic' => array(
                array(
                    array(
                        'field' => 'field_hero_content_type',
                        'operator' => '==',
                        'value' => 'image',
                    ),
                ),
            ),
        ),
        // Stats Group (conditional)
        array(
            'key' => 'field_hero_stats_lead',
            'label' => 'Stats Lead',
            'name' => 'stats_lead',
            'type' => 'text',
            'instructions' => 'Label above the stats',
            'required' => 0,
            'conditional_logic' => array(
                array(
                    array(
                        'field' => 'field_hero_content_type',
                        'operator' => '==',
                        'value' => 'stats',
                    ),
                ),
            ),
        ),
        array(
            'key' => 'field_hero_stat_one_number',
            'label' => 'Stat 1 Number',
            'name' => 'stat_one_number',
            'type' => 'text',
            'instructions' => 'First statistic number or value',
            'required' => 0,
            'conditional_logic' => array(
                array(
                    array(
                        'field' => 'field_hero_content_type',
                        'operator' => '==',
                        'value' => 'stats',
                    ),
                ),
            ),
        ),
        array(
            'key' => 'field_hero_stat_two_number',
            'label' => 'Stat 2 Number',
            'name' => 'stat_two_number',
            'type' => 'text',
            'instructions' => 'Second statistic number or value',
            'required' => 0,
            'conditional_logic' => array(
                array(
                    array(
                        'field' => 'field_hero_content_type',
                        'operator' => '==',
                        'value' => 'stats',
                    ),
                ),
            ),
        ),
        array(
            'key' => 'field_hero_stats_description',
            'label' => 'Stats Description',
            'name' => 'stats_description',
            'type' => 'textarea',
            'instructions' => 'Description or context for the stats',
            'required' => 0,
            'rows' => 4,
            'conditional_logic' => array(
                array(
                    array(
                        'field' => 'field_hero_content_type',
                        'operator' => '==',
                        'value' => 'stats',
                    ),
                ),
            ),
        ),
    ),
    'location' => array(
        array(
            array(
                'param' => 'block',
                'operator' => '==',
                'value' => 'acf/hero',
            ),
        ),
    ),
));

endif;
