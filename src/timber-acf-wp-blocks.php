<?php
use Timber\Timber;

/**
 * Check if class exists before redefining it
 */
if ( ! class_exists( 'Timber_Acf_Wp_Blocks' ) ) {
    class Timber_Acf_Wp_Blocks {

        public function __construct() {
            if ( is_callable( 'add_action' )
                && is_callable( 'acf_register_block_type' )
                && class_exists( 'Timber' )
            ) {
                add_action( 'acf/init', array( __CLASS__, 'timber_block_init' ), 10, 0 );
            } elseif ( is_callable( 'add_action' ) ) {
                add_action(
                    'admin_notices',
                    function() {
                        echo '<div class="error"><p>Timber ACF WP Blocks requires Timber and ACF.';
                        echo ' Check if the plugins or libraries are installed and activated.</p></div>';
                    }
                );
            }
        }

        /**
         * Initialize Timber blocks from templates
         */
        public static function timber_block_init(): void {
            $directories = self::timber_block_directory_getter();

            foreach ( $directories as $dir ) {
                $full_path = locate_template( $dir );
                if ( ! file_exists( $full_path ) ) {
                    continue;
                }

                $template_directory = new DirectoryIterator( $full_path );
                foreach ( $template_directory as $template ) {
                    if ( $template->isDot() || $template->isDir() ) continue;

                    $file_parts = pathinfo( $template->getFilename() );
                    if ( 'twig' !== $file_parts['extension'] ) continue;

                    // Fix: sanitize block slug
                    $slug = sanitize_title( $file_parts['filename'] );

                    $file_path    = locate_template( $dir . "/{$file_parts['filename']}.twig" );
                    $file_headers = get_file_data(
                        $file_path,
                        array(
                            'title'       => 'Title',
                            'description' => 'Description',
                            'category'    => 'Category',
                            'icon'        => 'Icon',
                            'keywords'    => 'Keywords',
                            'mode'        => 'Mode',
                            'align'       => 'Align',
                            'post_types'  => 'PostTypes',
                        )
                    );

                    if ( empty( $file_headers['title'] ) || empty( $file_headers['category'] ) ) {
                        continue;
                    }

                    $keywords = str_getcsv( $file_headers['keywords'], ' ', '"' );

                    $data = array_filter(array(
                        'name'            => $slug,
                        'title'           => $file_headers['title'],
                        'description'     => $file_headers['description'],
                        'category'        => $file_headers['category'],
                        'icon'            => $file_headers['icon'],
                        'keywords'        => $keywords,
                        'mode'            => $file_headers['mode'],
                        'align'           => $file_headers['align'],
                        'render_callback' => array( __CLASS__, 'timber_blocks_callback' ),
                    ));

                    if ( ! empty( $file_headers['post_types'] ) ) {
                        $data['post_types'] = explode( ' ', $file_headers['post_types'] );
                    }

                    $data = self::timber_block_default_data( $data );

                    acf_register_block_type( $data );
                }
            }
        }

        /**
         * Render Timber ACF block
         */
        public static function timber_blocks_callback( array $block, string $content = '', bool $is_preview = false, int $post_id = 0 ): void {
            $context = method_exists( 'Timber', 'context' ) ? Timber::context() : Timber::get_context();
            $slug    = str_replace( 'acf/', '', $block['name'] );

            $context['block']      = $block;
            $context['post_id']    = $post_id;
            $context['slug']       = $slug;
            $context['is_preview'] = $is_preview;
            $context['fields']     = get_fields();
            $classes               = array_merge(
                array( $slug ),
                isset( $block['className'] ) ? array( $block['className'] ) : array(),
                $is_preview ? array( 'is-preview' ) : array(),
                isset( $block['align'] ) ? array( 'align' . $block['align'] ) : array()
            );

            $context['classes'] = implode( ' ', $classes );

            Timber::render( self::timber_acf_path_render( $slug, $is_preview, false ), $context );
        }

        /**
         * Returns template paths for Timber to render
         */
        public static function timber_acf_path_render( string $slug, bool $is_preview, bool $is_example ): array {
            $directories = self::timber_block_directory_getter();
            $ret         = array();
            $example_identifier = apply_filters( 'timber/acf-gutenberg-blocks-example-identifier', '-example' );
            $preview_identifier = apply_filters( 'timber/acf-gutenberg-blocks-preview-identifier', '-preview' );

            foreach ( $directories as $directory ) {
                if ( $is_example ) $ret[] = "{$directory}/{$slug}{$example_identifier}.twig";
                if ( $is_preview ) $ret[] = "{$directory}/{$slug}{$preview_identifier}.twig";
                $ret[] = "{$directory}/{$slug}.twig";
            }

            return $ret;
        }

        public static function timber_blocks_subdirectories( array $directories ): array {
            $ret = array();
            foreach ( $directories as $base_directory ) {
                if ( ! file_exists( locate_template( $base_directory ) ) ) continue;

                $template_directory = new RecursiveDirectoryIterator(
                    locate_template( $base_directory ),
                    FilesystemIterator::KEY_AS_PATHNAME | FilesystemIterator::CURRENT_AS_SELF
                );

                foreach ( $template_directory as $directory ) {
                    if ( $directory->isDir() && ! $directory->isDot() ) {
                        $ret[] = $base_directory . '/' . $directory->getFilename();
                    }
                }
            }
            return $ret;
        }

        public static function timber_block_directory_getter(): array {
            $directories = apply_filters( 'timber/acf-gutenberg-blocks-templates', array( 'views/blocks' ) );
            $subdirectories = self::timber_blocks_subdirectories( $directories );
            if ( ! empty( $subdirectories ) ) $directories = array_merge( $directories, $subdirectories );
            return $directories;
        }

        public static function timber_block_default_data( array $data ): array {
            $default_data = apply_filters( 'timber/acf-gutenberg-blocks-default-data', array() );
            $data_array   = array();

            if ( ! empty( $data['default_data'] ) ) {
                $default_data_key = $data['default_data'];
                if ( isset( $default_data[ $default_data_key ] ) ) {
                    $data_array = $default_data[ $default_data_key ];
                }
            } elseif ( ! empty( $default_data['default'] ) ) {
                $data_array = $default_data['default'];
            }

            if ( is_array( $data_array ) ) {
                $data = array_merge( $data_array, $data );
            }

            return $data;
        }
    }
}

// Initialize the class
if ( is_callable( 'add_action' ) ) {
    add_action( 'after_setup_theme', function() {
        new Timber_Acf_Wp_Blocks();
    } );
}