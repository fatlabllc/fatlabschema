# Conflict Detection System - Research & Analysis

## Executive Summary

The FatLab Schema Wizard plugin includes a conflict detection system to prevent duplicate schema markup when other SEO plugins (Yoast, Rank Math, All in One SEO, SEOPress) are active.

**Key Findings:**
- ✅ System correctly identifies that Yoast/Rank Math/AIOSEO output Article schema on posts by default
- ✅ Conflict warnings display properly in admin UI during wizard workflow
- ✅ Schema output is blocked on frontend when conflicts detected
- ❌ **CRITICAL BUG**: Override checkbox doesn't save, preventing users from bypassing warnings
- ⚠️ Only Article schema conflicts are detected (Event, FAQ, HowTo, Service not checked)
- ⚠️ Detection based on plugin presence + post type, not actual HTML output verification

---

## 1. How Conflict Detection Currently Works

### Detection Method

**Location:** `includes/class-fls-conflict-detector.php`

The system uses a **two-phase detection approach**:

#### Phase 1: Plugin Detection (Lines 23-58)

Detects 4 SEO plugins by checking for constants or classes:

| Plugin | Detection Method | Version Retrieved |
|--------|------------------|-------------------|
| **Yoast SEO** | `WPSEO_VERSION` constant or `WPSEO_Options` class | `WPSEO_VERSION` |
| **Rank Math** | `RANK_MATH_VERSION` constant or `RankMath` class | `RANK_MATH_VERSION` |
| **All in One SEO** | `AIOSEO_VERSION` constant or `AIOSEO\Plugin\AIOSEO` class | `AIOSEO_VERSION` |
| **SEOPress** | `SEOPRESS_VERSION` constant or `SEOPress` class | `SEOPRESS_VERSION` |

#### Phase 2: Schema Conflict Detection (Lines 68-112)

**Only checks Article and ScholarlyArticle schema types:**

```php
if ( 'article' === $our_type || 'scholarly' === $our_type ) {
    // Check each detected plugin for Article schema
}
```

**Yoast Detection Logic (Lines 75-84):**
- Checks post meta: `_yoast_wpseo_schema_article_type`
- Calls `check_yoast_article_schema()` method
- **Special post type handling**: If `post_type === 'post'`, ALWAYS returns TRUE
  - Assumes Yoast outputs Article schema on all posts by default (correct assumption)
  - Does NOT verify Yoast settings

**Rank Math Detection (Lines 86-96):**
- Checks post meta: `rank_math_schema_Article`
- If present and not empty, flags conflict

**All in One SEO Detection (Lines 98-108):**
- Checks post meta: `_aioseo_schema_type`
- Looks for values: `'article'` or `'blogposting'`

**SEOPress:**
- Detected but NOT checked for conflicts (potential oversight)

### Frontend Output Blocking

**Location:** `includes/class-fls-output.php` (Lines 112-124)

When outputting schema to `wp_head`:

```php
// Check for conflicts if conflict detection is enabled
$settings = get_option( 'fatlabschema_settings', array() );
if ( isset( $settings['conflict_detection'] ) && $settings['conflict_detection'] ) {
    $conflicts = FLS_Conflict_Detector::get_conflicting_schema_types( $post_id, $schema_type );

    if ( ! empty( $conflicts ) ) {
        $override = get_post_meta( $post_id, '_fatlabschema_override_conflict', true );
        if ( ! $override ) {
            return null;  // BLOCKS OUTPUT
        }
    }
}
```

**Result:** Schema output is completely blocked when:
1. Conflict detection is enabled (global setting)
2. Conflict is detected
3. Override flag is NOT set

---

## 2. What Other SEO Plugins Actually Do

### Research Findings (Web Search Results)

#### Yoast SEO
- **Default Behavior:** YES, automatically outputs Article schema on all standard WordPress posts (`post_type='post'`)
- **Requirements:** Post must support authorship and have an author
- **Customization:** Can be changed per-post or globally in Search Appearance > Content Types
- **Custom Post Types:** Does NOT output Article schema on custom post types by default

**Source:** Yoast official documentation, 2024

#### Rank Math
- **Default Behavior:** YES, uses Article schema for Posts by default
- **Configuration:** Set globally at WordPress Dashboard → Rank Math SEO → Titles & Meta → Posts
- **Auto-Population:** Automatically populates schema fields from page content
- **Customization:** Default can be changed; per-post override available

**Source:** Rank Math official documentation

