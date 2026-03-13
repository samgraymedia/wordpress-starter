<?php

/**
 * Register Downloads / Resources Block Fields
 */
if( function_exists('acf_add_local_field_group') ):

acf_add_local_field_group(array(
    'key' => 'group_downloads_block',
    'title' => 'Downloads / Resources Block Fields',
    'fields' => array(
        array(
            'key' => 'field_downloads_title',
            'label' => 'Section Title',
            'name' => 'title',
            'type' => 'text',
            'instructions' => 'Enter the section title (e.g., "Download Resources")',
            'required' => 0,
            'maxlength' => 200,
        ),
        array(
            'key' => 'field_downloads_description',
            'label' => 'Section Description',
            'name' => 'description',
            'type' => 'textarea',
            'instructions' => 'Enter a description or intro text for the downloads section',
            'required' => 0,
            'rows' => 3,
        ),
        array(
            'key' => 'field_downloads_files',
            'label' => 'Files & Resources',
            'name' => 'files',
            'type' => 'repeater',
            'instructions' => 'Add downloadable files (PDFs, documents, etc.)',
            'required' => 1,
            'layout' => 'table',
            'min' => 1,
            'sub_fields' => array(
                array(
                    'key' => 'field_downloads_file',
                    'label' => 'File',
                    'name' => 'file',
                    'type' => 'file',
                    'instructions' => 'Upload a file (PDF, DOC, ZIP, etc.)',
                    'required' => 1,
                    'return_format' => 'url',
                ),
                array(
                    'key' => 'field_downloads_label',
                    'label' => 'Label / Title',
                    'name' => 'label',
                    'type' => 'text',
                    'instructions' => 'Display name for the file',
                    'required' => 1,
                    'maxlength' => 150,
                ),
                array(
                    'key' => 'field_downloads_description',
                    'label' => 'Description',
                    'name' => 'description',
                    'type' => 'textarea',
                    'instructions' => 'Brief description of what this file contains',
                    'required' => 0,
                    'rows' => 2,
                ),
                array(
                    'key' => 'field_downloads_category',
                    'label' => 'Category',
                    'name' => 'category',
                    'type' => 'select',
                    'instructions' => 'Optional category for organizing downloads',
                    'required' => 0,
                    'choices' => array(
                        'guide' => 'Guide',
                        'brochure' => 'Brochure',
                        'form' => 'Form',
                        'policy' => 'Policy',
                        'report' => 'Report',
                        'menu' => 'Menu',
                        'whitepaper' => 'Whitepaper',
                        'case-study' => 'Case Study',
                        'template' => 'Template',
                        'other' => 'Other',
                    ),
                    'allow_null' => 1,
                ),
            ),
        ),
        array(
            'key' => 'field_downloads_show_filesize',
            'label' => 'Show File Size',
            'name' => 'show_filesize',
            'type' => 'true_false',
            'instructions' => 'Display file size next to each download',
            'required' => 0,
            'ui' => 1,
            'default_value' => 1,
        ),
        array(
            'key' => 'field_downloads_show_category',
            'label' => 'Show Category',
            'name' => 'show_category',
            'type' => 'true_false',
            'instructions' => 'Display file category/type badge',
            'required' => 0,
            'ui' => 1,
            'default_value' => 1,
        ),
        array(
            'key' => 'field_downloads_layout',
            'label' => 'Layout Style',
            'name' => 'layout',
            'type' => 'select',
            'instructions' => 'Choose how to display the downloads',
            'required' => 0,
            'choices' => array(
                'list' => 'List',
                'grid' => 'Grid',
            ),
            'default_value' => 'list',
            'allow_null' => 0,
        ),
        array(
            'key' => 'field_downloads_columns',
            'label' => 'Grid Columns (for Grid layout)',
            'name' => 'columns',
            'type' => 'select',
            'instructions' => 'Number of columns for grid layout',
            'required' => 0,
            'choices' => array(
                2 => '2 Columns',
                3 => '3 Columns',
                4 => '4 Columns',
            ),
            'default_value' => 3,
            'conditional_logic' => array(
                array(
                    array(
                        'field' => 'field_downloads_layout',
                        'operator' => '==',
                        'value' => 'grid',
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
                'value' => 'acf/downloads',
            ),
        ),
    ),
));

endif;
