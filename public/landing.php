<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <meta
    name="description"
    content="Budget Tracker App - Track spending, catch drift early, and turn your budget into action with smart categories, alerts, AI Coach, and managed support."
  />
  <title>Budget Tracker - Turn budget visibility into better decisions</title>
  <script src="https://cdn.tailwindcss.com"></script>
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
  <header class="sticky top-0 z-30 border-b border-black/5 glass">
    <div class="shell py-4 flex items-center justify-between gap-6">
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
  </header>

  <main>
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
            Turn budget into <span class="relative inline-block">action<span class="absolute left-0 right-0 bottom-1 h-3 bg-[#E0E7FF] -z-0"></span></span>.
          </h1>
          <p class="mt-6 text-lg md:text-xl leading-relaxed text-black/68 max-w-2xl">
            Budget Tracker helps solo operators and growing teams monitor category limits, review spending trends, and act before overspending compounds, starting at $5/mo.
          </p>
          <div class="mt-8 flex flex-wrap gap-3">
            <a href="checkout.php?plan=growth" class="cta-primary px-6 py-3.5 text-sm md:text-base">Start Growth - $10/mo</a>
            <a href="#pricing" class="cta-secondary px-6 py-3.5 text-sm md:text-base">Pricing Sheet</a>
            <a href="#interactive-demo" class="cta-secondary px-6 py-3.5 text-sm md:text-base">Product Demo</a>
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
              <span style="font-family: 'Playfair Display', serif; font-size: 1rem;">−$412 saved</span>
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
                <span class="mini-label text-[#52525B]">Dec · 2025</span>
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

    <section class="section-divider py-6 overflow-hidden">
      <div class="shell">
        <div class="flex items-center gap-4">
          <span class="mono text-[10px] uppercase tracking-[0.22em] text-[#52525B] shrink-0">Trusted by lean operators</span>
          <div class="relative overflow-hidden flex-1">
            <div class="logo-track">
              <span class="text-xl md:text-2xl text-[#0A0A0B]/55 hover:text-[#0A0A0B] transition-colors" style="font-family: 'Playfair Display', serif;">Northwind Studio</span>
              <span class="text-xl md:text-2xl text-[#0A0A0B]/55 hover:text-[#0A0A0B] transition-colors" style="font-family: 'Playfair Display', serif;">Helios Labs</span>
              <span class="text-xl md:text-2xl text-[#0A0A0B]/55 hover:text-[#0A0A0B] transition-colors" style="font-family: 'Playfair Display', serif;">Atlas &amp; Co.</span>
              <span class="text-xl md:text-2xl text-[#0A0A0B]/55 hover:text-[#0A0A0B] transition-colors" style="font-family: 'Playfair Display', serif;">Pinepoint</span>
              <span class="text-xl md:text-2xl text-[#0A0A0B]/55 hover:text-[#0A0A0B] transition-colors" style="font-family: 'Playfair Display', serif;">Foundry Seven</span>
              <span class="text-xl md:text-2xl text-[#0A0A0B]/55 hover:text-[#0A0A0B] transition-colors" style="font-family: 'Playfair Display', serif;">Mercatus</span>
              <span class="text-xl md:text-2xl text-[#0A0A0B]/55 hover:text-[#0A0A0B] transition-colors" style="font-family: 'Playfair Display', serif;">Ember Holdings</span>
              <span class="text-xl md:text-2xl text-[#0A0A0B]/55 hover:text-[#0A0A0B] transition-colors" style="font-family: 'Playfair Display', serif;">Quintile</span>
              <span class="text-xl md:text-2xl text-[#0A0A0B]/55 hover:text-[#0A0A0B] transition-colors" style="font-family: 'Playfair Display', serif;">Northwind Studio</span>
              <span class="text-xl md:text-2xl text-[#0A0A0B]/55 hover:text-[#0A0A0B] transition-colors" style="font-family: 'Playfair Display', serif;">Helios Labs</span>
              <span class="text-xl md:text-2xl text-[#0A0A0B]/55 hover:text-[#0A0A0B] transition-colors" style="font-family: 'Playfair Display', serif;">Atlas &amp; Co.</span>
              <span class="text-xl md:text-2xl text-[#0A0A0B]/55 hover:text-[#0A0A0B] transition-colors" style="font-family: 'Playfair Display', serif;">Pinepoint</span>
              <span class="text-xl md:text-2xl text-[#0A0A0B]/55 hover:text-[#0A0A0B] transition-colors" style="font-family: 'Playfair Display', serif;">Foundry Seven</span>
              <span class="text-xl md:text-2xl text-[#0A0A0B]/55 hover:text-[#0A0A0B] transition-colors" style="font-family: 'Playfair Display', serif;">Mercatus</span>
              <span class="text-xl md:text-2xl text-[#0A0A0B]/55 hover:text-[#0A0A0B] transition-colors" style="font-family: 'Playfair Display', serif;">Ember Holdings</span>
              <span class="text-xl md:text-2xl text-[#0A0A0B]/55 hover:text-[#0A0A0B] transition-colors" style="font-family: 'Playfair Display', serif;">Quintile</span>
            </div>
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
            The reference site leans hard into movement and proof. This version mirrors that with a focused demo section that shows alerts, review cadence, and AI Coach guidance working together.
          </p>
          <div class="mt-8 flex flex-wrap gap-3">
            <a href="#interactive-demo" class="cta-primary px-6 py-3.5 text-sm md:text-base">Watch Product Demo</a>
            <a href="#pricing" class="cta-secondary px-6 py-3.5 text-sm md:text-base">Open Pricing Sheet</a>
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
                  <div class="rounded-2xl border border-white/10 px-4 py-3">Managed service path for higher-need teams</div>
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
          The experience is built for lean operators who want earlier signals, less ambiguity, and a clear path from self-serve software into deeper support when the business gets more complex.
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
          <p class="mt-3 text-sm leading-6 text-black/62">Move from dashboards to decisions with generated recommendations and practical follow-up prompts.</p>
        </article>
        <article class="feature-card panel-soft rounded-3xl p-6">
          <p class="eyebrow text-[var(--accent)]">04</p>
          <h3 class="text-2xl mt-3">Managed Path</h3>
          <p class="mt-3 text-sm leading-6 text-black/62">Offer software and premium service from one product funnel, with a clear step-up path for higher-need teams.</p>
        </article>
      </div>
    </section>

    <section id="calculator" class="section-divider py-20 md:py-28">
      <div class="shell grid lg:grid-cols-12 gap-8 items-start">
        <div class="lg:col-span-5">
          <p class="eyebrow text-[var(--accent)]">ROI Calculator</p>
          <h2 class="text-4xl md:text-6xl mt-4">Estimate what one saved overspend cycle is worth.</h2>
          <p class="mt-5 text-lg leading-relaxed text-black/64 max-w-xl">
            The reference site includes an ROI calculator to make the pricing feel small compared with the cost of missed drift. This version mirrors that idea with a lightweight live calculator.
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
            Most teams start with Growth. It gives them enough monitoring, review rhythm, and guidance to feel real value quickly. Scale is there when the stakes, pace, or support needs go up.
          </p>
        </div>
        <div class="lg:col-span-7 grid md:grid-cols-3 gap-4">
          <article class="rounded-3xl border border-white/10 bg-white/5 p-6">
            <p class="eyebrow text-white/45">Starter</p>
            <h3 class="text-3xl mt-4">$5<span class="text-lg text-white/45">/mo</span></h3>
            <p class="mt-4 text-white/72 text-sm leading-6">The lower-friction entry step for solo operators who want basic visibility and early warning signals.</p>
          </article>
          <article class="rounded-3xl border border-[#0052FF] bg-[#0052FF]/10 p-6 shadow-[0_18px_40px_-25px_rgba(0,82,255,0.4)]">
            <p class="eyebrow text-[#7aa2ff]">Growth</p>
            <h3 class="text-3xl mt-4">$10<span class="text-lg text-white/45">/mo</span></h3>
            <p class="mt-4 text-white/82 text-sm leading-6">The strongest default plan with biweekly reviews, full alerts, and AI Coach access.</p>
          </article>
          <article class="rounded-3xl border border-white/10 bg-white/5 p-6">
            <p class="eyebrow text-white/45">Scale</p>
            <h3 class="text-3xl mt-4">$19.99<span class="text-lg text-white/45">/mo</span></h3>
            <p class="mt-4 text-white/72 text-sm leading-6">For customers who want faster support, more planning help, and a stronger bridge into managed service.</p>
          </article>
        </div>
      </div>
    </section>

    <section id="pricing" class="shell py-20 md:py-28">
      <div class="max-w-3xl">
        <p class="eyebrow text-[var(--accent)]">Pricing</p>
        <h2 class="text-4xl md:text-6xl mt-4">Choose the level of support your team actually needs.</h2>
        <p class="mt-5 text-lg leading-relaxed text-black/64">
          Growth is the strongest default. It includes alerts, AI Coach access, and enough ongoing value to justify a monthly subscription for solo operators and small teams.
        </p>
      </div>

      <div class="grid lg:grid-cols-3 gap-5 mt-12">
        <article class="price-card panel rounded-[28px] p-7 flex flex-col">
          <p class="eyebrow text-black/42">Starter</p>
          <h3 class="text-4xl mt-4">$5<span class="text-lg text-black/45">/mo</span></h3>
          <p class="mt-4 text-sm text-black/62 leading-6">A low-friction starting point that works like the paid trial step for the product.</p>
          <ul class="mt-6 space-y-3 text-sm text-black/68 flex-1">
            <li>Core dashboard access</li>
            <li>Monthly budget health report</li>
            <li>Early drift visibility by category</li>
            <li>Limited alerts and threshold monitoring</li>
            <li>Best for solo operators testing the workflow</li>
          </ul>
          <a href="checkout.php?plan=starter" class="cta-secondary mt-8 px-5 py-3 text-sm font-semibold">Start Starter</a>
        </article>

        <article class="price-card rounded-[28px] border border-[#0052FF] bg-[#0A0A0B] text-white p-7 flex flex-col shadow-[0_24px_60px_-30px_rgba(0,82,255,0.5)]">
          <p class="eyebrow text-[#7aa2ff]">Best Value</p>
          <h3 class="text-4xl mt-4">Growth<span class="block text-2xl mt-1">$10/mo</span></h3>
          <p class="mt-4 text-sm text-white/72 leading-6">Most teams should start here. It is the strongest mix of alerts, cadence, and guided action.</p>
          <ul class="mt-6 space-y-3 text-sm text-white/80 flex-1">
            <li>Biweekly spend and variance reviews</li>
            <li>Full dashboard alerts</li>
            <li>Alert tuning and threshold updates</li>
            <li>Monthly action plan</li>
            <li>AI Coach guidance</li>
          </ul>
          <a href="checkout.php?plan=growth" class="cta-primary mt-8 px-5 py-3 text-sm">Start Growth</a>
        </article>

        <article class="price-card panel rounded-[28px] p-7 flex flex-col">
          <p class="eyebrow text-black/42">Scale</p>
          <h3 class="text-4xl mt-4">$19.99<span class="text-lg text-black/45">/mo</span></h3>
          <p class="mt-4 text-sm text-black/62 leading-6">For customers who need faster support, planning help, or a stepping stone into managed finance operations.</p>
          <ul class="mt-6 space-y-3 text-sm text-black/68 flex-1">
            <li>Weekly advisor check-ins</li>
            <li>Forecasting and scenario planning</li>
            <li>Priority support lane</li>
            <li>Custom workflow guidance</li>
            <li>Best bridge into managed service</li>
          </ul>
          <div class="mt-8 grid gap-3">
            <a href="checkout.php?plan=scale" class="cta-secondary px-5 py-3 text-sm font-semibold">Start Scale</a>
            <a href="#managed-service" class="cta-secondary px-5 py-3 text-sm font-semibold">Explore Managed Service</a>
          </div>
        </article>
      </div>
    </section>

    <section class="shell py-20 md:py-28">
      <div class="max-w-3xl">
        <p class="eyebrow text-[var(--accent)]">Testimonials</p>
        <h2 class="text-4xl md:text-6xl mt-4">Built for lean operators who need clarity, not finance theater.</h2>
      </div>
      <div class="grid md:grid-cols-3 gap-4 mt-12">
        <article class="quote-card panel-soft rounded-3xl p-6 transition">
          <p class="text-lg leading-8 text-black/72">&ldquo;The alerts gave us something actionable before the month was gone. That alone justified the subscription.&rdquo;</p>
          <p class="mt-6 font-semibold">Operations lead</p>
          <p class="text-sm text-black/52 mt-1">8-person services team</p>
        </article>
        <article class="quote-card panel-soft rounded-3xl p-6 transition">
          <p class="text-lg leading-8 text-black/72">&ldquo;Growth felt like the right middle ground. Enough structure to stay ahead, not so much that it slowed us down.&rdquo;</p>
          <p class="mt-6 font-semibold">Founder</p>
          <p class="text-sm text-black/52 mt-1">Bootstrapped SaaS company</p>
        </article>
        <article class="quote-card panel-soft rounded-3xl p-6 transition">
          <p class="text-lg leading-8 text-black/72">&ldquo;We started self-serve and moved into managed support when the team got busier. The path up felt natural.&rdquo;</p>
          <p class="mt-6 font-semibold">Finance manager</p>
          <p class="text-sm text-black/52 mt-1">Growing agency</p>
        </article>
      </div>
    </section>

    <section id="managed-service" class="section-divider py-20 md:py-28">
      <div class="shell grid lg:grid-cols-12 gap-8 items-center">
        <div class="lg:col-span-7">
          <p class="eyebrow text-[var(--accent)]">Managed Service</p>
          <h2 class="text-4xl md:text-6xl mt-4">Need a second pair of eyes instead of another dashboard?</h2>
          <p class="mt-5 text-lg leading-relaxed text-black/64 max-w-3xl">
            Use Budget Tracker as the self-serve entry point, then move higher-need customers into a premium service layer with setup help, recurring reviews, workflow tuning, and operating support.
          </p>
          <div class="mt-8 flex flex-wrap gap-3">
            <a href="#managed-service-contact" class="cta-primary px-6 py-3.5 text-sm md:text-base">Explore Managed Service</a>
            <a href="#pricing" class="cta-secondary px-6 py-3.5 text-sm md:text-base">Open Pricing Sheet</a>
          </div>
        </div>
        <div class="lg:col-span-5">
          <div class="rounded-[28px] bg-[#0A0A0B] text-white p-8">
            <p class="eyebrow text-[#7aa2ff]">Why teams upgrade</p>
            <ul class="mt-6 space-y-4 text-white/72">
              <li>Weekly support replaces biweekly cadence</li>
              <li>Planning help goes deeper than software prompts</li>
              <li>Stronger accountability around follow-through</li>
              <li>Cleaner bridge from self-serve into premium ops support</li>
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
          <p class="text-white/70 mt-6 text-lg leading-relaxed max-w-md">Tell us about your team. We&apos;ll point you to the right plan or open a managed service conversation if that&apos;s a better fit.</p>
          <div class="mt-10 space-y-3 text-sm text-white/70">
            <div class="flex items-center gap-2"><span class="text-[#0052FF]">●</span>One business day response</div>
            <div class="flex items-center gap-2"><span class="text-[#0052FF]">●</span>No sales pressure - straight recommendation</div>
            <div class="flex items-center gap-2"><span class="text-[#0052FF]">●</span>Honest path from self-serve to managed</div>
          </div>
        </div>
        <div class="lg:col-span-7">
          <form class="bg-white/5 border border-white/10 rounded-2xl p-7 md:p-10 backdrop-blur-sm">
            <div class="grid grid-cols-1 md:grid-cols-2 gap-5">
              <div>
                <label for="lead-name-input" class="text-white/70 mono text-[10px] uppercase tracking-widest">Name</label>
                <input id="lead-name-input" class="form-input" placeholder="Avery Tan" />
              </div>
              <div>
                <label for="lead-email-input" class="text-white/70 mono text-[10px] uppercase tracking-widest">Work email</label>
                <input id="lead-email-input" type="email" class="form-input" placeholder="you@company.com" />
              </div>
              <div>
                <label for="lead-company-size" class="text-white/70 mono text-[10px] uppercase tracking-widest">Company size</label>
                <select id="lead-company-size" class="form-select">
                  <option>Select team size</option>
                  <option>1-5</option>
                  <option>6-15</option>
                  <option>16-50</option>
                  <option>50+</option>
                </select>
              </div>
              <div>
                <label for="lead-plan-interest" class="text-white/70 mono text-[10px] uppercase tracking-widest">Plan interest</label>
                <select id="lead-plan-interest" class="form-select">
                  <option>Pick a plan</option>
                  <option>Starter</option>
                  <option>Growth</option>
                  <option>Scale</option>
                  <option>Managed service</option>
                </select>
              </div>
            </div>
            <div class="mt-5">
              <label for="lead-message-input" class="text-white/70 mono text-[10px] uppercase tracking-widest">What are you trying to control?</label>
              <textarea id="lead-message-input" rows="4" class="form-textarea" placeholder="Contractor spend, marketing drift, monthly close cadence..."></textarea>
            </div>
            <div class="mt-7 flex flex-wrap items-center justify-between gap-4">
              <span class="text-xs text-white/50">We&apos;ll never share your details. Reply within one business day.</span>
              <a href="mailto:sales@budgettrackerpro.com?subject=Managed%20service%20inquiry" class="cta-primary px-6 py-3 text-sm">Send message</a>
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
          <div class="faq-content">Growth is the strongest default. It includes alerts, AI Coach access, and enough ongoing value to justify a monthly subscription for solo operators and small teams.</div>
        </details>
        <details class="faq-item">
          <summary class="faq-summary">
            <span>What is Scale for?</span>
            <span class="faq-icon" aria-hidden="true"></span>
          </summary>
          <div class="faq-content">Scale is for customers who need faster support, planning help, or a stepping stone into managed finance operations. Weekly check-ins replace the biweekly cadence on Growth.</div>
        </details>
        <details class="faq-item">
          <summary class="faq-summary">
            <span>Can I sell both software and services?</span>
            <span class="faq-icon" aria-hidden="true"></span>
          </summary>
          <div class="faq-content">Yes. The funnel supports low-ticket self-serve subscriptions and a premium service upsell from the same site. Managed clients usually graduate from Growth or Scale.</div>
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
          <div class="faq-content">Starter is intentionally priced at $5/mo as the trial step. It is lower friction than a free trial and proves the workflow before you commit to Growth.</div>
        </details>
        <details class="faq-item">
          <summary class="faq-summary">
            <span>What do I need before taking payments?</span>
            <span class="faq-icon" aria-hidden="true"></span>
          </summary>
          <div class="faq-content">Add your Stripe Payment Links in <code>config/payments.local.php</code> or set the matching <code>BT_STRIPE_*_LINK</code> environment variables. Checkout works the moment links are configured.</div>
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
            <a href="pricing-sheet.php" class="cta-secondary px-6 py-3.5 text-sm md:text-base text-white border-white/20 bg-white/5">Pricing Sheet</a>
          </div>
        </div>

        <div class="lg:col-span-5 grid grid-cols-2 gap-8 text-sm">
          <div>
            <div class="mono text-[10px] uppercase tracking-[0.22em] text-white/40 mb-4">Product</div>
            <ul class="space-y-3 text-white/74">
              <li><a href="#features" class="hover:text-white">Features</a></li>
              <li><a href="#calculator" class="hover:text-white">ROI calculator</a></li>
              <li><a href="#pricing" class="hover:text-white">Pricing</a></li>
              <li><a href="#pricing" class="hover:text-white">Pricing Sheet</a></li>
              <li><a href="#interactive-demo" class="hover:text-white">Product demo</a></li>
            </ul>
          </div>
          <div>
            <div class="mono text-[10px] uppercase tracking-[0.22em] text-white/40 mb-4">Company</div>
            <ul class="space-y-3 text-white/74">
              <li><a href="#managed-service" class="hover:text-white">Managed service</a></li>
              <li><a href="mailto:sales@budgettrackerpro.com" class="hover:text-white">Contact sales</a></li>
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
          <span>Built for lean operators.</span>
          <a href="#top" class="hover:text-white transition">budget.konticode.com</a>
        </div>
      </div>
    </div>
  </footer>
  <script>
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
  </script>
</body>
</html>
