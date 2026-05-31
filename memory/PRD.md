# Budget Tracker — Landing Page PRD

## Original Problem Statement
> "build me the best landing page for https://budget.konticode.com/"

## Product Context
Budget Tracker App (budget.konticode.com) is a self-serve + managed-service budget tool for solo operators and growing teams. Three plans (Starter $5, Growth $10, Scale $19.99) plus a managed service upsell. Features: Smart Categories, Budget Thresholds, Alerts, AI Coach.

## User Choices (Dec 2025)
- Design: hybrid editorial premium fintech + bold modern SaaS (designer's call)
- Scope: full landing + lead capture form + interactive demo + ROI calculator + testimonials
- AI Coach chat demo: not included
- Checkout: external links to existing budget.konticode.com Stripe checkout
- Featured: testimonials, ROI calculator

## Architecture
- **Backend** (FastAPI + MongoDB): `POST/GET /api/leads` for lead capture; existing `/api/status`, `/api/` retained.
- **Frontend** (React + Tailwind + shadcn/ui + framer-motion-ready):
  - Single route `/` → `pages/Landing.jsx` composing 11 sections.
  - Fonts: Playfair Display (headings) + Manrope (body) + JetBrains Mono (overlines).
  - Palette: `#F9F8F6` canvas, `#0A0A0B` ink, `#0052FF` action blue, `#E0E7FF` highlight.

## Implemented (2025-12)
- Sticky glass header with smooth-scroll anchor nav.
- Hero with editorial typography, dual CTAs, KPI strip, animated dashboard mock (bars + category bars + drift callout).
- Marquee logo strip ("Trusted by lean operators").
- Features bento grid (dark hero card + 3 supporting cards).
- Interactive category demo (tabs → live detail panel with thresholds, trend, AI Coach signal).
- ROI Calculator with two shadcn sliders + real-time monthly/annual/ROI output.
- Pricing ladder (Starter / Growth highlighted / Scale) → external Stripe links.
- Managed Service dark CTA block.
- Testimonials (3 cards, Unsplash portraits, masonry offset).
- Lead capture form (Name, Email, Company Size, Plan Interest, Message) → MongoDB + sonner toasts + success state.
- FAQ accordion (6 items).
- Editorial footer with giant "Turn budget visibility into better decisions" CTA.

## Verified
- testing_agent_v3 iteration 1: 100% backend (9/9) + 100% frontend (14/14), zero issues.

## Personas
1. **Solo operator** — needs the cheapest entry point, wants Growth's AI Coach.
2. **Small team ops lead** — uses ROI calculator + lead form to evaluate Scale or managed service.
3. **Managed-service buyer** — clicks "Need managed help?" / dark CTA → external page.

## Backlog
**P1**
- Wire up actual analytics events on CTA clicks (PostHog already loaded).
- Add OG image + structured data (Product + FAQPage schema) for SEO.
- Lead-source attribution (UTM capture on lead model).

**P2**
- Add a screenshot/video carousel pulling from demo-slideshow.
- Comparison table vs. spreadsheets / generic tools.
- Light/dark theme toggle for the landing.
- A/B variant: hero headline test.

**P3**
- Connect lead form to email (Resend/SendGrid) for instant notification.
- Replace external Stripe links with embedded Stripe Checkout via this app.

## Files
- `/app/backend/server.py`
- `/app/frontend/src/App.js`
- `/app/frontend/src/pages/Landing.jsx`
- `/app/frontend/src/components/landing/*.jsx` (11 sections + DashboardMock)
- `/app/frontend/src/lib/testIds.js`
- `/app/frontend/src/index.css` (theme tokens + Playfair/Manrope wiring)
- `/app/frontend/public/index.html` (Google Fonts)
