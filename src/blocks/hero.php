<?php
/**
 * Register Hero Block Fields
 */
if( function_exists('acf_add_local_field_group') ):

acf_add_local_field_group(array(
    'key' => 'group_hero_block',
    'title' => 'Hero Block Fields',
    'fields' => array(
        array(
            'key' => 'field_hero_title',
            'label' => 'Title',
            'name' => 'title',
            'type' => 'text',
            'instructions' => 'Main hero title',
            'required' => 0,
        ),
          array(
            'key' => 'field_hero_title_styled',
            'label' => 'Styled Title',
            'name' => 'title_styled',
            'type' => 'text',
            'instructions' => 'Styled part of the hero title',
            'required' => 0,
        ),
        array(
            'key' => 'field_hero_background_media',
            'label' => 'Background Media',
            'name' => 'background_media',
            'type' => 'file',
            'instructions' => 'Upload a background image or video',
            'required' => 0,
            'return_format' => 'id',
            'library' => 'all',
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
