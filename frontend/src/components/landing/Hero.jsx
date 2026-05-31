import { TID } from "@/lib/testIds";
import { ArrowUpRight, Play } from "lucide-react";
import DashboardMock from "@/components/landing/DashboardMock";

export default function Hero() {
  return (
    <section id="top" className="relative pt-28 md:pt-36 pb-20 md:pb-28 overflow-hidden bt-grain">
      {/* Decorative background grid */}
      <div
        aria-hidden
        className="absolute inset-0 opacity-[0.04] pointer-events-none"
        style={{
          backgroundImage:
            "linear-gradient(#0A0A0B 1px, transparent 1px), linear-gradient(90deg, #0A0A0B 1px, transparent 1px)",
          backgroundSize: "64px 64px",
        }}
      />

      <div className="max-w-7xl mx-auto px-6 md:px-10 relative">
        {/* Eyebrow */}
        <div className="flex items-center gap-3 mb-7">
          <span className="font-mono-bt text-[11px] uppercase tracking-[0.22em] text-[#0052FF] font-semibold">
            Budget Tracker · AI Coach · Managed Service
          </span>
          <span className="h-px flex-1 bg-black/10 max-w-[140px]" />
        </div>

        <div className="grid grid-cols-1 lg:grid-cols-12 gap-10 lg:gap-12 items-start">
          {/* Left: headline */}
          <div className="lg:col-span-7">
            <h1 className="font-serif-display text-[44px] sm:text-6xl lg:text-[78px] leading-[0.98] tracking-tight">
              Track spending.
              <br />
              <span className="italic text-[#52525B]">Catch drift.</span>
              <br />
              Turn budget into <span className="relative inline-block">
                <span className="relative z-10">action.</span>
                <span className="absolute left-0 right-0 bottom-1 h-3 bg-[#E0E7FF] -z-0" aria-hidden />
              </span>
            </h1>

            <p className="mt-7 text-lg md:text-xl text-[#52525B] max-w-xl leading-relaxed">
              Budget Tracker helps solo operators and growing teams monitor category limits, review
              spending trends, and act before overspending compounds — starting at $5/mo.
            </p>

            <div className="mt-9 flex flex-wrap items-center gap-3">
              <a
                href="https://budget.konticode.com/checkout.php?plan=growth"
                target="_blank"
                rel="noopener noreferrer"
                data-testid={TID.heroPrimaryCta}
                className="group inline-flex items-center gap-2 bg-[#0052FF] text-white rounded-full pl-6 pr-5 py-3.5 font-medium hover:bg-[#0040C5] transition-all hover:-translate-y-0.5 hover:shadow-[0_12px_30px_-12px_rgba(0,82,255,0.55)]"
              >
                Start Growth Plan — $10/mo
                <ArrowUpRight size={18} className="group-hover:translate-x-0.5 group-hover:-translate-y-0.5 transition-transform" />
              </a>
              <a
                href="https://budget.konticode.com/demo-slideshow.php"
                target="_blank"
                rel="noopener noreferrer"
                data-testid={TID.heroSecondaryCta}
                className="inline-flex items-center gap-2 text-[#0A0A0B] border border-black/15 rounded-full px-6 py-3.5 font-medium hover:bg-black/5 transition-colors"
              >
                <Play size={16} className="text-[#0052FF]" /> Watch Demo
              </a>
              <a
                href="https://budget.konticode.com/managed/"
                target="_blank"
                rel="noopener noreferrer"
                data-testid={TID.heroManagedLink}
                className="text-sm text-[#52525B] underline underline-offset-4 decoration-black/20 hover:text-[#0A0A0B] hover:decoration-[#0052FF] transition-colors"
              >
                Need managed help?
              </a>
            </div>

            {/* Stats row */}
            <div className="mt-12 grid grid-cols-3 gap-6 max-w-lg">
              {[
                { k: "$5", v: "Starter plan / month" },
                { k: "3 min", v: "From checkout to dashboard" },
                { k: "AI Coach", v: "On Growth & Scale" },
              ].map((s, i) => (
                <div key={i} className="border-l border-black/10 pl-4">
                  <div className="font-serif-display text-2xl md:text-3xl">{s.k}</div>
                  <div className="text-xs text-[#52525B] mt-1 leading-snug">{s.v}</div>
                </div>
              ))}
            </div>
          </div>

          {/* Right: dashboard mock */}
          <div className="lg:col-span-5 relative">
            <div className="bt-float">
              <DashboardMock />
            </div>
            {/* Floating callouts */}
            <div className="hidden md:flex absolute -left-6 top-8 bg-white border border-black/10 shadow-[0_18px_40px_-20px_rgba(0,0,0,0.18)] rounded-xl px-3 py-2 items-center gap-2">
              <span className="w-2 h-2 rounded-full bg-[#0052FF] bt-pulse" />
              <span className="text-xs font-medium">Alert tuned · Marketing 92%</span>
            </div>
            <div className="hidden md:flex absolute -right-2 bottom-10 bg-[#0A0A0B] text-white rounded-xl px-3 py-2 items-center gap-2 shadow-lg">
              <span className="font-mono-bt text-[10px] uppercase tracking-widest text-[#9CA3AF]">Drift</span>
              <span className="font-serif-display text-base">−$412 saved</span>
            </div>
          </div>
        </div>
      </div>
    </section>
  );
}
