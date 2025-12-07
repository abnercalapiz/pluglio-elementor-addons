<?php
/**
 * Toggle Search Widget
 */

// Exit if accessed directly
if (!defined('ABSPATH')) {
    exit;
}

use Elementor\Widget_Base;
use Elementor\Controls_Manager;
use Elementor\Group_Control_Typography;
use Elementor\Group_Control_Box_Shadow;
use Elementor\Group_Control_Border;
use Elementor\Icons_Manager;

class Pluglio_Toggle_Search extends Widget_Base {
    
    public function __construct($data = [], $args = null) {
        parent::__construct($data, $args);
        
        wp_register_style('pluglio-toggle-search', PLUGLIO_ASSETS_URL . 'css/toggle-search.css', [], PLUGLIO_VERSION);
        wp_register_script('pluglio-toggle-search', PLUGLIO_ASSETS_URL . 'js/toggle-search.js', ['jquery'], PLUGLIO_VERSION, true);
    }
    
    public function get_style_depends() {
        return ['pluglio-toggle-search'];
    }
    
    public function get_script_depends() {
        return ['pluglio-toggle-search'];
    }
    
    public function get_name() {
        return 'pluglio-toggle-search';
    }
    
    public function get_title() {
        return __('Toggle Search', 'pluglio-elementor-addons');
    }
    
    public function get_icon() {
        return 'eicon-search';
    }
    
    public function get_categories() {
        return ['pluglio-widgets'];
    }
    
    public function get_keywords() {
        return ['search', 'toggle', 'form', 'find'];
    }
    
    protected function register_controls() {
        
        // Content Section
        $this->start_controls_section(
            'section_content',
            [
                'label' => __('Content', 'pluglio-elementor-addons'),
            ]
        );
        
        $this->add_control(
            'placeholder',
            [
                'label' => __('Placeholder', 'pluglio-elementor-addons'),
                'type' => Controls_Manager::TEXT,
                'default' => __('Search...', 'pluglio-elementor-addons'),
                'placeholder' => __('Search...', 'pluglio-elementor-addons'),
            ]
        );
        
        $this->add_control(
            'search_post_type',
            [
                'label' => __('Search Post Type', 'pluglio-elementor-addons'),
                'type' => Controls_Manager::SELECT,
                'default' => 'all',
                'options' => [
                    'all' => __('All', 'pluglio-elementor-addons'),
                    'post' => __('Posts', 'pluglio-elementor-addons'),
                    'page' => __('Pages', 'pluglio-elementor-addons'),
                    'product' => __('Products', 'pluglio-elementor-addons'),
                ],
            ]
        );
        
        $this->add_control(
            'toggle_icon',
            [
                'label' => __('Toggle Icon', 'pluglio-elementor-addons'),
                'type' => Controls_Manager::ICONS,
                'default' => [
                    'value' => 'fas fa-search',
                    'library' => 'fa-solid',
                ],
            ]
        );
        
        $this->add_control(
            'close_icon',
            [
                'label' => __('Close Icon', 'pluglio-elementor-addons'),
                'type' => Controls_Manager::ICONS,
                'default' => [
                    'value' => 'fas fa-times',
                    'library' => 'fa-solid',
                ],
            ]
        );
        
        $this->add_control(
            'show_submit_button',
            [
                'label' => __('Show Submit Button', 'pluglio-elementor-addons'),
                'type' => Controls_Manager::SWITCHER,
                'default' => 'yes',
            ]
        );
        
        $this->add_control(
            'submit_button_text',
            [
                'label' => __('Submit Button Text', 'pluglio-elementor-addons'),
                'type' => Controls_Manager::TEXT,
                'default' => __('Search', 'pluglio-elementor-addons'),
                'condition' => [
                    'show_submit_button' => 'yes',
                ],
            ]
        );
        
        $this->end_controls_section();
        
        // Toggle Button Style
        $this->start_controls_section(
            'section_toggle_style',
            [
                'label' => __('Toggle Button', 'pluglio-elementor-addons'),
                'tab' => Controls_Manager::TAB_STYLE,
            ]
        );
        
        $this->add_responsive_control(
            'toggle_size',
            [
                'label' => __('Size', 'pluglio-elementor-addons'),
                'type' => Controls_Manager::SLIDER,
                'size_units' => ['px'],
                'range' => [
                    'px' => [
                        'min' => 30,
                        'max' => 100,
                        'step' => 1,
                    ],
                ],
                'default' => [
                    'unit' => 'px',
                    'size' => 40,
                ],
                'selectors' => [
                    '{{WRAPPER}} .pluglio-toggle-search-button' => 'width: {{SIZE}}{{UNIT}}; height: {{SIZE}}{{UNIT}};',
                ],
            ]
        );
        
        $this->add_responsive_control(
            'toggle_icon_size',
            [
                'label' => __('Icon Size', 'pluglio-elementor-addons'),
                'type' => Controls_Manager::SLIDER,
                'size_units' => ['px'],
                'range' => [
                    'px' => [
                        'min' => 10,
                        'max' => 50,
                        'step' => 1,
                    ],
                ],
                'default' => [
                    'unit' => 'px',
                    'size' => 18,
                ],
                'selectors' => [
                    '{{WRAPPER}} .pluglio-toggle-search-button i' => 'font-size: {{SIZE}}{{UNIT}};',
                    '{{WRAPPER}} .pluglio-toggle-search-button svg' => 'width: {{SIZE}}{{UNIT}}; height: {{SIZE}}{{UNIT}};',
                ],
            ]
        );
        
        $this->start_controls_tabs('toggle_tabs');
        
        $this->start_controls_tab(
            'toggle_normal',
            [
                'label' => __('Normal', 'pluglio-elementor-addons'),
            ]
        );
        
        $this->add_control(
            'toggle_background',
            [
                'label' => __('Background Color', 'pluglio-elementor-addons'),
                'type' => Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .pluglio-toggle-search-button' => 'background-color: {{VALUE}};',
                ],
            ]
        );
        