#### All in One SEO (AIOSEO)
- **Default Behavior:** Can be configured to use Article schema for posts by default
- **Setup:** Search Appearance > Content Types > Posts > Schema Markup > Schema Type = Article
- **Auto-Population:** Name, Headline, Description, Image auto-filled from post data
- **Flexibility:** More granular than others; requires explicit configuration

**Source:** AIOSEO official documentation

### Plugin Assumptions: CORRECT ✅

The plugin's assumption that these SEO plugins output Article schema on posts is **accurate for typical configurations**.

---

## 3. Current User Experience (UI/UX)

### Conflict Warning Display

**Visual Design:**
- Yellow/amber warning box (`#fff3cd` background)
- Warning icon (dashicons-warning)
- Clear headline: "Schema Conflict Detected"
- Friendly explanation of the problem
- Actionable recommendations

**Warning Message Format:**
```
[Plugin Name] is already adding [Schema Type] schema to this page.
Having duplicate schema can confuse search engines and harm your SEO.

We recommend:
• Let [Plugin Name] handle [Schema Type] schema (it's working!)
• Use FatLab Schema Wizard for other schema types (Event, Service, HowTo, etc.)

☐ I understand the risks - use FatLab Schema anyway
```

### Where Warnings Appear

#### 1. During Wizard Flow (Step 2: Recommendation)
**Location:** AJAX response when user selects "Article" schema type

**File:** `includes/class-fls-ajax.php` (Lines 51-55, 110-112)

**Flow:**
1. User selects "Article" in wizard
2. Clicks "Continue"
3. AJAX calls `get_wizard_recommendation`
4. System checks for conflicts
5. Warning displayed BEFORE "Add Schema" button
6. User sees recommendation + conflict warning simultaneously

**User Decision Points:**
- See warning immediately
- Can back out before committing
- Can check override box if they want to proceed

#### 2. After Schema is Configured
**Location:** Meta box summary view when editing post with existing Article schema

**File:** `admin/views/wizard-metabox.php` (Lines 82-88)

**Display:**
- Green "Schema active" indicator appears first
- Conflict warning displays below if applicable
- Shows persistent reminder that conflict exists
- Override checkbox available

### Override Mechanism

**UI Component:**
```html
<input type="checkbox" name="fatlabschema_override_conflict" value="1" />
I understand the risks - use FatLab Schema anyway
```

**Intended Behavior:**
- User acknowledges the risks
- Checks box to proceed with duplicate schema
- Saves post
- Override flag stored: `_fatlabschema_override_conflict`
- Schema outputs despite conflict

**Actual Behavior: ❌ BROKEN**
- User checks box
- Saves post
- Override flag NOT saved (bug in save handler)
- Schema output still blocked
- Override has no effect

---

## 4. Critical Bug: Override Checkbox Not Saved

### The Problem

**Location:** `includes/class-fls-admin.php` (Lines 280-325)

The `save_meta_box_data()` method saves:
- ✅ Schema type
- ✅ Enabled status
- ✅ Wizard completed flag
- ✅ Schema data (JSON)
- ❌ **Override conflict flag** (MISSING)

### Expected Code (Missing)

```php
// Save conflict override flag
if ( isset( $_POST['fatlabschema_override_conflict'] ) && $_POST['fatlabschema_override_conflict'] ) {
    update_post_meta( $post_id, '_fatlabschema_override_conflict', true );
} else {
    delete_post_meta( $post_id, '_fatlabschema_override_conflict' );
}
```

### Impact

**Severity:** HIGH

