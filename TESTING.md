# Testing Guide for FatLab Schema Wizard

Thank you for helping test FatLab Schema Wizard! This guide will walk you through installation and what to test.

## Installation Methods

### Method 1: Direct Download (Easiest)

1. **Download the Plugin**
   - Go to https://github.com/fatlabllc/fatlabschema/releases
   - Download the latest release (v1.0.0)
   - Or download the ZIP directly: https://github.com/fatlabllc/fatlabschema/archive/refs/tags/v1.0.0.zip

2. **Install in WordPress**
   - In your WordPress admin, go to **Plugins > Add New**
   - Click **Upload Plugin** button at the top
   - Choose the downloaded ZIP file
   - Click **Install Now**
   - Click **Activate Plugin**

### Method 2: Git Clone (For Developers)

```bash
cd /path/to/wordpress/wp-content/plugins
git clone https://github.com/fatlabllc/fatlabschema.git
```

Then activate the plugin in WordPress admin.

### Method 3: WordPress Admin (Coming Soon)

Once approved on WordPress.org, you'll be able to search for "FatLab Schema Wizard" directly in **Plugins > Add New**.

---

## Initial Setup (Required!)

After activation, you **must** configure your Organization schema first:

1. Go to **Schema Wizard > Organization** in WordPress admin
2. Fill in your organization details:
   - Organization Name (required)
   - Website URL (required)
   - Logo URL (recommended)
   - Contact information
   - Social media profiles
3. Click **Save Organization Schema**

This Organization schema appears on every page of your site and is required before adding page-level schemas.

---

## What to Test

### 1. Basic Functionality

**Test the Schema Wizard on Different Content Types:**

1. **Create/Edit a Blog Post**
   - Find the "FatLab Schema Wizard" meta box below the editor
   - Try selecting "Blog post or article" as content type
   - Click "Get Recommendation"
   - Does it recommend Article schema?
   - Fill in the form and check the preview
   - Publish and verify JSON-LD appears in page source

2. **Create an Event Page**
   - Create a new page about an upcoming event
   - Use the Schema Wizard meta box
   - Select "Event, webinar, or fundraiser"
   - Does it provide appropriate guidance?
   - Fill in event details (date, location, etc.)
   - Check the preview before publishing

3. **Create a FAQ Page**
   - Create a page with Q&A content
   - Select "FAQ page" in the wizard
   - Add multiple FAQ items
   - Verify the schema preview shows all questions

### 2. Conflict Detection

**If you have Yoast SEO, Rank Math, or All in One SEO installed:**

1. Create a new blog post
2. Open the Schema Wizard
3. Look for conflict warnings
4. Does it detect schema from your SEO plugin?
5. Does it provide clear guidance about avoiding duplicates?

### 3. "No Schema Needed" Recommendations

**Test when schema ISN'T appropriate:**

1. Create a generic "About Us" page
2. Use the Schema Wizard
3. Select "Other/I'm not sure"
4. Does it tell you when schema might not be needed?

### 4. Multiple Schemas Per Page

1. Create a page that could use multiple schemas (e.g., an event at your business location)
2. Add an Event schema
3. Try adding a second schema (LocalBusiness or Service)
4. Do both schemas appear in the preview?
5. Do both appear in the published page source?

### 5. User Experience

**Rate these aspects:**
- Is the interface intuitive?
- Is the language clear and free of jargon?
- Do the recommendations make sense?
- Are error messages helpful?
- Does auto-fill work correctly?

### 6. Validation

**Test your schemas in Google's tools:**

1. After publishing a page with schema, copy the URL
2. Go to https://search.google.com/test/rich-results
3. Paste your URL
4. Does it validate without errors?
5. Does it show the expected rich result preview?

---

## Test Scenarios by User Type

### For Nonprofits/NGOs
- [ ] Organization schema with mission statement
- [ ] Event schema for fundraiser or rally
- [ ] Person schema for board members/staff
- [ ] FAQ schema for policy positions

### For Small Businesses
- [ ] Organization or LocalBusiness schema
- [ ] Service schema for services offered
- [ ] Event schema for webinars or workshops
- [ ] HowTo schema for tutorials/guides

### For Content Publishers
- [ ] Article schema for blog posts
- [ ] ScholarlyArticle for research content
- [ ] HowTo schema for instructional content
- [ ] Course schema for educational programs

---

## What to Report

### Bugs & Issues
Please report bugs at: https://github.com/fatlabllc/fatlabschema/issues

Include:
- WordPress version
- PHP version
- Active theme name
- Other active plugins (especially SEO plugins)
- Steps to reproduce the issue
- Screenshots if applicable
- What you expected vs what happened

### Feature Requests
We're focusing on the core 10 schema types, but if you have suggestions for improvements to existing features, please share them!

### Performance Issues
If you notice slowdowns:
- Which admin page is slow?
- How many schemas are on the page?
- What other plugins are active?

---

## Known Limitations

- **10 Schema Types Only**: This is intentional! We focus on schemas that actually deliver results.
- **No Breadcrumb Schema**: Most themes and SEO plugins handle this automatically.
- **No Product Schema**: For e-commerce, use WooCommerce which has built-in Product schema.

---

## Support & Questions

- **Documentation**: https://fatlabwebsupport.com/projects/schema-wizard/docs
- **Support**: https://fatlabwebsupport.com/get-support/
- **GitHub Issues**: https://github.com/fatlabllc/fatlabschema/issues

---

## Quick Validation Checklist

After installing and testing, verify:

- [ ] Organization schema configured and saving correctly
- [ ] Can add schemas to posts/pages via meta box
- [ ] Schema preview shows valid JSON-LD
- [ ] Published pages show schema in page source (view source, search for `application/ld+json`)
- [ ] Google Rich Results Test validates your schemas
- [ ] No JavaScript errors in browser console
- [ ] No PHP errors in WordPress debug log
- [ ] Conflict detection works with other SEO plugins (if applicable)
- [ ] Multiple schemas can be added to same page
- [ ] Can delete/remove schemas from pages

---

## Advanced Testing (Optional)

### Multisite Testing
If you have a WordPress multisite:
- Install network-wide
- Configure Organization schema on multiple sites
- Verify each site has independent schema settings

### Translation Testing
If you speak another language:
- Try using a translation plugin (like Loco Translate)
- Check if strings are translatable
- Report any hardcoded English strings

### Developer Testing
If you're a developer:
- Check code follows WordPress coding standards
- Test available hooks and filters
- Try customizing schema output
- Review security (nonces, sanitization, escaping)

---

## Thank You!

Your testing helps make FatLab Schema Wizard better for nonprofits, political organizations, and mission-driven businesses everywhere.

**Questions?** Open an issue on GitHub or contact us at https://fatlabwebsupport.com/get-support/
