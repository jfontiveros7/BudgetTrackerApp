<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <title>Budget Tracker Managed Service | Premium budget operations support</title>
  <link rel="stylesheet" href="/assets/css/tailwind.css">
  <link rel="preconnect" href="https://fonts.googleapis.com" />
  <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin />
  <link href="https://fonts.googleapis.com/css2?family=Newsreader:opsz,wght@6..72,500;6..72,700&family=Space+Grotesk:wght@400;500;600;700&family=IBM+Plex+Mono:wght@400;500&display=swap" rel="stylesheet" />
  <style>
    :root {
      --bg: #f7f1e8;
      --surface: #fffaf4;
      --line: rgba(102, 82, 61, 0.14);
      --line-soft: rgba(102, 82, 61, 0.08);
      --text: #231912;
      --muted: #6e6053;
      --accent: #0c7a70;
      --accent-strong: #0a655d;
      --accent-alt: #4768de;
      --warn: #e89a36;
      --shadow: 0 18px 42px rgba(93, 64, 30, 0.08);
    }

    body {
      margin: 0;
      font-family: "Space Grotesk", sans-serif;
      color: var(--text);
      background:
        radial-gradient(900px 420px at 84% -10%, rgba(232, 154, 54, 0.22), transparent 56%),
        radial-gradient(880px 500px at -6% 12%, rgba(71, 104, 222, 0.1), transparent 60%),
        linear-gradient(180deg, #fffdf9 0%, var(--bg) 46%, #efe3d3 100%);
    }

    h1, h2 {
      font-family: "Newsreader", serif;
      font-weight: 700;
      letter-spacing: -0.03em;
      line-height: 0.98;
    }

    .mono {
      font-family: "IBM Plex Mono", monospace;
    }

    .frame {
      border: 1px solid var(--line);
      background: linear-gradient(180deg, rgba(255, 251, 245, 0.95), rgba(251, 245, 236, 0.96));
      box-shadow: var(--shadow);
    }

    .soft-frame {
      border: 1px solid var(--line-soft);
      background: rgba(255,255,255,0.68);
    }
  </style>
</head>
<body class="min-h-screen">
  <main class="max-w-4xl mx-auto px-5 py-16">
    <section class="frame rounded-3xl p-8 md:p-10">
    <p class="mono text-xs tracking-[0.2em] uppercase text-[var(--accent-alt)] mb-4">Budget Tracker Managed Service</p>
    <h1 class="text-4xl md:text-5xl mb-4">Premium support for teams that want more than a dashboard</h1>
    <p class="text-[color:var(--muted)] mb-8 max-w-3xl leading-7">Start with Budget Tracker for everyday visibility, then add a premium service layer when your team needs hands-on support, recurring reviews, and stronger follow-through.</p>
    <div class="grid gap-4 mb-8">
      <div class="soft-frame rounded-2xl p-5">
        <p class="font-semibold mb-2">Best fit for</p>
        <p class="text-sm text-[color:var(--muted)]">Teams with rising transaction volume, inconsistent follow-through, or a need for stronger accountability around budget decisions.</p>
      </div>
      <div class="soft-frame rounded-2xl p-5">
        <p class="font-semibold mb-2">What this service does</p>
        <p class="text-sm text-[color:var(--muted)]">Adds a premium path for customers who want expert setup, recurring oversight, and decision support without hiring a full finance lead.</p>
      </div>
    </div>
    <div class="flex flex-wrap gap-3">
      <a href="../landing.php#pricing" class="px-5 py-3 rounded-md bg-[var(--accent)] text-[#fffaf2] font-semibold hover:bg-[var(--accent-strong)] transition">See pricing</a>
      <a href="../checkout.php?plan=scale" class="px-5 py-3 rounded-md border border-[rgba(105,84,63,0.16)] hover:bg-[rgba(12,122,112,0.10)] hover:border-[rgba(12,122,112,0.22)] transition">Start Scale</a>
      <a href="mailto:jfontiveros7@gmail.com" class="px-5 py-3 rounded-md border border-[rgba(105,84,63,0.16)] hover:bg-[rgba(12,122,112,0.10)] hover:border-[rgba(12,122,112,0.22)] transition">Talk To The Team</a>
    </div>
    </section>
  </main>
</body>
</html>
