# Zencare Practice Management landing — draft install

This package is a **draft WordPress page template**. Install it for human review. Do not publish the page. Pointing `zpm.zencare.co` at WordPress, changing Google Ads, and editing the live GTM container are **out of scope**.

## Upload

1. In WordPress admin, go to **Plugins → Add New → Upload Plugin**.
2. Choose `dist/zencare-zpm-landing.zip`.
3. Install and activate **Zencare Practice Management Landing**.

Activating the plugin only registers one page template. It does not change existing theme templates, menus, or posts.

## Create the draft page

1. Go to **Pages → Add New**.
2. Title: `Zencare Practice Management`.
3. Leave the block editor content empty. The template supplies the landing page.
4. Slug: `practice-management`.
   - Draft preview will look like `https://<your-wordpress-host>/practice-management/?preview=true`.
   - That slug is for the draft review page. The live Cloudflare page at `https://zpm.zencare.co/` stays where it is.
5. In **Page** attributes (or the template panel), choose **Zencare Practice Management Landing**.
6. Set status to **Draft**. Save. Do not click Publish.

Use **Preview** while logged in to review the page. Anonymous visitors do not receive draft content.

## Google Tag Manager

The template already includes container `GTM-PQF8HF5T` from the current landing page. It loads only when the hostname is `therapist.zencare.co` or `zpm.zencare.co`. Draft previews on any other host do not load the container.

No new container ID was added. Do not paste a different ID. If the existing ID ever needs to change, Daniel can define `ZPM_LANDING_GTM_ID` in `wp-config.php`. An empty or invalid value leaves an HTML comment stub and does not guess an ID.

A `<noscript>` iframe is intentionally omitted. It cannot check the hostname, so it would fire GTM on draft and staging hosts.

## Password-protected pages

If the page has a password and the visitor has not entered it, WordPress keeps the theme's password form. The landing HTML is not sent.

## Explicitly not in this install

- Do not publish the page.
- Do not set it as the site homepage.
- Do not change DNS, Cloudflare, or the `zpm.zencare.co` origin.
- Do not edit the Google Ads account or the live GTM container.

## Rebuild

From the repo root:

```bash
php scripts/build-zpm-landing.php
php scripts/verify-zpm-landing.php
```

Page copy comes from `index.html`. Rebuild after editing that file so the plugin template, `vercel-review/index.html`, and the zip stay in sync.
