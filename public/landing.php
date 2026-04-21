<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <title>Budget Tracker App | Managed Budget Operations</title>
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
      background: rgba(13, 21, 30, 0.7);
    }

    .lift {
      transition: transform 180ms ease, border-color 180ms ease;
    }

    .lift:hover {
      transform: translateY(-3px);
      border-color: #365068;
    }

    .reveal {
      opacity: 0;
      transform: translateY(12px);
      animation: reveal 560ms ease forwards;
    }

    .d1 {
      animation-delay: 100ms;
    }

    .d2 {
      animation-delay: 180ms;
    }

    .d3 {
      animation-delay: 280ms;
    }

    @keyframes reveal {
      to {
        opacity: 1;
        transform: translateY(0);
      }
    }
  </style>
</head>
<body>
  <header class="sticky top-0 z-30 bg-[#070c12]/88 border-b border-[#1b2a38] backdrop-blur">
    <div class="max-w-6xl mx-auto px-5 py-4 flex items-center justify-between gap-4">
      <a href="index.php" class="font-semibold tracking-tight text-base md:text-lg">Budget Tracker App</a>
      <div class="hidden md:flex items-center gap-6 text-sm text-slate-300">
        <a href="#work" class="hover:text-white transition">Work</a>
        <a href="#pricing" class="hover:text-white transition">Pricing</a>
        <a href="#process" class="hover:text-white transition">Process</a>
        <a href="#contact" class="hover:text-white transition">Contact</a>
      </div>
      <div class="flex items-center gap-2">
        <span class="hidden sm:inline mono text-[11px] px-2 py-1 rounded border border-[#254158] text-sky-300">BOOKING Q2 2026</span>
        <a href="login.php" class="text-xs sm:text-sm px-3 py-2 rounded border border-[#2a3f54] hover:bg-[#13202c] transition">Client Portal</a>
      </div>
    </div>
  </header>

  <main>
    <section class="max-w-6xl mx-auto px-5 pt-16 pb-12" id="work">
      <p class="mono reveal text-[11px] sm:text-xs uppercase tracking-[0.22em] text-[var(--accent-alt)] mb-5">Managed Service For Freelancers And Small Teams</p>
      <h1 class="reveal d1 text-4xl sm:text-5xl md:text-7xl font-semibold leading-[1.03] tracking-tight max-w-5xl">
        Run clean budget operations
        <span class="text-slate-400">without hiring a finance team.</span>
      </h1>
      <p class="reveal d2 mt-7 text-slate-300 text-base md:text-lg max-w-3xl leading-relaxed">
        We monitor transactions, tune budget thresholds, and deliver weekly guidance so you spend with confidence and move faster.
      </p>

      <div class="reveal d3 mt-9 flex flex-wrap gap-3">
        <a href="#pricing" class="px-6 py-3 rounded-md bg-[var(--accent)] text-[#032318] font-semibold hover:brightness-95 transition">View Service Plans</a>
        <a href="pricing-sheet.php?v=20260420" class="px-6 py-3 rounded-md border border-[#30475d] bg-[#10202d] hover:bg-[#132734] transition">Open Pricing Sheet</a>
        <a href="#contact" class="px-6 py-3 rounded-md border border-[#30475d] hover:bg-[#132734] transition">Book Discovery Call</a>
      </div>

      <div class="mt-10 grid sm:grid-cols-3 gap-3">
        <div class="soft-frame rounded-md px-4 py-3 mono text-xs text-slate-300">24+ client workflows improved</div>
        <div class="soft-frame rounded-md px-4 py-3 mono text-xs text-slate-300">&lt;24h support response target</div>
        <div class="soft-frame rounded-md px-4 py-3 mono text-xs text-slate-300">100% service-led delivery</div>
      </div>
    </section>

    <section class="max-w-6xl mx-auto px-5 py-14">
      <div class="frame rounded-2xl p-6 md:p-8">
        <p class="mono text-[11px] tracking-[0.2em] text-emerald-300 mb-3">SERVICES</p>
        <h2 class="text-2xl md:text-3xl font-semibold mb-8">Focused delivery for measurable budget outcomes.</h2>
        <div class="grid md:grid-cols-3 gap-4">
          <article class="soft-frame lift rounded-xl p-5">
            <p class="mono text-xs text-cyan-300 mb-2">MONITORING</p>
            <h3 class="font-semibold text-lg mb-2">Weekly Spend Oversight</h3>
            <p class="text-sm text-slate-300">Catch anomalies early with recurring checks and actionable alerts before overspending compounds.</p>
          </article>
          <article class="soft-frame lift rounded-xl p-5">
            <p class="mono text-xs text-cyan-300 mb-2">OPTIMIZATION</p>
            <h3 class="font-semibold text-lg mb-2">Budget Threshold Tuning</h3>
            <p class="text-sm text-slate-300">Your categories and limits are continuously adjusted as your operations and cashflow patterns evolve.</p>
          </article>
          <article class="soft-frame lift rounded-xl p-5">
            <p class="mono text-xs text-cyan-300 mb-2">REPORTING</p>
            <h3 class="font-semibold text-lg mb-2">Executive Summary Delivery</h3>
            <p class="text-sm text-slate-300">Receive practical monthly summaries with risk signals, priorities, and clear next-step recommendations.</p>
          </article>
        </div>
      </div>
    </section>

    <section class="max-w-6xl mx-auto px-5 py-14" id="pricing">
      <p class="mono text-[11px] tracking-[0.2em] text-[var(--warn)] mb-3">PRICING</p>
      <h2 class="text-2xl md:text-4xl font-semibold mb-3">3-tier managed service pricing</h2>
      <p class="text-slate-300 mb-8 max-w-3xl">Pick the support level that matches your current stage. Every tier includes dashboard access plus service execution from our side.</p>

      <div class="grid md:grid-cols-3 gap-4">
        <article class="frame lift rounded-2xl p-6 flex flex-col">
          <p class="mono text-xs text-emerald-300 mb-2">TIER 1</p>
          <h3 class="text-2xl font-semibold mb-1">Starter</h3>
          <p class="text-sm text-slate-300 mb-4">For solo operators needing monthly clarity.</p>
          <p class="text-4xl font-bold mb-1">$5.99<span class="text-lg text-slate-400">/mo</span></p>
          <p class="mono text-xs text-slate-400 mb-5">month-to-month</p>
          <ul class="text-sm text-slate-300 space-y-2 mb-7 flex-1">
            <li>Monthly budget health report</li>
            <li>One optimization pass per month</li>
            <li>Email support, 48-hour response</li>
            <li>Client portal access</li>
          </ul>
          <a href="#contact" class="text-center px-4 py-3 rounded-md border border-[#34506a] bg-[#152837] hover:bg-[#1a3144] transition">Start Starter Plan</a>
        </article>

        <article class="frame lift rounded-2xl p-6 flex flex-col border-2 border-[var(--accent)]">
          <p class="mono text-xs text-emerald-300 mb-2">MOST POPULAR</p>
          <h3 class="text-2xl font-semibold mb-1">Growth</h3>
          <p class="text-sm text-slate-300 mb-4">For freelancers and small teams needing active oversight.</p>
          <p class="text-4xl font-bold mb-1">$10.99<span class="text-lg text-slate-400">/mo</span></p>
          <p class="mono text-xs text-slate-400 mb-5">best value</p>
          <ul class="text-sm text-slate-300 space-y-2 mb-7 flex-1">
            <li>Biweekly spend and variance reviews</li>
            <li>Alert tuning and threshold updates</li>
            <li>Monthly strategic action plan</li>
            <li>Priority support, 24-hour response</li>
          </ul>
          <a href="#contact" class="text-center px-4 py-3 rounded-md bg-[var(--accent)] text-[#032318] font-semibold hover:brightness-95 transition">Book Growth Plan</a>
        </article>

        <article class="frame lift rounded-2xl p-6 flex flex-col">
          <p class="mono text-xs text-emerald-300 mb-2">TIER 3</p>
          <h3 class="text-2xl font-semibold mb-1">Scale</h3>
          <p class="text-sm text-slate-300 mb-4">For teams that want weekly operations support.</p>
          <p class="text-4xl font-bold mb-1">$19.99<span class="text-lg text-slate-400">/mo</span></p>
          <p class="mono text-xs text-slate-400 mb-5">custom cadence available</p>
          <ul class="text-sm text-slate-300 space-y-2 mb-7 flex-1">
            <li>Weekly advisor check-ins</li>
            <li>Forecasting and scenario planning</li>
            <li>Custom workflows and automation support</li>
            <li>Priority support and escalation access</li>
          </ul>
          <a href="#contact" class="text-center px-4 py-3 rounded-md border border-[#34506a] bg-[#152837] hover:bg-[#1a3144] transition">Talk To Sales</a>
        </article>
      </div>
    </section>

    <section class="max-w-6xl mx-auto px-5 py-14" id="process">
      <div class="frame rounded-2xl p-6 md:p-8">
        <p class="mono text-[11px] tracking-[0.2em] text-sky-300 mb-3">HOW IT WORKS</p>
        <h2 class="text-2xl md:text-3xl font-semibold mb-7">A process built for speed and certainty.</h2>
        <div class="grid md:grid-cols-3 gap-4">
          <article class="soft-frame rounded-xl p-5">
            <p class="mono text-sm text-emerald-300 mb-3">01 / Scope</p>
            <h3 class="font-semibold mb-2">Goals and baseline</h3>
            <p class="text-sm text-slate-300">We lock business goals, current budget posture, and the reporting rhythm before optimization starts.</p>
          </article>
          <article class="soft-frame rounded-xl p-5">
            <p class="mono text-sm text-emerald-300 mb-3">02 / Operate</p>
            <h3 class="font-semibold mb-2">Weekly execution</h3>
            <p class="text-sm text-slate-300">You get recurring monitoring, threshold updates, and tactical recommendations with visible progress.</p>
          </article>
          <article class="soft-frame rounded-xl p-5">
            <p class="mono text-sm text-emerald-300 mb-3">03 / Improve</p>
            <h3 class="font-semibold mb-2">Review and scale</h3>
            <p class="text-sm text-slate-300">Monthly reviews identify what to keep, cut, or automate as your transaction volume grows.</p>
          </article>
        </div>
      </div>
    </section>

    <section class="max-w-6xl mx-auto px-5 py-16" id="contact">
      <div class="frame rounded-2xl p-7 md:p-10 text-center">
        <p class="mono text-[11px] tracking-[0.2em] text-[var(--accent-alt)] mb-3">START HERE</p>
        <h2 class="text-3xl md:text-5xl font-semibold mb-4">Ready to move from tracking to action?</h2>
        <p class="text-slate-300 max-w-2xl mx-auto mb-8">Tell us your transaction volume and current process. We will map the right tier and next steps in a 20-minute discovery call.</p>
        <div class="flex flex-wrap justify-center gap-3">
          <a href="mailto:sales@budgettrackerpro.com" class="px-7 py-3 rounded-md bg-[var(--accent)] text-[#032318] font-semibold hover:brightness-95 transition">Book Discovery Call</a>
          <a href="pricing-sheet.php?v=20260420" class="px-7 py-3 rounded-md border border-[#34506a] hover:bg-[#132734] transition">View Full Pricing Sheet</a>
          <a href="login.php" class="px-7 py-3 rounded-md border border-[#34506a] hover:bg-[#132734] transition">Open Client Portal</a>
        </div>
      </div>
    </section>
  </main>
</body>
</html>
