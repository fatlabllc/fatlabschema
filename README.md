# FatLab Schema Wizard

**Schema markup that knows when to say no.**

A WordPress plugin that provides an intelligent wizard to guide users toward correct schema implementation, optimized for AI search engines. Unlike other schema plugins, FatLab Schema Wizard explicitly tells you when schema markup isn't needed.

[![WordPress Plugin Version](https://img.shields.io/badge/version-1.0.0-blue.svg)](https://github.com/fatlab/schema-wizard)
[![WordPress](https://img.shields.io/badge/WordPress-5.8+-green.svg)](https://wordpress.org/)
[![PHP](https://img.shields.io/badge/PHP-7.4+-purple.svg)](https://php.net/)
[![License](https://img.shields.io/badge/license-GPL--2.0+-orange.svg)](LICENSE)

---

## Features

### Intelligent Recommendations
- **Wizard-Based Approach**: Asks questions before adding markup
- **"No Schema Needed" Advice**: Explicitly advises when schema isn't required or beneficial
- **Content Analysis**: Evaluates page content to suggest appropriate schema types
- **AI Search Optimized**: Structured data positioned for ChatGPT, Perplexity, Google AI Overviews, Bing Chat

### Conflict Detection
- **Multi-Plugin Compatibility**: Detects Yoast SEO, Rank Math, All in One SEO
- **Duplicate Prevention**: Warns about conflicting schema markup
- **Smart Coexistence**: Works alongside other SEO plugins without duplication

### User Experience
- **Plain English Interface**: Zero technical jargon
- **Auto-Fill Functionality**: Pulls data from WordPress and other sources
- **Live Preview**: See JSON-LD output before publishing
- **Validation**: Built-in schema validation

### Supported Schema Types

The plugin focuses on 8 essential schema types instead of overwhelming users with 50+ options:

1. **Organization / NGO** - For nonprofits, advocacy groups, political organizations, businesses
2. **LocalBusiness** - Physical offices, campaign headquarters, retail stores
3. **FAQPage** - FAQ pages, policy positions, campaign stances
4. **Event** - Fundraisers, rallies, town halls, volunteer events, webinars
5. **Service** - Services offered by businesses or nonprofits
6. **Article / ScholarlyArticle** - Blog posts, research papers, policy white papers
7. **HowTo** - Step-by-step guides, tutorials, instructional content
8. **Person** - Political candidates, executive directors, staff profiles

Additional schema types:
- **JobPosting** - Job listings and employment opportunities
- **Course** - Educational courses and training programs

---

## Installation

### For End Users

1. Download the plugin ZIP file
2. Go to **WordPress Admin > Plugins > Add New**
3. Click **Upload Plugin** and select the ZIP file
4. Click **Install Now**
5. Activate the plugin
6. Navigate to **Settings > FatLab Schema** to configure your Organization schema

### For Developers

```bash
# Clone the repository
git clone https://github.com/fatlab/schema-wizard.git fatlabschema

# Navigate to your WordPress plugins directory
cd /path/to/wordpress/wp-content/plugins/

# Create symbolic link or copy files
ln -s /path/to/fatlabschema fatlabschema

# Or copy directly
cp -r /path/to/fatlabschema fatlabschema/

# Activate via WP-CLI
wp plugin activate fatlabschema
```

---

## Usage

### Quick Start

1. **Configure Organization Schema** (Required First Step)
   - Go to **Settings > FatLab Schema > Organization Settings**
   - Fill in your organization details
   - Save settings

2. **Add Schema to a Page**
   - Edit any page or post
   - Scroll to the **FatLab Schema Wizard** meta box
   - Answer the wizard questions
   - Fill in the schema form if recommended
   - Publish or update the page

3. **Review Conflicts**
   - Go to **Settings > FatLab Schema > Conflict Detection**
   - Review any detected conflicts with other plugins
   - Follow recommendations to avoid duplicate markup

### Page/Post Schema Workflow

The wizard follows a conversational flow:

```
What is this page about?
  → Select content type (Event, Article, Service, etc.)

Is this [content type] appropriate?
  → Wizard analyzes and provides recommendation
  → "Yes, add schema" → Form appears
  → "No schema needed" → Explanation provided

Fill in required fields
  → Auto-fill pulls existing data where possible
  → Required fields marked clearly
  → Validation on save

Publish
  → Schema automatically added to page as JSON-LD
  → Conflict warnings if duplicate schema detected
```

### Conflict Detection in Detail

FatLab Schema Wizard includes intelligent conflict detection to prevent duplicate schema markup when other SEO plugins are active.

**Detected Plugins:**
- Yoast SEO
- Rank Math SEO
- All in One SEO (AIOSEO)
- SEOPress

**How It Works:**

1. **Automatic Detection**: When you select Article schema for a blog post, the plugin checks if Yoast, Rank Math, or AIOSEO are active and outputting Article schema.

2. **Proactive Warnings**: If a conflict is detected, a warning appears during the wizard process explaining that duplicate schema can harm your SEO.

3. **Recommended Action**: The plugin recommends letting your existing SEO plugin handle Article schema for posts, while using FatLab Schema Wizard for specialized types (Event, Service, HowTo, FAQ, etc.).

4. **Override Option**: If you intentionally want to use FatLab Schema instead of your SEO plugin's schema, you can check "I understand the risks - use FatLab Schema anyway" and the plugin will output your custom schema.

5. **Automatic Blocking**: If conflict detection is enabled (Settings > FatLab Schema) and a conflict is detected without an override, schema output is automatically blocked on the frontend to prevent duplicates.

**Example Scenario:**

You have Yoast SEO active, which automatically adds Article schema to all blog posts. When you try to add Article schema to a post using FatLab Schema Wizard:
- ⚠️ Warning appears: "Yoast SEO is already adding Article schema to this page"
- 💡 Recommendation: "Let Yoast handle Article schema (it's working!)"
- ✅ You can still add other schema types (Event, HowTo, etc.) without conflicts
- 🔓 Or check the override box if you want to replace Yoast's schema with custom data

**Important Notes:**
- Conflict detection can be disabled in Settings > FatLab Schema
- Currently only checks Article/ScholarlyArticle schema conflicts
- Other schema types (Event, Service, FAQ, HowTo) can be added freely
- For detailed technical information, see `CONFLICT-DETECTION-RESEARCH.md`

---

## Architecture

### Directory Structure

```
fatlabschema/
├── admin/                      # Admin interface
│   ├── css/                   # Admin stylesheets
│   ├── js/                    # Admin JavaScript
│   └── views/                 # Admin page templates
│       ├── forms/             # Schema type forms
│       ├── help-page.php
│       ├── organization-settings.php
│       ├── settings-page.php
│       ├── tools-page.php
│       └── wizard-metabox.php
├── includes/                   # Core functionality
│   ├── class-fls-admin.php            # Admin initialization
│   ├── class-fls-admin-notices.php    # Admin notices system
│   ├── class-fls-ajax.php             # AJAX handlers
│   ├── class-fls-conflict-detector.php # Conflict detection
│   ├── class-fls-output.php           # Frontend schema output
│   ├── class-fls-schema-generator.php # JSON-LD generation
│   ├── class-fls-validator.php        # Schema validation
│   └── class-fls-wizard.php           # Wizard logic
├── schemas/                    # Schema type classes
│   ├── class-fls-schema-article.php
│   ├── class-fls-schema-course.php
│   ├── class-fls-schema-event.php
│   ├── class-fls-schema-faqpage.php
│   ├── class-fls-schema-howto.php
│   ├── class-fls-schema-jobposting.php
│   ├── class-fls-schema-localbusiness.php
│   ├── class-fls-schema-organization.php
│   ├── class-fls-schema-person.php
│   └── class-fls-schema-service.php
├── languages/                  # Internationalization
├── assets/                     # Plugin assets (screenshots, icons)
├── fatlabschema.php           # Main plugin file
├── uninstall.php              # Cleanup on uninstall
└── readme.txt                 # WordPress.org readme

```

### Key Classes

#### `FatLab_Schema_Wizard` (Main Plugin Class)
- Singleton pattern
- Loads dependencies
- Initializes admin and public hooks
- Handles plugin activation/deactivation

#### `FLS_Admin`
- Registers admin menus and settings
- Enqueues admin assets
- Handles meta box registration

#### `FLS_Wizard`
- Wizard logic and recommendations
- Content analysis
- Schema type suggestions

#### `FLS_Schema_Generator`
- Generates JSON-LD markup
- Handles schema type-specific generation
- Validates required fields

#### `FLS_Conflict_Detector`
- Detects other SEO plugins
- Identifies duplicate schema
- Provides conflict warnings

#### `FLS_Output`
- Outputs JSON-LD to frontend
- Handles schema injection
- Manages output priorities

#### `FLS_Validator`
- Validates schema data
- Checks required fields
- Sanitizes input

### Hooks and Filters

#### Actions

```php
// Plugin initialization
do_action('fatlabschema_init');

// Before schema output
do_action('fatlabschema_before_output', $schema_type, $schema_data);

// After schema output
do_action('fatlabschema_after_output', $schema_type, $schema_data);

// On settings save
do_action('fatlabschema_settings_saved', $settings);
```

#### Filters

```php
// Modify schema output
apply_filters('fatlabschema_schema_output', $json_ld, $schema_type, $post_id);

// Modify wizard recommendations
apply_filters('fatlabschema_wizard_recommendation', $recommendation, $content_type, $post);

// Modify conflict detection
apply_filters('fatlabschema_detected_conflicts', $conflicts, $post_id);

// Modify supported schema types
apply_filters('fatlabschema_supported_types', $types);

// Modify organization schema
apply_filters('fatlabschema_organization_schema', $schema);
```

---

## Development

### Requirements

- PHP 7.4 or higher
- WordPress 5.8 or higher
- WP-CLI (recommended for testing)

### Development Setup

See [DEV-README.md](DEV-README.md) for detailed development workflow including:
- Development environment setup
- Sync scripts for testing
- WP-CLI testing commands
- Git workflow

### Coding Standards

This plugin follows [WordPress Coding Standards](https://developer.wordpress.org/coding-standards/wordpress-coding-standards/):

- **PHP**: WordPress PHP Coding Standards
- **JavaScript**: WordPress JavaScript Coding Standards
- **CSS**: WordPress CSS Coding Standards
- **Documentation**: Inline documentation using PHPDoc

### Testing

```bash
# Check plugin status
wp plugin status fatlabschema

# Activate plugin
wp plugin activate fatlabschema

# Deactivate plugin
wp plugin deactivate fatlabschema

# Test full lifecycle (with development scripts)
./test-plugin.sh reinstall
```

### Schema Validation

Use Google's Rich Results Test to validate schema output:
https://search.google.com/test/rich-results

---

## Contributing

We welcome contributions! Here's how you can help:

### Reporting Issues

1. Check existing issues first
2. Provide clear description and steps to reproduce
3. Include WordPress version, PHP version, and active plugins
4. Screenshots or error logs are helpful

### Submitting Pull Requests

1. Fork the repository
2. Create a feature branch (`git checkout -b feature/your-feature-name`)
3. Follow WordPress coding standards
4. Test your changes thoroughly
5. Write clear commit messages (see [DEV-README.md](DEV-README.md) for commit message policy)
6. Submit pull request with detailed description

### Code Review Process

- All PRs require review before merging
- Automated tests must pass
- Code must follow WordPress standards
- Documentation must be updated

---

## Roadmap

### Version 1.1 (Planned)
- [ ] Additional schema types (Product, Review)
- [ ] Bulk schema management
- [ ] Import/export schema configurations
- [ ] Enhanced auto-fill from additional sources

### Version 1.2 (Future)
- [ ] Gutenberg block for inline schema
- [ ] Schema templates library
- [ ] Advanced conflict resolution
- [ ] REST API expansion

### Future Considerations

**Intelligent Auto-Suggest**
An AI-powered content analysis system that automatically suggests the most appropriate schema type based on page content. This feature would need to intelligently handle:
- ACF field content extraction
- Gutenberg block parsing
- Classic editor content
- Block theme variations
- Custom post type detection

This was considered for V1 but deferred due to complexity. Manual schema type selection provides a more reliable user experience in the initial release.

---

## Support

- **Documentation**: [Plugin Help Page](admin/views/help-page.php)
- **Issues**: [GitHub Issues](https://github.com/fatlab/schema-wizard/issues)
- **Support**: https://fatlab.com/support

---

## License

FatLab Schema Wizard is licensed under the GNU General Public License v2 or later.

```
Copyright (C) 2024 FatLab Web Support

This program is free software; you can redistribute it and/or modify
it under the terms of the GNU General Public License as published by
the Free Software Foundation; either version 2 of the License, or
(at your option) any later version.

This program is distributed in the hope that it will be useful,
but WITHOUT ANY WARRANTY; without even the implied warranty of
MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE. See the
GNU General Public License for more details.
```

See [LICENSE](LICENSE) file for full license text.

---

## Credits

### Built For
- Nonprofits and NGOs
- Political campaigns and organizations
- Mission-driven businesses
- Service providers

### Developed By
**FatLab Web Support**
Website: https://fatlab.com
Support: https://fatlab.com/support

---

## FAQ

### Do I need this if I already have Yoast/Rank Math/AIOSEO?

Maybe! Those plugins handle basic Article schema well, but they don't support Event, Service, HowTo, or specialized schema types. FatLab Schema Wizard fills the gaps. Our conflict detection ensures you don't create duplicate schema.

**Best practice:** Keep your existing SEO plugin for Article schema on posts, and use FatLab Schema Wizard for:
- Event schema (fundraisers, webinars, rallies)
- Service schema (services offered)
- HowTo schema (tutorials and guides)
- FAQ schema (Q&A pages)
- Person schema (staff profiles)
- LocalBusiness schema (physical locations)

### What happens if I try to add Article schema when Yoast is already adding it?

The plugin will detect the conflict and show you a friendly warning explaining that duplicate schema can harm your SEO. You have two options:

1. **Recommended:** Let your existing SEO plugin handle Article schema and use FatLab Schema Wizard for other schema types
2. **Advanced:** Check "I understand the risks - use FatLab Schema anyway" to override the warning and use your custom Article schema instead

The plugin automatically prevents duplicate schema from appearing on your site unless you explicitly choose to override.

### Will this work with my theme?

Yes! FatLab Schema Wizard works with any properly coded WordPress theme. The schema is added to your page's HTML as JSON-LD, which is theme-independent.

### Does this help with Google search rankings?

Schema markup doesn't directly improve rankings, but it helps search engines understand your content better, which can lead to rich results (enhanced search listings). Rich results typically get higher click-through rates.

### Is this optimized for AI search engines?

Yes! Properly structured schema helps AI assistants (ChatGPT, Perplexity, Bing Chat, Google Bard) understand and cite your content more accurately.

### How do I know if my schema is working?

Use Google's Rich Results Test (https://search.google.com/test/rich-results) to validate your schema. The wizard also includes a preview feature to check your JSON-LD before publishing.

---

**Made with care for the WordPress community.**
