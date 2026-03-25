<?php

/**
 * Register Spacer / Divider Block Fields
 */
if( function_exists('acf_add_local_field_group') ):

acf_add_local_field_group(array(
    'key' => 'group_spacer_block',
    'title' => 'Spacer / Divider Block Fields',
    'fields' => array(
        array(
            'key' => 'field_spacer_height',
            'label' => 'Height',
            'name' => 'height',
            'type' => 'button_group',
            'instructions' => 'Choose preset height or custom value',
            'required' => 1,
            'choices' => array(
                'sm' => 'Small (0.5rem)',
                'md' => 'Medium (1.5rem)',
                'lg' => 'Large (3rem)',
                'xl' => 'Extra Large (4.5rem)',
                'custom' => 'Custom',
            ),
            'default_value' => 'md',
        ),
        array(
            'key' => 'field_spacer_height_custom',
            'label' => 'Custom Height',
            'name' => 'height_custom',
            'type' => 'text',
            'instructions' => 'Enter custom height (e.g., 100px, 5rem, 10vh)',
            'required' => 0,
            'placeholder' => '2rem',
            'conditional_logic' => array(
                array(
                    array(
                        'field' => 'field_spacer_height',
                        'operator' => '==',
                        'value' => 'custom',
                    ),
                ),
            ),
        ),
        array(
            'key' => 'field_spacer_divider_style',
            'label' => 'Divider Style',
            'name' => 'divider_style',
            'type' => 'button_group',
            'instructions' => 'Choose divider appearance',
            'required' => 1,
            'choices' => array(
                'none' => 'None',
                'solid' => 'Solid Line',
                'dashed' => 'Dashed',
                'dotted' => 'Dotted',
            ),
            'default_value' => 'none',
        ),
        array(
            'key' => 'field_spacer_width',
            'label' => 'Divider Width',
            'name' => 'width',
            'type' => 'button_group',
            'instructions' => 'Full width or contained to content',
            'required' => 1,
            'choices' => array(
                'full' => 'Full Width',
                'contained' => 'Contained',
            ),
            'default_value' => 'contained',
            'conditional_logic' => array(
                array(
                    array(
                        'field' => 'field_spacer_divider_style',
                        'operator' => '!=',
                        'value' => 'none',
                    ),
                ),
            ),
        ),
        array(
            'key' => 'field_spacer_alignment',
            'label' => 'Divider Alignment',
            'name' => 'alignment',
            'type' => 'button_group',
            'instructions' => 'Horizontal alignment of divider line',
            'required' => 1,
            'choices' => array(
                'left' => 'Left',
                'center' => 'Center',
                'right' => 'Right',
            ),
            'default_value' => 'center',
            'conditional_logic' => array(
                array(
                    array(
                        'field' => 'field_spacer_divider_style',
                        'operator' => '!=',
                        'value' => 'none',
                    ),
                ),
            ),
        ),
        array(
            'key' => 'field_spacer_color',
            'label' => 'Divider Color',
            'name' => 'color',
            'type' => 'select',
            'instructions' => 'Color of divider line',
            'required' => 1,
            'choices' => array(
                'gray' => 'Gray (Default)',
                'primary' => 'Primary',
                'secondary' => 'Secondary',
                'light' => 'Light Gray',
                'dark' => 'Dark Gray',
            ),
            'default_value' => 'gray',
            'conditional_logic' => array(
                array(
                    array(
                        'field' => 'field_spacer_divider_style',
                        'operator' => '!=',
                        'value' => 'none',
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
                'value' => 'acf/spacer',
            ),
        ),
    ),
));

endif;