        $this->add_control(
            'toggle_color',
            [
                'label' => __('Icon Color', 'pluglio-elementor-addons'),
                'type' => Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .pluglio-toggle-search-button' => 'color: {{VALUE}};',
                ],
            ]
        );
        
        $this->end_controls_tab();
        
        $this->start_controls_tab(
            'toggle_hover',
            [
                'label' => __('Hover', 'pluglio-elementor-addons'),
            ]
        );
        
        $this->add_control(
            'toggle_background_hover',
            [
                'label' => __('Background Color', 'pluglio-elementor-addons'),
                'type' => Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .pluglio-toggle-search-button:hover' => 'background-color: {{VALUE}};',
                ],
            ]
        );
        
        $this->add_control(
            'toggle_color_hover',
            [
                'label' => __('Icon Color', 'pluglio-elementor-addons'),
                'type' => Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .pluglio-toggle-search-button:hover' => 'color: {{VALUE}};',
                ],
            ]
        );
        
        $this->end_controls_tab();
        
        $this->end_controls_tabs();
        
        $this->add_group_control(
            Group_Control_Border::get_type(),
            [
                'name' => 'toggle_border',
                'selector' => '{{WRAPPER}} .pluglio-toggle-search-button',
                'separator' => 'before',
            ]
        );
        
        $this->add_responsive_control(
            'toggle_border_radius',
            [
                'label' => __('Border Radius', 'pluglio-elementor-addons'),
                'type' => Controls_Manager::DIMENSIONS,
                'size_units' => ['px', '%'],
                'selectors' => [
                    '{{WRAPPER}} .pluglio-toggle-search-button' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
            ]
        );
        
        $this->add_group_control(
            Group_Control_Box_Shadow::get_type(),
            [
                'name' => 'toggle_box_shadow',
                'selector' => '{{WRAPPER}} .pluglio-toggle-search-button',
            ]
        );
        
        $this->end_controls_section();
        
        // Search Form Style
        $this->start_controls_section(
            'section_form_style',
            [
                'label' => __('Search Form', 'pluglio-elementor-addons'),
                'tab' => Controls_Manager::TAB_STYLE,
            ]
        );
        
        $this->add_control(
            'form_background',
            [
                'label' => __('Background Color', 'pluglio-elementor-addons'),
                'type' => Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .pluglio-search-form-container' => 'background-color: {{VALUE}};',
                ],
            ]
        );
        
        $this->add_responsive_control(
            'form_padding',
            [
                'label' => __('Padding', 'pluglio-elementor-addons'),
                'type' => Controls_Manager::DIMENSIONS,
                'size_units' => ['px', 'em', '%'],
                'selectors' => [
                    '{{WRAPPER}} .pluglio-search-form-container' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
            ]
        );
        
        $this->add_group_control(
            Group_Control_Box_Shadow::get_type(),
            [
                'name' => 'form_box_shadow',
                'selector' => '{{WRAPPER}} .pluglio-search-form-container',
            ]
        );
        
        $this->add_control(
            'input_heading',
            [
                'label' => __('Input Field', 'pluglio-elementor-addons'),
                'type' => Controls_Manager::HEADING,
                'separator' => 'before',
            ]
        );
        
        $this->add_control(
            'input_background',
            [
                'label' => __('Background Color', 'pluglio-elementor-addons'),
                'type' => Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .pluglio-search-input' => 'background-color: {{VALUE}};',
                ],
            ]
        );
        
        $this->add_control(
            'input_text_color',
            [
                'label' => __('Text Color', 'pluglio-elementor-addons'),
                'type' => Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .pluglio-search-input' => 'color: {{VALUE}};',
                    '{{WRAPPER}} .pluglio-search-input::placeholder' => 'color: {{VALUE}}; opacity: 0.6;',
                ],
            ]
        );
        
        $this->add_group_control(
            Group_Control_Typography::get_type(),
            [
                'name' => 'input_typography',
                'selector' => '{{WRAPPER}} .pluglio-search-input',
            ]
        );
        
        $this->add_responsive_control(
            'input_padding',
            [
                'label' => __('Padding', 'pluglio-elementor-addons'),
                'type' => Controls_Manager::DIMENSIONS,
                'size_units' => ['px', 'em'],
                'selectors' => [
                    '{{WRAPPER}} .pluglio-search-input' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
            ]
        );
        
        $this->add_group_control(
            Group_Control_Border::get_type(),
            [
                'name' => 'input_border',
                'selector' => '{{WRAPPER}} .pluglio-search-input',
            ]
        );
        
        $this->add_responsive_control(
            'input_border_radius',
            [
                'label' => __('Border Radius', 'pluglio-elementor-addons'),
                'type' => Controls_Manager::DIMENSIONS,
                'size_units' => ['px', '%'],
                'selectors' => [
                    '{{WRAPPER}} .pluglio-search-input' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
            ]
        );
        
        $this->add_control(
            'button_heading',
            [
                'label' => __('Submit Button', 'pluglio-elementor-addons'),
                'type' => Controls_Manager::HEADING,
                'separator' => 'before',
                'condition' => [
                    'show_submit_button' => 'yes',
                ],
            ]
        );
        
        $this->add_control(
            'button_background',
            [
                'label' => __('Background Color', 'pluglio-elementor-addons'),
                'type' => Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .pluglio-search-submit' => 'background-color: {{VALUE}};',
                ],
                'condition' => [
                    'show_submit_button' => 'yes',
                ],
            ]
        );
        
        $this->add_control(
            'button_text_color',
            [
                'label' => __('Text Color', 'pluglio-elementor-addons'),
                'type' => Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .pluglio-search-submit' => 'color: {{VALUE}};',
                ],
                'condition' => [
                    'show_submit_button' => 'yes',
                ],
            ]
        );
        
        $this->add_control(
            'button_background_hover',
            [
                'label' => __('Hover Background Color', 'pluglio-elementor-addons'),
                'type' => Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .pluglio-search-submit:hover' => 'background-color: {{VALUE}};',
                ],
                'condition' => [
                    'show_submit_button' => 'yes',
                ],
            ]
        );
        
        $this->add_control(
            'button_text_color_hover',
            [
                'label' => __('Hover Text Color', 'pluglio-elementor-addons'),
                'type' => Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .pluglio-search-submit:hover' => 'color: {{VALUE}};',
                ],
                'condition' => [
                    'show_submit_button' => 'yes',
                ],
            ]
        );
        
        $this->add_group_control(
            Group_Control_Typography::get_type(),
            [
                'name' => 'button_typography',
                'selector' => '{{WRAPPER}} .pluglio-search-submit',
                'condition' => [
                    'show_submit_button' => 'yes',
                ],
            ]
        );
        
        $this->add_responsive_control(
            'button_padding',
            [
                'label' => __('Padding', 'pluglio-elementor-addons'),
                'type' => Controls_Manager::DIMENSIONS,
                'size_units' => ['px', 'em'],
                'selectors' => [
                    '{{WRAPPER}} .pluglio-search-submit' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
                'condition' => [
                    'show_submit_button' => 'yes',
                ],
            ]
        );
        
        $this->add_group_control(
            Group_Control_Border::get_type(),
            [
                'name' => 'button_border',
                'selector' => '{{WRAPPER}} .pluglio-search-submit',
                'condition' => [
                    'show_submit_button' => 'yes',
                ],
            ]
        );
        
        $this->add_responsive_control(
            'button_border_radius',
            [
                'label' => __('Border Radius', 'pluglio-elementor-addons'),
                'type' => Controls_Manager::DIMENSIONS,
                'size_units' => ['px', '%'],
                'selectors' => [
                    '{{WRAPPER}} .pluglio-search-submit' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
                'condition' => [
                    'show_submit_button' => 'yes',
                ],
            ]
        );
        
        $this->end_controls_section();
    }
    
    protected function render() {
        $settings = $this->get_settings_for_display();
        
        $this->add_render_attribute('wrapper', 'class', 'pluglio-toggle-search-wrapper');
        $this->add_render_attribute('form', 'class', 'pluglio-search-form');
        $this->add_render_attribute('form', 'role', 'search');
        $this->add_render_attribute('form', 'method', 'get');
        $this->add_render_attribute('form', 'action', home_url('/'));
        
        $this->add_render_attribute('input', 'class', 'pluglio-search-input');
        $this->add_render_attribute('input', 'type', 'search');
        $this->add_render_attribute('input', 'name', 's');
        $this->add_render_attribute('input', 'placeholder', $settings['placeholder']);
        $this->add_render_attribute('input', 'autocomplete', 'off');
        
        ?>
        <div <?php echo $this->get_render_attribute_string('wrapper'); ?>>
            <button class="pluglio-toggle-search-button" aria-label="<?php esc_attr_e('Toggle search', 'pluglio-elementor-addons'); ?>" aria-expanded="false">
                <span class="pluglio-search-icon">
                    <?php Icons_Manager::render_icon($settings['toggle_icon'], ['aria-hidden' => 'true']); ?>
                </span>
                <span class="pluglio-close-icon" style="display: none;">
                    <?php Icons_Manager::render_icon($settings['close_icon'], ['aria-hidden' => 'true']); ?>
                </span>
            </button>
            
            <div class="pluglio-search-form-container" style="display: none;">
                <form <?php echo $this->get_render_attribute_string('form'); ?>>
                    <div class="pluglio-search-form-inner">
                        <input <?php echo $this->get_render_attribute_string('input'); ?>>
                        
                        <?php if ($settings['search_post_type'] !== 'all' && post_type_exists($settings['search_post_type'])) : ?>
                            <input type="hidden" name="post_type" value="<?php echo esc_attr($settings['search_post_type']); ?>">
                        <?php endif; ?>
                        
                        <?php if ($settings['show_submit_button'] === 'yes') : ?>
                            <button type="submit" class="pluglio-search-submit">
                                <?php echo esc_html($settings['submit_button_text']); ?>
                            </button>
                        <?php endif; ?>
                    </div>
                </form>
            </div>
        </div>
        <?php
    }
    
    protected function content_template() {
        ?>
        <#
        view.addRenderAttribute('wrapper', 'class', 'pluglio-toggle-search-wrapper');
        view.addRenderAttribute('form', 'class', 'pluglio-search-form');
        view.addRenderAttribute('form', 'role', 'search');
        view.addRenderAttribute('form', 'method', 'get');
        view.addRenderAttribute('form', 'action', '<?php echo home_url('/'); ?>');
        
        view.addRenderAttribute('input', 'class', 'pluglio-search-input');
        view.addRenderAttribute('input', 'type', 'search');
        view.addRenderAttribute('input', 'name', 's');
        view.addRenderAttribute('input', 'placeholder', settings.placeholder);
        view.addRenderAttribute('input', 'autocomplete', 'off');
        #>
        <div {{{ view.getRenderAttributeString('wrapper') }}}>
            <button class="pluglio-toggle-search-button" aria-label="Toggle search" aria-expanded="false">
                <span class="pluglio-search-icon">
                    <# 
                    var toggleIconHTML = elementor.helpers.renderIcon( view, settings.toggle_icon, { 'aria-hidden': true }, 'i', 'object' );
                    if (toggleIconHTML && toggleIconHTML.rendered) {
                        #>{{{ toggleIconHTML.value }}}<#
                    }
                    #>
                </span>
                <span class="pluglio-close-icon" style="display: none;">
                    <# 
                    var closeIconHTML = elementor.helpers.renderIcon( view, settings.close_icon, { 'aria-hidden': true }, 'i', 'object' );
                    if (closeIconHTML && closeIconHTML.rendered) {
                        #>{{{ closeIconHTML.value }}}<#
                    }
                    #>
                </span>
            </button>
            
            <div class="pluglio-search-form-container" style="display: none;">
                <form {{{ view.getRenderAttributeString('form') }}}>
                    <div class="pluglio-search-form-inner">
                        <input {{{ view.getRenderAttributeString('input') }}}>
                        
                        <# if (settings.search_post_type !== 'all') { #>
                            <input type="hidden" name="post_type" value="{{ settings.search_post_type }}">
                        <# } #>
                        
                        <# if (settings.show_submit_button === 'yes') { #>
                            <button type="submit" class="pluglio-search-submit">
                                {{ settings.submit_button_text }}
                            </button>
                        <# } #>
                    </div>
                </form>
            </div>
        </div>
        <?php
    }
}