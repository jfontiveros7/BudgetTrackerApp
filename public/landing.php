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
      background:
        radial-gradient(900px 380px at 88% -8%, rgba(0, 82, 255, 0.08), transparent 58%),
        radial-gradient(720px 300px at -5% 15%, rgba(10, 10, 11, 0.04), transparent 60%),
        var(--bg);
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
      font-weight: 700;
      transition: transform 180ms ease, background 180ms ease, box-shadow 180ms ease;
      box-shadow: 0 16px 30px rgba(0, 82, 255, 0.18);
    }

    .cta-primary:hover {
      background: var(--accent-strong);
      transform: translateY(-1px);
    }

    .cta-secondary {
      display: inline-flex;
      align-items: center;
      justify-content: center;
      gap: 0.5rem;
      border-radius: 999px;
      border: 1px solid rgba(10, 10, 11, 0.14);
      background: rgba(255, 255, 255, 0.72);
      transition: transform 180ms ease, border-color 180ms ease, background 180ms ease;
    }

    .cta-secondary:hover {
      transform: translateY(-1px);
      border-color: rgba(0, 82, 255, 0.24);
      background: rgba(0, 82, 255, 0.04);
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
  </style>
</head>
<body>
  <header class="sticky top-0 z-30 border-b border-black/5 glass">
    <div class="shell py-4 flex items-center justify-between gap-6">
      <a href="index.php" class="flex items-center gap-3">
        <span class="w-9 h-9 rounded-xl bg-[#0A0A0B] text-white flex items-center justify-center text-sm font-bold">BT</span>
        <span class="font-semibold tracking-tight text-base md:text-lg">Budget Tracker</span>
      </a>
      <nav class="hidden md:flex items-center gap-7 text-sm text-black/70">
        <a href="#features" class="hover:text-[var(--accent)] transition">Features</a>
        <a href="#pricing" class="hover:text-[var(--accent)] transition">Pricing</a>
        <a href="#faq" class="hover:text-[var(--accent)] transition">FAQ</a>
        <a href="managed/" class="hover:text-[var(--accent)] transition">Managed Service</a>
      </nav>
      <div class="flex items-center gap-2">
        <a href="login.php" class="hidden sm:inline-flex cta-secondary px-4 py-2.5 text-sm font-medium">Client Login</a>
        <a href="checkout.php?plan=growth" class="cta-primary px-4 py-2.5 text-sm">Start Growth</a>
      </div>
    </div>
  </header>

  <main>
    <section class="shell pt-10 md:pt-16 pb-12 md:pb-20">
      <div class="grid lg:grid-cols-12 gap-8 lg:gap-10 items-center">
        <div class="lg:col-span-7">
          <p class="eyebrow text-[var(--accent)]">Budget Tracker App</p>
          <h1 class="text-5xl md:text-7xl lg:text-[78px] mt-5 max-w-5xl">
            Turn budget visibility into <span class="italic text-black/55">better decisions.</span>
          </h1>
          <p class="mt-6 text-lg md:text-xl leading-relaxed text-black/68 max-w-2xl">
            Track spending, catch drift early, and move from raw numbers to next-step action with smart categories, alerts, AI Coach support, and a clean path into managed service.
          </p>
          <div class="mt-8 flex flex-wrap gap-3">
            <a href="checkout.php?plan=growth" class="cta-primary px-6 py-3.5 text-sm md:text-base">Start Growth - $10/mo</a>
            <a href="pricing-sheet.php" class="cta-secondary px-6 py-3.5 text-sm md:text-base">Pricing Sheet</a>
            <a href="demo-slideshow.php" class="cta-secondary px-6 py-3.5 text-sm md:text-base">Product Demo</a>
          </div>
          <div class="mt-10 grid sm:grid-cols-3 gap-3">
            <div class="panel-soft rounded-2xl p-4">
              <p class="eyebrow text-[var(--accent)]">Signals</p>
              <p class="font-semibold text-lg mt-2">Catch drift earlier</p>
              <p class="text-sm text-black/62 mt-2">See category pressure, unusual movement, and threshold risk before month-end gets messy.</p>
            </div>
            <div class="panel-soft rounded-2xl p-4">
              <p class="eyebrow text-[var(--accent)]">Action</p>
              <p class="font-semibold text-lg mt-2">Know what to do next</p>
              <p class="text-sm text-black/62 mt-2">Use AI Coach guidance to turn flagged activity into specific follow-up instead of vague concern.</p>
            </div>
            <div class="panel-soft rounded-2xl p-4">
              <p class="eyebrow text-[var(--accent)]">Support</p>
              <p class="font-semibold text-lg mt-2">Scale when needed</p>
              <p class="text-sm text-black/62 mt-2">Start self-serve, then move into weekly support or managed operations without changing tools.</p>
            </div>
          </div>
        </div>

        <div class="lg:col-span-5">
          <div class="panel hero-grid rounded-[28px] p-5 md:p-6 overflow-hidden">
            <div class="flex items-center justify-between border-b border-black/8 pb-4">
              <div>
                <p class="eyebrow text-black/45">Live Budget View</p>
                <p class="font-semibold text-lg mt-1">Control Center</p>
              </div>
              <span class="inline-flex items-center gap-2 rounded-full bg-[var(--accent-soft)] px-3 py-1 text-xs font-semibold text-[var(--accent)]">
                <span class="w-2 h-2 rounded-full bg-[var(--accent)] inline-block"></span>
                AI Coach Active
              </span>
            </div>

            <div class="grid gap-4 mt-5">
              <div class="panel-soft rounded-2xl p-4">
                <div class="flex items-start justify-between gap-4">
                  <div>
                    <p class="eyebrow text-black/40">This month</p>
                    <p class="text-3xl font-extrabold mt-2">$12,480</p>
                    <p class="text-sm text-black/58 mt-1">73% of monthly operating budget used</p>
                  </div>
                  <div class="text-right">
                    <p class="eyebrow text-black/40">Drift risk</p>
                    <p class="text-lg font-semibold text-[var(--accent)] mt-2">Medium</p>
                  </div>
                </div>
              </div>

              <div class="grid sm:grid-cols-2 gap-4">
                <div class="panel-soft rounded-2xl p-4">
                  <p class="eyebrow text-black/40">Alerts</p>
                  <ul class="mt-3 space-y-2 text-sm text-black/68">
                    <li>Marketing is 14% above pace</li>
                    <li>Contractor spend is accelerating</li>
                    <li>Software renewals hit next week</li>
                  </ul>
                </div>
                <div class="panel-soft rounded-2xl p-4">
                  <p class="eyebrow text-black/40">Coach note</p>
                  <p class="text-sm leading-6 text-black/68 mt-3">
                    Pause ad expansion, review contractor scope, and move the software renewal into next week&apos;s close checklist.
                  </p>
                </div>
              </div>

              <div class="rounded-2xl bg-[#0A0A0B] text-white p-5">
                <p class="eyebrow text-white/45">Why teams buy Growth</p>
                <h2 class="text-3xl mt-3">Enough structure to stay ahead without turning finance into a full-time job.</h2>
                <div class="grid grid-cols-3 gap-3 mt-5 text-sm">
                  <div>
                    <p class="text-white/55">Biweekly</p>
                    <p class="font-semibold mt-1">Review cadence</p>
                  </div>
                  <div>
                    <p class="text-white/55">Full</p>
                    <p class="font-semibold mt-1">Dashboard alerts</p>
                  </div>
                  <div>
                    <p class="text-white/55">Instant</p>
                    <p class="font-semibold mt-1">Plan upgrade path</p>
                  </div>
                </div>
              </div>
            </div>
          </div>
        </div>
      </div>
    </section>

    <section class="section-divider py-6">
      <div class="shell grid md:grid-cols-4 gap-4 text-sm text-black/62">
        <div class="panel-soft rounded-2xl px-5 py-4">Smart categories that stay readable</div>
        <div class="panel-soft rounded-2xl px-5 py-4">Threshold alerts tuned to your operating reality</div>
        <div class="panel-soft rounded-2xl px-5 py-4">AI Coach guidance instead of passive reporting</div>
        <div class="panel-soft rounded-2xl px-5 py-4">Managed service upsell built into the funnel</div>
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
            <a href="managed/" class="cta-secondary px-5 py-3 text-sm font-semibold">Explore Managed Service</a>
          </div>
        </article>
      </div>
    </section>

    <section class="section-divider py-20 md:py-28">
      <div class="shell grid lg:grid-cols-12 gap-8 items-center">
        <div class="lg:col-span-7">
          <p class="eyebrow text-[var(--accent)]">Managed Service</p>
          <h2 class="text-4xl md:text-6xl mt-4">Need a second pair of eyes instead of another dashboard?</h2>
          <p class="mt-5 text-lg leading-relaxed text-black/64 max-w-3xl">
            Use Budget Tracker as the self-serve entry point, then move higher-need customers into a premium service layer with setup help, recurring reviews, workflow tuning, and operating support.
          </p>
          <div class="mt-8 flex flex-wrap gap-3">
            <a href="managed/" class="cta-primary px-6 py-3.5 text-sm md:text-base">Explore Managed Service</a>
            <a href="pricing-sheet.php" class="cta-secondary px-6 py-3.5 text-sm md:text-base">Open Pricing Sheet</a>
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

    <section id="faq" class="shell py-20 md:py-28">
      <div class="max-w-3xl mx-auto text-center">
        <p class="eyebrow text-[var(--accent)]">FAQ</p>
        <h2 class="text-4xl md:text-6xl mt-4">Questions, <span class="italic text-black/52">answered.</span></h2>
      </div>

      <div class="grid md:grid-cols-2 gap-4 mt-12">
        <article class="faq-card panel-soft rounded-3xl p-6">
          <h3 class="text-2xl">Which plan should most people buy?</h3>
          <p class="mt-3 text-sm leading-6 text-black/64">Growth is the strongest default. It includes alerts, AI Coach access, and enough ongoing value to justify a monthly subscription for solo operators and small teams.</p>
        </article>
        <article class="faq-card panel-soft rounded-3xl p-6">
          <h3 class="text-2xl">What is Scale for?</h3>
          <p class="mt-3 text-sm leading-6 text-black/64">Scale is for customers who need faster support, planning help, or a stepping stone into managed finance operations. Weekly check-ins replace the biweekly cadence on Growth.</p>
        </article>
        <article class="faq-card panel-soft rounded-3xl p-6">
          <h3 class="text-2xl">Can I sell both software and services?</h3>
          <p class="mt-3 text-sm leading-6 text-black/64">Yes. The funnel supports low-ticket self-serve subscriptions and a premium service upsell from the same site. Managed clients usually graduate from Growth or Scale.</p>
        </article>
        <article class="faq-card panel-soft rounded-3xl p-6">
          <h3 class="text-2xl">Can I switch plans later?</h3>
          <p class="mt-3 text-sm leading-6 text-black/64">Yes. Upgrades and downgrades take effect on the next billing cycle. No re-onboarding and no lost data.</p>
        </article>
        <article class="faq-card panel-soft rounded-3xl p-6">
          <h3 class="text-2xl">Is there a free trial?</h3>
          <p class="mt-3 text-sm leading-6 text-black/64">Starter is intentionally priced at $5/mo as the trial step. It is lower friction than a free trial and proves the workflow before you commit to Growth.</p>
        </article>
        <article class="faq-card panel-soft rounded-3xl p-6">
          <h3 class="text-2xl">What happens after payment?</h3>
          <p class="mt-3 text-sm leading-6 text-black/64">After checkout, the buyer creates an account or signs in with the same email they want associated with their plan access.</p>
        </article>
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
              <li><a href="#pricing" class="hover:text-white">Pricing</a></li>
              <li><a href="pricing-sheet.php" class="hover:text-white">Pricing Sheet</a></li>
              <li><a href="demo-slideshow.php" class="hover:text-white">Product Demo</a></li>
            </ul>
          </div>
          <div>
            <div class="mono text-[10px] uppercase tracking-[0.22em] text-white/40 mb-4">Company</div>
            <ul class="space-y-3 text-white/74">
              <li><a href="managed/" class="hover:text-white">Managed Service</a></li>
              <li><a href="mailto:sales@budgettrackerpro.com" class="hover:text-white">Contact Sales</a></li>
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
          <span>&copy; <?php echo date("Y"); ?> Budget Tracker</span>
        </div>
        <div class="flex flex-wrap gap-6">
          <span>Built for lean operators.</span>
          <span>Software plus managed support.</span>
        </div>
      </div>
    </div>
  </footer>
</body>
</html>
