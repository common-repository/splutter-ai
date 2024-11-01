<?php
/*
Plugin Name: Splutter AI Chatbot | Ticketing, Lead Capture, Voicebot, Best Chatbot Builder
Plugin URI: https://splutter.ai
Description: Embed your Splutter AI Chatbot on any Wordpress site.
Version: 1.0
Author: Splutter AI
License: GPL2
Text Domain: splutter-ai
*/

// Exit if accessed directly
if ( ! defined( 'ABSPATH' ) ) {
    exit;
}

// Define plugin version
define( 'SPLUTTER_CHATBOT_VERSION', '1.0' );

// Add settings menu
add_action( 'admin_menu', 'splutter_chatbot_create_menu' );

function splutter_chatbot_create_menu() {
    add_menu_page(
        'Splutter AI Chatbot Settings',
        'Splutter AI Chatbot',
        'manage_options',
        'splutter-chatbot-settings',
        'splutter_chatbot_settings_page',
        esc_url( plugin_dir_url( __FILE__ ) . 'icon-16x16.png' ), // Admin menu icon
        81 // Position in the admin menu
    );

    // Register settings
    add_action( 'admin_init', 'splutter_chatbot_register_settings' );
}

function splutter_chatbot_register_settings() {
    register_setting( 'splutter-chatbot-settings-group', 'splutter_chatbot_id', 'splutter_chatbot_sanitize_text' );
    register_setting( 'splutter-chatbot-settings-group', 'splutter_chatbot_align', 'splutter_chatbot_sanitize_align' );
    register_setting( 'splutter-chatbot-settings-group', 'splutter_chatbot_background', 'splutter_chatbot_sanitize_text' );
    register_setting( 'splutter-chatbot-settings-group', 'splutter_chatbot_blur', 'splutter_chatbot_sanitize_blur' );
    register_setting( 'splutter-chatbot-settings-group', 'splutter_chatbot_size', 'splutter_chatbot_sanitize_size' );
    register_setting( 'splutter-chatbot-settings-group', 'splutter_chatbot_draggable', 'splutter_chatbot_sanitize_draggable' );
}

// Sanitization functions
function splutter_chatbot_sanitize_text( $input ) {
    return sanitize_text_field( $input );
}

function splutter_chatbot_sanitize_align( $input ) {
    $valid = array( 'BOTTOM-RIGHT', 'BOTTOM-LEFT', 'TOP-RIGHT', 'TOP-LEFT', 'MIDDLE-RIGHT', 'MIDDLE-LEFT', 'CUSTOM' );
    if ( in_array( $input, $valid, true ) ) {
        return $input;
    } else {
        return 'BOTTOM-RIGHT'; // default value
    }
}

function splutter_chatbot_sanitize_blur( $input ) {
    $input = intval( $input );
    if ( $input < 0 ) $input = 0;
    if ( $input > 100 ) $input = 100;
    return $input;
}

function splutter_chatbot_sanitize_size( $input ) {
    $input = floatval( $input );
    if ( $input < 0.2 ) $input = 0.2;
    if ( $input > 10 ) $input = 10;
    return $input;
}

function splutter_chatbot_sanitize_draggable( $input ) {
    if ( 'true' === $input || 'false' === $input ) {
        return $input;
    } else {
        return 'false';
    }
}

// Enqueue custom admin styles and scripts
add_action( 'admin_enqueue_scripts', 'splutter_chatbot_admin_assets' );

function splutter_chatbot_admin_assets( $hook ) {
    if ( 'toplevel_page_splutter-chatbot-settings' !== $hook ) {
        return;
    }

    // Enqueue WordPress color picker
    wp_enqueue_style( 'wp-color-picker' );
    wp_enqueue_script( 'wp-color-picker' );

    // Enqueue custom CSS and JS with version
    wp_enqueue_style(
        'splutter-chatbot-admin-css',
        plugin_dir_url( __FILE__ ) . 'admin-style.css',
        array(),
        SPLUTTER_CHATBOT_VERSION
    );
    wp_enqueue_script(
        'splutter-chatbot-admin-js',
        plugin_dir_url( __FILE__ ) . 'admin-script.js',
        array( 'jquery', 'wp-color-picker' ),
        SPLUTTER_CHATBOT_VERSION,
        true
    );
}

