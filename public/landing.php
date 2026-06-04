<!DOCTYPE html>
<?php
$canonicalUrl = "https://budget.konticode.com/";
$shareTitle = "Budget Tracker - Turn budget visibility into better decisions";
$shareDescription = "Track spending, catch drift early, and turn budget into action with smart categories, alerts, AI Coach, and managed support.";
$shareImage = "https://budget.konticode.com/assets/media/layout-video/01-landing.png";
?>
<html lang="en">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <meta
    name="description"
    content="<?php echo htmlspecialchars($shareDescription, ENT_QUOTES, 'UTF-8'); ?>"
  />
  <meta name="robots" content="index,follow" />
  <link rel="canonical" href="<?php echo htmlspecialchars($canonicalUrl, ENT_QUOTES, 'UTF-8'); ?>" />
  <meta property="og:type" content="website" />
  <meta property="og:site_name" content="Budget Tracker" />
  <meta property="og:url" content="<?php echo htmlspecialchars($canonicalUrl, ENT_QUOTES, 'UTF-8'); ?>" />
  <meta property="og:title" content="<?php echo htmlspecialchars($shareTitle, ENT_QUOTES, 'UTF-8'); ?>" />
  <meta property="og:description" content="<?php echo htmlspecialchars($shareDescription, ENT_QUOTES, 'UTF-8'); ?>" />
  <meta property="og:image" content="<?php echo htmlspecialchars($shareImage, ENT_QUOTES, 'UTF-8'); ?>" />
  <meta property="og:image:alt" content="Budget Tracker landing page preview" />
  <meta name="twitter:card" content="summary_large_image" />
  <meta name="twitter:title" content="<?php echo htmlspecialchars($shareTitle, ENT_QUOTES, 'UTF-8'); ?>" />
  <meta name="twitter:description" content="<?php echo htmlspecialchars($shareDescription, ENT_QUOTES, 'UTF-8'); ?>" />
  <meta name="twitter:image" content="<?php echo htmlspecialchars($shareImage, ENT_QUOTES, 'UTF-8'); ?>" />
  <meta name="twitter:image:alt" content="Budget Tracker landing page preview" />
  <title><?php echo htmlspecialchars($shareTitle, ENT_QUOTES, 'UTF-8'); ?></title>
  <link rel="stylesheet" href="/assets/css/tailwind.css">
  <link rel="preconnect" href="https://fonts.googleapis.com" />
  <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin />
  <link href="https://fonts.googleapis.com/css2?family=Playfair+Display:ital,wght@0,500;0,600;0,700;1,500&family=Manrope:wght@400;500;600;700;800&family=JetBrains+Mono:wght@400;500;600&display=swap" rel="stylesheet" />
  <style>
    :root {
      --bg: #f9f8f6;
      --panel: #ffffff;
      --panel-soft: rgba(255, 255, 255, 0.72);
      --ink: #0a0a0b;
      --muted: #5b5b61;
      --line: rgba(10, 10, 11, 0.08);
      --line-strong: rgba(10, 10, 11, 0.14);
      --accent: #0052ff;
      --accent-strong: #0040c5;
      --accent-soft: rgba(0, 82, 255, 0.08);
      --sand: #f1efea;
      --dark: #0a0a0b;
      --dark-soft: rgba(255, 255, 255, 0.7);
      --shadow: 0 24px 70px rgba(17, 24, 39, 0.08);
    }

    * {
      box-sizing: border-box;
    }

    html {
      scroll-behavior: smooth;
    }

    body {
      margin: 0;
      font-family: "Manrope", sans-serif;
      color: var(--ink);
      background: #f9f8f6;
    }

    h1,
    h2,
    h3 {
      font-family: "Playfair Display", serif;
      letter-spacing: -0.035em;
      line-height: 0.98;
    }

    .mono {
      font-family: "JetBrains Mono", monospace;
    }

    .shell {
      max-width: 80rem;
      margin: 0 auto;
      padding-left: 1.5rem;
      padding-right: 1.5rem;
    }

    .glass {
      background: rgba(249, 248, 246, 0.78);
      backdrop-filter: blur(14px);
    }

    .panel {
      background: linear-gradient(180deg, rgba(255, 255, 255, 0.95), rgba(255, 255, 255, 0.88));
      border: 1px solid var(--line);
      box-shadow: var(--shadow);
    }

    .panel-soft {
      background: var(--panel-soft);
      border: 1px solid rgba(10, 10, 11, 0.06);
    }

    .hero-grid {
      background:
        linear-gradient(180deg, rgba(255, 255, 255, 0.88), rgba(255, 255, 255, 0.78)),
        linear-gradient(rgba(10, 10, 11, 0.05) 1px, transparent 1px),
        linear-gradient(90deg, rgba(10, 10, 11, 0.05) 1px, transparent 1px);
      background-size: auto, 100% 44px, 44px 100%;
    }

    .hero-mesh {
      position: absolute;
      inset: 0;
      opacity: 0.04;
      pointer-events: none;
      background-image:
        linear-gradient(#0A0A0B 1px, transparent 1px),
        linear-gradient(90deg, #0A0A0B 1px, transparent 1px);
      background-size: 64px 64px;
    }

    .eyebrow {
      font-family: "JetBrains Mono", monospace;
      font-size: 11px;
      letter-spacing: 0.22em;
      text-transform: uppercase;
      font-weight: 600;
    }

    .cta-primary {
      display: inline-flex;
      align-items: center;
      justify-content: center;
      gap: 0.5rem;
      border-radius: 999px;
      background: #0052ff;
      color: #fff;
      font-weight: 500;
      transition: all 180ms ease;
    }

    .cta-primary:hover {
      background: #0040c5;
      transform: translateY(-2px);
    }

    .cta-secondary {
      display: inline-flex;
      align-items: center;
      justify-content: center;
      gap: 0.5rem;
      border-radius: 999px;
      border: 1px solid rgba(10, 10, 11, 0.15);
      background: transparent;
      font-weight: 500;
      transition: all 180ms ease;
    }

    .cta-secondary:hover {
      transform: translateY(-2px);
      border-color: rgba(10, 10, 11, 0.15);
      background: rgba(10, 10, 11, 0.03);
    }

    .feature-card,
    .price-card,
    .faq-card {
      transition: transform 180ms ease, box-shadow 180ms ease, border-color 180ms ease;
    }

    .feature-card:hover,
    .price-card:hover,
    .faq-card:hover {
      transform: translateY(-2px);
      border-color: rgba(0, 82, 255, 0.16);
      box-shadow: 0 18px 40px rgba(17, 24, 39, 0.08);
    }

    .quote-card:hover {
      transform: translateY(-2px);
      border-color: rgba(10, 10, 11, 0.1);
      box-shadow: 0 18px 36px rgba(17, 24, 39, 0.07);
    }

    .faq-item {
      border: 1px solid rgba(10, 10, 11, 0.06);
      border-radius: 1rem;
      background: white;
      transition: box-shadow 180ms ease, border-color 180ms ease, transform 180ms ease;
    }

    .faq-item[open] {
      box-shadow: 0 18px 40px -25px rgba(0, 0, 0, 0.15);
      border-color: rgba(10, 10, 11, 0.08);
    }

    .faq-item:hover {
      transform: translateY(-1px);
    }

    .faq-summary {
      list-style: none;
      display: flex;
      align-items: center;
      justify-content: space-between;
      gap: 1rem;
      cursor: pointer;
      padding: 1.25rem 1.5rem;
      font-size: 1rem;
      font-weight: 500;
    }

    .faq-summary::-webkit-details-marker {
      display: none;
    }

    .faq-icon {
      width: 1.25rem;
      height: 1.25rem;
      flex: 0 0 auto;
      position: relative;
      color: rgba(82, 82, 91, 1);
      transition: transform 180ms ease;
    }

    .faq-icon::before,
    .faq-icon::after {
      content: "";
      position: absolute;
      left: 50%;
      top: 50%;
      width: 14px;
      height: 1.5px;
      background: currentColor;
      border-radius: 999px;
      transform: translate(-50%, -50%);
    }

    .faq-icon::after {
      transform: translate(-50%, -50%) rotate(90deg);
    }

    .faq-item[open] .faq-icon {
      transform: rotate(180deg);
    }

    .faq-item[open] .faq-icon::after {
      opacity: 0;
    }

    .faq-content {
      padding: 0 1.5rem 1.5rem;
      color: #52525b;
      line-height: 1.7;
      font-size: 0.95rem;
    }

    .dark-section {
      background:
        radial-gradient(520px 240px at 82% 6%, rgba(0, 82, 255, 0.18), transparent 60%),
        radial-gradient(440px 220px at 10% 84%, rgba(255, 255, 255, 0.06), transparent 60%),
        var(--dark);
      color: white;
    }

    .section-divider {
      border-top: 1px solid rgba(10, 10, 11, 0.06);
      border-bottom: 1px solid rgba(10, 10, 11, 0.06);
      background: rgba(241, 239, 234, 0.6);
    }

    .form-input,
    .form-select,
    .form-textarea {
      width: 100%;
      margin-top: 0.5rem;
      border-radius: 0.9rem;
      border: 1px solid rgba(255, 255, 255, 0.2);
      background: transparent;
      color: white;
      padding: 0.85rem 1rem;
      outline: none;
    }

    .form-input::placeholder,
    .form-textarea::placeholder {
      color: rgba(255, 255, 255, 0.32);
    }

    .form-input:focus,
    .form-select:focus,
    .form-textarea:focus {
      border-color: rgba(0, 82, 255, 0.7);
      box-shadow: 0 0 0 3px rgba(0, 82, 255, 0.18);
    }

    .form-select option {
      color: black;
    }

    .logo-track {
      display: flex;
      gap: 3rem;
      width: max-content;
      animation: marquee 28s linear infinite;
      white-space: nowrap;
    }

    .bt-float {
      animation: bt-float 6s ease-in-out infinite;
    }

    .fit-chip {
      display: inline-flex;
      align-items: center;
      justify-content: center;
      border-radius: 999px;
      border: 1px solid rgba(10, 10, 11, 0.12);
      background: rgba(255, 255, 255, 0.78);
      color: rgba(10, 10, 11, 0.74);
      padding: 0.85rem 1.1rem;
      font-size: 0.9rem;
      font-weight: 600;
      transition: all 180ms ease;
    }

    .fit-chip:hover {
      border-color: rgba(0, 82, 255, 0.24);
      color: var(--ink);
      transform: translateY(-1px);
    }

    .fit-chip.is-active {
      border-color: rgba(0, 82, 255, 0.22);
      background: #0A0A0B;
      color: white;
      box-shadow: 0 18px 36px rgba(17, 24, 39, 0.12);
    }

    .fit-panel {
      display: none;
    }

    .fit-panel.is-active {
      display: block;
    }

    .mobile-nav-shell {
      display: grid;
      gap: 0.75rem;
    }

    .mobile-nav-toggle {
      display: inline-flex;
      align-items: center;
      justify-content: space-between;
      width: 100%;
      gap: 0.75rem;
      border-radius: 1.1rem;
      border: 1px solid rgba(10, 10, 11, 0.12);
      background: rgba(255, 255, 255, 0.82);
      color: rgba(10, 10, 11, 0.74);
      padding: 0.9rem 1rem;
      font-size: 0.9rem;
      font-weight: 600;
      transition: all 180ms ease;
    }

    .mobile-nav-toggle:hover {
      border-color: rgba(0, 82, 255, 0.18);
      background: rgba(255, 255, 255, 0.94);
    }

    .mobile-nav-toggle.is-open {
      border-color: rgba(0, 82, 255, 0.18);
      background: white;
      box-shadow: 0 16px 32px rgba(17, 24, 39, 0.12);
    }

    .mobile-nav-current {
      display: flex;
      flex-direction: column;
      align-items: flex-start;
      gap: 0.18rem;
      min-width: 0;
    }

    .mobile-nav-current-label {
      font-family: "JetBrains Mono", monospace;
      font-size: 10px;
      letter-spacing: 0.18em;
      text-transform: uppercase;
      color: rgba(91, 91, 97, 0.7);
    }

    .mobile-nav-current-value {
      color: #0A0A0B;
      font-size: 0.95rem;
      line-height: 1.2;
    }

    .mobile-nav-chevron {
      width: 1rem;
      height: 1rem;
      flex: 0 0 auto;
      color: rgba(91, 91, 97, 0.82);
      transition: transform 180ms ease;
    }

    .mobile-nav-toggle.is-open .mobile-nav-chevron {
      transform: rotate(180deg);
    }

    .mobile-nav-panel {
      display: grid;
      grid-template-columns: repeat(2, minmax(0, 1fr));
      gap: 0.65rem;
      padding: 0.2rem 0 0;
    }

    .mobile-nav-panel[hidden] {
      display: none;
    }

    .mobile-quick-link {
      display: inline-flex;
      align-items: center;
      justify-content: center;
      text-align: center;
      min-height: 3rem;
      border-radius: 1rem;
      border: 1px solid rgba(10, 10, 11, 0.1);
      background: rgba(255, 255, 255, 0.86);
      color: rgba(10, 10, 11, 0.74);
      padding: 0.8rem 0.9rem;
      font-size: 0.84rem;
      font-weight: 600;
      transition: all 180ms ease;
    }

    .mobile-quick-link.is-active {
      border-color: rgba(0, 82, 255, 0.18);
      background: #0A0A0B;
      color: white;
      box-shadow: 0 16px 32px rgba(17, 24, 39, 0.12);
    }

    .skip-link {
      position: absolute;
      left: 1rem;
      top: -3.5rem;
      z-index: 60;
      border-radius: 999px;
      background: #0A0A0B;
      color: white;
      padding: 0.8rem 1rem;
      font-size: 0.9rem;
      font-weight: 600;
      text-decoration: none;
      box-shadow: 0 18px 36px rgba(17, 24, 39, 0.16);
      transition: top 180ms ease;
    }

    .skip-link:focus {
      top: 1rem;
      outline: 3px solid rgba(0, 82, 255, 0.28);
      outline-offset: 2px;
    }

    .mockup-shell {
      position: relative;
    }

    .callout-chip {
      position: absolute;
      display: none;
      align-items: center;
      gap: 0.5rem;
      border-radius: 0.9rem;
      padding: 0.6rem 0.8rem;
      font-size: 0.75rem;
      line-height: 1.1;
      box-shadow: 0 18px 40px -20px rgba(0, 0, 0, 0.18);
      z-index: 2;
      backdrop-filter: blur(8px);
    }

    .callout-chip.light {
      background: rgba(255, 255, 255, 0.95);
      border: 1px solid rgba(10, 10, 11, 0.08);
      color: #0a0a0b;
    }

    .callout-chip.dark {
      background: #0a0a0b;
      color: white;
      border: 1px solid rgba(255, 255, 255, 0.08);
    }

    .callout-chip .dot {
      width: 0.5rem;
      height: 0.5rem;
      border-radius: 999px;
      flex: 0 0 auto;
    }

    .callout-chip.top-left {
      left: -1.5rem;
      top: 2rem;
    }

    .callout-chip.bottom-right {
      right: -0.5rem;
      bottom: 2.5rem;
    }

    .mini-label {
      font-family: "JetBrains Mono", monospace;
      font-size: 9px;
      letter-spacing: 0.18em;
      text-transform: uppercase;
    }

    @media (min-width: 768px) {
      .callout-chip {
        display: inline-flex;
      }
    }

    @keyframes marquee {
      from {
        transform: translateX(0);
      }
      to {
        transform: translateX(-50%);
      }
    }

    @keyframes bt-float {
      0%,
      100% {
        transform: translateY(0);
      }
      50% {
        transform: translateY(-10px);
      }
    }
  </style>
</head>
<body id="top">
  <a href="#main-content" class="skip-link">Skip to content</a>
  <header class="sticky top-0 z-30 border-b border-black/5 glass">
    <div class="shell py-4">
      <div class="flex items-center justify-between gap-4">
        <a href="#top" class="flex items-center gap-3">
          <span class="w-9 h-9 rounded-xl bg-[#0A0A0B] flex items-center justify-center">
            <span class="block w-3 h-3 bg-[#0052FF] rounded-sm rotate-12"></span>
          </span>
          <span class="text-xl tracking-tight" style="font-family: 'Playfair Display', serif;">Budget Tracker</span>
        </a>
        <nav class="hidden md:flex items-center gap-7 text-sm text-black/70">
          <a href="#features" class="hover:text-[var(--accent)] transition">Features</a>
          <a href="#pricing" class="hover:text-[var(--accent)] transition">Pricing</a>
          <a href="#faq" class="hover:text-[var(--accent)] transition">FAQ</a>
          <a href="#managed-service" class="hover:text-[var(--accent)] transition">Managed Service</a>
        </nav>
        <div class="flex items-center gap-2">
          <a href="login.php" class="hidden sm:inline-flex cta-secondary px-4 py-2.5 text-sm font-medium">Client Login</a>
          <a href="checkout.php?plan=growth" class="cta-primary px-4 py-2.5 text-sm">Start Growth</a>
        </div>
      </div>
      <div class="md:hidden mt-4 pt-4 border-t border-black/5">
        <div class="mobile-nav-shell">
          <button type="button" class="mobile-nav-toggle" id="mobile-nav-toggle" aria-expanded="false" aria-controls="mobile-nav-panel">
            <span class="mobile-nav-current">
              <span class="mobile-nav-current-label">Current section</span>
              <span class="mobile-nav-current-value" id="mobile-nav-current">Features</span>
            </span>
            <span class="mobile-nav-chevron" aria-hidden="true">⌄</span>
          </button>
          <div class="mobile-nav-panel" id="mobile-nav-panel" aria-label="Mobile section navigation" hidden>
            <a href="#features" class="mobile-quick-link is-active" data-mobile-nav-link="features" data-mobile-nav-label="Features">Features</a>
            <a href="#interactive-demo" class="mobile-quick-link" data-mobile-nav-link="interactive-demo" data-mobile-nav-label="See demo">See demo</a>
            <a href="#pricing" class="mobile-quick-link" data-mobile-nav-link="pricing" data-mobile-nav-label="Pricing">Pricing</a>
            <a href="#managed-service" class="mobile-quick-link" data-mobile-nav-link="managed-service" data-mobile-nav-label="Managed Service">Managed Service</a>
            <a href="#faq" class="mobile-quick-link" data-mobile-nav-link="faq" data-mobile-nav-label="FAQ">FAQ</a>
          </div>
        </div>
      </div>
    </div>
  </header>

  <main id="main-content" tabindex="-1">
    <section class="shell pt-10 md:pt-16 pb-12 md:pb-20 relative overflow-hidden">
      <div class="hero-mesh"></div>
      <div class="grid lg:grid-cols-12 gap-8 lg:gap-10 items-center relative">
        <div class="lg:col-span-7">
          <div class="flex items-center gap-3 mb-7">
            <span class="eyebrow text-[var(--accent)]">Budget Tracker · AI Coach · Managed Service</span>
            <span class="h-px flex-1 bg-black/10 max-w-[140px]"></span>
          </div>
          <h1 class="text-5xl md:text-7xl lg:text-[78px] mt-5 max-w-5xl">
            Track spending.<br />
            <span class="italic text-black/55">Catch drift.</span><br />
            Turn budget into action.
          </h1>
          <p class="mt-6 text-lg md:text-xl leading-relaxed text-black/68 max-w-2xl">
            Budget Tracker helps small teams spot overspending sooner, stay aligned on budget decisions, and take action before small misses become expensive habits.
          </p>
          <div class="mt-8 flex flex-wrap gap-3">
            <a href="checkout.php?plan=growth" class="cta-primary px-6 py-3.5 text-sm md:text-base">Start Growth for $10/mo</a>
            <a href="#pricing" class="cta-secondary px-6 py-3.5 text-sm md:text-base">See pricing</a>
            <a href="#interactive-demo" class="cta-secondary px-6 py-3.5 text-sm md:text-base">See demo</a>
          </div>
          <div class="mt-12 grid grid-cols-3 gap-6 max-w-lg">
            <div class="border-l border-black/10 pl-4">
              <div class="text-2xl md:text-3xl" style="font-family: 'Playfair Display', serif;">$5</div>
              <div class="text-xs text-[#52525B] mt-1 leading-snug">Starter plan / month</div>
            </div>
            <div class="border-l border-black/10 pl-4">
              <div class="text-2xl md:text-3xl" style="font-family: 'Playfair Display', serif;">3 min</div>
              <div class="text-xs text-[#52525B] mt-1 leading-snug">From checkout to dashboard</div>
            </div>
            <div class="border-l border-black/10 pl-4">
              <div class="text-2xl md:text-3xl" style="font-family: 'Playfair Display', serif;">AI Coach</div>
              <div class="text-xs text-[#52525B] mt-1 leading-snug">On Growth &amp; Scale</div>
            </div>
          </div>
        </div>

        <div class="lg:col-span-5">
          <div class="mockup-shell">
            <div class="callout-chip light top-left">
              <span class="dot bg-[#0052FF]"></span>
              <span>Alert tuned · Marketing 92%</span>
            </div>
            <div class="callout-chip dark bottom-right">
              <span class="mini-label text-white/55">Drift</span>
              <span style="font-family: 'Playfair Display', serif; font-size: 1rem;">-$412 saved</span>
            </div>

            <div class="panel hero-grid rounded-[28px] p-5 md:p-6 overflow-hidden bt-float">
              <div class="flex items-center justify-between mb-5">
                <div class="flex items-center gap-2">
                  <div class="flex gap-1.5">
                    <span class="w-2.5 h-2.5 rounded-full bg-[#F1EFEA] border border-black/10"></span>
                    <span class="w-2.5 h-2.5 rounded-full bg-[#F1EFEA] border border-black/10"></span>
                    <span class="w-2.5 h-2.5 rounded-full bg-[#F1EFEA] border border-black/10"></span>
                  </div>
                  <span class="mini-label text-[#52525B] ml-2">budgettracker / overview</span>
                </div>
                <span class="mini-label text-[#52525B]">Aug · 2026</span>
              </div>

              <div class="flex items-end justify-between border-b border-black/5 pb-5">
                <div>
                  <div class="mini-label text-[#52525B]">Monthly spend</div>
                  <div class="text-3xl md:text-4xl mt-1" style="font-family: 'Playfair Display', serif;">$8,780</div>
                  <div class="text-xs text-[#52525B] mt-1">of <span class="text-[#0A0A0B] font-medium">$9,700</span> budget</div>
                </div>
                <div class="text-right">
                  <div class="mini-label text-[#0052FF]">On track</div>
                  <div class="text-xs text-[#52525B] mt-1">9.5% headroom</div>
                </div>
              </div>

              <div class="mt-5">
                <div class="flex items-end gap-1.5 h-24">
                  <div class="flex-1 rounded-sm h-[38%] bg-[#0A0A0B]" style="opacity:0.85;"></div>
                  <div class="flex-1 rounded-sm h-[52%] bg-[#0A0A0B]" style="opacity:0.81;"></div>
                  <div class="flex-1 rounded-sm h-[44%] bg-[#0A0A0B]" style="opacity:0.77;"></div>
                  <div class="flex-1 rounded-sm h-[61%] bg-[#0A0A0B]" style="opacity:0.73;"></div>
                  <div class="flex-1 rounded-sm h-[48%] bg-[#0A0A0B]" style="opacity:0.69;"></div>
                  <div class="flex-1 rounded-sm h-[72%] bg-[#0A0A0B]" style="opacity:0.65;"></div>
                  <div class="flex-1 rounded-sm h-[59%] bg-[#0A0A0B]" style="opacity:0.61;"></div>
                  <div class="flex-1 rounded-sm h-[66%] bg-[#0A0A0B]" style="opacity:0.57;"></div>
                  <div class="flex-1 rounded-sm h-[80%] bg-[#0A0A0B]" style="opacity:0.53;"></div>
                  <div class="flex-1 rounded-sm h-[71%] bg-[#0A0A0B]" style="opacity:0.49;"></div>
                  <div class="flex-1 rounded-sm h-[84%] bg-[#0A0A0B]" style="opacity:0.45;"></div>
                  <div class="flex-1 rounded-sm h-[92%] bg-[#0052FF]"></div>
                </div>
                <div class="flex justify-between mt-2 mini-label text-[#52525B]">
                  <span>Jan</span><span>Apr</span><span>Jul</span><span>Oct</span><span>Dec</span>
                </div>
              </div>

              <div class="mt-5 space-y-3">
                <div>
                  <div class="flex items-center justify-between text-xs">
                    <span class="font-medium">Software</span>
                    <span class="mono text-[#52525B]">$1,240 / $1,500</span>
                  </div>
                  <div class="h-1.5 mt-1.5 rounded-full bg-[#F1EFEA] overflow-hidden">
                    <div class="h-full rounded-full bg-[#0052FF]" style="width:83%;"></div>
                  </div>
                </div>
                <div>
                  <div class="flex items-center justify-between text-xs">
                    <span class="font-medium">Marketing</span>
                    <span class="mono text-[#52525B]">$2,780 / $3,000</span>
                  </div>
                  <div class="h-1.5 mt-1.5 rounded-full bg-[#F1EFEA] overflow-hidden">
                    <div class="h-full rounded-full bg-[#0A0A0B]" style="width:92%;"></div>
                  </div>
                </div>
                <div>
                  <div class="flex items-center justify-between text-xs">
                    <span class="font-medium">Travel</span>
                    <span class="mono text-[#52525B]">$540 / $1,200</span>
                  </div>
                  <div class="h-1.5 mt-1.5 rounded-full bg-[#F1EFEA] overflow-hidden">
                    <div class="h-full rounded-full bg-[#0052FF]" style="width:45%;"></div>
                  </div>
                </div>
                <div>
                  <div class="flex items-center justify-between text-xs">
                    <span class="font-medium">Contractors</span>
                    <span class="mono text-[#DC2626]">$4,220 / $4,000</span>
                  </div>
                  <div class="h-1.5 mt-1.5 rounded-full bg-[#F1EFEA] overflow-hidden">
                    <div class="h-full rounded-full bg-[#DC2626]" style="width:100%;"></div>
                  </div>
                </div>
              </div>
            </div>
          </div>
        </div>
      </div>
    </section>

    <section class="section-divider py-10 md:py-12">
      <div class="shell">
        <div class="grid gap-6 lg:grid-cols-[0.95fr_1.05fr] lg:items-center">
          <div>
            <p class="mono text-[10px] uppercase tracking-[0.22em] text-[#52525B]">Built for focused teams</p>
            <h2 class="text-3xl md:text-4xl mt-3">Made for the people who actually carry budget discipline week to week.</h2>
          </div>
          <div class="grid grid-cols-2 md:grid-cols-3 gap-3">
            <div class="panel-soft rounded-2xl px-4 py-4 text-sm text-black/68">Founders watching every dollar</div>
            <div class="panel-soft rounded-2xl px-4 py-4 text-sm text-black/68">Operations leads keeping spending on pace</div>
            <div class="panel-soft rounded-2xl px-4 py-4 text-sm text-black/68">Finance managers reducing monthly surprises</div>
            <div class="panel-soft rounded-2xl px-4 py-4 text-sm text-black/68">Agencies balancing payroll, tools, and client work</div>
            <div class="panel-soft rounded-2xl px-4 py-4 text-sm text-black/68">Service businesses tightening category control</div>
            <div class="panel-soft rounded-2xl px-4 py-4 text-sm text-black/68">Growing teams adding structure before drift compounds</div>
          </div>
        </div>
      </div>
    </section>

    <section id="interactive-demo" class="shell py-20 md:py-28">
      <div class="grid lg:grid-cols-12 gap-8 items-center">
        <div class="lg:col-span-5">
          <p class="eyebrow text-[var(--accent)]">Interactive Demo</p>
          <h2 class="text-4xl md:text-6xl mt-4">See how budget visibility becomes a weekly operating rhythm.</h2>
          <p class="mt-5 text-lg leading-relaxed text-black/64 max-w-xl">
            See how alerts, review cadence, and AI Coach guidance work together to help your team catch drift earlier and act with more confidence.
          </p>
          <div class="mt-8 flex flex-wrap gap-3">
            <a href="#interactive-demo" class="cta-primary px-6 py-3.5 text-sm md:text-base">See demo</a>
            <a href="#pricing" class="cta-secondary px-6 py-3.5 text-sm md:text-base">See pricing</a>
          </div>
        </div>
        <div class="lg:col-span-7">
          <div class="rounded-[28px] bg-[#0A0A0B] text-white p-6 md:p-8 shadow-[0_28px_60px_-30px_rgba(0,0,0,0.5)]">
            <div class="grid md:grid-cols-[1.1fr_0.9fr] gap-5">
              <div class="rounded-3xl border border-white/10 bg-white/5 p-5">
                <div class="flex items-center justify-between">
                  <div>
                    <p class="eyebrow text-white/40">Weekly review</p>
                    <p class="text-2xl font-semibold mt-2">Friday budget check-in</p>
                  </div>
                  <span class="rounded-full bg-[#0052FF]/20 px-3 py-1 text-xs font-semibold text-[#7aa2ff]">Live</span>
                </div>
                <div class="mt-5 space-y-4">
                  <div class="rounded-2xl border border-white/10 bg-black/20 p-4">
                    <p class="text-sm text-white/52">Top category drift</p>
                    <div class="mt-2 flex items-center justify-between gap-4">
                      <span class="font-semibold">Marketing</span>
                      <span class="text-[#7aa2ff] font-semibold">+14% vs pace</span>
                    </div>
                  </div>
                  <div class="rounded-2xl border border-white/10 bg-black/20 p-4">
                    <p class="text-sm text-white/52">Coach recommendation</p>
                    <p class="mt-2 text-sm text-white/78 leading-6">Pause ad expansion, review contractor scope, and update next week&apos;s close checklist before new spend lands.</p>
                  </div>
                </div>
              </div>
              <div class="rounded-3xl border border-white/10 bg-white/5 p-5">
                <p class="eyebrow text-white/40">What teams feel</p>
                <h3 class="text-3xl mt-3">Fewer surprises. Better follow-through.</h3>
                <div class="mt-5 space-y-3 text-sm text-white/72">
                  <div class="rounded-2xl border border-white/10 px-4 py-3">Biweekly reviews on Growth</div>
                  <div class="rounded-2xl border border-white/10 px-4 py-3">Weekly check-ins on Scale</div>
                  <div class="rounded-2xl border border-white/10 px-4 py-3">Managed service support when your team needs more help</div>
                </div>
                <a href="#managed-service" class="inline-flex items-center gap-2 mt-6 text-sm text-[#7aa2ff] hover:text-white transition">Explore managed service</a>
              </div>
            </div>
          </div>
        </div>
      </div>
    </section>

    <section id="features" class="shell py-20 md:py-28">
      <div class="max-w-3xl">
        <p class="eyebrow text-[var(--accent)]">Features</p>
        <h2 class="text-4xl md:text-6xl mt-4">Everything you need to manage budget drift without getting buried in spreadsheets.</h2>
        <p class="mt-5 text-lg leading-relaxed text-black/64">
          Budget Tracker gives growing teams earlier signals, clearer decisions, and the confidence to stay ahead of drift before it turns into a bigger problem.
        </p>
      </div>

      <div class="grid md:grid-cols-2 xl:grid-cols-4 gap-4 mt-12">
        <article class="feature-card panel-soft rounded-3xl p-6">
          <p class="eyebrow text-[var(--accent)]">01</p>
          <h3 class="text-2xl mt-3">Signal Detection</h3>
          <p class="mt-3 text-sm leading-6 text-black/62">Catch threshold pressure, category variance, and overspend momentum before it becomes a bigger operating problem.</p>
        </article>
        <article class="feature-card panel-soft rounded-3xl p-6">
          <p class="eyebrow text-[var(--accent)]">02</p>
          <h3 class="text-2xl mt-3">Alert Tuning</h3>
          <p class="mt-3 text-sm leading-6 text-black/62">Adjust category limits and notification behavior so the right issues surface first instead of creating noise.</p>
        </article>
        <article class="feature-card panel-soft rounded-3xl p-6">
          <p class="eyebrow text-[var(--accent)]">03</p>
          <h3 class="text-2xl mt-3">AI Coach</h3>
          <p class="mt-3 text-sm leading-6 text-black/62">Move from dashboards to decisions with prioritized review prompts, next-step recommendations, and a clearer picture of what deserves attention first.</p>
        </article>
        <article class="feature-card panel-soft rounded-3xl p-6">
          <p class="eyebrow text-[var(--accent)]">04</p>
          <h3 class="text-2xl mt-3">Managed Path</h3>
          <p class="mt-3 text-sm leading-6 text-black/62">Start with software, then add hands-on help for setup, recurring reviews, and stronger follow-through when your budget process needs more support.</p>
        </article>
      </div>
    </section>

    <section id="calculator" class="section-divider py-20 md:py-28">
      <div class="shell grid lg:grid-cols-12 gap-8 items-start">
        <div class="lg:col-span-5">
          <p class="eyebrow text-[var(--accent)]">ROI Calculator</p>
          <h2 class="text-4xl md:text-6xl mt-4">Estimate what one saved overspend cycle is worth.</h2>
          <p class="mt-5 text-lg leading-relaxed text-black/64 max-w-xl">
            Compare the cost of your plan with the value of catching overspend earlier, before a small drift turns into a larger operating problem.
          </p>
        </div>
        <div class="lg:col-span-7">
          <div class="panel rounded-[28px] p-6 md:p-8">
            <div class="grid md:grid-cols-2 gap-6">
              <div class="space-y-5">
                <label class="block">
                  <span class="eyebrow text-black/45">Monthly budget</span>
                  <input id="calc-budget" type="range" min="5000" max="100000" step="500" value="25000" class="w-full mt-4" />
                  <div class="mt-2 text-2xl font-bold" id="calc-budget-value">$25,000</div>
                </label>
                <label class="block">
                  <span class="eyebrow text-black/45">Typical drift rate</span>
                  <input id="calc-drift" type="range" min="2" max="25" step="1" value="10" class="w-full mt-4" />
                  <div class="mt-2 text-2xl font-bold" id="calc-drift-value">10%</div>
                </label>
                <label class="block">
                  <span class="eyebrow text-black/45">Months caught earlier</span>
                  <input id="calc-months" type="range" min="1" max="6" step="1" value="3" class="w-full mt-4" />
                  <div class="mt-2 text-2xl font-bold" id="calc-months-value">3 months</div>
                </label>
              </div>
              <div class="rounded-[26px] bg-[#0A0A0B] text-white p-6">
                <p class="eyebrow text-[#7aa2ff]">Estimated value</p>
                <div class="mt-5">
                  <p class="text-white/58 text-sm">Potential overspend caught sooner</p>
                  <p class="text-5xl font-bold mt-2" id="calc-savings">$7,500</p>
                </div>
                <div class="mt-6 pt-6 border-t border-white/10">
                  <p class="text-white/58 text-sm">Growth plan cost over the same window</p>
                  <p class="text-3xl font-semibold mt-2" id="calc-cost">$30</p>
                </div>
                <div class="mt-6 rounded-2xl bg-white/5 border border-white/10 p-4">
                  <p class="text-sm text-white/74 leading-6" id="calc-summary">Catching a 10% drift on a $25,000 monthly budget even three months earlier dwarfs the cost of Growth.</p>
                </div>
              </div>
            </div>
          </div>
        </div>
      </div>
    </section>

    <section class="dark-section py-20 md:py-28">
      <div class="shell grid lg:grid-cols-12 gap-8 items-start">
        <div class="lg:col-span-5">
          <p class="eyebrow text-[#7aa2ff]">How it Works</p>
          <h2 class="text-4xl md:text-6xl mt-4">Three plans, one clean path from visibility to support.</h2>
          <p class="mt-6 text-lg leading-relaxed text-white/70 max-w-xl">
            Most teams start with Growth for the right mix of visibility, review cadence, and guidance. Scale adds faster support and deeper planning when the pace and stakes increase.
          </p>
        </div>
        <div class="lg:col-span-7 grid md:grid-cols-3 gap-4">
          <article class="rounded-3xl border border-white/10 bg-white/5 p-6">
            <p class="eyebrow text-white/45">Starter</p>
            <h3 class="text-3xl mt-4">$5<span class="text-lg text-white/45">/mo</span></h3>
            <p class="mt-4 text-white/72 text-sm leading-6">A simple starting point for smaller teams that want visibility and early warning signals without added complexity.</p>
          </article>
          <article class="rounded-3xl border border-[#0052FF] bg-[#0052FF]/10 p-6 shadow-[0_18px_40px_-25px_rgba(0,82,255,0.4)]">
            <p class="eyebrow text-[#7aa2ff]">Growth</p>
            <h3 class="text-3xl mt-4">$10<span class="text-lg text-white/45">/mo</span></h3>
            <p class="mt-4 text-white/82 text-sm leading-6">Our most popular plan, with biweekly reviews, full alerts, and AI Coach access.</p>
          </article>
          <article class="rounded-3xl border border-white/10 bg-white/5 p-6">
            <p class="eyebrow text-white/45">Scale</p>
            <h3 class="text-3xl mt-4">$20<span class="text-lg text-white/45">/mo</span></h3>
            <p class="mt-4 text-white/72 text-sm leading-6">For teams that want faster support, deeper planning help, and a closer working relationship.</p>
          </article>
        </div>
      </div>
    </section>

    <section class="section-divider py-20 md:py-28">
      <div class="shell grid lg:grid-cols-12 gap-8 items-start">
        <div class="lg:col-span-5">
          <p class="eyebrow text-[var(--accent)]">Start Here</p>
          <h2 class="text-4xl md:text-6xl mt-4">A clearer path from signup to a working budget rhythm.</h2>
          <p class="mt-5 text-lg leading-relaxed text-black/64 max-w-xl">
            Buyers usually want three things before they commit: confidence that this is for a team like theirs, clarity on what happens right after checkout, and a practical picture of what extra guidance actually looks like.
          </p>
          <div class="mt-8 flex flex-wrap gap-3" role="tablist" aria-label="Choose the plan-fit view">
            <button type="button" id="fit-tab-growth" class="fit-chip is-active" data-fit-target="fit-growth" role="tab" aria-selected="true" aria-controls="fit-growth" tabindex="0">Most teams</button>
            <button type="button" id="fit-tab-scale" class="fit-chip" data-fit-target="fit-scale" role="tab" aria-selected="false" aria-controls="fit-scale" tabindex="-1">Fast-moving teams</button>
            <button type="button" id="fit-tab-managed" class="fit-chip" data-fit-target="fit-managed" role="tab" aria-selected="false" aria-controls="fit-managed" tabindex="-1">Need hands-on help</button>
          </div>
        </div>
        <div class="lg:col-span-7">
          <div id="fit-panel-surface" class="panel rounded-[30px] p-6 md:p-8" role="tabpanel" aria-labelledby="fit-tab-growth" tabindex="-1">
            <div class="grid md:grid-cols-[1.1fr_0.9fr] gap-6">
              <div>
                <p id="fit-panel-eyebrow" class="eyebrow text-[var(--accent)]">Best fit right now</p>
                <h3 id="fit-panel-title" class="text-3xl md:text-4xl mt-3">Growth is the right starting point for most small teams with real monthly spend.</h3>
                <p id="fit-panel-body" class="mt-4 text-base leading-7 text-black/68">Choose this if one person is carrying budget accountability, reviews happen inconsistently, and you want earlier alerts without adding a lot of process.</p>
                <div id="fit-panel-points" class="mt-6 grid gap-3 text-sm text-black/70">
                  <div class="panel-soft rounded-2xl px-4 py-4">Best for founders, operators, and finance leads managing roughly 5 to 50 people.</div>
                  <div class="panel-soft rounded-2xl px-4 py-4">Good when marketing, software, contractors, or travel start drifting faster than your team can spot manually.</div>
                </div>
              </div>
              <div class="rounded-[26px] bg-[#0A0A0B] text-white p-6">
                <p id="fit-panel-side-eyebrow" class="eyebrow text-[#7aa2ff]">What happens after signup</p>
                <div id="fit-panel-side-content" class="mt-5 space-y-4 text-sm text-white/78 leading-6">
                  <div>1. Pay securely through Stripe and create your account.</div>
                  <div>2. Land in your dashboard and choose the categories you care about most.</div>
                  <div>3. Start seeing drift signals and use AI Coach to decide what to review first.</div>
                  <div>4. Build a repeatable weekly or biweekly review habit without starting from scratch.</div>
                </div>
              </div>
            </div>
          </div>
        </div>
      </div>
    </section>

    <section id="pricing" class="shell py-20 md:py-28">
      <div class="max-w-3xl">
        <p class="eyebrow text-[var(--accent)]">Pricing</p>
          <h2 class="text-4xl md:text-6xl mt-4">Start with the plan that fits today, then grow when you need more support.</h2>
          <p class="mt-5 text-lg leading-relaxed text-black/64">
          Most teams should begin with Growth. It gives you the visibility, alerts, and guidance to stay ahead without adding unnecessary complexity.
          </p>
      </div>

      <div class="grid lg:grid-cols-3 gap-5 mt-12">
        <article class="price-card panel rounded-[28px] p-7 flex flex-col">
          <p class="eyebrow text-black/42">Starter</p>
          <h3 class="text-4xl mt-4">$5<span class="text-lg text-black/45">/mo</span></h3>
          <p class="mt-4 text-sm text-black/62 leading-6">A simple way to start tracking spend, reviewing trends, and catching early signs of drift.</p>
          <ul class="mt-6 space-y-3 text-sm text-black/68 flex-1">
            <li>Core dashboard access</li>
            <li>Monthly budget health report</li>
            <li>Early drift visibility by category</li>
            <li>Limited alerts and threshold monitoring</li>
            <li>Best for smaller teams getting started</li>
          </ul>
          <a href="checkout.php?plan=starter" class="cta-secondary mt-8 px-5 py-3 text-sm font-semibold">Choose Starter</a>
        </article>

        <article class="price-card rounded-[28px] border border-[#0052FF] bg-[#0A0A0B] text-white p-7 flex flex-col shadow-[0_24px_60px_-30px_rgba(0,82,255,0.5)]">
          <p class="eyebrow text-[#7aa2ff]">Best Value</p>
          <h3 class="text-4xl mt-4">Growth<span class="block text-2xl mt-1">$10/mo</span></h3>
          <p class="mt-4 text-sm text-white/72 leading-6">The best fit for most teams, with the right mix of alerts, review cadence, and practical guidance.</p>
          <ul class="mt-6 space-y-3 text-sm text-white/80 flex-1">
            <li>Biweekly spend and variance reviews</li>
            <li>Full dashboard alerts</li>
            <li>Alert tuning and threshold updates</li>
            <li>Monthly action plan</li>
            <li>AI Coach guidance</li>
          </ul>
          <div class="mt-6 rounded-2xl border border-white/10 bg-white/5 px-4 py-4 text-sm text-white/74 leading-6">
            AI Coach helps you decide what to review first, what changed, and what action to take next when drift starts building.
          </div>
          <a href="checkout.php?plan=growth" class="cta-primary mt-8 px-5 py-3 text-sm">Choose Growth</a>
        </article>

        <article class="price-card panel rounded-[28px] p-7 flex flex-col">
          <p class="eyebrow text-black/42">Scale</p>
          <h3 class="text-4xl mt-4">$20<span class="text-lg text-black/45">/mo</span></h3>
          <p class="mt-4 text-sm text-black/62 leading-6">For teams that want faster support, deeper planning help, and a more hands-on working rhythm.</p>
          <ul class="mt-6 space-y-3 text-sm text-black/68 flex-1">
            <li>Weekly advisor check-ins</li>
            <li>Forecasting and scenario planning</li>
            <li>Priority support lane</li>
            <li>Custom workflow guidance</li>
            <li>Best fit for teams that want deeper support</li>
          </ul>
          <div class="mt-6 rounded-2xl border border-black/10 bg-black/[0.02] px-4 py-4 text-sm text-black/66 leading-6">
            Scale adds a closer working rhythm for teams that want more planning help and quicker follow-through before moving into managed service.
          </div>
          <div class="mt-8 grid gap-3">
            <a href="checkout.php?plan=scale" class="cta-secondary px-5 py-3 text-sm font-semibold">Choose Scale</a>
            <a href="#managed-service" class="cta-secondary px-5 py-3 text-sm font-semibold">Explore Managed Service</a>
          </div>
        </article>
      </div>
    </section>

    <section class="shell py-20 md:py-28">
      <div class="max-w-3xl">
        <p class="eyebrow text-[var(--accent)]">Use Cases</p>
        <h2 class="text-4xl md:text-6xl mt-4">Where teams usually feel the value first.</h2>
      </div>
      <div class="grid md:grid-cols-3 gap-4 mt-12">
        <article class="quote-card panel-soft rounded-3xl p-6 transition">
          <p class="eyebrow text-[var(--accent)]">Marketing spend</p>
          <h3 class="text-3xl mt-3">Catch overspend before campaigns outrun the plan.</h3>
          <p class="mt-4 text-base leading-7 text-black/72">Track category pace, see threshold pressure early, and make changes while there is still time to protect the month.</p>
        </article>
        <article class="quote-card panel-soft rounded-3xl p-6 transition">
          <p class="eyebrow text-[var(--accent)]">Monthly review</p>
          <h3 class="text-3xl mt-3">Give your team a simple rhythm for better budget decisions.</h3>
          <p class="mt-4 text-base leading-7 text-black/72">Use recurring reviews, alerts, and AI Coach guidance to turn scattered information into a clear weekly and monthly routine.</p>
        </article>
        <article class="quote-card panel-soft rounded-3xl p-6 transition">
          <p class="eyebrow text-[var(--accent)]">Growing complexity</p>
          <h3 class="text-3xl mt-3">Add support when the budget process starts to strain.</h3>
          <p class="mt-4 text-base leading-7 text-black/72">Start with the software, then step into managed help when you need more accountability, planning support, and follow-through.</p>
        </article>
      </div>
    </section>

    <section id="managed-service" class="section-divider py-20 md:py-28">
      <div class="shell grid lg:grid-cols-12 gap-8 items-center">
        <div class="lg:col-span-7">
          <p class="eyebrow text-[var(--accent)]">Managed Service</p>
          <h2 class="text-4xl md:text-6xl mt-4">Need more support than software alone can give?</h2>
          <p class="mt-5 text-lg leading-relaxed text-black/64 max-w-3xl">
            Start with Budget Tracker, then add hands-on support when your team wants help with setup, recurring reviews, workflow tuning, and ongoing operating discipline.
          </p>
          <div class="mt-8 flex flex-wrap gap-3">
            <a href="#managed-service-contact" class="cta-primary px-6 py-3.5 text-sm md:text-base">Explore Managed Service</a>
            <a href="#pricing" class="cta-secondary px-6 py-3.5 text-sm md:text-base">See pricing</a>
          </div>
        </div>
        <div class="lg:col-span-5">
          <div class="rounded-[28px] bg-[#0A0A0B] text-white p-8">
            <p class="eyebrow text-[#7aa2ff]">Why teams upgrade</p>
            <ul class="mt-6 space-y-4 text-white/72">
              <li>Weekly support replaces biweekly cadence</li>
              <li>Planning help goes deeper than on-screen recommendations</li>
              <li>Stronger accountability around follow-through</li>
              <li>A smoother move into higher-touch support</li>
            </ul>
          </div>
        </div>
      </div>
    </section>

    <section id="managed-service-contact" class="dark-section py-20 md:py-28">
      <div class="shell grid lg:grid-cols-12 gap-8">
        <div class="lg:col-span-5">
          <p class="eyebrow text-[#7aa2ff]">Managed service</p>
          <h2 class="text-5xl md:text-6xl mt-4">Need a second pair of eyes?</h2>
          <p class="text-white/70 mt-6 text-lg leading-relaxed max-w-md">Tell us about your team. We&apos;ll recommend the right plan or help you decide whether managed support is the better fit.</p>
          <div class="mt-10 space-y-3 text-sm text-white/70">
            <div class="flex items-center gap-2"><span class="text-[#0052FF]">●</span>One business day response</div>
            <div class="flex items-center gap-2"><span class="text-[#0052FF]">●</span>No sales pressure, just a clear recommendation</div>
            <div class="flex items-center gap-2"><span class="text-[#0052FF]">●</span>Guidance on when extra support is worth it</div>
          </div>
        </div>
        <div class="lg:col-span-7">
          <form id="lead-form" data-budget-contact-form action="/api/contact.php" method="post" class="bg-white/5 border border-white/10 rounded-2xl p-7 md:p-10 backdrop-blur-sm">
            <div class="grid grid-cols-1 md:grid-cols-2 gap-5">
              <div>
                <label for="lead-name-input" class="text-white/70 mono text-[10px] uppercase tracking-widest">Name</label>
                <input id="lead-name-input" name="name" class="form-input" placeholder="Avery Tan" autocomplete="name" required />
              </div>
              <div>
                <label for="lead-email-input" class="text-white/70 mono text-[10px] uppercase tracking-widest">Work email</label>
                <input id="lead-email-input" name="email" type="email" class="form-input" placeholder="you@company.com" autocomplete="email" required />
              </div>
              <div>
                <label for="lead-company-size" class="text-white/70 mono text-[10px] uppercase tracking-widest">Company size</label>
                <select id="lead-company-size" name="company_size" class="form-select">
                  <option value="">Select team size</option>
                  <option value="1-5">1-5</option>
                  <option value="6-15">6-15</option>
                  <option value="16-50">16-50</option>
                  <option value="50+">50+</option>
                </select>
              </div>
              <div>
                <label for="lead-plan-interest" class="text-white/70 mono text-[10px] uppercase tracking-widest">Plan interest</label>
                <select id="lead-plan-interest" name="plan_interest" class="form-select">
                  <option value="">Pick a plan</option>
                  <option value="Starter">Starter</option>
                  <option value="Growth">Growth</option>
                  <option value="Scale">Scale</option>
                  <option value="Managed service">Managed service</option>
                </select>
              </div>
            </div>
            <div class="mt-5">
              <label for="lead-message-input" class="text-white/70 mono text-[10px] uppercase tracking-widest">What are you trying to control?</label>
              <textarea id="lead-message-input" name="message" rows="4" class="form-textarea" placeholder="Contractor spend, marketing drift, monthly close cadence..." required></textarea>
            </div>
            <div class="mt-7 flex flex-wrap items-center justify-between gap-4">
              <div>
                <span class="text-xs text-white/50">We&apos;ll never share your details. Reply within one business day.</span>
                <p id="lead-form-status" data-budget-contact-status class="mt-2 text-sm text-white/70 hidden"></p>
              </div>
              <button id="lead-submit-button" type="submit" class="cta-primary px-6 py-3 text-sm">Send message</button>
            </div>
          </form>
        </div>
      </div>
    </section>

    <section id="faq" class="shell py-20 md:py-28">
      <div class="max-w-3xl mx-auto text-center">
        <p class="eyebrow text-[var(--accent)]">FAQ</p>
        <h2 class="text-4xl md:text-6xl mt-4">Questions, <span class="italic text-black/52">answered.</span></h2>
      </div>

      <div class="max-w-3xl mx-auto mt-12 space-y-3">
        <details class="faq-item" open>
          <summary class="faq-summary">
            <span>Which plan should most people buy?</span>
            <span class="faq-icon" aria-hidden="true"></span>
          </summary>
          <div class="faq-content">Growth is the best fit for most teams because it combines alerts, AI Coach access, and a steady review rhythm without overcomplicating the process.</div>
        </details>
        <details class="faq-item">
          <summary class="faq-summary">
            <span>What is Scale for?</span>
            <span class="faq-icon" aria-hidden="true"></span>
          </summary>
          <div class="faq-content">Scale is for teams that want faster support, deeper planning help, and weekly check-ins instead of the biweekly cadence on Growth.</div>
        </details>
        <details class="faq-item">
          <summary class="faq-summary">
            <span>What happens right after I sign up?</span>
            <span class="faq-icon" aria-hidden="true"></span>
          </summary>
          <div class="faq-content">You complete checkout, create or log into your account, and land in the dashboard ready to start tracking the categories that matter most. From there, alerts, review prompts, and your chosen plan support level start guiding the rhythm.</div>
        </details>
        <details class="faq-item">
          <summary class="faq-summary">
            <span>What does AI Coach actually do?</span>
            <span class="faq-icon" aria-hidden="true"></span>
          </summary>
          <div class="faq-content">AI Coach helps you turn raw budget signals into decisions by highlighting what needs attention first, suggesting practical next steps, and helping your team stay consistent in reviews and follow-through.</div>
        </details>
        <details class="faq-item">
          <summary class="faq-summary">
            <span>What does managed service include?</span>
            <span class="faq-icon" aria-hidden="true"></span>
          </summary>
          <div class="faq-content">Managed service adds hands-on help with setup, recurring reviews, planning support, and stronger accountability so your team is not left to figure out the process alone.</div>
        </details>
        <details class="faq-item">
          <summary class="faq-summary">
            <span>Can I sell both software and services?</span>
            <span class="faq-icon" aria-hidden="true"></span>
          </summary>
          <div class="faq-content">Yes. Many teams begin with the software, then add managed support when they want more hands-on help with setup, reviews, and follow-through.</div>
        </details>
        <details class="faq-item">
          <summary class="faq-summary">
            <span>Can I switch plans later?</span>
            <span class="faq-icon" aria-hidden="true"></span>
          </summary>
          <div class="faq-content">Yes. Upgrades and downgrades take effect on the next billing cycle. No re-onboarding and no lost data.</div>
        </details>
        <details class="faq-item">
          <summary class="faq-summary">
            <span>Is there a free trial?</span>
            <span class="faq-icon" aria-hidden="true"></span>
          </summary>
          <div class="faq-content">Starter gives you a low-risk way to get real visibility first, then move up to Growth when you want fuller alerts and more guidance.</div>
        </details>
        <details class="faq-item">
          <summary class="faq-summary">
            <span>What do I need before taking payments?</span>
            <span class="faq-icon" aria-hidden="true"></span>
          </summary>
          <div class="faq-content">Just choose the plan that fits your team and complete checkout securely with Stripe. If you need help selecting the right starting point, we can guide you before you buy.</div>
        </details>
      </div>
    </section>
  </main>

  <footer class="dark-section pt-20 pb-10">
    <div class="shell">
      <div class="grid lg:grid-cols-12 gap-10 border-b border-white/10 pb-14">
        <div class="lg:col-span-7">
          <p class="eyebrow text-[#7aa2ff]">Ready when you are</p>
          <h2 class="text-5xl md:text-7xl lg:text-[88px] mt-5">Turn budget visibility into <span class="italic text-white/55">better decisions.</span></h2>
          <div class="mt-9 flex flex-wrap gap-3">
            <a href="checkout.php?plan=growth" class="cta-primary px-6 py-3.5 text-sm md:text-base">Start Growth - $10/mo</a>
            <a href="#pricing" class="cta-secondary px-6 py-3.5 text-sm md:text-base text-white border-white/20 bg-white/5">See pricing</a>
          </div>
        </div>

        <div class="lg:col-span-5 grid grid-cols-2 gap-8 text-sm">
          <div>
            <div class="mono text-[10px] uppercase tracking-[0.22em] text-white/40 mb-4">Product</div>
            <ul class="space-y-3 text-white/74">
              <li><a href="#features" class="hover:text-white">Features</a></li>
              <li><a href="#calculator" class="hover:text-white">ROI calculator</a></li>
              <li><a href="#pricing" class="hover:text-white">Pricing</a></li>
              <li><a href="#pricing" class="hover:text-white">See pricing</a></li>
              <li><a href="#interactive-demo" class="hover:text-white">See demo</a></li>
            </ul>
          </div>
          <div>
            <div class="mono text-[10px] uppercase tracking-[0.22em] text-white/40 mb-4">Company</div>
            <ul class="space-y-3 text-white/74">
              <li><a href="#managed-service" class="hover:text-white">Managed service</a></li>
              <li><a href="mailto:contact@budget.konticode.com" class="hover:text-white">Contact us</a></li>
              <li><a href="#faq" class="hover:text-white">FAQ</a></li>
              <li><a href="login.php" class="hover:text-white">Client Login</a></li>
            </ul>
          </div>
        </div>
      </div>

      <div class="pt-8 flex flex-wrap items-center justify-between gap-4 text-xs text-white/45">
        <div class="flex items-center gap-3">
          <span class="w-6 h-6 rounded-md bg-white/5 border border-white/10 flex items-center justify-center">
            <span class="block w-2 h-2 bg-[var(--accent)] rounded-sm rotate-12"></span>
          </span>
          <span>&copy; <?php echo date("Y"); ?> Budget Tracker &middot; Konticode</span>
        </div>
        <div class="flex flex-wrap gap-6">
          <span>Built for focused teams.</span>
          <a href="#top" class="hover:text-white transition">budget.konticode.com</a>
        </div>
      </div>
    </div>
  </footer>
  <script>
    (function () {
      const mobileLinks = Array.from(document.querySelectorAll("[data-mobile-nav-link]"));
      const mobileToggle = document.getElementById("mobile-nav-toggle");
      const mobilePanel = document.getElementById("mobile-nav-panel");
      const mobileCurrent = document.getElementById("mobile-nav-current");

      if (!mobileLinks.length || !("IntersectionObserver" in window)) {
        return;
      }

      const sectionIds = mobileLinks
        .map(function (link) {
          return link.getAttribute("data-mobile-nav-link");
        })
        .filter(Boolean);

      const sections = sectionIds
        .map(function (id) {
          return document.getElementById(id);
        })
        .filter(Boolean);

      function setActiveMobileLink(id) {
        mobileLinks.forEach(function (link) {
          const isActive = link.getAttribute("data-mobile-nav-link") === id;
          link.classList.toggle("is-active", isActive);
          if (isActive && mobileCurrent) {
            mobileCurrent.textContent = link.getAttribute("data-mobile-nav-label") || link.textContent.trim();
          }
        });
      }

      function setMobilePanelOpen(isOpen) {
        if (!mobileToggle || !mobilePanel) {
          return;
        }

        mobileToggle.classList.toggle("is-open", isOpen);
        mobileToggle.setAttribute("aria-expanded", isOpen ? "true" : "false");
        mobilePanel.hidden = !isOpen;
      }

      const observer = new IntersectionObserver(function (entries) {
        const visible = entries
          .filter(function (entry) {
            return entry.isIntersecting;
          })
          .sort(function (a, b) {
            return b.intersectionRatio - a.intersectionRatio;
          });

        if (visible.length) {
          setActiveMobileLink(visible[0].target.id);
        }
      }, {
        rootMargin: "-20% 0px -55% 0px",
        threshold: [0.2, 0.45, 0.7]
      });

      sections.forEach(function (section) {
        observer.observe(section);
      });

      mobileLinks.forEach(function (link) {
        link.addEventListener("click", function () {
          setActiveMobileLink(link.getAttribute("data-mobile-nav-link"));
          setMobilePanelOpen(false);
        });
      });

      if (mobileToggle && mobilePanel) {
        mobileToggle.addEventListener("click", function () {
          const isOpen = mobileToggle.getAttribute("aria-expanded") === "true";
          setMobilePanelOpen(!isOpen);
        });
      }

      setMobilePanelOpen(false);
    })();

    (function () {
      const chips = Array.from(document.querySelectorAll("[data-fit-target]"));
      const panelSurface = document.getElementById("fit-panel-surface");
      const panelEyebrow = document.getElementById("fit-panel-eyebrow");
      const panelTitle = document.getElementById("fit-panel-title");
      const panelBody = document.getElementById("fit-panel-body");
      const panelPoints = document.getElementById("fit-panel-points");
      const panelSideEyebrow = document.getElementById("fit-panel-side-eyebrow");
      const panelSideContent = document.getElementById("fit-panel-side-content");

      if (!chips.length || !panelSurface || !panelEyebrow || !panelTitle || !panelBody || !panelPoints || !panelSideEyebrow || !panelSideContent) {
        return;
      }

      const fitContent = {
        "fit-growth": {
          eyebrow: "Best fit right now",
          title: "Growth is the right starting point for most small teams with real monthly spend.",
          body: "Choose this if one person is carrying budget accountability, reviews happen inconsistently, and you want earlier alerts without adding a lot of process.",
          points: [
            "Best for founders, operators, and finance leads managing roughly 5 to 50 people.",
            "Good when marketing, software, contractors, or travel start drifting faster than your team can spot manually."
          ],
          sideEyebrow: "What happens after signup",
          sideItems: [
            "1. Pay securely through Stripe and create your account.",
            "2. Land in your dashboard and choose the categories you care about most.",
            "3. Start seeing drift signals and use AI Coach to decide what to review first.",
            "4. Build a repeatable weekly or biweekly review habit without starting from scratch."
          ]
        },
        "fit-scale": {
          eyebrow: "When to move up",
          title: "Scale is for teams that need quicker follow-through and deeper planning support.",
          body: "Choose this when the cost of delay is higher, spending decisions are spread across more people, or you need a tighter review rhythm than Growth provides.",
          points: [
            "Best for teams with more moving parts, more approvals, or higher stakes around forecast accuracy.",
            "Includes weekly advisor check-ins, deeper planning help, and a faster support lane."
          ],
          sideEyebrow: "What AI Coach means in practice",
          sideItems: [
            "It flags which categories deserve attention first instead of making you hunt through the dashboard.",
            "It suggests next actions like pausing a spend line, reviewing contractor scope, or tightening a threshold.",
            "It helps turn alerts into a review list your team can actually act on that week."
          ]
        },
        "fit-managed": {
          eyebrow: "Higher-touch support",
          title: "Managed service is for teams that want a second pair of eyes, not just another tool.",
          body: "Choose this when the budget process is already straining, owners need accountability, or someone wants help translating alerts into a cleaner operating rhythm.",
          points: [
            "Best for teams that want support around setup, recurring reviews, planning decisions, and follow-through.",
            "A good fit when you know you need more structure but are not ready for a full finance hire."
          ],
          sideEyebrow: "What managed service includes",
          sideItems: [
            "Hands-on setup help so the right categories, thresholds, and review cadence are in place.",
            "Recurring budget reviews with a clearer list of what changed, what matters, and what to do next.",
            "Support tightening the process around approvals, category ownership, and monthly decision-making."
          ]
        }
      };

      const validTargets = new Set(chips.map(function (chip) {
        return chip.getAttribute("data-fit-target");
      }));

      function activate(targetId, options) {
        const shouldFocus = options && options.focusTab;
        const shouldUpdateHash = !options || options.updateHash !== false;
        const safeTargetId = validTargets.has(targetId) ? targetId : "fit-growth";
        const activeContent = fitContent[safeTargetId] || fitContent["fit-growth"];

        chips.forEach(function (chip) {
          const isActive = chip.getAttribute("data-fit-target") === safeTargetId;
          chip.classList.toggle("is-active", isActive);
          chip.setAttribute("aria-selected", isActive ? "true" : "false");
          chip.tabIndex = isActive ? 0 : -1;
          if (isActive && shouldFocus) {
            chip.focus();
          }
        });

        panelSurface.setAttribute("aria-labelledby", "fit-tab-" + safeTargetId.replace("fit-", ""));
        panelEyebrow.textContent = activeContent.eyebrow;
        panelTitle.textContent = activeContent.title;
        panelBody.textContent = activeContent.body;
        panelSideEyebrow.textContent = activeContent.sideEyebrow;

        panelPoints.innerHTML = "";
        activeContent.points.forEach(function (point) {
          const item = document.createElement("div");
          item.className = "panel-soft rounded-2xl px-4 py-4";
          item.textContent = point;
          panelPoints.appendChild(item);
        });

        panelSideContent.innerHTML = "";
        activeContent.sideItems.forEach(function (itemText) {
          const item = document.createElement("div");
          item.textContent = itemText;
          panelSideContent.appendChild(item);
        });

        if (shouldUpdateHash) {
          if (window.history && typeof window.history.replaceState === "function") {
            window.history.replaceState(null, "", "#" + safeTargetId);
          } else {
            window.location.hash = safeTargetId;
          }
        }
      }

      function moveTab(currentIndex, direction) {
        const nextIndex = (currentIndex + direction + chips.length) % chips.length;
        const nextChip = chips[nextIndex];
        if (nextChip) {
          activate(nextChip.getAttribute("data-fit-target"), { focusTab: true });
        }
      }

      chips.forEach(function (chip, index) {
        chip.addEventListener("click", function () {
          activate(chip.getAttribute("data-fit-target"), { focusTab: false });
        });

        chip.addEventListener("keydown", function (event) {
          if (event.key === "ArrowRight" || event.key === "ArrowDown") {
            event.preventDefault();
            moveTab(index, 1);
          } else if (event.key === "ArrowLeft" || event.key === "ArrowUp") {
            event.preventDefault();
            moveTab(index, -1);
          } else if (event.key === "Home") {
            event.preventDefault();
            activate(chips[0].getAttribute("data-fit-target"), { focusTab: true });
          } else if (event.key === "End") {
            event.preventDefault();
            activate(chips[chips.length - 1].getAttribute("data-fit-target"), { focusTab: true });
          }
        });
      });

      window.addEventListener("hashchange", function () {
        const hashTarget = (window.location.hash || "").replace(/^#/, "");
        if (validTargets.has(hashTarget)) {
          activate(hashTarget, { focusTab: false, updateHash: false });
        }
      });

      const initialTarget = (window.location.hash || "").replace(/^#/, "");
      activate(validTargets.has(initialTarget) ? initialTarget : "fit-growth", {
        focusTab: false,
        updateHash: validTargets.has(initialTarget)
      });
    })();

    (function () {
      const budget = document.getElementById("calc-budget");
      const drift = document.getElementById("calc-drift");
      const months = document.getElementById("calc-months");

      if (!budget || !drift || !months) {
        return;
      }

      const budgetValue = document.getElementById("calc-budget-value");
      const driftValue = document.getElementById("calc-drift-value");
      const monthsValue = document.getElementById("calc-months-value");
      const savingsValue = document.getElementById("calc-savings");
      const costValue = document.getElementById("calc-cost");
      const summaryValue = document.getElementById("calc-summary");

      const money = new Intl.NumberFormat("en-US", {
        style: "currency",
        currency: "USD",
        maximumFractionDigits: 0
      });

      function render() {
        const monthlyBudget = Number(budget.value);
        const driftRate = Number(drift.value) / 100;
        const monthCount = Number(months.value);
        const savings = monthlyBudget * driftRate * monthCount;
        const growthCost = monthCount * 10;

        budgetValue.textContent = money.format(monthlyBudget);
        driftValue.textContent = Math.round(driftRate * 100) + "%";
        monthsValue.textContent = monthCount + (monthCount === 1 ? " month" : " months");
        savingsValue.textContent = money.format(savings);
        costValue.textContent = money.format(growthCost);
        summaryValue.textContent =
          "Catching a " +
          Math.round(driftRate * 100) +
          "% drift on a " +
          money.format(monthlyBudget) +
          " monthly budget even " +
          monthCount +
          (monthCount === 1 ? " month" : " months") +
          " earlier dwarfs the cost of Growth.";
      }

      [budget, drift, months].forEach(function (input) {
        input.addEventListener("input", render);
      });

      render();
    })();

    (function () {
      const form = document.querySelector("[data-budget-contact-form]");
      const status = document.querySelector("[data-budget-contact-status]");
      const submitButton = document.getElementById("lead-submit-button");

      if (!form || !status || !submitButton) {
        return;
      }

      async function postLead(url, payload) {
        const response = await fetch(url, {
          method: "POST",
          headers: {
            "Content-Type": "application/json"
          },
          body: JSON.stringify(payload)
        });

        let data = {};
        try {
          data = await response.json();
        } catch (error) {
          data = { error: "Unexpected response from the server." };
        }

        return { response, data };
      }

      function setStatus(message, tone) {
        status.textContent = message;
        status.classList.remove("hidden", "text-emerald-300", "text-rose-300", "text-white/70");
        status.classList.add(tone);
      }

      form.addEventListener("submit", async function (event) {
        event.preventDefault();

        submitButton.disabled = true;
        submitButton.textContent = "Sending...";
        setStatus("Sending your message...", "text-white/70");

        const payload = {
          name: document.getElementById("lead-name-input").value.trim(),
          email: document.getElementById("lead-email-input").value.trim(),
          company_size: document.getElementById("lead-company-size").value.trim(),
          plan_interest: document.getElementById("lead-plan-interest").value.trim(),
          message: document.getElementById("lead-message-input").value.trim()
        };

        try {
          let result = await postLead("/api/contact", payload);
          if (result.response.status === 404 || result.response.status === 405) {
            result = await postLead("/api/contact.php", payload);
          }

          const response = result.response;
          const data = result.data;

          if (!response.ok || !data.ok) {
            throw new Error(data.error || "Something went wrong.");
          }

          form.reset();
          setStatus(data.message || "Thanks. We'll be in touch within one business day.", "text-emerald-300");
        } catch (error) {
          setStatus(error.message || "Unable to send your message right now.", "text-rose-300");
        } finally {
          submitButton.disabled = false;
          submitButton.textContent = "Send message";
        }
      });
    })();
  </script>
</body>
</html>
