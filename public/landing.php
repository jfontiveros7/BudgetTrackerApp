<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <title>Budget Tracker App | Track Spending With Confidence</title>
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
      --accent-soft: #4b3512;
      --accent-alt: #7dd4ff;
      --warn: #ff7f5a;
    }

    * {
      box-sizing: border-box;
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
      color: #ffe1a2 !important;
    }

    .bg-\[var\(--accent\)\] {
      color: #261402 !important;
      box-shadow: 0 8px 24px rgba(255, 191, 71, 0.32);
    }

    .bg-\[\#152837\],
    .bg-\[\#10202d\] {
      background: rgba(24, 36, 52, 0.9) !important;
    }

    .hover\:bg-\[\#1a3144\]:hover,
    .hover\:bg-\[\#132734\]:hover,
    .hover\:bg-\[\#13202c\]:hover {
      background: rgba(34, 50, 72, 0.95) !important;
    }

    .border-\[\#34506a\],
    .border-\[\#30475d\],
    .border-\[\#2a3f54\],
    .border-\[\#254158\],
    .border-\[\#1b2a38\] {
      border-color: #3c4f6b !important;
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
  <header class="sticky top-0 z-30 bg-[#090d14]/92 border-b border-[#1d2a3b] backdrop-blur">
    <div class="max-w-6xl mx-auto px-5 py-4 flex items-center justify-between gap-4">
      <a href="index.php" class="font-semibold tracking-tight text-base md:text-lg">Budget Tracker App</a>
      <nav class="hidden md:flex items-center gap-6 text-sm text-slate-300">
        <a href="#features" class="hover:text-white transition">Features</a>
        <a href="#pricing" class="hover:text-white transition">Pricing</a>
      </nav>
      <div class="flex items-center gap-2">
        <a href="demo-slideshow.php" class="text-xs sm:text-sm px-3 py-2 rounded border border-[#2a3f54] hover:bg-[#13202c] transition">Watch Demo</a>
        <a href="#pricing" class="text-xs sm:text-sm px-3 py-2 rounded border border-[#2a3f54] hover:bg-[#13202c] transition">View Pricing</a>
        <a href="mailto:sales@budgettrackerpro.com" class="text-xs sm:text-sm px-3 py-2 rounded bg-[var(--accent)] font-semibold hover:brightness-95 transition">Book Discovery Call</a>
      </div>
    </div>
  </header>

  <main class="px-5 py-8 md:py-12">
    <div class="max-w-6xl mx-auto text-center mb-8 text-slate-300">
      <p class="mono text-sm">Need expert help? <a href="managed/" class="text-sky-300 hover:text-white transition">-&gt; /managed</a></p>
    </div>

    <section class="max-w-6xl mx-auto frame rounded-2xl p-6 md:p-10 mb-8" id="problem">
      <p class="mono text-[11px] tracking-[0.18em] text-[var(--warn)] mb-4">PROBLEM</p>
      <h1 class="text-3xl sm:text-5xl md:text-6xl font-semibold leading-tight tracking-tight max-w-5xl">
        You're tracking spending but still feel behind.
      </h1>
      <p class="text-slate-300 text-base md:text-lg max-w-3xl mt-5">
        You already log transactions. But without consistent oversight, threshold updates, and practical execution, budget drift still creeps in.
      </p>
    </section>

    <section class="max-w-6xl mx-auto frame rounded-2xl p-6 md:p-10 mb-8" id="solution">
      <p class="mono text-[11px] tracking-[0.18em] text-[var(--accent-alt)] mb-4">SOLUTION</p>
      <h2 class="text-2xl sm:text-4xl md:text-5xl font-semibold leading-tight tracking-tight max-w-5xl">
        We operate your budget like a fractional finance team.
      </h2>
      <div class="grid md:grid-cols-3 gap-4 mt-7">
        <article class="soft-frame rounded-xl p-5">
          <h3 class="font-semibold mb-2">Hands-on Monitoring</h3>
          <p class="text-sm text-slate-300">Weekly review of spending patterns with early anomaly detection and escalation.</p>
        </article>
        <article class="soft-frame rounded-xl p-5">
          <h3 class="font-semibold mb-2">Threshold Operations</h3>
          <p class="text-sm text-slate-300">Category limits and alert rules are adjusted continuously as your workflows change.</p>
        </article>
        <article class="soft-frame rounded-xl p-5">
          <h3 class="font-semibold mb-2">Decision-ready Reporting</h3>
          <p class="text-sm text-slate-300">Clear monthly summaries with recommendations on what to keep, cut, or automate.</p>
        </article>
      </div>
    </section>

    <!-- Proof section hidden until real proof/testimonials are available.
    <section class="max-w-6xl mx-auto frame rounded-2xl p-6 md:p-10 mb-8" id="proof">
      <p class="mono text-[11px] tracking-[0.18em] text-[var(--accent-alt)] mb-4">PROOF</p>
      <h2 class="text-2xl md:text-4xl font-semibold mb-6">Outcomes from teams we support</h2>
      <div class="grid md:grid-cols-3 gap-4">
        <article class="soft-frame rounded-xl p-5">
          <p class="mono text-xs text-sky-300 mb-2">CASE STUDY</p>
          <h3 class="font-semibold mb-2">SaaS Ops Team</h3>
          <p class="text-sm text-slate-300 mb-3">"We finally had one budget operating rhythm instead of one-off fire drills."</p>
          <p class="mono text-xs text-emerald-300">Outcome: cut software spend 30% in 8 weeks</p>
        </article>
        <article class="soft-frame rounded-xl p-5">
          <p class="mono text-xs text-sky-300 mb-2">TESTIMONIAL</p>
          <h3 class="font-semibold mb-2">Agency Founder</h3>
          <p class="text-sm text-slate-300 mb-3">"Alerts became actionable and we stopped discovering overruns at month-end."</p>
          <p class="mono text-xs text-emerald-300">Outcome: reduced budget variance by 22%</p>
        </article>
        <article class="soft-frame rounded-xl p-5">
          <p class="mono text-xs text-sky-300 mb-2">CASE STUDY</p>
          <h3 class="font-semibold mb-2">Operations Team</h3>
          <p class="text-sm text-slate-300 mb-3">"Monthly reviews gave us confidence to make faster cost decisions."</p>
          <p class="mono text-xs text-emerald-300">Outcome: saved 11 hours/week in finance ops</p>
        </article>
      </div>
    </section>
    -->

    <section class="max-w-6xl mx-auto frame rounded-2xl p-6 md:p-10 mb-8" id="process">
      <p class="mono text-[11px] tracking-[0.18em] text-[var(--accent-alt)] mb-4">PROCESS</p>
      <h2 class="text-2xl md:text-4xl font-semibold mb-6">Scope -> Operate -> Improve</h2>
      <div class="grid md:grid-cols-3 gap-4">
        <article class="soft-frame rounded-xl p-5">
          <p class="mono text-sm text-emerald-300 mb-3">01 / Scope</p>
          <h3 class="font-semibold mb-2">Baseline and goals</h3>
          <p class="text-sm text-slate-300">We map spending posture, category model, and review cadence before execution starts.</p>
        </article>
        <article class="soft-frame rounded-xl p-5">
          <p class="mono text-sm text-emerald-300 mb-3">02 / Operate</p>
          <h3 class="font-semibold mb-2">Weekly execution</h3>
          <p class="text-sm text-slate-300">Ongoing monitoring, threshold tuning, and alerts management with clear weekly updates.</p>
        </article>
        <article class="soft-frame rounded-xl p-5">
          <p class="mono text-sm text-emerald-300 mb-3">03 / Improve</p>
          <h3 class="font-semibold mb-2">Review and optimize</h3>
          <p class="text-sm text-slate-300">Monthly recommendations focus on measurable savings and operational confidence.</p>
        </article>
      </div>
    </section>

    <section class="max-w-6xl mx-auto py-4" id="features">
      <p class="mono text-[11px] tracking-[0.18em] text-[var(--accent-alt)] mb-3">FEATURES</p>
      <div class="grid md:grid-cols-2 xl:grid-cols-4 gap-4">
        <article class="soft-frame lift rounded-xl p-5">
          <h3 class="font-semibold mb-2">Smart Categories</h3>
          <p class="text-sm text-slate-300">Group every transaction with clean category logic and quick edits.</p>
        </article>
        <article class="soft-frame lift rounded-xl p-5">
          <h3 class="font-semibold mb-2">Budget Thresholds</h3>
          <p class="text-sm text-slate-300">Set monthly limits per category and track progress in real time.</p>
        </article>
        <article class="soft-frame lift rounded-xl p-5">
          <h3 class="font-semibold mb-2">Alerts</h3>
          <p class="text-sm text-slate-300">Receive overspending and variance warnings before budgets drift.</p>
        </article>
        <article class="soft-frame lift rounded-xl p-5">
          <h3 class="font-semibold mb-2">Reports</h3>
          <p class="text-sm text-slate-300">See monthly summaries with trends, risks, and action-ready insights.</p>
        </article>
      </div>
    </section>

    <section class="max-w-6xl mx-auto" id="pricing">
      <p class="mono text-[11px] tracking-[0.18em] text-[var(--warn)] mb-3">PRICING</p>
      <h2 class="text-2xl md:text-4xl font-semibold mb-6">Budget App Purchase Options</h2>
      <div class="grid md:grid-cols-2 gap-4">
        <article class="frame rounded-2xl p-6 flex flex-col">
          <h3 class="text-2xl font-semibold mb-2">Starter</h3>
          <p class="text-4xl font-bold mb-4">$5<span class="text-lg text-slate-400">/mo</span></p>
          <ul class="text-sm text-slate-300 space-y-2 flex-1">
            <li>Monthly budget health report</li>
            <li>One optimization pass per month</li>
            <li>Limited dashboard alerts</li>
            <li>AI Coach not included</li>
          </ul>
          <a href="checkout.php?plan=starter" class="mt-6 inline-flex items-center justify-center rounded-lg border border-[#2a3f54] bg-[#13202c] px-4 py-3 text-sm font-medium text-slate-100 transition hover:bg-[#1a3144]">
            Choose Starter
          </a>
        </article>
        <article class="frame rounded-2xl p-6 border-2 border-[var(--accent)] flex flex-col">
          <p class="mono text-xs text-emerald-300 mb-2">MOST POPULAR</p>
          <h3 class="text-2xl font-semibold mb-2">Growth</h3>
          <p class="text-4xl font-bold mb-4">$10<span class="text-lg text-slate-400">/mo</span></p>
          <ul class="text-sm text-slate-300 space-y-2 flex-1">
            <li>Biweekly spend and variance reviews</li>
            <li>Alert tuning and threshold updates</li>
            <li>Monthly strategic action plan</li>
            <li>Full dashboard alerts</li>
            <li>Includes AI Coach visibility and chat</li>
          </ul>
          <a href="checkout.php?plan=growth" class="mt-6 inline-flex items-center justify-center rounded-lg bg-[var(--accent)] px-4 py-3 text-sm font-semibold text-[#261402] transition hover:brightness-95">
            Choose Growth
          </a>
        </article>
      </div>
    </section>

    <section class="max-w-6xl mx-auto py-12" id="contact">
      <div class="frame rounded-2xl p-7 md:p-10 text-center">
        <p class="mono text-[11px] tracking-[0.2em] text-[var(--accent-alt)] mb-3">NEXT STEP</p>
        <h2 class="text-3xl md:text-5xl font-semibold mb-4">Book your discovery call</h2>
        <p class="text-slate-300 max-w-2xl mx-auto mb-8">Tell us your current transaction volume and budget process. We will map your operating tier and rollout plan.</p>
        <div class="flex flex-wrap items-center justify-center gap-3">
          <!-- Removed duplicate demo button to keep only one on page -->
          <a href="mailto:sales@budgettrackerpro.com" class="inline-block px-7 py-3 rounded-md bg-[var(--accent)] text-[#261402] font-semibold hover:brightness-95 transition">Book Discovery Call</a>
        </div>
      </div>
    </section>
  </main>
</body>
</html>