**User Impact:**
- Users cannot override conflict warnings
- Schema is always blocked when conflicts detected
- Checkbox appears functional but does nothing
- Creates frustration and confusion
- Prevents legitimate use cases (e.g., replacing Yoast's schema with custom implementation)

**Workaround:**
- Disable conflict detection globally (Settings > FatLab Schema)
- Disables protection for ALL posts, not just specific ones
- Not ideal solution

### Fix Required

Add override checkbox saving logic to `includes/class-fls-admin.php` in `save_meta_box_data()` method.

---

## 5. Limitations & Gaps

### Limitation 1: Article Schema Only

**Current State:** Only Article and ScholarlyArticle schema conflicts are detected

**Not Checked:**
- **Event schema** - The Events Calendar, EventON, other event plugins
- **FAQ schema** - Rank Math FAQ block, Yoast FAQ block
- **HowTo schema** - Recipe plugins, tutorial schema plugins
- **Service schema** - Business directory plugins
- **Organization schema** - Most SEO plugins add this
- **LocalBusiness schema** - Local SEO plugins

**Risk:** Users could create duplicate Event, FAQ, or HowTo schema without warnings

**Recommendation:** Expand conflict detection to other schema types

### Limitation 2: No HTML Output Verification

**Current Detection Method:**
- Plugin presence check ✅
- Post type check ✅
- Post meta check ✅
- HTML parsing ❌

**Scenarios Missed:**
- Plugin installed but schema disabled in settings
- Post-specific schema override in other plugin
- Schema removed via filters/hooks
- Plugin inactive but still installed

**Risk:** False positives - warning shown when no actual conflict exists

**Recommendation:** Optional HTML parsing to verify actual schema output

### Limitation 3: No Rank Math Post Type Assumption

**Yoast Detection:**
- Checks post meta
- **Also checks:** If `post_type === 'post'`, assume Article schema (Line 129)

**Rank Math Detection:**
- Checks post meta only
- Does NOT assume Article schema for posts

**Issue:** Rank Math also outputs Article schema on posts by default, but plugin doesn't assume this

**Recommendation:** Add post type check for Rank Math similar to Yoast

### Limitation 4: SEOPress Not Checked

**Status:** SEOPress is detected (Lines 50-56) but never checked for conflicts

**Impact:** If SEOPress outputs Article schema, no warning is shown

**Recommendation:** Add SEOPress conflict checking logic

### Limitation 5: No Settings Verification

**Current Behavior:** Assumes plugin outputs schema if plugin is active

**Better Approach:** Check plugin settings to see if schema is actually enabled

**Yoast Example:**
- Check if schema output is enabled
- Check if Article type is set for posts
- Verify author requirement is met

**Complexity:** Each plugin has different settings structure - significant development effort

---

## 6. Recommendations & Next Steps

### Priority 1: Fix Override Checkbox (CRITICAL)

**File:** `includes/class-fls-admin.php`

**Action:** Add override checkbox saving logic to `save_meta_box_data()` method

**Impact:** Enables users to bypass warnings when intentional

**Effort:** Low (5-10 minutes)

### Priority 2: Add Rank Math Post Type Check

**File:** `includes/class-fls-conflict-detector.php`

**Action:** Add automatic conflict detection for Rank Math on `post_type='post'` (similar to Yoast)

**Impact:** More accurate conflict detection for Rank Math users

**Effort:** Low (10 minutes)

### Priority 3: Add SEOPress Conflict Checking

**File:** `includes/class-fls-conflict-detector.php`

**Action:** Implement conflict checking for SEOPress (currently detected but not checked)

**Impact:** Complete coverage of all 4 detected SEO plugins

**Effort:** Medium (30-60 minutes) - requires research on SEOPress meta structure

### Priority 4: Documentation Updates

**Files:**
- `README.md` - Add conflict detection details
- User-facing help documentation
- Code comments

**Action:** Document how conflict detection works, when it triggers, override process

**Impact:** Better user understanding, reduced support requests

**Effort:** Medium (1-2 hours)

### Priority 5: Expand Schema Type Coverage (Future)

**Action:** Add conflict detection for Event, FAQ, HowTo, Organization schema types

**Impact:** Comprehensive conflict protection

**Effort:** High (4-8 hours) - requires research on how plugins implement each type

### Priority 6: HTML Output Verification (Future)

**Action:** Optional HTML parsing to verify actual conflicts

**Impact:** Eliminate false positives

**Effort:** High (8+ hours) - complex implementation, performance considerations

---

## 7. Post Type Handling Recommendation

### Current Question

"Do we not display this on pages that are posts? Or what is the UI when there are conflicts?"

### Answer

**Current Behavior (CORRECT):**
1. Plugin DOES allow Article schema on posts (`post_type='post'`)
2. Plugin DOES show conflict warnings when Yoast/Rank Math/AIOSEO detected
3. Plugin DOES block schema output unless override is set
4. Plugin DOES NOT automatically exclude posts from Article schema

**UI Already Handles This Well:**
- ✅ Warnings appear proactively during wizard
- ✅ Clear explanation of duplicate schema risks
- ✅ Recommendation to let other plugin handle it
- ✅ Override option for advanced users
- ❌ Override doesn't work (needs fix)

### Recommended Approach: Keep Current Design

**Do NOT automatically hide Article schema for posts** because:
1. Some users may want to replace Yoast's default Article schema with custom data
2. Some users may disable Yoast's Article schema output
3. Current warning system is informative and helpful
4. Override mechanism (once fixed) provides flexibility

**DO make these improvements:**
1. ✅ Fix override checkbox saving (Priority 1)
2. ✅ Keep proactive warnings during wizard
3. ✅ Keep friendly, educational messaging
4. Consider: Add dismissible notice explaining "Most posts already have Article schema from [Plugin]"

### Alternative Approach: Auto-Suggest Different Schema

**Enhancement Idea (Future):**

When user tries to add Article schema to a post with detected conflict:

```
ℹ️ Yoast SEO is already handling Article schema for this post.

Instead, consider:
• Event schema (if announcing an event)
• HowTo schema (if this is a tutorial)
• FAQ schema (if answering questions)
• Or let Yoast handle the schema (recommended)

[Choose Different Schema Type] [Continue Anyway] [Cancel]
```

**Benefits:**
- Guides users toward non-conflicting schema types
- Educational
- Reduces support questions

**Implementation Complexity:** Medium

---

## 8. Testing Recommendations

### Manual Testing Required

1. **Test Override Checkbox (After Fix):**
   - Create post with Article schema
   - Verify conflict warning appears
   - Check override box
   - Save post
   - Verify `_fatlabschema_override_conflict` meta is saved
   - View frontend source
   - Confirm schema appears in `<head>`

2. **Test Conflict Detection:**
   - Test with Yoast active on `post_type='post'`
   - Test with Rank Math active with Article schema set
   - Test with AIOSEO active with Article schema type
   - Test with page (`post_type='page'`) - should not show warning
   - Test with custom post type - should not show warning

3. **Test Settings Toggle:**
   - Disable conflict detection in Settings
   - Verify no warnings appear
   - Verify schema outputs normally
   - Re-enable conflict detection
   - Verify warnings return

4. **Test Multiple Conflicts:**
   - Multiple SEO plugins active (edge case)
   - Verify first conflict shown
   - Verify output still blocked

### Automated Testing (Future)

Consider adding PHPUnit tests for:
- `FLS_Conflict_Detector::detect_active_seo_plugins()`
- `FLS_Conflict_Detector::check_yoast_article_schema()`
- `FLS_Conflict_Detector::get_conflicting_schema_types()`
- Override checkbox save/load cycle

---

## 9. Code Reference

### Key Files

| File | Purpose | Lines of Interest |
|------|---------|-------------------|
| `includes/class-fls-conflict-detector.php` | Core conflict detection logic | 23-58 (plugin detection)<br>68-112 (conflict checking)<br>120-134 (Yoast post type check)<br>143-202 (warning UI) |
| `includes/class-fls-output.php` | Frontend schema output | 112-124 (conflict check before output) |
| `includes/class-fls-admin.php` | Meta box save handler | 280-325 (save_meta_box_data - **missing override save**) |
| `includes/class-fls-wizard.php` | Wizard recommendations | 158 (conflict_check flag)<br>272-274 (default to article for posts) |
| `includes/class-fls-ajax.php` | AJAX wizard handler | 51-55 (conflict check)<br>77-112 (render warning) |
| `admin/views/wizard-metabox.php` | Meta box UI | 82-88 (show conflict warning) |
| `admin/views/settings-page.php` | Settings UI | 36-45 (conflict detection toggle) |

### Key Functions

```php
// Detect which SEO plugins are active
FLS_Conflict_Detector::detect_active_seo_plugins()

// Check if specific post has conflicting schema
FLS_Conflict_Detector::get_conflicting_schema_types( $post_id, $our_type )

// Special check for Yoast on posts
FLS_Conflict_Detector::check_yoast_article_schema( $post_id )

// Render conflict warning HTML
FLS_Conflict_Detector::show_conflict_warning( $post_id, $our_type )
```

---

## 10. Summary

### What Works Well ✅

1. **Accurate detection** of Yoast Article schema on posts
2. **Proactive warnings** during wizard workflow
3. **Clear, friendly UI** explaining conflicts
4. **Effective output blocking** when conflicts detected
5. **Global settings toggle** for conflict detection
6. **Educational approach** - tells users why conflicts matter

### What Needs Fixing ❌

1. **Override checkbox doesn't save** (critical bug)
2. **Rank Math post type not auto-detected** (like Yoast is)
3. **SEOPress detected but not checked** (incomplete)
4. **Limited to Article schema only** (other types ignored)
5. **No HTML output verification** (potential false positives)

### Overall Assessment

The conflict detection system is **well-designed and mostly functional**, with a **critical bug** preventing the override mechanism from working. The UI/UX is excellent - clear, educational, and helpful.

**Recommendation:** Fix the override checkbox bug (Priority 1) before public release. Other enhancements can be added in future versions based on user feedback.

---

**Document Created:** 2024
**Last Updated:** 2024
**Version:** 1.0
