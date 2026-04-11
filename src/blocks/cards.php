<?php

/**
 * Register Cards Block Fields
 */
if( function_exists('acf_add_local_field_group') ):

acf_add_local_field_group(array(
    'key' => 'group_cards_block',
    'title' => 'Cards Block Fields',
    'fields' => array(
        // Section Lead
        array(
            'key' => 'field_cards_lead',
            'label' => 'Section Lead',
            'name' => 'lead',
            'type' => 'text',
            'instructions' => 'Lead text for the cards section',
            'required' => 0,
        ),
        // Section Title
        array(
            'key' => 'field_cards_title',
            'label' => 'Section Title',
            'name' => 'title',
            'type' => 'text',
            'instructions' => 'Title for the cards section',
            'required' => 0,
        ),
        // Cards Repeater
        array(
            'key' => 'field_cards_repeater',
            'label' => 'Cards',
            'name' => 'cards',
            'type' => 'repeater',
            'instructions' => 'Add up to 3 cards',
            'required' => 0,
            'min' => 0,
            'max' => 3,
            'layout' => 'table',
            'button_label' => 'Add Card',
            'sub_fields' => array(
                // Card Icon
                array(
                    'key' => 'field_card_icon',
                    'label' => 'Icon',
                    'name' => 'icon',
                    'type' => 'image',
                    'instructions' => 'Card icon or image',
                    'required' => 0,
                    'return_format' => 'array',
                ),
                // Card Title
                array(
                    'key' => 'field_card_title',
                    'label' => 'Title',
                    'name' => 'title',
                    'type' => 'text',
                    'instructions' => 'Card title',
                    'required' => 1,
                ),
                // Card Description
                array(
                    'key' => 'field_card_description',
                    'label' => 'Description',
                    'name' => 'description',
                    'type' => 'textarea',
                    'instructions' => 'Card description text',
                    'required' => 0,
                    'rows' => 3,
                ),
                // Card Pill
                array(
                    'key' => 'field_card_pill',
                    'label' => 'Pill Label',
                    'name' => 'pill',
                    'type' => 'text',
                    'instructions' => 'Optional pill/badge label',
                    'required' => 0,
                ),
            ),
        ),
    ),
    'location' => array(
        array(
            array(
                'param' => 'block',
                'operator' => '==',
                'value' => 'acf/cards',
            ),
        ),
    ),
));

endif;
