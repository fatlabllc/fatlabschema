=== FatLab Schema Wizard ===
Contributors: fatlabwebsupport
Donate link: https://fatlab.com/donate
Tags: schema, structured data, seo, json-ld, rich results, ai search, schema markup
Requires at least: 5.8
Tested up to: 6.4
Stable tag: 1.0.0
Requires PHP: 7.4
License: GPLv2 or later
License URI: https://www.gnu.org/licenses/gpl-2.0.html

Schema markup that knows when to say no. Intelligent wizard guides you to correct schema implementation optimized for AI search.

== Description ==

**Finally, a schema plugin that tells you when you DON'T need schema!**

FatLab Schema Wizard is different from every other schema plugin. Instead of assuming every page needs schema markup, it asks intelligent questions and explicitly tells you when schema isn't needed. This prevents incorrect implementations and keeps your site clean.

= Key Features =

* **Intelligent Wizard** - Asks questions before adding markup
* **Says "No" When Needed** - Explicitly advises when schema isn't required
* **AI Search Optimized** - Positioned for ChatGPT, Perplexity, Google AI Overviews, Bing Chat
* **Conflict Detection** - Works alongside Yoast, Rank Math, All in One SEO
* **Plain English** - Zero technical jargon
* **Focused Approach** - 8 essential schema types, not 50+

= Supported Schema Types =

1. **Organization / NGO** - For nonprofits, advocacy groups, political organizations, businesses
2. **LocalBusiness** - Physical offices, campaign headquarters, retail stores
3. **FAQPage** - FAQ pages, policy positions, campaign stances
4. **Event** - Fundraisers, rallies, town halls, volunteer events, webinars
5. **Service** - Services offered by businesses or nonprofits
6. **Article / ScholarlyArticle** - Blog posts, research papers, policy white papers
7. **HowTo** - Step-by-step guides, tutorials, instructional content
8. **Person** - Political candidates, executive directors, staff profiles

= Perfect For =

* Nonprofits and NGOs
* Political campaigns and organizations
* Small to medium businesses
* Service providers
* Agencies managing multiple client sites
* Anyone who wants schema done right

= How It Works =

1. Edit any page or post
2. Open the FatLab Schema Wizard meta box
3. Answer simple questions about your content
4. Get a recommendation (including "no schema needed")
5. Fill in the schema details
6. Publish - schema is automatically added to your page

= Conflict Detection =

FatLab Schema Wizard automatically detects when other SEO plugins (Yoast, Rank Math, All in One SEO) are adding schema to your pages. It will warn you about conflicts and help you avoid duplicate schema markup that can harm your SEO.

= Developer Friendly =

* Clean, well-documented code following WordPress standards
* Extensive hooks and filters for customization
* REST API endpoints for advanced integrations
* Compatible with WordPress multisite

== Installation ==

1. Upload the plugin files to `/wp-content/plugins/fatlabschema`, or install through the WordPress plugins screen
2. Activate the plugin through the 'Plugins' screen in WordPress
3. Go to Settings > FatLab Schema to configure your Organization schema (required first step)
4. Edit any page or post and use the Schema Wizard meta box to add schema

== Frequently Asked Questions ==

= Do I need this if I already have Yoast/Rank Math/AIOSEO? =

Maybe! Those plugins handle basic Article schema well, but they don't support Event, Service, HowTo, or specialized schema types. FatLab Schema Wizard fills the gaps. Our conflict detection ensures you don't create duplicate schema.

= What if my page doesn't need schema? =

That's perfectly fine! The wizard will tell you when schema isn't needed. Not every page needs schema markup - it's only beneficial for specific content types.

= Will this work with my theme? =

Yes! FatLab Schema Wizard works with any properly coded WordPress theme. The schema is added to your page's HTML as JSON-LD, which is theme-independent.

= Does this help with Google search rankings? =

Schema markup doesn't directly improve rankings, but it helps search engines understand your content better, which can lead to rich results (enhanced search listings with extra information). Rich results typically get higher click-through rates.

= How do I know if my schema is working? =

Use Google's Rich Results Test (https://search.google.com/test/rich-results) to validate your schema. The wizard also includes a preview feature to check your JSON-LD before publishing.

= Is this optimized for AI search engines like ChatGPT? =

Yes! Properly structured schema helps AI assistants (ChatGPT, Perplexity, Bing Chat, Google Bard) understand and cite your content more accurately.

= Can I use this on a multisite network? =

Yes! Each site in your network can have its own Organization schema and page-level schema.

== Screenshots ==

1. Schema Wizard meta box - Initial content type selection
2. Event schema recommendation with benefits
3. Event schema form with auto-fill functionality
4. Organization settings page
5. Conflict detection warning
6. "No schema needed" recommendation

== Changelog ==

= 1.0.0 =
* Initial release
* 8 schema types supported: Organization, LocalBusiness, FAQPage, Event, Service, Article, HowTo, Person
* Intelligent wizard with recommendations
* Conflict detection for Yoast, Rank Math, All in One SEO
* Auto-fill functionality
* JSON-LD validation
* Admin notices system
* Internationalization ready

== Upgrade Notice ==

= 1.0.0 =
Initial release of FatLab Schema Wizard. Configure your Organization schema first, then add schema to individual pages.

== Privacy Policy ==

FatLab Schema Wizard does not collect, store, or transmit any personal data. All schema data is stored locally in your WordPress database.

== Support ==

For support, please visit https://fatlab.com/support or use the WordPress.org support forums.

== Credits ==

Developed by FatLab Web Support
Built for nonprofits, political organizations, and mission-driven businesses.
