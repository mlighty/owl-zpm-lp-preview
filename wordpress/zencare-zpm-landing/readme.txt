=== Zencare Practice Management Landing ===
Contributors: zencare
Tags: page-template, landing-page
Requires at least: 6.0
Tested up to: 6.5
Requires PHP: 7.4
Stable tag: 1.0.0
License: GPLv2 or later
License URI: https://www.gnu.org/licenses/gpl-2.0.html

Draft page template for the Zencare Practice Management ads landing page.

== Description ==

Installs one page template, "Zencare Practice Management Landing", that renders the current Practice Management ads page.

The plugin does not register other templates, does not edit the active theme, and does not publish a page. Password-protected pages stay on the theme template so the landing HTML is not sent instead of the password form.

Google Tag Manager container GTM-PQF8HF5T loads only when the hostname is therapist.zencare.co or zpm.zencare.co.

== Installation ==

Follow DRAFT-INSTALL.md. Create a Page, assign this template, and leave the status as Draft.

Do not publish the page. Do not point zpm.zencare.co at WordPress as part of this package.

== Changelog ==

= 1.0.0 =
* First installable draft of the Practice Management landing page as an isolated page-template plugin.
* GTM hostname allowlist is therapist.zencare.co and zpm.zencare.co. The previous static page allowlisted only therapist.zencare.co, so the container did not load on zpm.zencare.co.
* Start free trial links keep the locked members.zencare.co/practice-management URL and the data-cta-location values header, hero, trial, final, and mobile_sticky.
* There is no guessed GTM container. Clearing ZPM_LANDING_GTM_ID leaves a stub comment for Daniel.