function splutter_chatbot_settings_page() {
    ?>
    <div class="splutter-wrap">
    <div class="splutter-header">
            <img src="<?php echo esc_url( plugin_dir_url( __FILE__ ) . 'logo.png' ); ?>" alt="Splutter AI Logo" class="splutter-logo">
            <h1>Splutter AI Chatbot Settings</h1>
        </div>

        <!-- Small blurb about creating a Chatbot ID -->
        <div style="margin-top: 0px; margin-bottom: 10px;">
            *Important*: To integrate your chatbot with your WordPress site, please enter your Chatbot ID below.
            If you haven't created a chatbot yet, you can create one on the Splutter AI platform.
        </div>
        <form method="post" action="options.php" class="splutter-form">
            <?php settings_fields( 'splutter-chatbot-settings-group' ); ?>
            <?php do_settings_sections( 'splutter-chatbot-settings-group' ); ?>

            <div class="splutter-fields">

                <!-- (Form fields remain unchanged) -->

                <div class="splutter-field">
                    <label for="splutter_chatbot_id">Chatbot ID</label>
                    <input type="text" name="splutter_chatbot_id" id="splutter_chatbot_id" value="<?php echo esc_attr( get_option( 'splutter_chatbot_id' ) ); ?>" required />
                </div>

                <div class="splutter-field">
                    <label for="splutter_chatbot_align">Alignment</label>
                    <select name="splutter_chatbot_align" id="splutter_chatbot_align">
                        <option value="BOTTOM-RIGHT" <?php selected( get_option( 'splutter_chatbot_align', 'BOTTOM-RIGHT' ), 'BOTTOM-RIGHT' ); ?>>Bottom Right</option>
                        <option value="BOTTOM-LEFT" <?php selected( get_option( 'splutter_chatbot_align' ), 'BOTTOM-LEFT' ); ?>>Bottom Left</option>
                        <option value="TOP-RIGHT" <?php selected( get_option( 'splutter_chatbot_align' ), 'TOP-RIGHT' ); ?>>Top Right</option>
                        <option value="TOP-LEFT" <?php selected( get_option( 'splutter_chatbot_align' ), 'TOP-LEFT' ); ?>>Top Left</option>
                        <option value="MIDDLE-RIGHT" <?php selected( get_option( 'splutter_chatbot_align' ), 'MIDDLE-RIGHT' ); ?>>Middle Right</option>
                        <option value="MIDDLE-LEFT" <?php selected( get_option( 'splutter_chatbot_align' ), 'MIDDLE-LEFT' ); ?>>Middle Left</option>
                        <option value="CUSTOM" <?php selected( get_option( 'splutter_chatbot_align' ), 'CUSTOM' ); ?>>Custom</option>
                    </select>
                </div>

                <div class="splutter-field">
                    <label for="splutter_chatbot_background">Background Color</label>
                    <input type="text" name="splutter_chatbot_background" id="splutter_chatbot_background" value="<?php echo esc_attr( get_option( 'splutter_chatbot_background', '#FFFFFF' ) ); ?>" class="color-field" />
                </div>

                <div class="splutter-field">
                    <label for="splutter_chatbot_blur">Blur (0 to 100)</label>
                    <input type="number" name="splutter_chatbot_blur" id="splutter_chatbot_blur" value="<?php echo esc_attr( get_option( 'splutter_chatbot_blur', '10' ) ); ?>" min="0" max="100" />
                </div>

                <div class="splutter-field">
                    <label for="splutter_chatbot_size">Size (0.2 to 10)</label>
                    <input type="number" name="splutter_chatbot_size" id="splutter_chatbot_size" value="<?php echo esc_attr( get_option( 'splutter_chatbot_size', '0.5' ) ); ?>" min="0.2" max="10" step="0.1" />
                </div>

                <div class="splutter-field">
                    <label for="splutter_chatbot_draggable">Draggable</label>
                    <select name="splutter_chatbot_draggable" id="splutter_chatbot_draggable">
                        <option value="false" <?php selected( get_option( 'splutter_chatbot_draggable', 'false' ), 'false' ); ?>>No</option>
                        <option value="true" <?php selected( get_option( 'splutter_chatbot_draggable' ), 'true' ); ?>>Yes</option>
                    </select>
                </div>

            </div>

            <?php 
            submit_button( 'Save Changes', 'primary', 'submit', true, array( 'class' => 'splutter-save-button' ) );
            ?>

        </form>
        <!-- Need Help Section -->
        <div class="splutter-need-help">
            <h2>Need Help?</h2>
            <div>
                For detailed instructions on integrating your chatbot, please visit our 
                <a href="https://splutter.ai/guides/first-steps/integrate-chatbot" target="_blank" style="color: blue">Integration Guide</a>.
                After creating your chatbot, use the Chatbot ID provided to configure this plugin.
            </div>
        </div>
    </div>
    <?php
}

// Enqueue Scripts in Frontend
add_action( 'wp_enqueue_scripts', 'splutter_chatbot_enqueue_scripts' );

function splutter_chatbot_enqueue_scripts() {
    $chatbot_id = esc_js( get_option( 'splutter_chatbot_id' ) );

    if ( empty( $chatbot_id ) ) {
        return;
    }

    $align       = esc_js( get_option( 'splutter_chatbot_align', 'BOTTOM-RIGHT' ) );
    $background  = esc_js( get_option( 'splutter_chatbot_background', '#FFFFFF' ) );
    $blur        = esc_js( get_option( 'splutter_chatbot_blur', '10' ) );
    $size        = esc_js( get_option( 'splutter_chatbot_size', '0.5' ) );
    $draggable   = esc_js( get_option( 'splutter_chatbot_draggable', 'false' ) );

    // Register the external script with version
    wp_register_script( 
        'splutter-chatbot-script', 
        'https://splutter.ai/embed.min.js', 
        array(), 
        '1.0.0', // Replace with actual version
        true 
    );

    // Localize the script with new data
    $chatbot_config = array(
        'chatbotId'  => $chatbot_id,
        'domain'     => 'https://splutter.ai',
        'align'      => $align,
        'background' => $background,
        'blur'       => $blur,
        'size'       => $size,
        'draggable'  => $draggable,
    );

    wp_localize_script( 'splutter-chatbot-script', 'embeddedChatbotConfig', $chatbot_config );

    // Enqueue script with localized data.
    wp_enqueue_script( 'splutter-chatbot-script' );
}
?>
