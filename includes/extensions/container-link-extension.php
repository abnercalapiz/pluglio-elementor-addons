<?php
/**
 * Container Link Extension - Makes Elementor containers clickable
 */

// Exit if accessed directly
if (!defined('ABSPATH')) {
    exit;
}

class Pluglio_Container_Link_Extension {
    
    private static $instance = null;
    
    public static function get_instance() {
        if (!self::$instance) {
            self::$instance = new self();
        }
        return self::$instance;
    }
    
    private function __construct() {
        // Add controls to container
        add_action('elementor/element/container/section_layout/after_section_end', [$this, 'add_container_link_controls'], 10, 2);
        
        // Add render attributes
        add_action('elementor/frontend/container/before_render', [$this, 'before_render_container'], 10, 1);
        
        // Enqueue frontend script
        add_action('elementor/frontend/after_enqueue_scripts', [$this, 'enqueue_scripts']);
    }
    
    public function add_container_link_controls($element, $args) {
        // Check if the extension is enabled
        $enabled_widgets = get_option('pluglio_enabled_widgets', []);
        if (!isset($enabled_widgets['container_link']) || !$enabled_widgets['container_link']) {
            return;
        }
        
        $element->start_controls_section(
            'pluglio_container_link_section',
            [
                'label' => __('Container Link', 'pluglio-elementor-addons'),
                'tab' => \Elementor\Controls_Manager::TAB_ADVANCED,
            ]
        );
        
        $element->add_control(
            'pluglio_enable_link',
            [
                'label' => __('Make Container Clickable', 'pluglio-elementor-addons'),
                'type' => \Elementor\Controls_Manager::SWITCHER,
                'label_on' => __('Yes', 'pluglio-elementor-addons'),
                'label_off' => __('No', 'pluglio-elementor-addons'),
                'return_value' => 'yes',
                'default' => '',
            ]
        );
        
        $element->add_control(
            'pluglio_container_link',
            [
                'label' => __('Link', 'pluglio-elementor-addons'),
                'type' => \Elementor\Controls_Manager::URL,
                'placeholder' => __('https://your-link.com', 'pluglio-elementor-addons'),
                'dynamic' => [
                    'active' => true,
                ],
                'condition' => [
                    'pluglio_enable_link' => 'yes',
                ],
            ]
        );
        
        $element->end_controls_section();
    }
    
    public function before_render_container($element) {
        $settings = $element->get_settings_for_display();
        
        if (!empty($settings['pluglio_enable_link']) && $settings['pluglio_enable_link'] === 'yes' && !empty($settings['pluglio_container_link']['url'])) {
            $element->add_render_attribute('_wrapper', 'class', 'pluglio-container-link');
            $element->add_render_attribute('_wrapper', 'data-pluglio-link', esc_url($settings['pluglio_container_link']['url']));
            
            if (!empty($settings['pluglio_container_link']['is_external'])) {
                $element->add_render_attribute('_wrapper', 'data-pluglio-target', '_blank');
            }
            
            if (!empty($settings['pluglio_container_link']['nofollow'])) {
                $element->add_render_attribute('_wrapper', 'data-pluglio-rel', 'nofollow');
            }
        }
    }
    
    public function enqueue_scripts() {
        $enabled_widgets = get_option('pluglio_enabled_widgets', []);
        if (isset($enabled_widgets['container_link']) && $enabled_widgets['container_link']) {
            wp_enqueue_script(
                'pluglio-container-link',
                PLUGLIO_URL . 'assets/js/container-link.js',
                ['jquery'],
                PLUGLIO_VERSION,
                true
            );
        }
    }
}

// Initialize the extension
Pluglio_Container_Link_Extension::get_instance();