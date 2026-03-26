<?php
/**
 * Dark Mode Theme Options
 * 
 * Manages theme preference settings for WordPress admin and REST API endpoint
 * for users to save their theme preference server-side (optional).
 * 
 * @package StartTheme
 */

namespace StartTheme\ThemeToggle;

/**
 * Check if dark mode is enabled in ACF theme options
 * 
 * @return bool
 */
function is_dark_mode_enabled() {
    $enabled = get_field( 'enable_dark_mode', 'option' );
    
    // Treat empty value as enabled (for backward compatibility)
    if ( $enabled === '' ) {
        return true;
    }
    
    return (bool) $enabled;
}

/**
 * Initialize theme toggle functionality
 */
function init() {
    // Register REST API endpoint for theme preferences
    add_action( 'rest_api_init', __NAMESPACE__ . '\register_rest_routes' );
    
    // Enqueue theme toggle script early (in head) to prevent flash
    add_action( 'wp_head', __NAMESPACE__ . '\inline_theme_script' );
    
    // Add nonce for theme toggle
    add_action( 'wp_head', __NAMESPACE__ . '\output_theme_nonce' );
}

/**
 * Register REST API route for getting/setting theme preference
 * Endpoint: /wp-json/starter-theme/v1/theme-preference
 */
function register_rest_routes() {
    register_rest_route(
        'starter-theme/v1',
        '/theme-preference',
        array(
            array(
                'methods'             => \WP_REST_Server::READABLE,
                'callback'            => __NAMESPACE__ . '\get_theme_preference',
                'permission_callback' => '__return_true',
            ),
            array(
                'methods'             => \WP_REST_Server::CREATABLE,
                'callback'            => __NAMESPACE__ . '\set_theme_preference',
                'permission_callback' => __NAMESPACE__ . '\check_theme_write_permission',
                'args'                => array(
                    'preference' => array(
                        'type'              => 'string',
                        'enum'              => array( 'light', 'dark', 'system' ),
                        'required'          => true,
                        'sanitize_callback' => 'sanitize_text_field',
                    ),
                ),
            ),
        )
    );
}

/**
 * Get current user's theme preference
 * 
 * @return \WP_REST_Response
 */
function get_theme_preference( $request ) {
    $user_id = get_current_user_id();
    $dark_mode_enabled = is_dark_mode_enabled();
    
    if ( $user_id ) {
        // Logged-in user: get from user meta
        $preference = get_user_meta( $user_id, 'theme_preference', true );
    } else {
        // Anonymous user: preference is stored client-side in localStorage
        // Server-side default is empty (browser will use system preference)
        $preference = '';
    }
    
    return rest_ensure_response(
        array(
            'preference'       => $preference ?: 'system',
            'is_user'          => (bool) $user_id,
            'dark_mode_enabled' => $dark_mode_enabled,
        )
    );
}

/**
 * Set user's theme preference
 * 
 * @param \WP_REST_Request $request
 * @return \WP_REST_Response
 */
function set_theme_preference( $request ) {
    $user_id = get_current_user_id();
    
    if ( ! $user_id ) {
        return rest_ensure_response(
            array(
                'success'   => false,
                'message'   => 'User must be logged in',
                'store'     => 'localStorage',
            )
        );
    }
    
    $preference = $request->get_param( 'preference' );
    
    // Validate preference
    if ( ! in_array( $preference, array( 'light', 'dark', 'system' ), true ) ) {
        return rest_ensure_response(
            array(
                'success' => false,
                'message' => 'Invalid theme preference',
            ),
            400
        );
    }
    
    // Save to user meta
    update_user_meta( $user_id, 'theme_preference', $preference );
    
    return rest_ensure_response(
        array(
            'success'      => true,
            'preference'   => $preference,
            'message'      => 'Theme preference saved',
        )
    );
}

/**
 * Check permission for writing theme preference
 * Only logged-in users can save server-side preference
 * 
 * @return bool
 */
function check_theme_write_permission() {
    // Allow authenticated users to set their own preference
    // Anonymous users will use localStorage client-side
    return is_user_logged_in() || true; // Allow but preference won't be saved
}

/**
 * Output theme toggle script inline in <head>
 * Must run before any other scripts to prevent flash of wrong theme
 * Checks if dark mode is enabled in ACF options before running
 * 
 * @return void
 */
function inline_theme_script() {
    // Get dark mode setting from ACF options (defaults to true if not set)
    $dark_mode_enabled = get_field( 'enable_dark_mode', 'option' );
    if ( $dark_mode_enabled === '' ) {
        $dark_mode_enabled = true;
    }
    ?>
<script>
(function() {
    // Dark mode feature toggle (controlled via ACF options page)
    const DARK_MODE_ENABLED = <?php echo wp_json_encode( (bool) $dark_mode_enabled ); ?>;
    
    if ( ! DARK_MODE_ENABLED ) {
        return; // Exit if dark mode is disabled in theme options
    }
    
    const STORAGE_KEY = 'theme-preference';
    const ATTR_NAME = 'data-theme';
    
    function getCurrent() {
        const theme = document.documentElement.getAttribute(ATTR_NAME);
        return (theme === 'light' || theme === 'dark') ? theme : 'system';
    }
    
    function getReflected() {
        const current = getCurrent();
        if (current === 'system') {
            return window.matchMedia('(prefers-color-scheme: dark)').matches ? 'dark' : 'light';
        }
        return current;
    }
    
    function applyTheme(theme) {
        if (theme === 'system') {
            document.documentElement.removeAttribute(ATTR_NAME);
        } else {
            document.documentElement.setAttribute(ATTR_NAME, theme);
        }
    }
    
    // Read stored preference
    const stored = localStorage.getItem(STORAGE_KEY);
    
    if (stored === 'light' || stored === 'dark' || stored === 'system') {
        applyTheme(stored);
    } else {
        // Use system preference by default (no attribute needed)
        applyTheme('system');
    }
})();
</script>
    <?php
}

/**
 * Output theme toggle nonce for AJAX requests
 * 
 * @return void
 */
function output_theme_nonce() {
    $nonce = wp_create_nonce( 'theme_toggle_nonce' );
    echo '<script>window.themeToggleNonce = "' . esc_attr( $nonce ) . '";</script>' . "\n";
}

/**
 * Get user's stored theme preference
 * 
 * @param int $user_id User ID (default: current user)
 * @return string Theme preference: 'light', 'dark', or 'system'
 */
function get_user_theme_preference( $user_id = 0 ) {
    if ( ! $user_id ) {
        $user_id = get_current_user_id();
    }
    
    if ( ! $user_id ) {
        return 'system';
    }
    
    $preference = get_user_meta( $user_id, 'theme_preference', true );
    return $preference ?: 'system';
}

/**
 * Update user's theme preference
 * 
 * @param string $preference Theme preference: 'light', 'dark', or 'system'
 * @param int    $user_id    User ID (default: current user)
 * @return bool|int
 */
function set_user_theme_preference( $preference, $user_id = 0 ) {
    if ( ! $user_id ) {
        $user_id = get_current_user_id();
    }
    
    if ( ! $user_id ) {
        return false;
    }
    
    // Validate preference
    if ( ! in_array( $preference, array( 'light', 'dark', 'system' ), true ) ) {
        return false;
    }
    
    return update_user_meta( $user_id, 'theme_preference', $preference );
}

// Initialize on theme load
init();
