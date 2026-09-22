<?php
/**
 * Zencare Practice Management landing markup.
 *
 * Generated from the repo root index.html by scripts/build-zpm-landing.php.
 * Edit index.html, then rebuild. Password-protected pages return before any
 * landing HTML is sent.
 *
 * Template Name: Zencare Practice Management Landing
 * Template Post Type: page
 *
 * @package Zencare
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

if ( function_exists( 'post_password_required' ) && post_password_required() ) {
	return;
}

?><!doctype html>
<html <?php zpm_landing_language_attributes(); ?>>
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <meta name="theme-color" content="#F1F6FD">
  <meta name="description" content="Explore Zencare Practice Management: mental health EHR software for scheduling, notes, billing, client communication, and insurance workflows. Start your free trial.">
  <meta name="robots" content="noindex,follow">
  <?php if ( ! function_exists( 'current_theme_supports' ) || ! current_theme_supports( 'title-tag' ) ) : ?>
<title><?php echo esc_html( zpm_landing_document_title_string() ); ?></title>
<?php endif; ?>
  <link rel="preconnect" href="https://fonts.googleapis.com">
  <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
  <link href="https://fonts.googleapis.com/css2?family=Figtree:wght@400..800&display=swap" rel="stylesheet">
  <link rel="icon" type="image/png" sizes="32x32" href="<?php echo esc_url( zpm_landing_asset_uri( 'favicon-32x32.png' ) ); ?>">
  <link rel="icon" type="image/png" sizes="16x16" href="<?php echo esc_url( zpm_landing_asset_uri( 'favicon-16x16.png' ) ); ?>">
  <link rel="icon" href="<?php echo esc_url( zpm_landing_asset_uri( 'favicon.ico' ) ); ?>" sizes="16x16 32x32">
  <link rel="apple-touch-icon" sizes="180x180" href="<?php echo esc_url( zpm_landing_asset_uri( 'apple-touch-icon.png' ) ); ?>">
  <!-- ZPM_CANONICAL -->
  <?php zpm_landing_print_gtm_snippet(); ?>
  <?php if ( function_exists( 'wp_head' ) ) { wp_head(); } ?>
  <style>
    :root{--zc-navy:#1a2944;--zc-teal-bright:#37bec3;--zc-bg-blue:#f1f6fd;--zc-muted:#4e6076;--zc-border:#dce6ee;--navy:var(--zc-navy);--teal:var(--zc-teal-bright);--button:#087c80;--button-hover:#086569;--aqua:#c9f8f7;--blue:var(--zc-bg-blue);--muted:var(--zc-muted);--line:var(--zc-border);--white:#fff}
    *,*::before,*::after{box-sizing:border-box}
    html{scroll-behavior:smooth;scroll-padding-top:24px;font-family:"Figtree","Montserrat","Helvetica Neue",Helvetica,Arial,sans-serif}
    body{margin:0;color:var(--navy);background:var(--white);font-family:"Figtree","Montserrat","Helvetica Neue",Helvetica,Arial,sans-serif;-webkit-font-smoothing:antialiased;line-height:1.6}
    h1,h2,h3,p,figure{margin:0}h1,h2,h3{text-wrap:balance;overflow-wrap:anywhere}p{text-wrap:pretty}
    img{display:block;max-width:100%;height:auto}a{color:inherit}button,summary,a{-webkit-tap-highlight-color:transparent}
    a:focus-visible,summary:focus-visible{outline:3px solid var(--navy);outline-offset:5px}
    .wrap{width:min(1200px,calc(100% - 80px));margin-inline:auto}
    .header{background:white;border-bottom:1px solid var(--line)}
    .header-inner{min-height:90px;display:flex;align-items:center;justify-content:space-between;gap:16px;flex-wrap:wrap;padding-block:16px}
    .brand{width:166px}
    .button{display:inline-flex;align-items:center;justify-content:center;gap:30px;min-height:56px;max-width:100%;padding:15px 25px;background:var(--button);color:white;text-decoration:none;border-radius:9px;font-size:1.0625rem;font-weight:650;line-height:1.3;transition:background .18s ease}
    .button:hover{background:var(--button-hover)}.button span{font-size:1.5rem;font-weight:400;line-height:1;flex-shrink:0}
    .button-small{min-height:46px;padding:11px 20px;font-size:.9375rem;gap:20px}.button-small span{font-size:1.25rem}
    .eyebrow{color:var(--button);text-transform:uppercase;letter-spacing:.13em;font-size:.75rem;font-weight:750;line-height:1.5}
    .hero{background:var(--blue);overflow:hidden}
    .hero-grid{display:grid;grid-template-columns:minmax(0,1fr) minmax(0,1.12fr);gap:58px;align-items:center;padding-block:64px 68px}
    .hero h1{font-size:clamp(2.75rem,4.15vw,3.75rem);line-height:1.08;letter-spacing:-.045em;font-weight:650;margin-top:17px}
    .hero h1 em{font-style:normal;color:var(--button)}
    .lead{max-width:550px;font-size:1.125rem;line-height:1.7;color:var(--muted);margin-top:24px}
    .hero-action{margin-top:28px}.hero-note{color:var(--muted);font-size:.875rem;margin-top:14px;max-width:450px}
    .product-frame{border:1px solid #d3e2eb;border-radius:15px;background:white;box-shadow:0 20px 50px #1a294414;overflow:hidden}
    .product-frame img{width:100%}.product-frame figcaption{border-top:1px solid var(--line);padding:14px 18px;font-size:.875rem;color:var(--muted);background:white}
    .practice-strip{border-bottom:1px solid var(--line)}
    .practice-inner{display:flex;align-items:center;justify-content:center;gap:34px;flex-wrap:wrap;padding-block:22px}
    .practice-intro{font-size:.875rem;color:var(--muted)}.practice-types{display:flex;flex-wrap:wrap;justify-content:center;gap:12px 28px;list-style:none;margin:0;padding:0}
    .practice-types li{font-size:.875rem;font-weight:650}.practice-types li::before{content:'✓';color:var(--button);margin-right:8px}
    .section{padding-block:80px}h2{font-size:2.5rem;font-weight:650;line-height:1.18;letter-spacing:-.035em}
    .workflow-grid{display:grid;grid-template-columns:minmax(0,1.04fr) minmax(0,1fr);gap:70px;align-items:center}
    .workflow-copy h2{margin-top:15px}.workflow-copy>p:not(.eyebrow){font-size:1.0625rem;color:var(--muted);margin-top:19px}
    .checks{list-style:none;padding:0;margin:24px 0 0;display:grid;gap:14px}.checks li{position:relative;padding-left:25px;font-size:1rem;color:var(--muted)}.checks li::before{content:'✓';position:absolute;left:0;color:var(--button);font-weight:700}
    .notes-visual{box-shadow:none;border-color:var(--navy)}.notes-visual img{background:var(--navy)}
    .features{background:var(--blue);border-block:1px solid #e7eef3}
    .section-top{max-width:720px;margin:0 auto 38px;text-align:center}.section-top h2{margin-top:13px}.section-top p:not(.eyebrow){font-size:1rem;color:var(--muted);margin:18px auto 0;max-width:600px}
    .features-grid{display:grid;grid-template-columns:repeat(3,minmax(0,1fr));gap:18px}
    .feature{border:1px solid var(--line);border-radius:14px;padding:27px;background:white;min-width:0}.feature>div{min-width:0}
    .feature-icon{color:var(--button);width:34px;height:34px;margin-bottom:20px}.feature-icon svg{width:32px;height:32px}
    .feature h3{font-size:1.1875rem;line-height:1.35;letter-spacing:-.02em;margin-bottom:10px;font-weight:650}.feature p{font-size:1rem;color:var(--muted);line-height:1.65}
    .support-grid{display:grid;grid-template-columns:minmax(0,1fr) minmax(0,1fr);gap:76px;align-items:start}
    .support h2{margin-top:15px}.support-intro{font-size:1.0625rem;color:var(--muted);margin-top:20px}
    .support-points{display:grid;gap:24px;padding-top:4px}.support-point{padding-left:23px;border-left:3px solid var(--aqua)}.support-point h3{font-size:1.125rem;margin-bottom:7px}.support-point p{font-size:1rem;color:var(--muted)}
    .trial{padding-bottom:80px}.trial-inner{display:grid;grid-template-columns:minmax(0,1fr) minmax(0,.7fr);gap:60px;background:var(--aqua);border:1px solid #b4e8e8;border-radius:18px;padding:44px 46px;align-items:center}
    .trial h2{margin-top:12px;font-size:2.125rem}.trial-copy{font-size:1.0625rem;color:#344f61;margin-top:18px}.trial-action{display:flex;flex-direction:column;align-items:flex-start;gap:15px}.trial-action p{font-size:.875rem;color:#344f61}.trial .eyebrow{color:#086569}
    .faq{background:var(--blue);padding-block:72px}.faq-grid{display:grid;grid-template-columns:minmax(0,.82fr) minmax(0,1.18fr);gap:78px}.faq-intro h2{font-size:2.25rem;margin-top:15px}.faq-intro p:not(.eyebrow){font-size:1rem;color:var(--muted);margin-top:18px}
    .questions{border-top:1px solid #cfdee8}details{border-bottom:1px solid #cfdee8;padding:17px 0}summary{list-style:none;cursor:pointer;display:flex;align-items:center;justify-content:space-between;min-height:44px;gap:22px;font-size:1.0625rem;font-weight:650;line-height:1.5}summary::-webkit-details-marker{display:none}summary::after{content:'+';color:var(--button);font-size:1.6875rem;font-weight:400;line-height:1;flex-shrink:0}details[open] summary::after{content:'−'}details p{color:var(--muted);font-size:1rem;line-height:1.75;margin-top:10px;padding-right:28px}
    .final{padding-block:72px;background:var(--navy);color:white}.final-inner{display:flex;justify-content:space-between;align-items:center;gap:50px}.final-inner>div{min-width:0}.final h2{font-size:2.5rem;max-width:640px}.final p{color:#d5e1ee;font-size:1rem;max-width:580px;margin-top:18px}.final .button{flex-shrink:0;min-width:205px}.final .button:focus-visible{outline-color:var(--aqua)}
    .footer-inner{display:flex;align-items:center;justify-content:space-between;flex-wrap:wrap;gap:16px;padding-block:22px;font-size:.875rem;color:var(--muted)}.footer-brand{line-height:1.8}.footer-brand strong{color:var(--navy);font-weight:600}.legal-links{display:flex;gap:6px 20px;flex-wrap:wrap}.legal-links a{display:inline-flex;align-items:center;min-height:44px;min-width:44px;text-underline-offset:4px}.legal-links a:hover{color:var(--button)}
    .visually-hidden{position:absolute;width:1px;height:1px;padding:0;margin:-1px;overflow:hidden;clip-path:inset(50%);white-space:nowrap;border:0}
    .mobile-bar{display:none}
    @media(max-width:1050px){.wrap{width:calc(100% - 56px)}.hero-grid{gap:32px}.hero h1{font-size:3rem}.workflow-grid,.support-grid{gap:42px}.feature{padding:23px}.faq-grid{gap:45px}.trial-inner{gap:35px;padding:36px}.final h2{font-size:2.25rem}}
    @media(max-width:760px){
      html{scroll-padding-bottom:calc(100px + env(safe-area-inset-bottom))}.wrap{width:calc(100% - 40px)}.header-inner{min-height:78px;padding-block:14px}.brand{width:137px}.button-small{font-size:.875rem;gap:12px;padding:11px 15px}
      .hero-grid{grid-template-columns:minmax(0,1fr);gap:36px;padding-block:42px}.hero h1{font-size:clamp(2.5rem,7.4vw,3.25rem);max-width:590px}.lead{font-size:1rem;max-width:600px;margin-top:20px}.hero-action{margin-top:24px}.hero .button{min-width:220px}.hero-note{font-size:.875rem}.product-frame{border-radius:12px}.product-frame figcaption{padding:12px 15px;font-size:.8125rem}
      .practice-inner{gap:11px;padding-block:22px}.practice-types{column-gap:18px}.section{padding-block:54px}h2{font-size:2rem}
      .workflow-grid,.support-grid{grid-template-columns:minmax(0,1fr);gap:30px}.workflow-copy{grid-row:1}.workflow-copy>p:not(.eyebrow),.support-intro{font-size:1rem}.notes-visual{max-width:580px;margin-inline:auto;width:100%}.checks{margin-top:18px;gap:11px}
      .features-grid{grid-template-columns:repeat(2,minmax(0,1fr));gap:14px}.feature{padding:23px 20px}.section-top{margin-bottom:28px}.support-points{gap:22px}.trial{padding-bottom:54px}.trial-inner{grid-template-columns:minmax(0,1fr);gap:27px;padding:29px 25px}.trial h2{font-size:2rem}.trial-copy{font-size:1rem}.trial-action{gap:12px}
      .faq{padding-block:52px}.faq-grid{grid-template-columns:minmax(0,1fr);gap:27px}.faq-intro h2{font-size:2rem}.final{padding-block:52px}.final-inner{flex-direction:column;align-items:flex-start;gap:27px}.final h2{font-size:2.25rem}
      .footer-inner{align-items:flex-start;flex-direction:column;gap:8px}.mobile-bar{position:fixed;bottom:0;left:0;right:0;z-index:10;background:#fffffffa;box-shadow:0 -4px 24px #1a294410;padding:10px 20px calc(10px + env(safe-area-inset-bottom));border-top:1px solid var(--line)}.mobile-bar.is-visible{display:block}.mobile-bar .button{width:100%;min-height:48px;padding:12px 23px;font-size:.9375rem;justify-content:space-between}body.has-mobile-cta{padding-bottom:calc(80px + env(safe-area-inset-bottom))}
    }
    @media(max-width:560px){.features-grid{grid-template-columns:minmax(0,1fr)}.feature{display:flex;align-items:flex-start;gap:16px;padding:24px 21px}.feature-icon{margin:0;flex:0 0 32px}}
    @media(max-width:390px){.wrap{width:calc(100% - 32px)}.brand{width:117px}.button-small{padding:10px 12px;gap:9px}.hero h1{font-size:2.5rem}.trial-inner{padding:26px 21px}}
    @media(prefers-reduced-motion:reduce){html{scroll-behavior:auto}*,*::before,*::after{transition:none!important}}
  </style>
</head>
<body <?php zpm_landing_body_attributes(); ?>>
<?php if ( function_exists( 'wp_body_open' ) ) { wp_body_open(); } ?>
  <!-- ZPM_GTM_NOSCRIPT -->
  <header class="header"><div class="wrap header-inner">
    <img class="brand" src="<?php echo esc_url( zpm_landing_asset_uri( 'zencare-logo.webp' ) ); ?>" width="500" height="107" alt="Zencare">
    <a class="button button-small" data-cta-location="header" href="<?php echo esc_url( zpm_landing_cta_url() ); ?>">Start free trial<span aria-hidden="true">↗</span></a>
  </div></header>
  <main>
    <section class="hero" aria-labelledby="hero-title"><div class="wrap hero-grid">
      <div class="hero-copy">
        <p class="eyebrow">Mental health EHR for private practices</p>
        <h1 id="hero-title">Run your therapy practice in <em>one connected EHR.</em></h1>
        <p class="lead">Bring scheduling, clinical notes, billing, and client communication together with Zencare Practice Management.</p>
        <div class="hero-action"><a class="button " data-cta-location="hero" href="<?php echo esc_url( zpm_landing_cta_url() ); ?>">Start free trial<span aria-hidden="true">↗</span></a></div>
        <p class="hero-note">Onboarding and migration support to help you get started.</p>
      </div>
      <figure class="product-frame">
        <img src="<?php echo esc_url( zpm_landing_asset_uri( 'zpm-calendar.png' ) ); ?>" width="1225" height="873" alt="Zencare scheduling interface with a weekly calendar, appointments, locations, and therapist filters" fetchpriority="high" decoding="async">
        <figcaption>Appointments, availability, and your team in one calendar.</figcaption>
      </figure>
    </div></section>
    <div class="practice-strip"><div class="wrap practice-inner">
      <p class="practice-intro">Built for the way your practice grows</p>
      <ul class="practice-types"><li>Solo practices</li><li>Group practices</li><li>Growing practices</li></ul>
    </div></div>
    <section class="section workflow wrap" aria-labelledby="workflow-title"><div class="workflow-grid">
      <figure class="product-frame notes-visual">
        <img src="<?php echo esc_url( zpm_landing_asset_uri( 'zpm-notes.png' ) ); ?>" width="3072" height="2304" alt="Zencare Smart Notes controls for note detail, formatting, and SOAP or DAP templates" loading="lazy" decoding="async">
        <figcaption>Smart Notes templates and editing controls.</figcaption>
      </figure>
      <div class="workflow-copy">
        <p class="eyebrow">Documentation that fits your workflow</p>
        <h2 id="workflow-title">A draft to work from.<br>A final note you control.</h2>
        <p>Smart Notes helps turn session recordings or uploaded files into structured drafts. Review the transcript, make your edits, and finalize the note yourself.</p>
        <ul class="checks"><li>Choose SOAP, DAP, or session-summary templates.</li><li>Adjust the level of detail and formatting.</li><li>Keep your clinical judgment central to the final record.</li></ul>
      </div>
    </div></section>
    <section class="features section" aria-labelledby="features-title"><div class="wrap">
      <div class="section-top"><p class="eyebrow">The tools behind your day</p><h2 id="features-title">From the first appointment<br>to the next payment.</h2><p>Keep clinical work, client communication, and practice administration connected.</p></div>
      <div class="features-grid"><article class="feature">
    <div class="feature-icon" aria-hidden="true"><svg viewBox="0 0 32 32" fill="none" stroke="currentColor" stroke-width="1.7" stroke-linecap="round" stroke-linejoin="round"><rect x="4" y="6" width="24" height="22" rx="3"/><path d="M10 3v6m12-6v6M4 13h24m-18 6h4m4 0h4m-12 5h4"/></svg></div>
    <div><h3>Scheduling</h3><p>Manage availability, reminders, and recurring sessions in one place.</p></div>
  </article>
<article class="feature">
    <div class="feature-icon" aria-hidden="true"><svg viewBox="0 0 32 32" fill="none" stroke="currentColor" stroke-width="1.7" stroke-linecap="round" stroke-linejoin="round"><rect x="3" y="8" width="18" height="17" rx="4"/><path d="m21 13 8-4v15l-8-4"/></svg></div>
    <div><h3>Video Therapy</h3><p>Bring your remote sessions into a secure, connected workflow.</p></div>
  </article>
<article class="feature">
    <div class="feature-icon" aria-hidden="true"><svg viewBox="0 0 32 32" fill="none" stroke="currentColor" stroke-width="1.7" stroke-linecap="round" stroke-linejoin="round"><rect x="4" y="3" width="24" height="26" rx="4"/><circle cx="16" cy="12" r="4"/><path d="M9 24c0-7 14-7 14 0"/></svg></div>
    <div><h3>Client Portal & Messaging</h3><p>Connect booking, forms, and secure between-session communication.</p></div>
  </article>
<article class="feature">
    <div class="feature-icon" aria-hidden="true"><svg viewBox="0 0 32 32" fill="none" stroke="currentColor" stroke-width="1.7" stroke-linecap="round" stroke-linejoin="round"><path d="M5 4v24h24M10 21l6-7 5 3 7-10"/><path d="M23 7h5v5"/></svg></div>
    <div><h3>Clinical Measures</h3><p>Use assessments to track progress and support collaborative care.</p></div>
  </article>
<article class="feature">
    <div class="feature-icon" aria-hidden="true"><svg viewBox="0 0 32 32" fill="none" stroke="currentColor" stroke-width="1.7" stroke-linecap="round" stroke-linejoin="round"><path d="m16 3 11 4v8c0 7-11 14-11 14S5 22 5 15V7Z"/><path d="m11 15 4 4 7-8"/></svg></div>
    <div><h3>Insurance & Claims</h3><p>Manage insurance details, verify benefits, and organize claims workflows.</p></div>
  </article>
<article class="feature">
    <div class="feature-icon" aria-hidden="true"><svg viewBox="0 0 32 32" fill="none" stroke="currentColor" stroke-width="1.7" stroke-linecap="round" stroke-linejoin="round"><rect x="3" y="6" width="26" height="21" rx="4"/><path d="M3 13h26M9 21h6m5 0h3"/></svg></div>
    <div><h3>Invoicing and Payments</h3><p>Keep billing and payments connected to the rest of your practice.</p></div>
  </article></div>
    </div></section>
    <section class="support section wrap" aria-labelledby="support-title"><div class="support-grid">
      <div><p class="eyebrow">Support for your next step</p><h2 id="support-title">Switch EHRs with<br>support along the way.</h2><p class="support-intro">Starting a practice or moving from another system? Zencare offers structured onboarding and migration support to help you make the transition.</p></div>
      <div class="support-points">
        <div class="support-point"><h3>One-to-one onboarding</h3><p>Get guidance as you learn the platform and set up your practice.</p></div>
        <div class="support-point"><h3>Help with migration</h3><p>Work with Zencare’s team to understand the move from your existing system.</p></div>
        <div class="support-point"><h3>A connected place to work</h3><p>Bring scheduling, notes, records, billing, and communication into the same EHR.</p></div>
      </div>
    </div></section>
    <section class="trial wrap" aria-labelledby="trial-title"><div class="trial-inner">
      <div><p class="eyebrow">Explore Practice Management</p><h2 id="trial-title">Try the tools behind<br>a more connected practice.</h2><p class="trial-copy">Start a free trial to explore scheduling, notes, billing, and client communication in Zencare Practice Management.</p></div>
      <div class="trial-action"><a class="button " data-cta-location="trial" href="<?php echo esc_url( zpm_landing_cta_url() ); ?>">Start free trial<span aria-hidden="true">↗</span></a><p>Create your Practice Management account<br>to get started.</p></div>
    </div></section>
    <section class="faq" aria-labelledby="faq-title"><div class="wrap faq-grid">
      <div class="faq-intro"><p class="eyebrow">Before you get started</p><h2 id="faq-title">A few practical answers.</h2><p>Get to know the tools and support for your practice.</p></div>
      <div class="questions">
        <details><summary>Is this for solo or group practices?</summary><p>Zencare Practice Management supports solo therapists, group practices, and growing therapy practices.</p></details>
        <details><summary>Can I get help moving from another EHR?</summary><p>Yes. Zencare offers migration support and one-to-one onboarding. Its team can help you understand the setup and transition for your practice.</p></details>
        <details><summary>Do I stay in control of AI-generated notes?</summary><p>Yes. Smart Notes produces a draft for you to review and edit. You finalize the note before it becomes part of the client record.</p></details>
        <details><summary>Can I manage billing and insurance here?</summary><p>The platform includes billing and payment tools alongside workflows for insurance details, benefit verification, and claims. Check your selected plan for its applicable features and fees.</p></details>
        <details><summary>What can clients do in the portal?</summary><p>The client portal brings booking, forms, and communication into one place. Secure messaging helps keep between-session conversations organized.</p></details>
        <details><summary>Where does “Start free trial” take me?</summary><p>You’ll go to Zencare’s Practice Management signup to create your account and begin the trial.</p></details>
      </div>
    </div></section>
    <section class="final" aria-labelledby="final-title"><div class="wrap final-inner">
      <div><h2 id="final-title">See how Zencare fits<br>your practice.</h2><p>Explore an EHR that brings your clinical and administrative tools together.</p></div><a class="button " data-cta-location="final" href="<?php echo esc_url( zpm_landing_cta_url() ); ?>">Start free trial<span aria-hidden="true">↗</span></a>
    </div></section>
  </main>
  <footer><div class="wrap footer-inner">
    <div class="footer-brand"><strong>Zencare Practice Management</strong><br>© <span id="copyright-year">2026</span> Zencare Group Inc.</div>
    <div class="legal-links" aria-label="Legal information"><a href="https://zencare.co/policy/privacy-policy" target="_blank" rel="noopener noreferrer">Privacy<span class="visually-hidden"> (opens in a new tab)</span></a><a href="https://zencare.co/policy/practice-management-terms-and-conditions" target="_blank" rel="noopener noreferrer">Terms<span class="visually-hidden"> (opens in a new tab)</span></a></div>
  </div></footer>
  <div class="mobile-bar" aria-label="Start your Practice Management trial"><a class="button " data-cta-location="mobile_sticky" href="<?php echo esc_url( zpm_landing_cta_url() ); ?>">Start free trial<span aria-hidden="true">↗</span></a></div>
  <script>
    (function(){
      'use strict';
      document.getElementById('copyright-year').textContent = new Date().getFullYear();
      // Engagement only. Successful signup is measured in the signup application.
      document.querySelectorAll('[data-cta-location]').forEach(function(link){
        link.addEventListener('click',function(){
          window.dataLayer.push({event:'zpm_trial_cta_click',cta_location:link.dataset.ctaLocation,product:'practice_management'});
        });
      });
      var hero = document.querySelector('.hero-action');
      var finalCta = document.querySelector('[data-cta-location="final"]');
      var inlineCtas = document.querySelectorAll('main [data-cta-location]');
      var bar = document.querySelector('.mobile-bar');
      var mobile = window.matchMedia('(max-width:760px)');
      var scheduled = false;
      function update(){
        var inlineVisible = Array.prototype.some.call(inlineCtas,function(link){
          var rect = link.getBoundingClientRect();
          return rect.top >= 0 && rect.bottom <= window.innerHeight;
        });
        // Keep a focused sticky link present; otherwise yield to visible inline CTAs.
        var show = mobile.matches && ((hero.getBoundingClientRect().bottom < 0 && finalCta.getBoundingClientRect().bottom > window.innerHeight && !inlineVisible) || bar.contains(document.activeElement));
        bar.classList.toggle('is-visible', show);
        document.body.classList.toggle('has-mobile-cta', show);
        scheduled = false;
      }
      function queue(){if(!scheduled){scheduled=true;window.requestAnimationFrame(update);}}
      window.addEventListener('scroll',queue,{passive:true});
      window.addEventListener('resize',queue,{passive:true});
      bar.addEventListener('focusout',queue);
      update();
    })();
  </script>
<?php if ( function_exists( 'wp_footer' ) ) { wp_footer(); } ?>
</body>
</html>
