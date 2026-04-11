<?php

/**
 * Register Steps Block Fields
 */
if( function_exists('acf_add_local_field_group') ):

acf_add_local_field_group(array(
    'key' => 'group_steps_block',
    'title' => 'Steps Block Fields',
    'fields' => array(
        // Section Lead
        array(
            'key' => 'field_steps_lead',
            'label' => 'Section Lead',
            'name' => 'lead',
            'type' => 'text',
            'instructions' => 'Optional lead text for the steps section',
            'required' => 0,
        ),
        // Section Title
        array(
            'key' => 'field_steps_title',
            'label' => 'Section Title',
            'name' => 'title',
            'type' => 'text',
            'instructions' => 'Title for the steps section',
            'required' => 0,
        ),
        // Steps Repeater
        array(
            'key' => 'field_steps_repeater',
            'label' => 'Steps',
            'name' => 'steps',
            'type' => 'repeater',
            'instructions' => 'Add up to 3 steps',
            'required' => 0,
            'max' => 3,
            'min' => 0,
            'layout' => 'block',
            'button_label' => 'Add Step',
            'sub_fields' => array(
                array(
                    'key' => 'field_steps_step_title',
                    'label' => 'Step Title',
                    'name' => 'step_title',
                    'type' => 'text',
                    'required' => 1,
                ),
                array(
                    'key' => 'field_steps_step_description',
                    'label' => 'Step Description',
                    'name' => 'step_description',
                    'type' => 'textarea',
                    'required' => 0,
                    'rows' => 3,
                ),
            ),
        ),
    ),
    'location' => array(
        array(
            array(
                'param' => 'block',
                'operator' => '==',
                'value' => 'acf/steps',
            ),
        ),
    ),
));

endif;
