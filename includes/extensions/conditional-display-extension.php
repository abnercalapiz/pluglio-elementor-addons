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
                    'product_attribute' => __('Product Attribute (WooCommerce)', 'pluglio-elementor-addons'),
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
                'description' => __('Enter the custom field, user meta, post meta key, or product attribute slug (e.g., pa_color, pa_size)', 'pluglio-elementor-addons'),
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
                    'pluglio_condition_type!' => 'product_attribute',
                ],
            ]
        );
        
        $element->add_control(
            'pluglio_product_attribute_notice',
            [
                'type' => \Elementor\Controls_Manager::RAW_HTML,
                'raw' => __('Note: Product attributes work only on WooCommerce product pages. Use attribute slugs like "pa_color" for global attributes or "color" for custom attributes. Multiple values are comma-separated.', 'pluglio-elementor-addons'),
                'content_classes' => 'elementor-descriptor',
                'condition' => [
                    'pluglio_enable_condition' => 'yes',
                    'pluglio_condition_type' => 'product_attribute',
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
                
            case 'product_attribute':
                // Check if WooCommerce is active
                if (class_exists('WooCommerce')) {
                    global $product;
                    
                    // Try to get product from global first
                    if (!$product || !is_a($product, 'WC_Product')) {
                        // If we're in a loop (shop/archive), get the current product
                        $product_id = get_the_ID();
                        
                        // Check if this is a product post type
                        if (get_post_type($product_id) === 'product' && function_exists('wc_get_product')) {
                            $product = wc_get_product($product_id);
                        }
                    }
                    
                    if ($product && is_a($product, 'WC_Product')) {
                        // Handle both global attributes (pa_) and custom attributes
                        if (strpos($field_name, 'pa_') === 0) {
                            // Global attribute
                            $field_value = $product->get_attribute($field_name);
                        } else {
                            // Try as custom attribute
                            $field_value = $product->get_attribute($field_name);
                            
                            // If not found, try with pa_ prefix
                            if (empty($field_value)) {
                                $field_value = $product->get_attribute('pa_' . $field_name);
                            }
                        }
                        
                        // For variable products, get the selected variation's attribute
                        if (method_exists($product, 'is_type') && $product->is_type('variable') && empty($field_value)) {
                            if (method_exists($product, 'get_available_variations')) {
                                $variations = $product->get_available_variations();
                                if (!empty($variations) && method_exists($product, 'get_default_attributes')) {
                                    // Get default attributes or first variation
                                    $default_attributes = $product->get_default_attributes();
                                    if (isset($default_attributes[$field_name])) {
                                        $field_value = $default_attributes[$field_name];
                                    } elseif (isset($default_attributes[str_replace('pa_', '', $field_name)])) {
                                        $field_value = $default_attributes[str_replace('pa_', '', $field_name)];
                                    }
                                }
                            }
                        }
                        
                        // Ensure field_value is always a string
                        $field_value = (string) $field_value;
                    }
                }
                break;
        }
        
        // Perform comparison
        switch ($operator) {
            case 'equals':
                // Ensure field_value is a string for strpos check
                $field_value_str = (string) $field_value;
                
                // For product attributes, handle comma-separated values
                if ($settings['pluglio_condition_type'] === 'product_attribute' && strpos($field_value_str, ',') !== false) {
                    $values = array_map('trim', explode(',', $field_value_str));
                    return in_array($compare_value, $values);
                }
                return $field_value == $compare_value;
                
            case 'not_equals':
                // Ensure field_value is a string for strpos check
                $field_value_str = (string) $field_value;
                
                // For product attributes, handle comma-separated values
                if ($settings['pluglio_condition_type'] === 'product_attribute' && strpos($field_value_str, ',') !== false) {
                    $values = array_map('trim', explode(',', $field_value_str));
                    return !in_array($compare_value, $values);
                }
                return $field_value != $compare_value;
                
            case 'contains':
                // Ensure both values are strings
                $field_value = (string) $field_value;
                $compare_value = (string) $compare_value;
                
                // For product attributes, check each value
                if ($settings['pluglio_condition_type'] === 'product_attribute' && strpos($field_value, ',') !== false) {
                    $values = array_map('trim', explode(',', $field_value));
                    foreach ($values as $value) {
                        if (strpos((string) $value, $compare_value) !== false) {
                            return true;
                        }
                    }
                    return false;
                }
                return strpos($field_value, $compare_value) !== false;
                
            case 'not_contains':
                // Ensure both values are strings
                $field_value = (string) $field_value;
                $compare_value = (string) $compare_value;
                
                // For product attributes, check each value
                if ($settings['pluglio_condition_type'] === 'product_attribute' && strpos($field_value, ',') !== false) {
                    $values = array_map('trim', explode(',', $field_value));
                    foreach ($values as $value) {
                        if (strpos((string) $value, $compare_value) !== false) {
                            return false;
                        }
                    }
                    return true;
                }
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