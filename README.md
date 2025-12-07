# Pluglio Elementor Addons

A lightweight WordPress plugin that adds container link functionality, conditional display controls, custom WooCommerce breadcrumbs, and a modern toggle search widget to Elementor.

## Description

Pluglio Elementor Addons extends Elementor with powerful features including clickable containers, conditional display controls, custom WooCommerce breadcrumbs, and a modern toggle search widget. These simple yet powerful features enhance your Elementor workflow without unnecessary bloat.

## Features

- **Container Link**: Make any Elementor container clickable
  - Simple Interface: Easy toggle in the container's Advanced tab
  - Smart Click Handling: Respects existing links and form elements inside containers
- **Conditional Display**: Show/hide any Elementor element based on conditions
  - Works with ALL Elementor elements (widgets, sections, columns, containers)
  - Multiple condition types: Custom fields, User meta, Post meta, **WooCommerce Product Attributes**
  - **NEW**: Full WooCommerce product attribute support (global and custom attributes)
  - Advanced comparison operators: equals, not equals, contains, greater than, less than, empty/not empty, true/false
  - ACF (Advanced Custom Fields) compatible
  - Works on single product pages and shop/archive loops
  - Easy to use interface in the Advanced tab
- **Custom WC Breadcrumbs**: Beautiful WooCommerce breadcrumbs with hierarchy display
  - Format: Products > Parent Category > Child Category
  - Full customization options for styling and appearance
  - Works on product pages, category pages, and shop pages
- **Toggle Search**: Modern search widget with smooth animations
  - Click to reveal/hide search form
  - Customizable toggle and close icons
  - Post type filtering (posts, pages, products)
  - Mobile-friendly full-screen overlay
  - Optional submit button
  - Full styling control
- **Lightweight**: Minimal code, no unnecessary features
- **Settings Panel**: Enable/disable features from the WordPress admin

## Requirements

- WordPress 5.0 or higher
- Elementor 3.0 or higher
- PHP 7.2 or higher
- WooCommerce 3.0 or higher (for Custom WC Breadcrumbs feature)

## Installation

1. Download the plugin zip file
2. Navigate to WordPress Admin > Plugins > Add New
3. Click "Upload Plugin" and select the zip file
4. Click "Install Now" and then "Activate"
5. Navigate to "Pluglio Addons" in the admin menu to enable the Container Link feature

## Usage

### Enabling Features

1. Go to WordPress Admin > Pluglio Addons
2. Check the features you want to enable:
   - Container Link
   - Conditional Display
   - Custom WC Breadcrumbs
   - Toggle Search
3. Click "Save Settings"

### Using Container Link

1. Edit any page/post with Elementor
2. Select a container element
3. Go to the Advanced tab
4. Find the "Container Link" section
5. Toggle "Make Container Clickable" to ON
6. Enter your link URL
7. Optional: Set link to open in new tab

The container will now be clickable while preserving the functionality of any links, buttons, or form elements inside it.

### Using Conditional Display

1. Edit any page/post with Elementor
2. Select ANY element (widget, section, column, or container)
3. Go to the Advanced tab
4. Find the "Conditional Display" section
5. Toggle "Enable Conditional Display" to ON
6. Configure your conditions:
   - **Condition Type**: Choose between Custom Field, User Meta, Post Meta, or Product Attribute (WooCommerce)
   - **Field Name**: Enter the field key/name
     - For product attributes: Use `pa_color`, `pa_size` (global) or `color`, `brand` (custom)
   - **Comparison**: Select how to compare (equals, not equals, contains, etc.)
   - **Value**: Enter the value to compare against (if needed)
7. The element will be hidden if conditions are not met

#### WooCommerce Product Attribute Examples:
- Show element only for products with color "Red": 
  - Condition Type: `Product Attribute`, Field: `pa_color`, Comparison: `Equals`, Value: `Red`
- Hide element for products with size "Large" or "XL":
  - Condition Type: `Product Attribute`, Field: `pa_size`, Comparison: `Not Contains`, Value: `Large`
- Show element only if product has any brand:
  - Condition Type: `Product Attribute`, Field: `brand`, Comparison: `Is Not Empty`

Note: Elements are always visible in the Elementor editor for easy editing.

### Using Custom WC Breadcrumbs

1. Edit any WooCommerce page/template with Elementor
2. Search for "Custom WC Breadcrumbs" in the widgets panel
3. Drag the widget to your desired location
4. Customize the settings:
   - **Separator**: Choose your separator symbol (default: >)
   - **Show Home**: Toggle to show/hide the home link
   - **Home Text**: Customize the home link text (default: "Products")
   - **Home URL**: Set custom URL for home link (defaults to shop page)
