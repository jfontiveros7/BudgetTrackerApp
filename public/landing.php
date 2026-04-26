<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <title>Budget Tracker App | Turn Budget Visibility Into Better Decisions</title>
  <script src="https://cdn.tailwindcss.com"></script>
  <link rel="preconnect" href="https://fonts.googleapis.com" />
  <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin />
  <link href="https://fonts.googleapis.com/css2?family=Space+Grotesk:wght@400;500;600;700&family=IBM+Plex+Mono:wght@400;500&display=swap" rel="stylesheet" />
  <style>
    :root {
      --bg: #090b10;
      --bg-soft: #11161f;
      --panel: #171f2b;
      --line: #2f3f57;
      --line-soft: #243145;
      --text: #f5f2e9;
      --muted: #b7c2d3;
      --accent: #ffbf47;
      --accent-alt: #7dd4ff;
      --warn: #ff7f5a;
      --success: #9be58a;
    }

    * {
      box-sizing: border-box;
    }

    html {
      scroll-behavior: smooth;
    }

    body {
      margin: 0;
      font-family: "Space Grotesk", sans-serif;
      color: var(--text);
      background:
        radial-gradient(1100px 480px at 80% -10%, rgba(255, 191, 71, 0.16), transparent 56%),
        radial-gradient(1200px 600px at -10% 10%, rgba(125, 212, 255, 0.14), transparent 62%),
        linear-gradient(180deg, #06070b 0%, var(--bg) 45%, #0b1018 100%);
      min-height: 100vh;
    }

    .mono {
      font-family: "IBM Plex Mono", monospace;
    }

    .frame {
      border: 1px solid var(--line);
      background: linear-gradient(180deg, rgba(23, 31, 43, 0.84), rgba(15, 20, 30, 0.88));
      backdrop-filter: blur(8px);
      box-shadow: 0 18px 40px rgba(2, 5, 12, 0.36);
    }

    .soft-frame {
      border: 1px solid var(--line-soft);
      background: rgba(17, 23, 34, 0.76);
    }

    .lift {
      transition: transform 180ms ease, border-color 180ms ease;
    }

    .lift:hover {
      transform: translateY(-3px);
      border-color: #5f7596;
    }

    .metric {
      border: 1px solid #31435f;
      background: rgba(10, 15, 23, 0.72);
    }

    .text-slate-300 {
      color: var(--muted) !important;
    }

    .text-slate-400 {
      color: #8e9db3 !important;
    }

    .text-sky-300,
    .text-cyan-300 {
      color: var(--accent-alt) !important;
    }

    .text-emerald-300 {
      color: var(--success) !important;
    }

    .bg-\[var\(--accent\)\] {
      color: #261402 !important;
      box-shadow: 0 8px 24px rgba(255, 191, 71, 0.32);
    }
  </style>
</head>
<body>
  <header class="sticky top-0 z-30 bg-[#090d14]/92 border-b border-[#1d2a3b] backdrop-blur">
    <div class="max-w-6xl mx-auto px-5 py-4 flex items-center justify-between gap-4">
      <a href="index.php" class="font-semibold tracking-tight text-base md:text-lg">Budget Tracker App</a>
      <nav class="hidden md:flex items-center gap-6 text-sm text-slate-300">
        <a href="#features" class="hover:text-white transition">Features</a>
        <a href="#pricing" class="hover:text-white transition">Pricing</a>
        <a href="#faq" class="hover:text-white transition">FAQ</a>
      </nav>
      <div class="flex items-center gap-2">
        <a href="demo-slideshow.php" class="text-xs sm:text-sm px-3 py-2 rounded border border-[#2a3f54] hover:bg-[#13202c] transition">Watch Demo</a>
        <a href="login.php" class="text-xs sm:text-sm px-3 py-2 rounded border border-[#2a3f54] hover:bg-[#13202c] transition">Client Login</a>
        <a href="#pricing" class="text-xs sm:text-sm px-3 py-2 rounded bg-[var(--accent)] font-semibold hover:brightness-95 transition">Start Now</a>
      </div>
    </div>
  </header>

  <main class="px-5 py-8 md:py-12">
    <section class="max-w-6xl mx-auto frame rounded-3xl p-6 md:p-10 mb-8 overflow-hidden">
      <div class="grid lg:grid-cols-[1.2fr_0.8fr] gap-8 items-start">
        <div>
          <p class="mono text-[11px] tracking-[0.18em] text-[var(--accent-alt)] mb-4">BUDGET TRACKER + AI COACH + PREMIUM SUPPORT</p>
          <h1 class="text-3xl sm:text-5xl md:text-6xl font-semibold leading-tight tracking-tight max-w-5xl">
            Track spending, catch drift early, and turn your budget into action.
          </h1>
          <p class="text-slate-300 text-base md:text-lg max-w-3xl mt-5">
            Budget Tracker App helps solo operators and growing teams monitor category limits, review spending trends, and act before overspending compounds.
          </p>
          <div class="flex flex-wrap gap-3 mt-7">
            <a href="checkout.php?plan=growth" class="inline-flex items-center justify-center rounded-lg bg-[var(--accent)] px-5 py-3 text-sm font-semibold transition hover:brightness-95">
              Start Growth Plan
            </a>
            <a href="demo-slideshow.php" class="inline-flex items-center justify-center rounded-lg border border-[#2a3f54] px-5 py-3 text-sm transition hover:bg-[#13202c]">
              Watch Product Demo
            </a>
            <a href="managed/" class="inline-flex items-center justify-center rounded-lg border border-[#2a3f54] px-5 py-3 text-sm transition hover:bg-[#13202c]">
              Need Managed Help?
            </a>
          </div>
          <div class="grid sm:grid-cols-3 gap-3 mt-8">
            <div class="metric rounded-xl px-4 py-4">
              <p class="mono text-[11px] text-sky-300 mb-1">VALUE</p>
              <p class="font-semibold">$5 to $19.99/mo</p>
              <p class="text-sm text-slate-300 mt-1">Low-friction entry point for budget discipline.</p>
            </div>
            <div class="metric rounded-xl px-4 py-4">
              <p class="mono text-[11px] text-sky-300 mb-1">SPEED</p>
              <p class="font-semibold">Fast setup</p>
              <p class="text-sm text-slate-300 mt-1">Register after checkout and get into the dashboard immediately.</p>
            </div>
            <div class="metric rounded-xl px-4 py-4">
              <p class="mono text-[11px] text-sky-300 mb-1">UPSIDE</p>
              <p class="font-semibold">Upgrade path</p>
              <p class="text-sm text-slate-300 mt-1">Start self-serve, then add AI and higher-touch support as you grow.</p>
            </div>
          </div>
        </div>

        <div class="soft-frame rounded-2xl p-5">
          <p class="mono text-[11px] tracking-[0.18em] text-[var(--warn)] mb-3">WHY PEOPLE BUY</p>
          <div class="space-y-4">
            <div>
              <h2 class="font-semibold mb-1">Most budgets fail from inconsistency, not intent.</h2>
              <p class="text-sm text-slate-300">Teams usually know they should review spending. What slips is the habit, the thresholds, and the follow-through.</p>
            </div>
            <div class="soft-frame rounded-xl p-4">
              <p class="mono text-xs text-emerald-300 mb-2">BUILT TO CONVERT</p>
              <ul class="text-sm text-slate-300 space-y-2">
                <li>Clear plan ladder from Starter to Scale</li>
                <li>AI Coach reserved for higher-value plans</li>
                <li>Managed service available as premium upsell</li>
                <li>Checkout-ready Stripe payment link flow</li>
              </ul>
            </div>
            <a href="pricing-sheet.php" class="inline-flex items-center justify-center w-full rounded-lg border border-[#2a3f54] px-4 py-3 text-sm transition hover:bg-[#13202c]">
              View Pricing Sheet
            </a>
          </div>
        </div>
      </div>
    </section>

    <section class="max-w-6xl mx-auto frame rounded-2xl p-6 md:p-10 mb-8" id="features">
      <p class="mono text-[11px] tracking-[0.18em] text-[var(--accent-alt)] mb-4">FEATURES</p>
      <h2 class="text-2xl sm:text-4xl md:text-5xl font-semibold leading-tight tracking-tight max-w-4xl">
        Everything needed to move from expense logging to budget control.
      </h2>
      <div class="grid md:grid-cols-2 xl:grid-cols-4 gap-4 mt-7">
        <article class="soft-frame lift rounded-xl p-5">
          <h3 class="font-semibold mb-2">Smart Categories</h3>
          <p class="text-sm text-slate-300">Organize every transaction with cleaner category logic and faster edits.</p>
        </article>
        <article class="soft-frame lift rounded-xl p-5">
          <h3 class="font-semibold mb-2">Budget Thresholds</h3>
          <p class="text-sm text-slate-300">Set monthly limits by category and see drift before it becomes a surprise.</p>
        </article>
        <article class="soft-frame lift rounded-xl p-5">
          <h3 class="font-semibold mb-2">Alerts</h3>
          <p class="text-sm text-slate-300">Flag overspending, anomalies, and variance so action happens sooner.</p>
        </article>
        <article class="soft-frame lift rounded-xl p-5">
          <h3 class="font-semibold mb-2">AI Coach</h3>
          <p class="text-sm text-slate-300">Use guided AI budget insight on Growth and Scale to turn data into next steps.</p>
        </article>
      </div>
    </section>

    <section class="max-w-6xl mx-auto frame rounded-2xl p-6 md:p-10 mb-8" id="process">
      <p class="mono text-[11px] tracking-[0.18em] text-[var(--warn)] mb-4">HOW IT SELLS</p>
      <h2 class="text-2xl md:text-4xl font-semibold mb-6">Three buying paths for three budgets</h2>
      <div class="grid md:grid-cols-3 gap-4">
        <article class="soft-frame rounded-xl p-5">
          <p class="mono text-sm text-emerald-300 mb-3">01 / STARTER</p>
          <h3 class="font-semibold mb-2">Try the core workflow</h3>
          <p class="text-sm text-slate-300">A low-risk plan for users who want dashboard visibility and monthly accountability.</p>
        </article>
        <article class="soft-frame rounded-xl p-5">
          <p class="mono text-sm text-emerald-300 mb-3">02 / GROWTH</p>
          <h3 class="font-semibold mb-2">Upgrade for AI and active review</h3>
          <p class="text-sm text-slate-300">The main revenue tier for users who want alerts, AI insight, and a stronger operating rhythm.</p>
        </article>
        <article class="soft-frame rounded-xl p-5">
          <p class="mono text-sm text-emerald-300 mb-3">03 / SCALE</p>
          <h3 class="font-semibold mb-2">Monetize urgency and support needs</h3>
          <p class="text-sm text-slate-300">Premium plan and managed-service bridge for customers who want ongoing help and faster response.</p>
        </article>
      </div>
    </section>

    <section class="max-w-6xl mx-auto" id="pricing">
      <p class="mono text-[11px] tracking-[0.18em] text-[var(--accent-alt)] mb-3">PRICING</p>
      <h2 class="text-2xl md:text-4xl font-semibold mb-3">Choose the plan that matches your budget maturity</h2>
      <p class="text-slate-300 max-w-3xl mb-6">The best conversion path is usually Growth: enough value to feel meaningful, still priced for easy self-serve purchase.</p>
      <div class="grid lg:grid-cols-3 gap-4">
        <article class="frame rounded-2xl p-6 flex flex-col">
          <h3 class="text-2xl font-semibold mb-2">Starter</h3>
          <p class="text-4xl font-bold mb-4">$5<span class="text-lg text-slate-400">/mo</span></p>
          <ul class="text-sm text-slate-300 space-y-2 flex-1">
            <li>Monthly budget health report</li>
            <li>One optimization pass per month</li>
            <li>Core dashboard access</li>
            <li>Limited alerts</li>
            <li>AI Coach not included</li>
          </ul>
          <a href="checkout.php?plan=starter" class="mt-6 inline-flex items-center justify-center rounded-lg border border-[#2a3f54] bg-[#13202c] px-4 py-3 text-sm font-medium text-slate-100 transition hover:bg-[#1a3144]">
            Start Starter
          </a>
        </article>

        <article class="frame rounded-2xl p-6 border-2 border-[var(--accent)] flex flex-col">
          <p class="mono text-xs text-emerald-300 mb-2">BEST VALUE</p>
          <h3 class="text-2xl font-semibold mb-2">Growth</h3>
          <p class="text-4xl font-bold mb-4">$10<span class="text-lg text-slate-400">/mo</span></p>
          <ul class="text-sm text-slate-300 space-y-2 flex-1">
            <li>Biweekly spend and variance reviews</li>
            <li>Alert tuning and threshold updates</li>
            <li>Monthly action plan</li>
            <li>Full dashboard alerts</li>
            <li>AI Coach visibility and chat</li>
          </ul>
          <a href="checkout.php?plan=growth" class="mt-6 inline-flex items-center justify-center rounded-lg bg-[var(--accent)] px-4 py-3 text-sm font-semibold text-[#261402] transition hover:brightness-95">
            Start Growth
          </a>
        </article>

        <article class="frame rounded-2xl p-6 flex flex-col">
          <p class="mono text-xs text-sky-300 mb-2">PREMIUM</p>
          <h3 class="text-2xl font-semibold mb-2">Scale</h3>
          <p class="text-4xl font-bold mb-4">$19.99<span class="text-lg text-slate-400">/mo</span></p>
          <ul class="text-sm text-slate-300 space-y-2 flex-1">
            <li>Weekly advisor check-ins</li>
            <li>Forecasting and scenario planning</li>
            <li>Priority support lane</li>
            <li>Custom workflow guidance</li>
            <li>Strongest path into managed services</li>
          </ul>
          <div class="mt-6 grid gap-3">
            <a href="checkout.php?plan=scale" class="inline-flex items-center justify-center rounded-lg border border-[#2a3f54] bg-[#13202c] px-4 py-3 text-sm font-medium text-slate-100 transition hover:bg-[#1a3144]">
              Start Scale
            </a>
            <a href="mailto:sales@budgettrackerpro.com" class="inline-flex items-center justify-center rounded-lg border border-[#2a3f54] px-4 py-3 text-sm transition hover:bg-[#13202c]">
              Book Discovery Call
            </a>
          </div>
        </article>
      </div>
    </section>

    <section class="max-w-6xl mx-auto py-12">
      <div class="frame rounded-2xl p-7 md:p-10">
        <p class="mono text-[11px] tracking-[0.2em] text-[var(--accent-alt)] mb-3">HIGHER-TICKET OFFER</p>
        <div class="grid lg:grid-cols-[1fr_auto] gap-6 items-center">
          <div>
            <h2 class="text-3xl md:text-4xl font-semibold mb-3">Want hands-on support instead of self-serve?</h2>
            <p class="text-slate-300 max-w-3xl">The managed service offer turns this product into a second revenue lane: monthly retainers for setup, oversight, reporting, and optimization.</p>
          </div>
          <div class="flex flex-wrap gap-3">
            <a href="managed/" class="inline-block px-7 py-3 rounded-md bg-[var(--accent)] text-[#261402] font-semibold hover:brightness-95 transition">Explore Managed Service</a>
            <a href="pricing-sheet.php" class="inline-block px-7 py-3 rounded-md border border-[#2a3f54] hover:bg-[#13202c] transition">Open Pricing Sheet</a>
          </div>
        </div>
      </div>
    </section>

    <section class="max-w-6xl mx-auto frame rounded-2xl p-6 md:p-10 mb-8" id="faq">
      <p class="mono text-[11px] tracking-[0.18em] text-[var(--warn)] mb-4">FAQ</p>
      <div class="grid md:grid-cols-2 gap-4">
        <article class="soft-frame rounded-xl p-5">
          <h3 class="font-semibold mb-2">Which plan should most people buy?</h3>
          <p class="text-sm text-slate-300">Growth is the strongest default. It includes alerts, AI Coach access, and enough ongoing value to justify a monthly subscription.</p>
        </article>
        <article class="soft-frame rounded-xl p-5">
          <h3 class="font-semibold mb-2">What is Scale for?</h3>
          <p class="text-sm text-slate-300">Scale is for customers who need faster support, planning help, or a stepping stone into managed finance operations.</p>
        </article>
        <article class="soft-frame rounded-xl p-5">
          <h3 class="font-semibold mb-2">Can I sell both software and services?</h3>
          <p class="text-sm text-slate-300">Yes. This funnel now supports low-ticket self-serve subscriptions and a premium service upsell from the same site.</p>
        </article>
        <article class="soft-frame rounded-xl p-5">
          <h3 class="font-semibold mb-2">What do I need before taking payments?</h3>
          <p class="text-sm text-slate-300">Add your Stripe Payment Links in `config/payments.local.php` or set the matching `BT_STRIPE_*_LINK` environment variables.</p>
        </article>
      </div>
    </section>
  </main>
</body>
</html>
