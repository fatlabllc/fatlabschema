# FatLab Schema Wizard

**Schema markup that knows when to say no.**

[![WordPress Plugin Version](https://img.shields.io/badge/wordpress-5.8%2B-blue.svg)](https://wordpress.org/)
[![PHP Version](https://img.shields.io/badge/php-7.4%2B-purple.svg)](https://php.net/)
[![License](https://img.shields.io/badge/license-GPL%20v2%2B-green.svg)](LICENSE.txt)

FatLab Schema Wizard is an intelligent WordPress plugin that focuses on the **11 schema types that actually matter** for SEO and AI citations. Unlike competitors offering 35+ obscure schema types, we guide you to correct implementations with the schemas proven to deliver results—and explicitly tell you when schema isn't needed.

**Developed by [FatLab Web Support](https://fatlabwebsupport.com)**

---

## 🌟 Key Features

### Intelligent Decision Making
- **Says "No" When Needed** - Explicitly advises when schema isn't required or appropriate
- **Smart Recommendations** - Analyzes your content and suggests the right schema type
- **Conflict Detection** - Detects and warns about duplicate schema from other SEO plugins
- **Schema Suppression** - Automatically prevents duplicate Organization schema from competing plugins

### AI Search Optimized
Properly structured schema helps AI assistants understand and cite your content:
- ChatGPT
- Perplexity
- Google Gemini
- Microsoft Copilot

### User-Friendly Interface
- **Plain English** - Zero technical jargon throughout the interface
- **Guided Wizard** - Step-by-step process for each schema type
- **Auto-Fill** - Intelligent pre-population from your existing content
- **Live Preview** - See your JSON-LD before publishing

### Focused on What Actually Works
**11 schema types that matter, not 35+ types that don't.**

Most schema plugins overwhelm you with 35+ schema types. Here's the truth: **only about 11 schema types actually deliver results for SEO and AI citations.** The rest are either too specialized, not supported by search engines for rich results, or ignored by AI assistants.

We focus exclusively on schemas proven to work:
- ✅ Recognized by Google for rich results
- ✅ Used by AI assistants for citations
- ✅ Proven to improve click-through rates
- ✅ Actually relevant for most websites

**The 11 Schema Types That Actually Matter:**

1. **Organization / NGO** - For businesses, nonprofits, political organizations
2. **LocalBusiness** - Physical locations with full address information
3. **Event** - Fundraisers, rallies, webinars, town halls
4. **Video** - YouTube videos, Vimeo, video tutorials, promotional videos
5. **FAQPage** - Frequently asked questions, policy positions
6. **Service** - Services offered by your organization
7. **Article / ScholarlyArticle** - Blog posts, research papers, white papers
8. **HowTo** - Step-by-step guides and tutorials
9. **Person** - Staff profiles, leadership bios, candidate information
10. **JobPosting** - Job listings and employment opportunities
11. **Course** - Educational courses and training programs

Less decision paralysis. Better results. No wasted time.

---

## 📥 Installation

### From WordPress.org (Coming Soon)
1. Go to **Plugins > Add New** in your WordPress admin
2. Search for "FatLab Schema Wizard"
3. Click **Install Now** and then **Activate**

### From GitHub
1. Download the latest release from the [Releases page](https://github.com/fatlabllc/fatlabschema/releases)
2. Upload to `/wp-content/plugins/fatlabschema`
3. Activate through the WordPress **Plugins** menu

### Via Git Clone
```bash
cd /path/to/wordpress/wp-content/plugins
git clone https://github.com/fatlabllc/fatlabschema.git
```

---

## 🚀 Quick Start Guide

### 1. Configure Organization Schema (Required First Step)
After activation, go to **Schema Wizard > Organization** in your WordPress admin:
- Fill in your organization details (name, URL, logo)
- Add contact information and social profiles
- This schema appears on every page of your site

### 2. Add Schema to Individual Pages
Edit any page or post and find the **FatLab Schema Wizard** meta box:

1. **Select Content Type** - Choose what best describes your page
2. **Get Recommendation** - The wizard analyzes and provides guidance
3. **Fill in Details** - Complete the schema form (many fields auto-fill)
4. **Preview JSON-LD** - Review the structured data before publishing
5. **Publish** - Schema is automatically added to your page's `<head>`

### 3. Validate Your Schema
Use Google's Rich Results Test to verify:
```
https://search.google.com/test/rich-results
```

---

## 💡 Why FatLab Schema Wizard?

### Problem: Schema Overload & Choice Paralysis
Most schema plugins create two major problems:

1. **Too Many Useless Options**: They offer 35+ schema types, but most are irrelevant for SEO and ignored by AI assistants
2. **No Guidance**: They assume every page needs schema, leading to incorrect implementations

The result? Decision paralysis, wasted time, and schema that doesn't help your site.

### Solution: Focus + Intelligence
FatLab Schema Wizard takes a different approach:

**Focus on What Works**
- ✅ Only the 11 schema types proven to generate rich results or AI citations
- ✅ No obscure schema types that search engines ignore
- ✅ Every schema type we support actually delivers value

**Intelligent Guidance**
- ✅ Tells you when schema **is** appropriate
- ✅ Tells you when schema **isn't** needed
- ✅ Detects conflicts with other plugins
- ✅ Provides specific recommendations based on your content
- ✅ Suppresses duplicate Organization schema automatically

Quality over quantity. Guidance over guesswork.

---

## 🎯 Perfect For

- **Nonprofits & NGOs** - Mission statements, events, donation pages
- **Political Campaigns** - Candidate profiles, policy positions, rally events
- **Small to Medium Businesses** - Services, locations, business information
- **Service Providers** - Detailed service descriptions with schema
- **Content Publishers** - Articles, how-to guides, FAQ pages
- **Agencies** - Managing multiple client sites with consistent schema

---

## 🔧 Technical Details

### Requirements
- **WordPress**: 5.8 or higher
- **PHP**: 7.4 or higher
- **Recommended**: WordPress 6.0+ and PHP 8.0+

### Compatibility
- ✅ Works with any properly coded WordPress theme
- ✅ Compatible with Yoast SEO, Rank Math, All in One SEO
- ✅ Multisite compatible
- ✅ Translation ready (i18n)
- ✅ GDPR compliant (no data collection)

### Performance
- **Lazy Loading** - Schema classes loaded only when needed
- **Caching** - Built-in transient caching for Organization schema
- **Deferred JavaScript** - Admin scripts load with defer attribute
- **Clean Output** - Minimal JSON-LD in page `<head>`

---

## 🛠️ For Developers

### Hooks & Filters

#### Filters
```php
// Modify schema output before rendering
add_filter( 'fls_should_output_schema', function( $should_output, $post_id ) {
    // Custom logic
    return $should_output;
}, 10, 2 );

// Modify generated JSON-LD
add_filter( 'fls_json_ld_output', function( $schema, $type, $data ) {
    // Customize schema
    return $schema;
}, 10, 3 );
```

#### Actions
```php
// Before schema is saved
add_action( 'fls_before_save_schema', function( $post_id, $schema_type, $schema_data ) {
    // Custom processing
}, 10, 3 );

// After schema is saved
add_action( 'fls_after_save_schema', function( $post_id, $schema_id ) {
    // Custom actions
}, 10, 2 );
```

### File Structure
```
fatlabschema/
├── admin/
│   ├── css/              # Admin styles
│   ├── js/               # Admin JavaScript
│   └── views/            # Admin page templates
│       └── forms/        # Schema type forms
├── includes/             # Core functionality classes
├── languages/            # Translation files
├── schemas/              # Schema type classes
├── assets/               # Plugin assets (icons, banners)
├── fatlabschema.php      # Main plugin file
├── uninstall.php         # Cleanup on uninstall
├── LICENSE.txt           # GPL v2 license
└── README.md             # This file
```

### Coding Standards
- Follows [WordPress Coding Standards](https://developer.wordpress.org/coding-standards/wordpress-coding-standards/)
- All user input sanitized and validated
- All output properly escaped
- Nonces used for all forms and AJAX requests
- Prepared statements for database queries

---

## 🤝 Contributing

We welcome contributions! Here's how to get started:

1. **Fork the Repository**
   ```bash
   git clone https://github.com/fatlabllc/fatlabschema.git
   cd fatlabschema
   ```

2. **Create a Feature Branch**
   ```bash
   git checkout -b feature/your-feature-name
   ```

3. **Make Your Changes**
   - Follow WordPress coding standards
   - Add proper documentation
   - Test thoroughly

4. **Submit a Pull Request**
   - Describe your changes
   - Reference any related issues
   - Ensure all tests pass

### Development Setup
```bash
# Check PHP syntax
find . -name "*.php" -exec php -l {} \;

# Use the included development scripts
./test-plugin.sh reinstall
```

See [DEV-README.md](DEV-README.md) for detailed development workflow.

---

## 📝 Changelog

### Version 1.0.1 (Current)
**Video Schema Added**

Expanding from 10 to 11 schema types with the addition of VideoObject:
- ✨ New: Video schema (VideoObject) for YouTube, Vimeo, and self-hosted videos
- ✨ New: Video admin form with thumbnail, duration, transcript, and view count fields
- ✨ New: Smart duration formatting (accepts MM:SS, HH:MM:SS, or seconds)
- 📝 Updated: All documentation to reflect 11 schema types
- 📝 Updated: README and development plan

### Version 1.0.0
**Initial Release - Quality Over Quantity**

Unlike competitors offering 35+ schema types (most useless), we focus on schemas that actually matter:
- 10 schema types proven for SEO and AI citations (Organization, LocalBusiness, Event, FAQPage, Service, Article, HowTo, Person, JobPosting, Course)
- Intelligent wizard with recommendations
- Conflict detection for major SEO plugins (Yoast, Rank Math, AIOSEO)
- Auto-fill functionality pulls data from your content
- JSON-LD validation and preview
- Multiple schemas per page/post
- Schema suppression for Organization markup (prevents duplicates)
- Admin notices system
- Translation ready (i18n/l10n)
- Full WordPress coding standards compliance

---

## 🐛 Support & Bug Reports

### Getting Help
- **Documentation**: [https://fatlabwebsupport.com/schema-wizard/docs](https://fatlabwebsupport.com/schema-wizard/docs)
- **Support**: [https://fatlabwebsupport.com/support](https://fatlabwebsupport.com/support)
- **WordPress.org Forums**: Coming soon after initial release

### Reporting Bugs
Found a bug? Please report it:
1. Check [existing issues](https://github.com/fatlabllc/fatlabschema/issues) first
2. Create a new issue with:
   - WordPress version
   - PHP version
   - Steps to reproduce
   - Expected vs actual behavior
   - Screenshots if applicable

---

## 📄 License

This plugin is licensed under the GNU General Public License v2 or later.

```
FatLab Schema Wizard
Copyright (C) 2025 FatLab Web Support

This program is free software; you can redistribute it and/or modify
it under the terms of the GNU General Public License as published by
the Free Software Foundation; either version 2 of the License, or
(at your option) any later version.

This program is distributed in the hope that it will be useful,
but WITHOUT ANY WARRANTY; without even the implied warranty of
MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE. See the
GNU General Public License for more details.
```

See [LICENSE.txt](LICENSE.txt) for the full license text.

---

## 🙏 Credits

**Author**: FatLab Web Support
**Website**: [https://fatlabwebsupport.com](https://fatlabwebsupport.com)
**WordPress.org**: [fatlabllc](https://profiles.wordpress.org/fatlabllc/)

Built with ❤️ for nonprofits, political organizations, and mission-driven businesses.

### Special Thanks
- The WordPress community
- Schema.org for structured data standards
- All beta testers and early adopters

---

## 🔗 Links

- **Plugin Homepage**: [https://fatlabwebsupport.com/schema-wizard](https://fatlabwebsupport.com/schema-wizard)
- **GitHub Repository**: [https://github.com/fatlabllc/fatlabschema](https://github.com/fatlabllc/fatlabschema)
- **Documentation**: [https://fatlabwebsupport.com/schema-wizard/docs](https://fatlabwebsupport.com/schema-wizard/docs)
- **Support**: [https://fatlabwebsupport.com/support](https://fatlabwebsupport.com/support)
- **Schema.org**: [https://schema.org](https://schema.org)
- **Google Rich Results Test**: [https://search.google.com/test/rich-results](https://search.google.com/test/rich-results)

---

## ❓ FAQ

### Do I need this if I already have Yoast/Rank Math/AIOSEO?

Yes, if you need Event, Service, HowTo, FAQ, or specialized schema types! Those SEO plugins handle basic Article schema well, but they don't support the other schema types that can generate rich results.

**FatLab Schema Wizard fills the gaps** with the 11 schema types that actually deliver results. Our conflict detection ensures you don't create duplicate schema—keep your SEO plugin for Article schema, and use FatLab for everything else.

### Why only 11 schema types? Other plugins have 35+!

That's exactly the point. **Most schema types don't matter.** We analyzed which schemas actually:
- Generate rich results in Google
- Get used by AI assistants for citations
- Improve click-through rates
- Are relevant for typical websites

The answer? About 11 schema types. The rest are either too specialized (like MedicalScholarlyArticle), not supported for rich results (like BreadcrumbList is handled automatically), or simply ignored by AI assistants.

We focus on quality over quantity so you spend time on schemas that actually help your site, not obscure types that deliver zero value.

### What if my page doesn't need schema?

That's perfectly fine! The wizard will tell you when schema isn't needed. Not every page needs schema markup - it's only beneficial for specific content types.

### Will this work with my theme?

Yes! FatLab Schema Wizard works with any properly coded WordPress theme. The schema is added to your page's HTML as JSON-LD, which is theme-independent.

### Does this help with Google search rankings?

Schema markup doesn't directly improve rankings, but it helps search engines understand your content better, which can lead to rich results (enhanced search listings with extra information). Rich results typically get higher click-through rates.

### How do I know if my schema is working?

Use Google's Rich Results Test (https://search.google.com/test/rich-results) to validate your schema. The wizard also includes a preview feature to check your JSON-LD before publishing.

### Is this optimized for AI search engines like ChatGPT?

Yes! Properly structured schema helps AI assistants (ChatGPT, Perplexity, Google Gemini, Microsoft Copilot) understand and cite your content more accurately.

### Can I use this on a multisite network?

Yes! Each site in your network can have its own Organization schema and page-level schema.

---

**Ready to add intelligent schema to your WordPress site?**
[Download FatLab Schema Wizard](https://github.com/fatlabllc/fatlabschema/releases) or install from WordPress.org (coming soon).
