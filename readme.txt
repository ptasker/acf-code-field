=== ACF Code Field ===

Contributors: ptasker
Donate link: https://www.paypal.com/cgi-bin/webscr?cmd=_s-xclick&hosted_button_id=ZFP4RL9XM2ZWW
Tags: Advanced Custom Fields, ACF, Codemirror, code, code editor, code coloring, code highlighting, WordPress IDE, syntax highlighter
Requires at least: 3.0.1
Tested up to: 5.2
Stable tag: 1.8
License: GPLv2 or later
License URI: http://www.gnu.org/licenses/gpl-2.0.html

Code field for Advanced Custom Fields (ACF)


== Description ==
Code field for [Advanced Custom Fields](https://www.advancedcustomfields.com). Works with ACF versions 4 (free) and 5 (Pro). Based on the [Codemirror](https://codemirror.net/) javascript plugin.

Plugin requires ACF free or pro to be installed to function.


== Installation ==

1. Upload the plugin files to the `/wp-content/plugins/acf-code-field` directory, or install the plugin through the WordPress plugins screen directly.
2. Activate the plugin through the 'Plugins' screen in WordPress
3. In the ACF custom fields manager, the option for the code field will be under the "Field Type" drop down under "Code Tools"


== Frequently Asked Questions ==


= What version fo ACF does this work with? =

Currently the plugin works with the free (v4) and PRO (v5) versions.


= Does the plugin escape or sanitize any data? =

It doesn't. Though Codemirror has several settings to escape data, the plugin doesn't implement them


== Screenshots ==

1. Settings page screenshot
2. Editor screenshot with the Monokai theme enabled.

== Changelog ==

= 1.8 =
 * Fix up CodeMirror to update correctly with Gutenberg/new Editor 🎉💪

= 1.7 =
 * Using WP Core's built-in CodeMirror JavaScript and CSS if > WP 4.9

= 1.6.4 =
 * Updating tested up to 4.9

= 1.6.3 =
 * Readme.txt fix for new UI
 * CSS fix for flexible content areas

= 1.6.2 =
 * Loading in all CodeMirror css themes as options for editor colors
 * Adding PHP support to the v4 plugin
 * Updating codemirror to version 5.23

= 1.6.1 =
 * Bumping tested up to version

= 1.6 =
* Improvement: Adding PHP language editor support
* Improvement: Making height automatically grow to the height of the content
* Bug fix: Hidden panels using the code field need to be clicked on to display contents


= 1.5 =
* Bug Fix: Adding support for flexible content fields
* Bug Fix: Correctly adding multiple stylesheets if multiple fields themes are selected on the same page


= 1.0 =
* First deploy and commit to wordpress.org repository



== Upgrade Notice ==

= 1.6.3 =
 * CSS fix for flexible content areas

= 1.6.2 =
 * Loading in all CodeMirror css themes as options for editor colors
 * Adding PHP support to the v4 plugin
 * Updating codemirror to version 5.23

= 1.6.1 =
 * Bumping tested up to version

= 1.6 =
Adding PHP language editor support


= 1.5 =
Adding support for using ACF Code Field inside Flexible Content and Repeater Fields
