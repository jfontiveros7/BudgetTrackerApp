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
  <title>Budget Tracker App | Pricing Sheet</title>
  <script src="https://cdn.tailwindcss.com"></script>
  <link rel="preconnect" href="https://fonts.googleapis.com" />
  <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin />
  <link href="https://fonts.googleapis.com/css2?family=Sora:wght@400;500;600;700;800&family=JetBrains+Mono:wght@400;600&display=swap" rel="stylesheet" />
  <style>
    :root {
      --bg: #0a0f14;
      --bg-soft: #0f161e;
      --panel: #111b26;
      --line: #253545;
      --line-soft: #1b2733;
      --text: #ebf3ff;
      --muted: #97aec7;
      --accent: #2fe39f;
      --accent-soft: #173f35;
      --accent-alt: #7dc4ff;
      --warn: #ff9860;
    }

    * {
      box-sizing: border-box;
    }

    body {
      margin: 0;
      font-family: "Sora", sans-serif;
      color: var(--text);
      background:
        radial-gradient(1200px 500px at 80% -10%, rgba(125, 196, 255, 0.14), transparent 55%),
        radial-gradient(1000px 460px at -10% 10%, rgba(47, 227, 159, 0.14), transparent 60%),
        linear-gradient(180deg, #070b10 0%, var(--bg) 45%, #081118 100%);
      min-height: 100vh;
    }

    .mono {
      font-family: "JetBrains Mono", monospace;
    }

    .frame {
      border: 1px solid var(--line);
      background: linear-gradient(180deg, rgba(17, 27, 38, 0.78), rgba(12, 20, 29, 0.84));
      backdrop-filter: blur(8px);
    }

    .soft-frame {
      border: 1px solid var(--line-soft);
      background: rgba(13, 21, 30, 0.72);
    }

    .lift {
      transition: transform 180ms ease, border-color 180ms ease;
    }

    .lift:hover {
      transform: translateY(-3px);
      border-color: #365068;
    }

    @media print {
      .no-print {
        display: none !important;
      }

      body {
        background: #0a0f14;
      }

      .frame {
        break-inside: avoid;
      }
    }
  </style>
</head>
<body>
  <header class="sticky top-0 z-30 bg-[#070c12]/88 border-b border-[#1b2a38] backdrop-blur">
    <div class="max-w-6xl mx-auto px-5 py-4 flex items-center justify-between gap-4">
      <a href="landing.php" class="font-semibold tracking-tight text-base md:text-lg">Budget Tracker App</a>
      <div class="hidden md:flex items-center gap-6 text-sm text-slate-300">
        <a href="landing.php#pricing" class="hover:text-white transition">Pricing</a>
        <a href="landing.php#process" class="hover:text-white transition">Process</a>
        <a href="landing.php#contact" class="hover:text-white transition">Contact</a>
      </div>
      <div class="flex items-center gap-2">
        <span class="hidden sm:inline mono text-[11px] px-2 py-1 rounded border border-[#254158] text-sky-300">PROPOSAL SHEET</span>
        <a href="login.php" class="text-xs sm:text-sm px-3 py-2 rounded border border-[#2a3f54] hover:bg-[#13202c] transition">Client Portal</a>
      </div>
    </div>
  </header>

  <main class="max-w-6xl mx-auto px-5 py-10 md:py-14">
    <section class="frame rounded-2xl p-6 md:p-9 mb-6">
      <p class="mono text-[11px] tracking-[0.2em] text-[var(--accent-alt)] mb-3">BUDGET TRACKER APP / PRICING SHEET</p>
      <h1 class="text-3xl md:text-5xl font-semibold leading-tight mb-4">Managed budget operations pricing</h1>
      <p class="text-slate-300 max-w-3xl mb-7">Service-first retainers for freelancers and small teams that want recurring oversight, not another app to maintain.</p>
      <div class="grid sm:grid-cols-3 gap-3 mb-6">
        <div class="soft-frame rounded-md px-4 py-3 mono text-xs text-slate-300">prepared: April 2026</div>
        <div class="soft-frame rounded-md px-4 py-3 mono text-xs text-slate-300">billing: monthly retainer</div>
        <div class="soft-frame rounded-md px-4 py-3 mono text-xs text-slate-300">response target: &lt;24h</div>
      </div>
      <div class="no-print flex flex-wrap gap-3">
        <a href="landing.php" class="px-5 py-3 rounded-md border border-[#34506a] hover:bg-[#132734] transition">Back To Landing</a>
        <button onclick="window.print()" class="px-5 py-3 rounded-md bg-[var(--accent)] text-[#032318] font-semibold hover:brightness-95 transition">Print Or Save PDF</button>
        <a href="mailto:sales@budgettrackerpro.com" class="px-5 py-3 rounded-md border border-[#34506a] hover:bg-[#132734] transition">Email Sales</a>
      </div>
    </section>

    <section class="grid md:grid-cols-3 gap-4 mb-6">
      <article class="frame lift rounded-2xl p-6 flex flex-col">
        <p class="mono text-xs text-emerald-300 mb-2">TIER 1</p>
        <h2 class="text-2xl font-semibold mb-1">Starter</h2>
        <p class="text-sm text-slate-300 mb-4">For solo operators needing monthly clarity.</p>
        <p class="text-4xl font-bold mb-1">$5.99<span class="text-lg text-slate-400">/mo</span></p>
        <p class="mono text-xs text-slate-400 mb-5">month-to-month</p>
        <ul class="text-sm text-slate-300 space-y-2 mb-7 flex-1">
          <li>Monthly budget health report</li>
          <li>One category and threshold optimization pass</li>
          <li>Transaction anomaly highlights</li>
          <li>Email support, 48-hour response SLA</li>
        </ul>
        <p class="text-xs text-slate-400">Best for founders moving from spreadsheets to a predictable finance cadence.</p>
      </article>

      <article class="frame lift rounded-2xl p-6 flex flex-col border-2 border-[var(--accent)]">
        <p class="mono text-xs text-emerald-300 mb-2">MOST POPULAR</p>
        <h2 class="text-2xl font-semibold mb-1">Growth</h2>
        <p class="text-sm text-slate-300 mb-4">For freelancers and teams needing active oversight.</p>
        <p class="text-4xl font-bold mb-1">$10.99<span class="text-lg text-slate-400">/mo</span></p>
        <p class="mono text-xs text-slate-400 mb-5">recommended minimum: 3 months</p>
        <ul class="text-sm text-slate-300 space-y-2 mb-7 flex-1">
          <li>Biweekly spend and variance reviews</li>
          <li>Alert tuning and threshold updates</li>
          <li>Monthly strategic action plan</li>
          <li>Priority support, 24-hour response SLA</li>
        </ul>
        <p class="text-xs text-slate-400">Best for teams with increasing transaction volume and tighter cashflow windows.</p>
      </article>

      <article class="frame lift rounded-2xl p-6 flex flex-col">
        <p class="mono text-xs text-emerald-300 mb-2">TIER 3</p>
        <h2 class="text-2xl font-semibold mb-1">Scale</h2>
        <p class="text-sm text-slate-300 mb-4">For teams wanting weekly operations support.</p>
        <p class="text-4xl font-bold mb-1">$19.99<span class="text-lg text-slate-400">/mo</span></p>
        <p class="mono text-xs text-slate-400 mb-5">custom scope available</p>
        <ul class="text-sm text-slate-300 space-y-2 mb-7 flex-1">
          <li>Weekly advisor check-ins</li>
          <li>Forecasting and scenario planning</li>
          <li>Custom workflows and automation support</li>
          <li>Priority support and escalation channel</li>
        </ul>
        <p class="text-xs text-slate-400">Best for teams using finance operations as a growth lever.</p>
      </article>
    </section>

    <section class="frame rounded-2xl p-6 md:p-8 mb-6">
      <p class="mono text-[11px] tracking-[0.2em] text-[var(--warn)] mb-3">TERMS</p>
      <h3 class="text-2xl font-semibold mb-5">Scope, SLAs, and commercial notes</h3>
      <div class="grid md:grid-cols-2 gap-4 text-sm">
        <div class="soft-frame rounded-xl p-4">
          <p class="font-semibold mb-3">Included in all tiers</p>
          <ul class="space-y-2 text-slate-300">
            <li>Secure client portal access</li>
            <li>Recurring financial operations review</li>
            <li>Actionable recommendations, not just charts</li>
            <li>Monthly executive summary in plain language</li>
          </ul>
        </div>
        <div class="soft-frame rounded-xl p-4">
          <p class="font-semibold mb-3">Commercial notes</p>
          <ul class="space-y-2 text-slate-300">
            <li>Pricing excludes tax and third-party fees</li>
            <li>Advisory and optimization only, no tax filing services</li>
            <li>Payment due at start of each monthly cycle</li>
            <li>Extra requests can be scoped as add-ons</li>
          </ul>
        </div>
      </div>
    </section>

    <section class="frame rounded-2xl p-6 md:p-8">
      <p class="mono text-[11px] tracking-[0.2em] text-emerald-300 mb-3">OPTIONAL ADD-ONS</p>
      <div class="grid md:grid-cols-3 gap-4 mb-7">
        <div class="soft-frame rounded-xl p-4">
          <p class="font-semibold mb-1">30-Day Pilot</p>
          <p class="text-sm text-slate-300">$150 setup + first month tier fee</p>
        </div>
        <div class="soft-frame rounded-xl p-4">
          <p class="font-semibold mb-1">Extra Advisory Call</p>
          <p class="text-sm text-slate-300">$180 per 45-minute session</p>
        </div>
        <div class="soft-frame rounded-xl p-4">
          <p class="font-semibold mb-1">Custom Workflow Build</p>
          <p class="text-sm text-slate-300">From $300 per scoped request</p>
        </div>
      </div>

      <div class="soft-frame rounded-xl p-5">
        <p class="font-semibold mb-2">Next step</p>
        <p class="text-slate-300 mb-4">Book a 20-minute discovery call and we will map the right tier based on transaction volume, goals, and support expectations.</p>
        <div class="no-print flex flex-wrap gap-3">
          <a href="mailto:sales@budgettrackerpro.com" class="px-5 py-3 rounded-md bg-[var(--accent)] text-[#032318] font-semibold hover:brightness-95 transition">Book Discovery Call</a>
          <a href="landing.php" class="px-5 py-3 rounded-md border border-[#34506a] hover:bg-[#132734] transition">Return To Landing</a>
        </div>
      </div>
    </section>
  </main>
</body>
</html>
