<?php
/**
 * Conditional Display Extension - Show/hide Elementor elements based on conditions
 */

// Exit if accessed directly
if (!defined('ABSPATH')) {
    exit;
}

class Pluglio_Conditional_Display_Extension {
    
    private static $instance = null;
    
    public static function get_instance() {
        if (!self::$instance) {
            self::$instance = new self();
        }
        return self::$instance;
    }
    
    private function __construct() {
        // Add controls to ALL elements
        add_action('elementor/element/common/_section_style/after_section_end', [$this, 'add_conditional_display_controls'], 10, 2);
        add_action('elementor/element/section/section_advanced/after_section_end', [$this, 'add_conditional_display_controls'], 10, 2);
        add_action('elementor/element/column/section_advanced/after_section_end', [$this, 'add_conditional_display_controls'], 10, 2);
        add_action('elementor/element/container/section_layout/after_section_end', [$this, 'add_conditional_display_controls'], 10, 2);
        
        // Handle visibility
        add_action('elementor/frontend/widget/before_render', [$this, 'check_render_conditions'], 10, 1);
        add_action('elementor/frontend/section/before_render', [$this, 'check_render_conditions'], 10, 1);
        add_action('elementor/frontend/column/before_render', [$this, 'check_render_conditions'], 10, 1);
        add_action('elementor/frontend/container/before_render', [$this, 'check_render_conditions'], 10, 1);
    }
    
    public function add_conditional_display_controls($element, $args) {
        // Check if the extension is enabled
        $enabled_widgets = get_option('pluglio_enabled_widgets', []);
        if (!isset($enabled_widgets['conditional_display']) || !$enabled_widgets['conditional_display']) {
            return;
        }
        
        $element->start_controls_section(
            'pluglio_conditional_display_section',
            [
                'label' => __('Conditional Display', 'pluglio-elementor-addons'),
                'tab' => \Elementor\Controls_Manager::TAB_ADVANCED,
            ]
        );
        
        $element->add_control(
            'pluglio_enable_condition',
            [
                'label' => __('Enable Conditional Display', 'pluglio-elementor-addons'),
                'type' => \Elementor\Controls_Manager::SWITCHER,
                'label_on' => __('Yes', 'pluglio-elementor-addons'),
                'label_off' => __('No', 'pluglio-elementor-addons'),
                'return_value' => 'yes',
                'default' => '',
            ]
        );
        
        $element->add_control(
            'pluglio_condition_type',
            [
                'label' => __('Condition Type', 'pluglio-elementor-addons'),
                'type' => \Elementor\Controls_Manager::SELECT,
                'default' => 'custom_field',
                'options' => [
                    'custom_field' => __('Custom Field', 'pluglio-elementor-addons'),
                    'user_meta' => __('User Meta', 'pluglio-elementor-addons'),
                    'post_meta' => __('Post Meta', 'pluglio-elementor-addons'),
                ],
                'condition' => [
                    'pluglio_enable_condition' => 'yes',
                ],
            ]
        );
        
        $element->add_control(
            'pluglio_field_name',
            [
                'label' => __('Field Name', 'pluglio-elementor-addons'),
                'type' => \Elementor\Controls_Manager::TEXT,
                'placeholder' => __('field_name', 'pluglio-elementor-addons'),
                'description' => __('Enter the custom field, user meta, or post meta key', 'pluglio-elementor-addons'),
                'condition' => [
                    'pluglio_enable_condition' => 'yes',
                ],
            ]
        );
        
        $element->add_control(
            'pluglio_comparison_operator',
            [
                'label' => __('Comparison', 'pluglio-elementor-addons'),
                'type' => \Elementor\Controls_Manager::SELECT,
                'default' => 'equals',
                'options' => [
                    'equals' => __('Equals', 'pluglio-elementor-addons'),
                    'not_equals' => __('Not Equals', 'pluglio-elementor-addons'),
                    'contains' => __('Contains', 'pluglio-elementor-addons'),
                    'not_contains' => __('Not Contains', 'pluglio-elementor-addons'),
                    'empty' => __('Is Empty', 'pluglio-elementor-addons'),
                    'not_empty' => __('Is Not Empty', 'pluglio-elementor-addons'),
                    'greater_than' => __('Greater Than', 'pluglio-elementor-addons'),
                    'less_than' => __('Less Than', 'pluglio-elementor-addons'),
                    'true' => __('Is True', 'pluglio-elementor-addons'),
                    'false' => __('Is False', 'pluglio-elementor-addons'),
                ],
                'condition' => [
                    'pluglio_enable_condition' => 'yes',
                ],
            ]
        );
        
        $element->add_control(
            'pluglio_comparison_value',
            [
                'label' => __('Value', 'pluglio-elementor-addons'),
                'type' => \Elementor\Controls_Manager::TEXT,
                'placeholder' => __('Value to compare', 'pluglio-elementor-addons'),
                'description' => __('Leave empty for "Is Empty" or "Is Not Empty" operators', 'pluglio-elementor-addons'),
                'condition' => [
                    'pluglio_enable_condition' => 'yes',
                    'pluglio_comparison_operator!' => ['empty', 'not_empty', 'true', 'false'],
                ],
            ]
        );
        
        $element->add_control(
            'pluglio_condition_notice',
            [
                'type' => \Elementor\Controls_Manager::RAW_HTML,
                'raw' => __('Note: Elements will be hidden if conditions are not met. Conditions are always visible in the editor.', 'pluglio-elementor-addons'),
                'content_classes' => 'elementor-descriptor',
                'condition' => [
                    'pluglio_enable_condition' => 'yes',
                ],
            ]
        );
        
        $element->end_controls_section();
    }
    
