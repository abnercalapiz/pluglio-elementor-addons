<?php
/**
 * Settings page for Pluglio Elementor Addons
 */

// Exit if accessed directly
if (!defined('ABSPATH')) {
    exit;
}

class Pluglio_Settings {
    
    private static $instance = null;
    
    public static function get_instance() {
        if (!self::$instance) {
            self::$instance = new self();
        }
        return self::$instance;
    }
    
    private function __construct() {
        add_action('admin_menu', [$this, 'add_menu_page']);
        add_action('admin_init', [$this, 'register_settings']);
        add_action('admin_enqueue_scripts', [$this, 'enqueue_admin_scripts']);
    }
    
    public function add_menu_page() {
        add_menu_page(
            __('Pluglio Addons', 'pluglio-elementor-addons'),
            __('Pluglio Addons', 'pluglio-elementor-addons'),
            'manage_options',
            'pluglio-settings',
            [$this, 'render_settings_page'],
            'dashicons-admin-generic',
            100
        );
    }
    
    public function register_settings() {
        register_setting('pluglio_settings', 'pluglio_enabled_widgets', [
            'sanitize_callback' => [$this, 'sanitize_enabled_widgets']
        ]);
    }
    
    public function sanitize_enabled_widgets($input) {
        $sanitized = [];
        
        $available_widgets = $this->get_available_widgets();
        
        foreach ($available_widgets as $widget_key => $widget_data) {
            $sanitized[$widget_key] = isset($input[$widget_key]) ? true : false;
        }
        
        return $sanitized;
    }
    
    public function enqueue_admin_scripts($hook) {
        if ($hook !== 'toplevel_page_pluglio-settings') {
            return;
        }
        
        wp_enqueue_style(
            'pluglio-admin-style',
            PLUGLIO_ASSETS_URL . 'css/admin-style.css',
            [],
            PLUGLIO_VERSION
        );
    }
    
    public function render_settings_page() {
        $enabled_widgets = get_option('pluglio_enabled_widgets', []);
        $available_widgets = $this->get_available_widgets();
        ?>
        <div class="wrap">
            <h1><?php echo esc_html(get_admin_page_title()); ?></h1>
            
            <form method="post" action="options.php">
                <?php settings_fields('pluglio_settings'); ?>
                
                <div class="pluglio-settings-container">
                    <h2><?php _e('Available Widgets', 'pluglio-elementor-addons'); ?></h2>
                    <p><?php _e('Enable or disable widgets as per your requirements.', 'pluglio-elementor-addons'); ?></p>
                    
                    <table class="wp-list-table widefat fixed striped">
                        <thead>
                            <tr>
                                <th class="check-column"></th>
                                <th><?php _e('Widget Name', 'pluglio-elementor-addons'); ?></th>
                                <th><?php _e('Description', 'pluglio-elementor-addons'); ?></th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach ($available_widgets as $widget_key => $widget_data): ?>
                                <tr>
                                    <td class="check-column">
                                        <input type="checkbox" 
                                               name="pluglio_enabled_widgets[<?php echo esc_attr($widget_key); ?>]" 
                                               id="widget_<?php echo esc_attr($widget_key); ?>"
                                               value="1" 
                                               <?php checked(isset($enabled_widgets[$widget_key]) && $enabled_widgets[$widget_key], true); ?>>
                                    </td>
                                    <td>
                                        <label for="widget_<?php echo esc_attr($widget_key); ?>">
                                            <strong><?php echo esc_html($widget_data['name']); ?></strong>
                                        </label>
                                    </td>
                                    <td><?php echo esc_html($widget_data['description']); ?></td>
                                </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                    
                    <div class="pluglio-save-settings">
                        <?php submit_button(__('Save Settings', 'pluglio-elementor-addons')); ?>
                    </div>
                </div>
            </form>
        </div>
        <?php
    }
    
    private function get_available_widgets() {
        return [
            'container_link' => [
                'name' => __('Container Link', 'pluglio-elementor-addons'),
                'description' => __('Adds link functionality to Elementor containers in the Advanced tab with hover animations and click area options.', 'pluglio-elementor-addons')
            ]
        ];
    }
}

// Initialize settings
Pluglio_Settings::get_instance();