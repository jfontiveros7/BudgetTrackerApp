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
  <title>Driftwise | Pricing Sheet</title>
  <script src="https://cdn.tailwindcss.com"></script>
  <link rel="preconnect" href="https://fonts.googleapis.com" />
  <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin />
  <link href="https://fonts.googleapis.com/css2?family=Newsreader:opsz,wght@6..72,500;6..72,700&family=Space+Grotesk:wght@400;500;600;700&family=JetBrains+Mono:wght@400;600&display=swap" rel="stylesheet" />
  <style>
    :root {
      --bg: #f7f1e8;
      --surface: #fffaf4;
      --surface-2: #fdf7ef;
      --line: rgba(102, 82, 61, 0.14);
      --line-soft: rgba(102, 82, 61, 0.08);
      --text: #231912;
      --muted: #6e6053;
      --accent: #0c7a70;
      --accent-strong: #0a655d;
      --accent-alt: #4768de;
      --warn: #e89a36;
      --success: #1e8c67;
      --shadow: 0 18px 42px rgba(93, 64, 30, 0.08);
    }

    * {
      box-sizing: border-box;
    }

    body {
      margin: 0;
      font-family: "Space Grotesk", sans-serif;
      color: var(--text);
      background:
        radial-gradient(900px 420px at 84% -10%, rgba(232, 154, 54, 0.22), transparent 56%),
        radial-gradient(880px 500px at -6% 12%, rgba(71, 104, 222, 0.1), transparent 60%),
        linear-gradient(180deg, #fffdf9 0%, var(--bg) 46%, #efe3d3 100%);
      min-height: 100vh;
    }

    body::before {
      content: "";
      position: fixed;
      inset: 0;
      pointer-events: none;
      background: linear-gradient(180deg, rgba(255,255,255,0.24), transparent 24%);
      opacity: 0.65;
    }

    .mono {
      font-family: "JetBrains Mono", monospace;
    }

    .frame {
      border: 1px solid var(--line);
      background: linear-gradient(180deg, rgba(255, 251, 245, 0.95), rgba(251, 245, 236, 0.96));
      box-shadow: var(--shadow);
    }

    .soft-frame {
      border: 1px solid var(--line-soft);
      background: rgba(255, 255, 255, 0.68);
    }

    .lift {
      transition: transform 180ms ease, border-color 180ms ease, box-shadow 180ms ease;
    }

    .lift:hover {
      transform: translateY(-2px);
      border-color: rgba(12, 122, 112, 0.18);
      box-shadow: 0 12px 26px rgba(93, 64, 30, 0.08);
    }

    h1,
    h2,
    h3 {
      font-family: "Newsreader", serif;
      font-weight: 700;
      letter-spacing: -0.03em;
      line-height: 0.98;
    }

    .body-copy {
      line-height: 1.75;
      max-width: 60ch;
    }

    .price-card {
      position: relative;
      overflow: hidden;
    }

    .price-card.featured {
      border-color: rgba(12, 122, 112, 0.24);
      box-shadow: 0 16px 34px rgba(12, 122, 112, 0.08);
    }

    .price-card.featured::before {
      content: "";
      position: absolute;
      inset: 0 auto auto 0;
      width: 100%;
      height: 4px;
      background: linear-gradient(90deg, var(--accent), var(--warn));
    }

    @media print {
      .no-print {
        display: none !important;
      }

      body {
        background: #fffdf9;
      }

      .frame {
        break-inside: avoid;
      }
    }
  </style>
</head>
<body>
  <header class="sticky top-0 z-30 bg-[#fffaf4]/90 border-b border-[rgba(105,84,63,0.10)] backdrop-blur">
    <div class="max-w-6xl mx-auto px-5 py-4 flex items-center justify-between gap-4">
      <a href="landing.php" class="font-semibold tracking-tight text-base md:text-lg">Driftwise</a>
      <div class="hidden md:flex items-center gap-6 text-sm text-slate-300">
        <a href="landing.php#pricing" class="hover:text-[var(--accent)] transition">Pricing</a>
        <a href="landing.php#process" class="hover:text-[var(--accent)] transition">Process</a>
        <a href="landing.php#faq" class="hover:text-[var(--accent)] transition">FAQ</a>
      </div>
      <div class="flex items-center gap-2">
        <span class="hidden sm:inline mono text-[11px] px-2 py-1 rounded border border-[rgba(102,82,61,0.14)] text-[var(--accent-alt)]">PRICING SHEET</span>
        <a href="login.php" class="text-xs sm:text-sm px-3 py-2 rounded border border-[rgba(105,84,63,0.16)] hover:bg-[rgba(12,122,112,0.10)] hover:border-[rgba(12,122,112,0.22)] transition">Client Portal</a>
      </div>
    </div>
  </header>

  <main class="max-w-6xl mx-auto px-5 py-10 md:py-14">
    <section class="frame rounded-2xl p-6 md:p-9 mb-6">
      <p class="mono text-[11px] tracking-[0.2em] text-[var(--accent-alt)] mb-3">DRIFTWISE / PRICING SHEET</p>
      <h1 class="text-4xl md:text-5xl mb-4">Simple pricing for teams that want clearer budget decisions</h1>
      <p class="text-slate-300 body-copy mb-7">Use this page to compare self-serve plans and the premium support path. Most teams should start with Control, then grow into Command or Ops+ only if they need more guidance and hands-on help.</p>
      <div class="grid sm:grid-cols-3 gap-3 mb-6">
        <div class="soft-frame rounded-md px-4 py-3 mono text-xs text-slate-300">always-on budget visibility</div>
        <div class="soft-frame rounded-md px-4 py-3 mono text-xs text-slate-300">billing: monthly subscription</div>
        <div class="soft-frame rounded-md px-4 py-3 mono text-xs text-slate-300">response target: &lt;24h</div>
      </div>
      <div class="no-print flex flex-wrap gap-3">
        <a href="landing.php" class="px-5 py-3 rounded-md border border-[rgba(105,84,63,0.16)] hover:bg-[rgba(12,122,112,0.10)] hover:border-[rgba(12,122,112,0.22)] transition">Back To Landing</a>
        <button onclick="window.print()" class="px-5 py-3 rounded-md bg-[var(--accent)] text-[#fffaf2] font-semibold hover:bg-[var(--accent-strong)] transition">Print Or Save PDF</button>
        <a href="mailto:jfontiveros7@gmail.com" class="px-5 py-3 rounded-md border border-[rgba(105,84,63,0.16)] hover:bg-[rgba(12,122,112,0.10)] hover:border-[rgba(12,122,112,0.22)] transition">Email Sales</a>
      </div>
    </section>

    <section class="grid md:grid-cols-3 gap-4 mb-6">
      <article class="frame price-card lift rounded-2xl p-6 flex flex-col">
        <p class="mono text-xs text-emerald-300 mb-2">TIER 1</p>
        <h2 class="text-2xl font-semibold mb-1">Monitor</h2>
        <p class="text-sm text-slate-300 mb-4">For solo operators who want a cleaner view of drift and a low-risk entry point.</p>
        <p class="text-4xl font-bold mb-1">$5<span class="text-lg text-slate-400">/mo</span></p>
        <p class="mono text-xs text-slate-400 mb-5">month-to-month</p>
        <ul class="text-sm text-slate-300 space-y-2 mb-7 flex-1">
          <li>Monthly budget health report</li>
          <li>Core dashboard visibility</li>
          <li>Transaction anomaly highlights</li>
          <li>Early category drift awareness</li>
          <li>Self-serve onboarding flow</li>
        </ul>
        <a href="checkout.php?plan=starter" class="mt-5 inline-flex items-center justify-center rounded-lg border border-[rgba(105,84,63,0.16)] px-4 py-3 text-sm font-medium hover:bg-[rgba(12,122,112,0.10)] hover:border-[rgba(12,122,112,0.22)] transition">Choose Monitor</a>
      </article>

      <article class="frame price-card featured lift rounded-2xl p-6 flex flex-col border-2 border-[var(--accent)]">
        <p class="mono text-xs text-emerald-300 mb-2">MOST POPULAR</p>
        <h2 class="text-2xl font-semibold mb-1">Control</h2>
        <p class="text-sm text-slate-300 mb-4">For teams that need stronger accountability, guided action, and a weekly operating rhythm.</p>
        <p class="text-4xl font-bold mb-1">$10<span class="text-lg text-slate-400">/mo</span></p>
        <p class="mono text-xs text-slate-400 mb-5">recommended minimum: 3 months</p>
        <ul class="text-sm text-slate-300 space-y-2 mb-7 flex-1">
          <li>Biweekly spend and variance reviews</li>
          <li>Alert tuning and threshold updates</li>
          <li>Monthly strategic action plan</li>
          <li>Priority support, 24-hour response SLA</li>
          <li>Budget Copilot guidance</li>
        </ul>
        <a href="checkout.php?plan=growth" class="mt-5 inline-flex items-center justify-center rounded-lg bg-[var(--accent)] px-4 py-3 text-sm font-semibold text-[#fffaf2] hover:bg-[var(--accent-strong)] transition">Choose Control</a>
      </article>

      <article class="frame price-card lift rounded-2xl p-6 flex flex-col">
        <p class="mono text-xs text-emerald-300 mb-2">TIER 3</p>
        <h2 class="text-2xl font-semibold mb-1">Command</h2>
        <p class="text-sm text-slate-300 mb-4">For customers who need planning help, faster support, and a premium budget operations lane.</p>
        <p class="text-4xl font-bold mb-1">$19.99<span class="text-lg text-slate-400">/mo</span></p>
        <p class="mono text-xs text-slate-400 mb-5">custom scope available</p>
        <ul class="text-sm text-slate-300 space-y-2 mb-7 flex-1">
          <li>Weekly advisor check-ins</li>
          <li>Forecasting and scenario planning</li>
          <li>Custom workflows and automation support</li>
          <li>Priority support and escalation channel</li>
          <li>Best bridge into Driftwise Ops+</li>
        </ul>
        <div class="mt-5 grid gap-3">
          <a href="checkout.php?plan=scale" class="inline-flex items-center justify-center rounded-lg border border-[rgba(105,84,63,0.16)] px-4 py-3 text-sm font-medium hover:bg-[rgba(12,122,112,0.10)] hover:border-[rgba(12,122,112,0.22)] transition">Choose Command</a>
          <a href="mailto:jfontiveros7@gmail.com" class="inline-flex items-center justify-center rounded-lg border border-[rgba(105,84,63,0.16)] px-4 py-3 text-sm hover:bg-[rgba(12,122,112,0.10)] hover:border-[rgba(12,122,112,0.22)] transition">Talk To Sales</a>
        </div>
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
            <li>Recurring budget operations review</li>
            <li>Actionable recommendations, not just charts</li>
            <li>Clear visibility into variance and drift</li>
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
      <p class="mono text-[11px] tracking-[0.2em] text-emerald-300 mb-3">DRIFTWISE OPS+ OPTIONS</p>
      <div class="grid md:grid-cols-3 gap-4 mb-7">
        <div class="soft-frame rounded-xl p-4">
        <p class="font-semibold mb-1">Managed Onboarding</p>
        <p class="text-sm text-slate-300">$75 setup + first month tier fee</p>
      </div>
      <div class="soft-frame rounded-xl p-4">
        <p class="font-semibold mb-1">Extra Advisory Call</p>
        <p class="text-sm text-slate-300">$90 per 45-minute session</p>
      </div>
        <div class="soft-frame rounded-xl p-4">
          <p class="font-semibold mb-1">Custom Workflow Build</p>
          <p class="text-sm text-slate-300">From $150 per scoped request</p>
        </div>
      </div>

        <div class="soft-frame rounded-xl p-5">
        <p class="font-semibold mb-2">Next step</p>
        <p class="text-slate-300 mb-4">Most buyers can start immediately on Monitor or Control. If the buyer needs planning help or process ownership, route them to Command or a discovery call.</p>
        <div class="no-print flex flex-wrap gap-3">
          <a href="checkout.php?plan=growth" class="px-5 py-3 rounded-md bg-[var(--accent)] text-[#fffaf2] font-semibold hover:bg-[var(--accent-strong)] transition">Start Control</a>
          <a href="mailto:jfontiveros7@gmail.com" class="px-5 py-3 rounded-md border border-[rgba(105,84,63,0.16)] hover:bg-[rgba(12,122,112,0.10)] hover:border-[rgba(12,122,112,0.22)] transition">Book Discovery Call</a>
        </div>
      </div>
    </section>
  </main>
</body>
</html>
