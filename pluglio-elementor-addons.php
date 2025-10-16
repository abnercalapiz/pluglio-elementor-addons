<?php
/**
 * Plugin Name: Pluglio Elementor Addons
 * Description: Custom Elementor widgets and addons with configurable settings
 * Version: 1.0.0
 * Author: Jezweb
 * Author URI: https://jezweb.com.au
 * Text Domain: pluglio-elementor-addons
 * Elementor tested up to: 3.18.0
 * Elementor Pro tested up to: 3.18.0
 */

// Exit if accessed directly
if (!defined('ABSPATH')) {
    exit;
}

// Define plugin constants
define('PLUGLIO_VERSION', '1.0.0');
define('PLUGLIO_URL', plugin_dir_url(__FILE__));
define('PLUGLIO_PATH', plugin_dir_path(__FILE__));
define('PLUGLIO_ASSETS_URL', PLUGLIO_URL . 'assets/');

// Main plugin class
class Pluglio_Elementor_Addons {
    
    private static $instance = null;
    
    public static function get_instance() {
        if (!self::$instance) {
            self::$instance = new self();
        }
        return self::$instance;
    }
    
    private function __construct() {
        add_action('init', [$this, 'init']);
    }
    
    public function init() {
        // Check if Elementor is active
        if (!did_action('elementor/loaded')) {
            add_action('admin_notices', [$this, 'admin_notice_missing_elementor']);
            return;
        }
        
        // Include required files
        $this->includes();
        
        // Register widgets
        add_action('elementor/widgets/register', [$this, 'register_widgets']);
        
        // Register widget categories
        add_action('elementor/elements/categories_registered', [$this, 'add_elementor_widget_categories']);
    }
    
    private function includes() {
        // Include settings page
        require_once PLUGLIO_PATH . 'includes/admin/settings.php';
        
        // Include extensions
        if ($this->is_widget_enabled('container_link')) {
            require_once PLUGLIO_PATH . 'includes/extensions/container-link-extension.php';
        }
        
        // Include widgets if needed (keeping for future widgets)
        // if ($this->is_widget_enabled('some_widget')) {
        //     require_once PLUGLIO_PATH . 'includes/widgets/some-widget.php';
        // }
    }
    
    public function register_widgets($widgets_manager) {
        // This method can be used for future widget registrations
        // Currently Container Link is implemented as an extension, not a widget
    }
    
    public function add_elementor_widget_categories($elements_manager) {
        $elements_manager->add_category(
            'pluglio-widgets',
            [
                'title' => __('Pluglio Widgets', 'pluglio-elementor-addons'),
                'icon' => 'fa fa-plug',
            ]
        );
    }
    
    public function admin_notice_missing_elementor() {
        if (isset($_GET['activate'])) {
            unset($_GET['activate']);
        }
        
        $message = sprintf(
            /* translators: 1: Plugin name 2: Elementor */
            esc_html__('"%1$s" requires "%2$s" to be installed and activated.', 'pluglio-elementor-addons'),
            '<strong>' . esc_html__('Pluglio Elementor Addons', 'pluglio-elementor-addons') . '</strong>',
            '<strong>' . esc_html__('Elementor', 'pluglio-elementor-addons') . '</strong>'
        );
        
        printf('<div class="notice notice-warning is-dismissible"><p>%1$s</p></div>', $message);
    }
    
    private function is_widget_enabled($widget_name) {
        $enabled_widgets = get_option('pluglio_enabled_widgets', []);
        return isset($enabled_widgets[$widget_name]) && $enabled_widgets[$widget_name];
    }
}

// Initialize the plugin
Pluglio_Elementor_Addons::get_instance();