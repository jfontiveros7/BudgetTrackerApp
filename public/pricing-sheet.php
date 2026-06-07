<?php
header("Cache-Control: no-store, no-cache, must-revalidate, max-age=0");
header("Pragma: no-cache");
header("Expires: 0");
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <meta
    name="description"
    content="Budget Tracker pricing sheet for Starter, Growth, Scale, and managed service support."
  />
  <title>Budget Tracker - Pricing</title>
  <link rel="stylesheet" href="/assets/css/tailwind.css">
  <link rel="preconnect" href="https://fonts.googleapis.com" />
  <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin />
  <link href="https://fonts.googleapis.com/css2?family=Playfair+Display:ital,wght@0,500;0,600;0,700;1,500&family=Manrope:wght@400;500;600;700;800&family=JetBrains+Mono:wght@400;500;600&display=swap" rel="stylesheet" />
  <style>
    :root {
      --bg: #f9f8f6;
      --panel: #ffffff;
      --panel-soft: rgba(255, 255, 255, 0.78);
      --ink: #0a0a0b;
      --muted: #5b5b61;
      --line: rgba(10, 10, 11, 0.08);
      --line-strong: rgba(10, 10, 11, 0.14);
      --accent: #0052ff;
      --accent-strong: #0040c5;
      --shadow: 0 24px 70px rgba(17, 24, 39, 0.08);
    }

    * {
      box-sizing: border-box;
    }

    body {
      margin: 0;
      font-family: "Manrope", sans-serif;
      color: var(--ink);
      background: var(--bg);
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
      background: var(--accent);
      color: #fff;
      font-weight: 500;
      transition: all 180ms ease;
    }

    .cta-primary:hover {
      background: var(--accent-strong);
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
      color: var(--ink);
      font-weight: 500;
      transition: all 180ms ease;
    }

    .cta-secondary:hover {
      transform: translateY(-2px);
      background: rgba(10, 10, 11, 0.03);
    }

    .price-card,
    .info-card {
      transition: transform 180ms ease, box-shadow 180ms ease, border-color 180ms ease;
    }

    .price-card:hover,
    .info-card:hover {
      transform: translateY(-2px);
      border-color: rgba(0, 82, 255, 0.16);
      box-shadow: 0 18px 40px rgba(17, 24, 39, 0.08);
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

    .dark-panel {
      background:
        radial-gradient(420px 220px at 84% 10%, rgba(0, 82, 255, 0.18), transparent 60%),
        #0a0a0b;
      color: white;
    }

    @media print {
      .no-print {
        display: none !important;
      }

      body {
        background: white;
      }

      .panel,
      .panel-soft,
      .dark-panel {
        box-shadow: none !important;
        break-inside: avoid;
      }
    }
  </style>
</head>
<body>
  <header class="sticky top-0 z-30 border-b border-black/5 glass">
    <div class="mx-auto flex max-w-7xl items-center justify-between gap-6 px-6 py-4 md:px-10">
      <a href="landing.php#top" class="flex items-center gap-3">
        <span class="flex h-9 w-9 items-center justify-center rounded-xl bg-[#0A0A0B]">
          <span class="block h-3 w-3 rotate-12 rounded-sm bg-[#0052FF]"></span>
        </span>
        <span class="text-xl tracking-tight" style="font-family: 'Playfair Display', serif;">Budget Tracker</span>
      </a>
      <nav class="hidden md:flex items-center gap-7 text-sm text-black/70">
        <a href="landing.php#features" class="hover:text-[#0052FF] transition">Features</a>
        <a href="landing.php#pricing" class="hover:text-[#0052FF] transition">See pricing</a>
        <a href="landing.php#faq" class="hover:text-[#0052FF] transition">FAQ</a>
        <a href="landing.php#managed-service" class="hover:text-[#0052FF] transition">Explore Managed Service</a>
      </nav>
      <div class="flex items-center gap-2">
        <a href="login.php" class="hidden sm:inline-flex cta-secondary px-4 py-2.5 text-sm font-medium">Client Login</a>
        <a href="checkout.php?plan=growth" class="cta-primary px-4 py-2.5 text-sm">Start Growth</a>
      </div>
    </div>
  </header>

  <main class="relative overflow-hidden">
    <div class="hero-mesh"></div>

    <section class="relative mx-auto max-w-7xl px-6 pt-10 pb-8 md:px-10 md:pt-16 md:pb-12">
      <div class="panel rounded-[32px] p-7 md:p-10">
        <div class="flex flex-wrap items-center gap-3">
          <span class="eyebrow text-[#0052FF]">Budget Tracker · Pricing</span>
          <span class="h-px w-20 bg-black/10"></span>
          <span class="mono rounded-full border border-black/10 px-3 py-1 text-[11px] text-black/52">Updated for current plans</span>
        </div>
        <div class="mt-6 grid gap-8 lg:grid-cols-[1.1fr_0.9fr] lg:items-end">
          <div>
            <h1 class="max-w-4xl text-5xl md:text-7xl lg:text-[76px]">Clear pricing for teams that want budget visibility without finance theater.</h1>
            <p class="mt-6 max-w-2xl text-lg leading-relaxed text-black/64 md:text-xl">
              Use this page to compare Starter, Growth, and Scale, then route higher-need teams into managed service only when they genuinely need the extra support.
            </p>
          </div>
          <div class="grid gap-3 sm:grid-cols-3 lg:grid-cols-1">
            <div class="panel-soft rounded-3xl px-5 py-4">
              <p class="mono text-[11px] uppercase tracking-[0.2em] text-[#0052FF]">Default recommendation</p>
              <p class="mt-2 text-sm text-black/64">Most teams should start with Growth.</p>
            </div>
            <div class="panel-soft rounded-3xl px-5 py-4">
              <p class="mono text-[11px] uppercase tracking-[0.2em] text-[#0052FF]">Billing</p>
              <p class="mt-2 text-sm text-black/64">Monthly subscription, secure Stripe checkout.</p>
            </div>
            <div class="panel-soft rounded-3xl px-5 py-4">
              <p class="mono text-[11px] uppercase tracking-[0.2em] text-[#0052FF]">Support target</p>
              <p class="mt-2 text-sm text-black/64">Managed service inquiries answered within one business day.</p>
            </div>
          </div>
        </div>

        <div class="no-print mt-8 flex flex-wrap gap-3">
          <a href="landing.php#pricing" class="cta-secondary px-5 py-3 text-sm">See pricing</a>
          <a href="checkout.php?plan=growth" class="cta-primary px-5 py-3 text-sm">Start Growth</a>
          <button onclick="window.print()" class="cta-secondary px-5 py-3 text-sm">Print Or Save PDF</button>
        </div>
      </div>
    </section>

    <section class="mx-auto max-w-7xl px-6 pb-8 md:px-10 md:pb-12">
      <div class="grid gap-5 lg:grid-cols-3">
        <article class="price-card panel rounded-[28px] p-7 flex flex-col">
          <p class="eyebrow text-black/42">Starter</p>
          <h2 class="mt-4 text-4xl">$5<span class="text-lg text-black/45">/mo</span></h2>
          <p class="mt-4 text-sm leading-6 text-black/62">A low-friction entry point for solo operators who want visibility before committing to a heavier operating rhythm.</p>
          <ul class="mt-6 space-y-3 text-sm text-black/68 flex-1">
            <li>Core dashboard access</li>
            <li>Monthly budget health report</li>
            <li>Early drift visibility by category</li>
            <li>Limited alerts and threshold monitoring</li>
            <li>Best for testing the workflow</li>
          </ul>
          <a href="checkout.php?plan=starter" class="cta-secondary mt-8 px-5 py-3 text-sm font-semibold">Start Starter</a>
        </article>

        <article class="price-card rounded-[28px] border border-[#0052FF] bg-[#0A0A0B] text-white p-7 flex flex-col shadow-[0_24px_60px_-30px_rgba(0,82,255,0.5)]">
          <p class="eyebrow text-[#7aa2ff]">Best Value</p>
          <h2 class="mt-4 text-4xl">Growth<span class="block text-2xl mt-1">$10/mo</span></h2>
          <p class="mt-4 text-sm leading-6 text-white/72">The strongest default for most teams. Enough structure, alerts, and guided follow-through to make the product feel real quickly.</p>
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
          <h2 class="mt-4 text-4xl">$20<span class="text-lg text-black/45">/mo</span></h2>
          <p class="mt-4 text-sm leading-6 text-black/62">For customers who need faster support, more planning help, and a stronger bridge into managed service.</p>
          <ul class="mt-6 space-y-3 text-sm text-black/68 flex-1">
            <li>Weekly advisor check-ins</li>
            <li>Forecasting and scenario planning</li>
            <li>Priority support lane</li>
            <li>Custom workflow guidance</li>
            <li>Best bridge into managed service</li>
          </ul>
          <div class="mt-8 grid gap-3">
            <a href="checkout.php?plan=scale" class="cta-secondary px-5 py-3 text-sm font-semibold">Start Scale</a>
            <a href="landing.php#managed-service-contact" class="cta-secondary px-5 py-3 text-sm font-semibold">Talk To Sales</a>
          </div>
        </article>
      </div>
    </section>

    <section class="mx-auto max-w-7xl px-6 pb-8 md:px-10 md:pb-12">
      <div class="grid gap-5 lg:grid-cols-2">
        <article class="info-card panel-soft rounded-[28px] p-7">
          <p class="eyebrow text-[#0052FF]">Included in all plans</p>
          <h3 class="mt-4 text-3xl">What every subscriber gets</h3>
          <ul class="mt-6 space-y-3 text-sm leading-6 text-black/68">
            <li>Secure client portal access</li>
            <li>Recurring budget operations review</li>
            <li>Visibility into variance and category drift</li>
            <li>Action-oriented recommendations, not just charts</li>
          </ul>
        </article>

        <article class="info-card panel-soft rounded-[28px] p-7">
          <p class="eyebrow text-[#0052FF]">Commercial notes</p>
          <h3 class="mt-4 text-3xl">Terms worth knowing</h3>
          <ul class="mt-6 space-y-3 text-sm leading-6 text-black/68">
            <li>Pricing excludes tax and third-party payment fees where applicable.</li>
            <li>Budget Tracker provides monitoring, advisory, and workflow support, not tax filing services.</li>
            <li>Subscriptions renew monthly unless canceled through the payment provider.</li>
            <li>Extra advisory requests can be scoped separately when needed.</li>
          </ul>
        </article>
      </div>
    </section>

    <section class="mx-auto max-w-7xl px-6 pb-12 md:px-10 md:pb-16">
      <div class="dark-panel rounded-[32px] p-7 md:p-10">
        <div class="grid gap-8 lg:grid-cols-[1fr_1.1fr]">
          <div>
            <p class="eyebrow text-[#7aa2ff]">Managed service options</p>
            <h3 class="mt-4 text-4xl md:text-5xl">When software alone is not enough, step up cleanly.</h3>
            <p class="mt-5 max-w-xl text-lg leading-relaxed text-white/70">
              Keep self-serve pricing simple, then use managed service only for higher-need teams that want setup help, recurring support, or deeper finance operating structure.
            </p>
          </div>

          <div class="grid gap-4 md:grid-cols-3">
            <div class="rounded-[24px] border border-white/10 bg-white/5 p-5">
              <p class="mono text-[11px] uppercase tracking-[0.2em] text-[#7aa2ff]">Setup</p>
              <p class="mt-3 text-xl" style="font-family: 'Playfair Display', serif;">Managed Onboarding</p>
              <p class="mt-2 text-sm text-white/70">$75 setup plus first month tier fee.</p>
            </div>
            <div class="rounded-[24px] border border-white/10 bg-white/5 p-5">
              <p class="mono text-[11px] uppercase tracking-[0.2em] text-[#7aa2ff]">Support</p>
              <p class="mt-3 text-xl" style="font-family: 'Playfair Display', serif;">Extra Advisory Call</p>
              <p class="mt-2 text-sm text-white/70">$90 per 45-minute session.</p>
            </div>
            <div class="rounded-[24px] border border-white/10 bg-white/5 p-5">
              <p class="mono text-[11px] uppercase tracking-[0.2em] text-[#7aa2ff]">Build</p>
              <p class="mt-3 text-xl" style="font-family: 'Playfair Display', serif;">Custom Workflow</p>
              <p class="mt-2 text-sm text-white/70">From $150 per scoped request.</p>
            </div>
          </div>
        </div>

        <div class="mt-8 flex flex-wrap gap-3">
          <a href="landing.php#managed-service-contact" class="cta-primary px-5 py-3 text-sm">Explore Managed Service</a>
          <a href="landing.php#faq" class="cta-secondary px-5 py-3 text-sm" style="color: white; border-color: rgba(255,255,255,0.2);">Read FAQ</a>
        </div>
      </div>
    </section>
  </main>
</body>
</html>