    public function check_render_conditions($element) {
        // Skip in editor
        if (\Elementor\Plugin::$instance->editor->is_edit_mode()) {
            return;
        }
        
        $settings = $element->get_settings_for_display();
        
        if (!isset($settings['pluglio_enable_condition']) || $settings['pluglio_enable_condition'] !== 'yes') {
            return;
        }
        
        $condition_met = $this->evaluate_condition($settings);
        
        if (!$condition_met) {
            $element->add_render_attribute('_wrapper', 'style', 'display: none !important;');
        }
    }
    
    private function evaluate_condition($settings) {
        $field_name = trim($settings['pluglio_field_name']);
        if (empty($field_name)) {
            return true; // If no field name, show element
        }
        
        $operator = $settings['pluglio_comparison_operator'];
        $compare_value = $settings['pluglio_comparison_value'];
        $field_value = '';
        
        // Get field value based on condition type
        switch ($settings['pluglio_condition_type']) {
            case 'custom_field':
                // ACF support if available
                if (function_exists('get_field')) {
                    $field_value = get_field($field_name);
                }
                // Fallback to post meta
                if ($field_value === null || !function_exists('get_field')) {
                    $field_value = get_post_meta(get_the_ID(), $field_name, true);
                }
                break;
                
            case 'user_meta':
                $user_id = get_current_user_id();
                if ($user_id) {
                    $field_value = get_user_meta($user_id, $field_name, true);
                }
                break;
                
            case 'post_meta':
                $field_value = get_post_meta(get_the_ID(), $field_name, true);
                break;
        }
        
        // Perform comparison
        switch ($operator) {
            case 'equals':
                return $field_value == $compare_value;
                
            case 'not_equals':
                return $field_value != $compare_value;
                
            case 'contains':
                return strpos($field_value, $compare_value) !== false;
                
            case 'not_contains':
                return strpos($field_value, $compare_value) === false;
                
            case 'empty':
                return empty($field_value);
                
            case 'not_empty':
                return !empty($field_value);
                
            case 'greater_than':
                return is_numeric($field_value) && is_numeric($compare_value) && $field_value > $compare_value;
                
            case 'less_than':
                return is_numeric($field_value) && is_numeric($compare_value) && $field_value < $compare_value;
                
            case 'true':
                return filter_var($field_value, FILTER_VALIDATE_BOOLEAN) === true;
                
            case 'false':
                return filter_var($field_value, FILTER_VALIDATE_BOOLEAN) === false;
        }
        
        return true;
    }
}

// Initialize the extension
Pluglio_Conditional_Display_Extension::get_instance();