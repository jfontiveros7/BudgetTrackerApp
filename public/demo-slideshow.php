<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <title>Layout Slideshow Demo | Budget Tracker App</title>
  <style>
    :root {
      --bg: #090c0b;
      --panel: #121614;
      --border: #233027;
      --text: #f2f5f3;
      --muted: #9ca9a2;
      --accent: #7fff9e;
    }

    * {
      box-sizing: border-box;
    }

    body {
      margin: 0;
      font-family: "Space Grotesk", sans-serif;
      color: var(--text);
      background: radial-gradient(circle at 85% 8%, #1e2b23 0%, #090c0b 38%), var(--bg);
      min-height: 100vh;
      padding: 24px;
    }

    .wrap {
      max-width: 1120px;
      margin: 0 auto;
    }

    .panel {
      background: var(--panel);
      border: 1px solid var(--border);
      border-radius: 14px;
      box-shadow: 0 0 0 1px rgba(127, 255, 158, 0.2), 0 16px 36px rgba(0, 0, 0, 0.35);
      padding: 16px;
    }

    h1 {
      margin: 0 0 8px;
      font-size: 1.65rem;
    }

    p {
      margin: 0 0 14px;
      color: var(--muted);
    }

    video {
      width: 100%;
      border-radius: 10px;
      border: 1px solid var(--border);
      background: #000;
      display: block;
    }

    .links {
      margin-top: 12px;
      display: flex;
      gap: 10px;
      flex-wrap: wrap;
    }

    .btn {
      text-decoration: none;
      color: #0b0d0c;
      background: var(--accent);
      border: 1px solid var(--accent);
      padding: 8px 12px;
      border-radius: 8px;
      font-weight: 600;
      font-size: 0.9rem;
    }

    .btn.secondary {
      background: transparent;
      color: var(--text);
      border-color: var(--border);
    }

    .fallback {
      margin-top: 22px;
    }

    .grid {
      display: grid;
      grid-template-columns: repeat(auto-fit, minmax(220px, 1fr));
      gap: 12px;
    }

    .grid img {
      width: 100%;
      border-radius: 8px;
      border: 1px solid var(--border);
      display: block;
    }
  </style>
</head>
<body>
  <main class="wrap">
    <section class="panel">
      <h1>Layout Slideshow Demo</h1>
      <p>Screenshot-only preview of the current app layout for sharing and pre-sale demos.</p>
      <video controls autoplay muted loop playsinline preload="metadata" poster="assets/media/layout-video/03-dashboard.png">
        <source src="assets/media/layout-video/layout-preview.mp4" type="video/mp4" />
        Your browser does not support HTML5 video.
      </video>
      <div class="links">
        <a class="btn" href="assets/media/layout-video/layout-preview.mp4">Open MP4</a>
        <a class="btn secondary" href="landing.php">Back to Landing</a>
      </div>
    </section>

    <section class="fallback">
      <p>Fallback image deck (if video playback is blocked):</p>
      <div class="grid">
        <img src="assets/media/layout-video/01-landing.png" alt="Landing layout" />
        <img src="assets/media/layout-video/02-login.png" alt="Login layout" />
        <img src="assets/media/layout-video/03-dashboard.png" alt="Dashboard layout" />
        <img src="assets/media/layout-video/04-add-transaction.png" alt="Add Transaction layout" />
        <img src="assets/media/layout-video/05-settings.png" alt="Settings layout" />
      </div>
    </section>
  </main>
</body>
</html>
