import { Layers, Gauge, BellRing, Sparkles, ArrowRight } from "lucide-react";

const ICONS = { Layers, Gauge, BellRing, Sparkles };

const FEATURES = [
  {
    icon: "Layers",
    title: "Smart Categories",
    body: "Organize every transaction with cleaner category logic and faster edits — no more spreadsheet detective work.",
    accent: "from-[#0052FF]/10 to-transparent",
  },
  {
    icon: "Gauge",
    title: "Budget Thresholds",
    body: "Set monthly limits by category and see drift before it becomes a surprise on the P&L.",
    accent: "from-[#E0E7FF] to-transparent",
  },
  {
    icon: "BellRing",
    title: "Alerts",
    body: "Flag overspending, anomalies, and variance so action happens sooner — not at month-end.",
    accent: "from-[#F1EFEA] to-transparent",
  },
  {
    icon: "Sparkles",
    title: "AI Coach",
    body: "Guided AI budget insight on Growth & Scale turns data into next steps — a CFO whisper in your pocket.",
    accent: "from-[#0052FF]/15 to-transparent",
  },
];

export default function Features() {
  return (
    <section id="features" className="py-24 md:py-32">
      <div className="max-w-7xl mx-auto px-6 md:px-10">
        <div className="grid grid-cols-1 lg:grid-cols-12 gap-10 mb-14">
          <div className="lg:col-span-7">
            <span className="font-mono-bt text-[11px] uppercase tracking-[0.22em] text-[#0052FF] font-semibold">
              Features
            </span>
            <h2 className="font-serif-display text-4xl md:text-5xl lg:text-6xl mt-4 leading-[1.05] tracking-tight">
              Everything needed to move from{" "}
              <span className="italic text-[#52525B]">expense logging</span> to{" "}
              budget control.
            </h2>
          </div>
          <div className="lg:col-span-5 lg:pt-8">
            <p className="text-lg text-[#52525B] leading-relaxed">
              Teams usually know they should review spending. What slips is the habit, the
              thresholds, and the follow-through. Budget Tracker tightens all three.
            </p>
          </div>
        </div>

        {/* Bento grid */}
        <div className="grid grid-cols-1 md:grid-cols-12 gap-5 md:gap-6">
          {/* Big feature card */}
          <article className="md:col-span-7 relative overflow-hidden rounded-2xl bg-[#0A0A0B] text-white p-8 md:p-10 group">
            <div className="absolute -top-20 -right-20 w-72 h-72 rounded-full bg-[#0052FF]/30 blur-3xl group-hover:bg-[#0052FF]/45 transition-all" />
            <span className="font-mono-bt text-[10px] uppercase tracking-[0.22em] text-[#0052FF]">
              01 / Categories
            </span>
            <h3 className="font-serif-display text-3xl md:text-4xl mt-4 leading-tight">
              A category model that finally matches how you actually spend.
            </h3>
            <p className="text-white/70 mt-4 max-w-md">
              Rename, merge, and auto-tag with smart rules. Reclassify a quarter in minutes — not
              an afternoon.
            </p>
            <div className="mt-8 grid grid-cols-3 gap-3 relative">
              {[
                { l: "Software", v: "$1,240" },
                { l: "Marketing", v: "$2,780" },
                { l: "Travel", v: "$540" },
              ].map((c, i) => (
                <div key={i} className="bg-white/5 border border-white/10 rounded-lg p-3">
                  <div className="font-mono-bt text-[9px] uppercase tracking-widest text-white/50">
                    {c.l}
                  </div>
                  <div className="font-serif-display text-xl mt-1">{c.v}</div>
                </div>
              ))}
            </div>
          </article>

          {/* Side feature card */}
          <article className="md:col-span-5 rounded-2xl bg-white border border-black/5 p-8 md:p-10 flex flex-col justify-between">
            <div>
              <span className="font-mono-bt text-[10px] uppercase tracking-[0.22em] text-[#0052FF]">
                02 / Thresholds
              </span>
              <h3 className="font-serif-display text-2xl md:text-3xl mt-4 leading-tight">
                Limits that warn you weeks early — not after the surprise.
              </h3>
              <p className="text-[#52525B] mt-3">
                Per-category caps, soft warnings at 70%, hard alerts at 90%. Predictable, not
                punishing.
              </p>
            </div>
            <div className="mt-7">
              <div className="text-xs text-[#52525B] mb-1.5 flex justify-between">
                <span>Contractors</span>
                <span className="font-mono-bt text-[#DC2626]">$4,220 / $4,000</span>
              </div>
              <div className="h-2 rounded-full bg-[#F1EFEA] overflow-hidden">
                <div className="h-full w-[105%] bg-[#DC2626] rounded-full" />
              </div>
            </div>
          </article>

          {/* Small feature cards */}
          {FEATURES.slice(2).map((f, i) => {
            const Icon = ICONS[f.icon];
            return (
              <article
                key={f.title}
                className="md:col-span-6 group relative overflow-hidden rounded-2xl bg-white border border-black/5 p-8 md:p-10 hover:-translate-y-1 hover:shadow-[0_18px_50px_-25px_rgba(0,0,0,0.18)] transition-all"
              >
                <div className={`absolute inset-0 bg-gradient-to-br ${f.accent} opacity-60 pointer-events-none`} />
                <div className="relative">
                  <div className="w-12 h-12 rounded-xl bg-[#0A0A0B] text-white flex items-center justify-center mb-6 group-hover:rotate-3 transition-transform">
                    <Icon size={22} />
                  </div>
                  <span className="font-mono-bt text-[10px] uppercase tracking-[0.22em] text-[#0052FF]">
                    {String(i + 3).padStart(2, "0")} / {f.title}
                  </span>
                  <h3 className="font-serif-display text-2xl md:text-3xl mt-3 leading-tight">
                    {f.title}
                  </h3>
                  <p className="text-[#52525B] mt-3 leading-relaxed">{f.body}</p>
                  <span className="mt-6 inline-flex items-center gap-1.5 text-sm text-[#0A0A0B] font-medium opacity-0 group-hover:opacity-100 transition-opacity">
                    Learn more <ArrowRight size={14} />
                  </span>
                </div>
              </article>
            );
          })}
        </div>
      </div>
    </section>
  );
}
