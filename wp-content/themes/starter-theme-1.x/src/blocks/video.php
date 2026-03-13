<?php

/**
 * Register Video Block Fields
 */
if( function_exists('acf_add_local_field_group') ):

acf_add_local_field_group(array(
    'key' => 'group_video_block',
    'title' => 'Video Block Fields',
    'fields' => array(
        array(
            'key' => 'field_video_title',
            'label' => 'Title',
            'name' => 'title',
            'type' => 'text',
            'instructions' => 'Optional title for the video section',
            'required' => 0,
            'maxlength' => 200,
        ),
        array(
            'key' => 'field_video_type',
            'label' => 'Video Source',
            'name' => 'video_type',
            'type' => 'radio',
            'instructions' => 'Choose whether to use an uploaded video file or YouTube link',
            'required' => 1,
            'choices' => array(
                'file' => 'Uploaded File',
                'youtube' => 'YouTube Link',
            ),
            'default_value' => 'file',
            'layout' => 'horizontal',
        ),
        array(
            'key' => 'field_video_file',
            'label' => 'Video File',
            'name' => 'video_file',
            'type' => 'file',
            'instructions' => 'Upload an MP4 video file',
            'required' => 0,
            'return_format' => 'array',
            'conditional_logic' => array(
                array(
                    array(
                        'field' => 'field_video_type',
                        'operator' => '==',
                        'value' => 'file',
                    ),
                ),
            ),
        ),
        array(
            'key' => 'field_video_poster_image',
            'label' => 'Poster Image',
            'name' => 'poster_image',
            'type' => 'image',
            'instructions' => 'Image to display before video plays (optional)',
            'required' => 0,
            'return_format' => 'array',
            'conditional_logic' => array(
                array(
                    array(
                        'field' => 'field_video_type',
                        'operator' => '==',
                        'value' => 'file',
                    ),
                ),
            ),
        ),
        array(
            'key' => 'field_video_youtube_url',
            'label' => 'YouTube Video ID',
            'name' => 'youtube_url',
            'type' => 'text',
            'instructions' => 'Enter just the video ID (e.g., dQw4w9WgXcQ) not the full URL',
            'required' => 0,
            'placeholder' => 'dQw4w9WgXcQ',
            'conditional_logic' => array(
                array(
                    array(
                        'field' => 'field_video_type',
                        'operator' => '==',
                        'value' => 'youtube',
                    ),
                ),
            ),
        ),
        array(
            'key' => 'field_video_description',
            'label' => 'Description',
            'name' => 'description',
            'type' => 'wysiwyg',
            'instructions' => 'Optional description text below the video',
            'required' => 0,
            'tabs' => 'visual',
            'toolbar' => 'basic',
            'media_upload' => 0,
        ),
    ),
    'location' => array(
        array(
            array(
                'param' => 'block',
                'operator' => '==',
                'value' => 'acf/video',
            ),
        ),
    ),
));

endif;
