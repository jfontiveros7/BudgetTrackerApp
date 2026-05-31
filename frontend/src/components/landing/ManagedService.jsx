import { ArrowUpRight } from "lucide-react";
import { TID } from "@/lib/testIds";

export default function ManagedService() {
  return (
    <section className="py-24 md:py-32">
      <div className="max-w-7xl mx-auto px-6 md:px-10">
        <div className="relative overflow-hidden rounded-3xl bg-[#0A0A0B] text-white p-10 md:p-16">
          <div
            aria-hidden
            className="absolute inset-0 opacity-30"
            style={{
              backgroundImage:
                "radial-gradient(circle at 20% 30%, rgba(0,82,255,0.35), transparent 45%), radial-gradient(circle at 80% 70%, rgba(255,255,255,0.06), transparent 50%)",
            }}
          />
          <div className="relative grid grid-cols-1 lg:grid-cols-12 gap-10 items-end">
            <div className="lg:col-span-7">
              <span className="font-mono-bt text-[11px] uppercase tracking-[0.22em] text-[#0052FF] font-semibold">
                Higher-ticket offer
              </span>
              <h2 className="font-serif-display text-4xl md:text-5xl lg:text-6xl mt-5 leading-[1.05]">
                Want hands-on support{" "}
                <span className="italic text-white/55">instead of self-serve?</span>
              </h2>
              <p className="text-white/70 mt-6 text-lg max-w-xl leading-relaxed">
                Our managed service turns Budget Tracker into a second revenue lane: monthly
                retainers for setup, oversight, reporting, and optimization.
              </p>
            </div>
            <div className="lg:col-span-5">
              <div className="grid grid-cols-2 gap-4 mb-8">
                {[
                  { k: "Setup", v: "Tailored category model" },
                  { k: "Oversight", v: "Weekly variance review" },
                  { k: "Reporting", v: "Board-ready cadence" },
                  { k: "Optimization", v: "Quarterly cuts & re-allocations" },
                ].map((b) => (
                  <div key={b.k} className="border border-white/10 rounded-xl p-4">
                    <div className="font-mono-bt text-[9px] uppercase tracking-widest text-white/50">
                      {b.k}
                    </div>
                    <div className="text-sm mt-1.5">{b.v}</div>
                  </div>
                ))}
              </div>
              <div className="flex flex-wrap gap-3">
                <a
                  href="https://budget.konticode.com/managed/"
                  target="_blank"
                  rel="noopener noreferrer"
                  data-testid={TID.pricingManaged}
                  className="inline-flex items-center gap-2 bg-white text-[#0A0A0B] rounded-full px-6 py-3 font-medium hover:bg-[#F1EFEA] transition-all hover:-translate-y-0.5"
                >
                  Explore Managed Service <ArrowUpRight size={16} />
                </a>
                <a
                  href="https://budget.konticode.com/pricing-sheet.php"
                  target="_blank"
                  rel="noopener noreferrer"
                  className="inline-flex items-center gap-2 border border-white/25 rounded-full px-6 py-3 font-medium hover:bg-white/5 transition-colors"
                >
                  Open Pricing Sheet
                </a>
              </div>
            </div>
          </div>
        </div>
      </div>
    </section>
  );
}
