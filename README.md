# Pluglio Elementor Addons

A lightweight WordPress plugin that adds container link functionality and custom WooCommerce breadcrumbs to Elementor.

## Description

Pluglio Elementor Addons extends Elementor with powerful features including clickable containers and custom WooCommerce breadcrumbs. These simple yet powerful features enhance your Elementor workflow without unnecessary bloat.

## Features

- **Container Link**: Make any Elementor container clickable
  - Simple Interface: Easy toggle in the container's Advanced tab
  - Smart Click Handling: Respects existing links and form elements inside containers
- **Custom WC Breadcrumbs**: Beautiful WooCommerce breadcrumbs with hierarchy display
  - Format: Products > Parent Category > Child Category
  - Full customization options for styling and appearance
  - Works on product pages, category pages, and shop pages
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
   - Custom WC Breadcrumbs
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

## How It Works

### Container Link
- The entire container becomes clickable
- Existing links, buttons, and form elements inside the container remain functional
- Ctrl/Cmd + Click opens links in a new tab
- Cursor changes to pointer on hover

### Custom WC Breadcrumbs
- Automatically detects the current WooCommerce page context
- Displays hierarchical category structure for products
- Shows parent categories before child categories
- Works seamlessly with deep category structures
- Fully integrated with Elementor's live preview

## File Structure

```
pluglio-elementor-addons/
├── assets/
│   ├── css/
│   │   ├── admin-style.css
│   │   └── custom-wc-breadcrumbs.css
│   └── js/
│       └── container-link.js
├── includes/
│   ├── admin/
│   │   └── settings.php
│   ├── extensions/
│   │   └── container-link-extension.php
│   └── widgets/
│       └── custom-wc-breadcrumbs.php
├── pluglio-elementor-addons.php
└── README.md
```

## Changelog

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