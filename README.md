# Pluglio Elementor Addons

A lightweight WordPress plugin that adds container link functionality to Elementor.

## Description

Pluglio Elementor Addons extends Elementor by adding the ability to make entire containers clickable. This simple yet powerful feature allows you to turn any container into a link without complex workarounds.

## Features

- **Container Link**: Make any Elementor container clickable
- **Simple Interface**: Easy toggle in the container's Advanced tab
- **Smart Click Handling**: Respects existing links and form elements inside containers
- **Lightweight**: Minimal code, no unnecessary features
- **Settings Panel**: Enable/disable features from the WordPress admin

## Requirements

- WordPress 5.0 or higher
- Elementor 3.0 or higher
- PHP 7.2 or higher

## Installation

1. Download the plugin zip file
2. Navigate to WordPress Admin > Plugins > Add New
3. Click "Upload Plugin" and select the zip file
4. Click "Install Now" and then "Activate"
5. Navigate to "Pluglio Addons" in the admin menu to enable the Container Link feature

## Usage

### Enabling Container Link

1. Go to WordPress Admin > Pluglio Addons
2. Check "Container Link" to enable the feature
3. Click "Save Settings"

### Making a Container Clickable

1. Edit any page/post with Elementor
2. Select a container element
3. Go to the Advanced tab
4. Find the "Container Link" section
5. Toggle "Make Container Clickable" to ON
6. Enter your link URL
7. Optional: Set link to open in new tab

The container will now be clickable while preserving the functionality of any links, buttons, or form elements inside it.

## How It Works

- The entire container becomes clickable
- Existing links, buttons, and form elements inside the container remain functional
- Ctrl/Cmd + Click opens links in a new tab
- Cursor changes to pointer on hover

## File Structure

```
pluglio-elementor-addons/
├── assets/
│   ├── css/
│   │   └── admin-style.css
│   └── js/
│       └── container-link.js
├── includes/
│   ├── admin/
│   │   └── settings.php
│   └── extensions/
│       └── container-link-extension.php
├── pluglio-elementor-addons.php
└── README.md
```

## Changelog

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