5. Style your breadcrumbs:
   - Typography settings
   - Text and link colors
   - Hover effects
   - Alignment
   - Separator spacing

The breadcrumbs will automatically display the correct hierarchy based on the current page.

### Using Toggle Search

1. Edit any page/post with Elementor (commonly used in headers)
2. Search for "Toggle Search" in the widgets panel
3. Drag the widget to your desired location
4. Customize the settings:
   - **Placeholder Text**: Set the search input placeholder
   - **Search Post Type**: Filter searches to specific post types
   - **Toggle Icon**: Choose the icon for the search toggle button
   - **Close Icon**: Choose the icon for closing the search
   - **Submit Button**: Toggle to show/hide the search submit button
5. Style your search widget:
   - Toggle button size and colors
   - Search form background and padding
   - Input field styling
   - Submit button appearance
   - Hover states and animations

The search form will smoothly reveal when clicked and can be closed by clicking the close icon, clicking outside, or pressing ESC.

## How It Works

### Container Link
- The entire container becomes clickable
- Existing links, buttons, and form elements inside the container remain functional
- Ctrl/Cmd + Click opens links in a new tab
- Cursor changes to pointer on hover

### Conditional Display
- Evaluates conditions on page load
- Supports multiple field types:
  - **Custom Fields**: Works with ACF and native WordPress custom fields
  - **User Meta**: Check logged-in user's metadata
  - **Post Meta**: Check current post/page metadata
  - **Product Attributes**: Check WooCommerce product attributes (both global and custom)
- Advanced operators for flexible conditions
- Smart handling of comma-separated attribute values
- Works on single product pages and shop/archive loops
- Elements are completely hidden (display: none) when conditions fail
- No performance impact - conditions are checked server-side

### Custom WC Breadcrumbs
- Automatically detects the current WooCommerce page context
- Displays hierarchical category structure for products
- Shows parent categories before child categories
- Works seamlessly with deep category structures
- Fully integrated with Elementor's live preview

### Toggle Search
- Smooth animations for revealing/hiding search form
- Click outside or ESC key to close
- Mobile-responsive with full-screen overlay on small screens
- Preserves accessibility with proper ARIA attributes
- Supports search filtering by post type
- No page reload - form appears instantly
- Proper focus management for keyboard navigation

## File Structure

```
pluglio-elementor-addons/
├── assets/
│   ├── css/
│   │   ├── admin-style.css
│   │   ├── custom-wc-breadcrumbs.css
│   │   └── toggle-search.css
│   └── js/
│       ├── container-link.js
│       └── toggle-search.js
├── includes/
│   ├── admin/
│   │   └── settings.php
│   ├── extensions/
│   │   ├── container-link-extension.php
│   │   └── conditional-display-extension.php
│   └── widgets/
│       ├── custom-wc-breadcrumbs.php
│       └── toggle-search.php
├── pluglio-elementor-addons.php
└── README.md
```

## Changelog

### Version 1.0.4
- Added Toggle Search widget with modern animations
- Click to reveal/hide search functionality
- Customizable toggle and close icons
- Post type search filtering support
- Mobile-responsive full-screen overlay
- Optional submit button with full styling control
- Smooth animations and transitions
- Accessibility features with proper ARIA attributes
- Focus management for keyboard navigation
- Fixed JavaScript event handling for better performance
- Added proper cleanup on widget removal

### Version 1.0.3
- Added WooCommerce product attribute support to Conditional Display
- Support for both global attributes (pa_*) and custom product attributes
- Smart comma-separated value handling for multi-value attributes
- Works on product pages and shop/archive loops
- Enhanced error handling and type safety
- Fixed potential PHP warnings with string comparisons
- Added method existence checks for better compatibility

### Version 1.0.2
- Added Conditional Display extension for all Elementor elements
- Removed Custom Carousel widget (replaced with Conditional Display)
- Enhanced extension architecture
- Added support for custom fields, user meta, and post meta conditions
- Added ACF (Advanced Custom Fields) compatibility
- Added multiple comparison operators for flexible conditions
- Performance optimizations

### Version 1.0.1
- Added Custom WC Breadcrumbs widget
- Enhanced settings panel with multiple widget support
- Added CSS styling for breadcrumbs
- Support for custom SVG separators
- Enhanced typography controls

### Version 1.0.0
- Initial release
- Simple container link functionality
- Admin settings panel

## Support

For support, feature requests, or bug reports, please contact [Jezweb](https://jezweb.com.au).

## License

This plugin is licensed under the GPL v2 or later.

## Credits

Developed by [Jezweb](https://jezweb.com.au)

---

**Note**: This plugin requires Elementor Page Builder to be installed and activated.