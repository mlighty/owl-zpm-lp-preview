# Changelog

## 1.0.0

First installable WordPress draft of the Zencare Practice Management landing page. This repo did not already contain a `wordpress/` plugin or `dist/zencare-zpm-landing.zip`, so this is a new isolated page-template plugin. It does not modify the Zencare blog theme.

- **GTM hostname allowlist.** The static page only loaded container `GTM-PQF8HF5T` on `therapist.zencare.co`, so Tag Manager never started on `zpm.zencare.co`. The allowlist is now both `therapist.zencare.co` and `zpm.zencare.co`, in `index.html` and in the plugin. Other hosts, including local and draft previews, still do not load GTM. No new container ID was introduced. If `ZPM_LANDING_GTM_ID` is empty or invalid, the template prints a stub comment and does not guess an ID.
- **Noscript.** A GTM `<noscript>` iframe is still omitted, because it cannot check the hostname and would fire on every host.
- **CTAs.** Every Start free trial control still goes to the locked `https://members.zencare.co/practice-management` URL, including the query string already stored in `index.html`. Locations remain `header`, `hero`, `trial`, `final`, and `mobile_sticky`.
- **Page.** Figtree, the beans favicon, and the product screenshots stay. `--zc-*` aliases match the blog theme tokens (navy, bright teal, blue surface). The ads-page button color is unchanged. Montserrat is the fallback family after Figtree; the blog theme itself is not edited.
- **WordPress.** One page template is registered. Password-protected pages stay on the theme password form and do not receive landing HTML. Publish, and pointing `zpm.zencare.co` at WordPress, are out of scope.
