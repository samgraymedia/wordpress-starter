<?php

/**
 * Register Quote / Pull Quote Block Fields
 */
if( function_exists('acf_add_local_field_group') ):

acf_add_local_field_group(array(
    'key' => 'group_quote_block',
    'title' => 'Quote / Pull Quote Block Fields',
    'fields' => array(
        array(
            'key' => 'field_quote_text',
            'label' => 'Quote',
            'name' => 'quote',
            'type' => 'textarea',
            'instructions' => 'The main quote text',
            'required' => 1,
            'placeholder' => 'Enter the quote...',
            'rows' => 4,
        ),
        array(
            'key' => 'field_quote_author',
            'label' => 'Author / Citation',
            'name' => 'author',
            'type' => 'text',
            'instructions' => 'Name of the person being quoted',
            'required' => 0,
            'placeholder' => 'John Doe',
            'maxlength' => 150,
        ),
        array(
            'key' => 'field_quote_role',
            'label' => 'Role / Attribution',
            'name' => 'role',
            'type' => 'text',
            'instructions' => 'Optional role, title, or source attribution',
            'required' => 0,
            'placeholder' => 'CEO at Acme Corp',
            'maxlength' => 150,
            'conditional_logic' => array(
                array(
                    array(
                        'field' => 'field_quote_author',
                        'operator' => '!=',
                        'value' => '',
                    ),
                ),
            ),
        ),
        array(
            'key' => 'field_quote_alignment',
            'label' => 'Alignment',
            'name' => 'alignment',
            'type' => 'button_group',
            'instructions' => 'How to align the quote',
            'required' => 1,
            'choices' => array(
                'left' => 'Left',
                'center' => 'Center',
                'right' => 'Right',
            ),
            'default_value' => 'center',
        ),
        array(
            'key' => 'field_quote_style',
            'label' => 'Style Variant',
            'name' => 'style',
            'type' => 'button_group',
            'instructions' => 'Visual style for the quote',
            'required' => 1,
            'choices' => array(
                'simple' => 'Simple',
                'highlighted' => 'Highlighted',
                'card' => 'Card',
            ),
            'default_value' => 'simple',
        ),
    ),
    'location' => array(
        array(
            array(
                'param' => 'block',
                'operator' => '==',
                'value' => 'acf/quote',
            ),
        ),
    ),
));

endif;
