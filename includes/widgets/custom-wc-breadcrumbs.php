<?php
/**
 * Custom WC Breadcrumbs Widget
 */

// Exit if accessed directly
if (!defined('ABSPATH')) {
    exit;
}

use Elementor\Widget_Base;
use Elementor\Controls_Manager;
use Elementor\Group_Control_Typography;
use Elementor\Group_Control_Text_Shadow;

class Pluglio_Custom_WC_Breadcrumbs extends Widget_Base {
    
    public function __construct($data = [], $args = null) {
        parent::__construct($data, $args);
        
        wp_register_style('pluglio-wc-breadcrumbs', PLUGLIO_ASSETS_URL . 'css/custom-wc-breadcrumbs.css', [], PLUGLIO_VERSION);
    }
    
    public function get_style_depends() {
        return ['pluglio-wc-breadcrumbs'];
    }
    
    public function get_name() {
        return 'pluglio-custom-wc-breadcrumbs';
    }
    
    public function get_title() {
        return __('Custom WC Breadcrumbs', 'pluglio-elementor-addons');
    }
    
    public function get_icon() {
        return 'eicon-navigation-horizontal';
    }
    
    public function get_categories() {
        return ['pluglio-widgets'];
    }
    
    public function get_keywords() {
        return ['breadcrumb', 'woocommerce', 'navigation', 'product', 'category'];
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
            'separator_type',
            [
                'label' => __('Separator Type', 'pluglio-elementor-addons'),
                'type' => Controls_Manager::SELECT,
                'default' => 'text',
                'options' => [
                    'text' => __('Text', 'pluglio-elementor-addons'),
                    'icon' => __('Icon', 'pluglio-elementor-addons'),
                    'custom_svg' => __('Custom SVG', 'pluglio-elementor-addons'),
                ],
            ]
        );
        
        $this->add_control(
            'separator',
            [
                'label' => __('Separator', 'pluglio-elementor-addons'),
                'type' => Controls_Manager::TEXT,
                'default' => '>',
                'placeholder' => __('>', 'pluglio-elementor-addons'),
                'condition' => [
                    'separator_type' => 'text',
                ],
            ]
        );
        
        $this->add_control(
            'separator_icon',
            [
                'label' => __('Icon', 'pluglio-elementor-addons'),
                'type' => Controls_Manager::ICONS,
                'default' => [
                    'value' => 'fas fa-chevron-right',
                    'library' => 'fa-solid',
                ],
                'condition' => [
                    'separator_type' => 'icon',
                ],
            ]
        );
        
        $this->add_control(
            'separator_svg',
            [
                'label' => __('Custom SVG Code', 'pluglio-elementor-addons'),
                'type' => Controls_Manager::TEXTAREA,
                'placeholder' => '<svg>...</svg>',
                'description' => __('Paste your SVG code here', 'pluglio-elementor-addons'),
                'condition' => [
                    'separator_type' => 'custom_svg',
                ],
                'default' => '<svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 20 20" fill="currentColor"><path d="M7.73 4.2a.75.75 0 0 0 0 1.06L11.47 10l-3.74 4.74a.75.75 0 1 0 1.18.93l4-5.06a.75.75 0 0 0 0-.93l-4-5.06a.75.75 0 0 0-1.18-.48Z"/></svg>',
            ]
        );
        
        $this->add_control(
            'show_home',
            [
                'label' => __('Show Home', 'pluglio-elementor-addons'),
                'type' => Controls_Manager::SWITCHER,
                'default' => 'yes',
            ]
        );
        
        $this->add_control(
            'home_text',
            [
                'label' => __('Home Text', 'pluglio-elementor-addons'),
                'type' => Controls_Manager::TEXT,
                'default' => __('Products', 'pluglio-elementor-addons'),
                'condition' => [
                    'show_home' => 'yes',
                ],
            ]
        );
        
        $this->add_control(
            'home_url',
            [
                'label' => __('Home URL', 'pluglio-elementor-addons'),
                'type' => Controls_Manager::URL,
                'dynamic' => [
                    'active' => true,
                ],
                'default' => [
                    'url' => '',
                ],
                'condition' => [
                    'show_home' => 'yes',
                ],
            ]
        );
        
        $this->add_control(
            'show_product_title',
            [
                'label' => __('Show Product Title', 'pluglio-elementor-addons'),
                'type' => Controls_Manager::SWITCHER,
                'default' => 'yes',
                'description' => __('Toggle to show/hide product title in breadcrumbs on product pages', 'pluglio-elementor-addons'),
            ]
        );
        
        $this->end_controls_section();
        
        // Style Section
        $this->start_controls_section(
            'section_style',
            [
                'label' => __('Style', 'pluglio-elementor-addons'),
                'tab' => Controls_Manager::TAB_STYLE,
            ]
        );
        
        $this->add_responsive_control(
            'align',
            [
                'label' => __('Alignment', 'pluglio-elementor-addons'),
                'type' => Controls_Manager::CHOOSE,
                'options' => [
                    'left' => [
                        'title' => __('Left', 'pluglio-elementor-addons'),
                        'icon' => 'eicon-text-align-left',
                    ],
                    'center' => [
                        'title' => __('Center', 'pluglio-elementor-addons'),
                        'icon' => 'eicon-text-align-center',
                    ],
                    'right' => [
                        'title' => __('Right', 'pluglio-elementor-addons'),
                        'icon' => 'eicon-text-align-right',
                    ],
                ],
                'default' => 'left',
                'selectors' => [
                    '{{WRAPPER}} .pluglio-breadcrumbs' => 'text-align: {{VALUE}};',
                ],
            ]
        );
        
        $this->add_group_control(
            Group_Control_Typography::get_type(),
            [
                'name' => 'typography',
                'label' => __('Typography', 'pluglio-elementor-addons'),
                'selector' => '{{WRAPPER}} .pluglio-breadcrumbs, {{WRAPPER}} .pluglio-breadcrumbs a',
            ]
        );
        
        $this->add_control(
            'text_color',
            [
                'label' => __('Text Color', 'pluglio-elementor-addons'),
                'type' => Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .pluglio-breadcrumbs' => 'color: {{VALUE}};',
                    '{{WRAPPER}} .pluglio-breadcrumbs a' => 'color: {{VALUE}};',
                ],
            ]
        );
        
        $this->add_control(
            'link_color',
            [
                'label' => __('Link Color', 'pluglio-elementor-addons'),
                'type' => Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .pluglio-breadcrumbs a' => 'color: {{VALUE}};',
                ],
            ]
        );
        
        $this->add_control(
            'link_hover_color',
            [
                'label' => __('Link Hover Color', 'pluglio-elementor-addons'),
                'type' => Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .pluglio-breadcrumbs a:hover' => 'color: {{VALUE}};',
                ],
            ]
        );
        
        $this->add_control(
            'separator_color',
            [
                'label' => __('Separator Color', 'pluglio-elementor-addons'),
                'type' => Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .pluglio-breadcrumbs-separator' => 'color: {{VALUE}};',
                ],
            ]
        );
        
        $this->add_responsive_control(
            'separator_spacing',
            [
                'label' => __('Separator Spacing', 'pluglio-elementor-addons'),
                'type' => Controls_Manager::SLIDER,
                'size_units' => ['px'],
                'range' => [
                    'px' => [
                        'min' => 0,
                        'max' => 20,
                        'step' => 1,
                    ],
                ],
                'default' => [
                    'unit' => 'px',
                    'size' => 5,
                ],
                'selectors' => [
                    '{{WRAPPER}} .pluglio-breadcrumbs-separator' => 'margin: 0 {{SIZE}}{{UNIT}};',
                ],
            ]
        );
        
        $this->add_responsive_control(
            'separator_size',
            [
                'label' => __('Separator Size', 'pluglio-elementor-addons'),
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
                    'size' => 16,
                ],
                'selectors' => [
                    '{{WRAPPER}} .pluglio-breadcrumbs-separator i' => 'font-size: {{SIZE}}{{UNIT}};',
                    '{{WRAPPER}} .pluglio-breadcrumbs-separator svg' => 'width: {{SIZE}}{{UNIT}}; height: {{SIZE}}{{UNIT}};',
                ],
                'conditions' => [
                    'relation' => 'or',
                    'terms' => [
                        [
                            'name' => 'separator_type',
                            'operator' => '==',
                            'value' => 'icon',
                        ],
                        [
                            'name' => 'separator_type',
                            'operator' => '==',
                            'value' => 'custom_svg',
                        ],
                    ],
                ],
            ]
        );
        
        $this->add_group_control(
            Group_Control_Text_Shadow::get_type(),
            [
                'name' => 'text_shadow',
                'selector' => '{{WRAPPER}} .pluglio-breadcrumbs',
            ]
        );
        
        $this->end_controls_section();
    }
    
    protected function render() {
        if (!function_exists('is_product') || !function_exists('is_product_category')) {
            return;
        }
        
        $settings = $this->get_settings_for_display();
        
        $breadcrumb_items = $this->get_breadcrumb_items($settings);
        
        if (empty($breadcrumb_items)) {
            return;
        }
        
        $this->render_breadcrumbs($breadcrumb_items, $settings);
    }
    
    private function get_breadcrumb_items($settings) {
        $items = [];
        
        // Add home/products link
        if ($settings['show_home'] === 'yes') {
            $home_url = !empty($settings['home_url']['url']) ? $settings['home_url']['url'] : get_permalink(wc_get_page_id('shop'));
            $items[] = [
                'title' => $settings['home_text'],
                'url' => $home_url,
                'is_link' => true
            ];
        }
        
        // Product page
        if (is_product()) {
            global $post;
            
            $terms = wc_get_product_terms($post->ID, 'product_cat', ['orderby' => 'parent', 'order' => 'DESC']);
            
            if (!empty($terms)) {
                $main_term = $terms[0];
                $ancestors = get_ancestors($main_term->term_id, 'product_cat');
                
                // Add ancestors in reverse order (parent to child)
                $ancestors = array_reverse($ancestors);
                foreach ($ancestors as $ancestor) {
                    $ancestor_term = get_term($ancestor, 'product_cat');
                    if (!is_wp_error($ancestor_term)) {
                        $items[] = [
                            'title' => $ancestor_term->name,
                            'url' => get_term_link($ancestor_term, 'product_cat'),
                            'is_link' => true
                        ];
                    }
                }
                
                // Add the main term
                $items[] = [
                    'title' => $main_term->name,
                    'url' => get_term_link($main_term, 'product_cat'),
                    'is_link' => true
                ];
            }
            
            // Add current product (no link) if enabled
            if ($settings['show_product_title'] === 'yes') {
                $items[] = [
                    'title' => get_the_title(),
                    'url' => '',
                    'is_link' => false
                ];
            }
        }
        
        // Product category page
        elseif (is_product_category()) {
            $current_term = get_queried_object();
            
            if ($current_term) {
                $ancestors = get_ancestors($current_term->term_id, 'product_cat');
                
                // Add ancestors in reverse order (parent to child)
                $ancestors = array_reverse($ancestors);
                foreach ($ancestors as $ancestor) {
                    $ancestor_term = get_term($ancestor, 'product_cat');
                    if (!is_wp_error($ancestor_term)) {
                        $items[] = [
                            'title' => $ancestor_term->name,
                            'url' => get_term_link($ancestor_term, 'product_cat'),
                            'is_link' => true
                        ];
                    }
                }
                
                // Add current category (no link)
                $items[] = [
                    'title' => $current_term->name,
                    'url' => '',
                    'is_link' => false
                ];
            }
        }
        
        // Shop page
        elseif (is_shop()) {
            // Just show the shop/products text without link
            $items[] = [
                'title' => $settings['home_text'],
                'url' => '',
                'is_link' => false
            ];
        }
        
        return $items;
    }
    
    private function render_breadcrumbs($items, $settings) {
        $separator = $this->get_separator_html($settings);
        
        echo '<nav class="pluglio-breadcrumbs">';
        
        $total_items = count($items);
        foreach ($items as $index => $item) {
            if ($item['is_link']) {
                echo '<a href="' . esc_url($item['url']) . '">' . esc_html($item['title']) . '</a>';
            } else {
                echo '<span class="pluglio-breadcrumbs-current">' . esc_html($item['title']) . '</span>';
            }
            
            // Add separator except for the last item
            if ($index < $total_items - 1) {
                echo $separator;
            }
        }
        
        echo '</nav>';
    }
    
    private function get_separator_html($settings) {
        $separator_html = '';
        
        switch ($settings['separator_type']) {
            case 'text':
                $separator_html = esc_html($settings['separator']);
                break;
                
            case 'icon':
                ob_start();
                \Elementor\Icons_Manager::render_icon($settings['separator_icon'], ['aria-hidden' => 'true']);
                $separator_html = ob_get_clean();
                break;
                
            case 'custom_svg':
                if (!empty($settings['separator_svg'])) {
                    // Sanitize SVG
                    $allowed_svg = [
                        'svg' => [
                            'class' => true,
                            'aria-hidden' => true,
                            'xmlns' => true,
                            'width' => true,
                            'height' => true,
                            'viewbox' => true,
                            'fill' => true,
                            'stroke' => true,
                            'stroke-width' => true,
                            'stroke-linecap' => true,
                            'stroke-linejoin' => true,
                        ],
                        'path' => [
                            'd' => true,
                            'fill' => true,
                            'stroke' => true,
                        ],
                        'polygon' => [
                            'points' => true,
                            'fill' => true,
                            'stroke' => true,
                        ],
                        'circle' => [
                            'cx' => true,
                            'cy' => true,
                            'r' => true,
                            'fill' => true,
                            'stroke' => true,
                        ],
                        'ellipse' => [
                            'cx' => true,
                            'cy' => true,
                            'rx' => true,
                            'ry' => true,
                            'fill' => true,
                            'stroke' => true,
                        ],
                        'line' => [
                            'x1' => true,
                            'y1' => true,
                            'x2' => true,
                            'y2' => true,
                            'stroke' => true,
                        ],
                        'rect' => [
                            'x' => true,
                            'y' => true,
                            'width' => true,
                            'height' => true,
                            'rx' => true,
                            'ry' => true,
                            'fill' => true,
                            'stroke' => true,
                        ],
                        'g' => [
                            'fill' => true,
                            'stroke' => true,
                        ],
                    ];
                    $separator_html = wp_kses($settings['separator_svg'], $allowed_svg);
                }
                break;
        }
        
        return '<span class="pluglio-breadcrumbs-separator">' . $separator_html . '</span>';
    }
}