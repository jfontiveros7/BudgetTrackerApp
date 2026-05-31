import { useMemo, useState } from "react";
import { Slider } from "@/components/ui/slider";
import { TID } from "@/lib/testIds";
import { ArrowUpRight } from "lucide-react";

const fmt = (n) =>
  n.toLocaleString("en-US", { maximumFractionDigits: 0 });

export default function ROICalculator() {
  const [spend, setSpend] = useState([12000]);
  const [drift, setDrift] = useState([14]);

  const monthlySavings = useMemo(() => {
    // Assume Budget Tracker recovers ~65% of category drift on average.
    return Math.round(spend[0] * (drift[0] / 100) * 0.65);
  }, [spend, drift]);

  const annualSavings = monthlySavings * 12;
  const planCost = 10;
  const roi = monthlySavings > 0 ? Math.round((monthlySavings / planCost) * 10) / 10 : 0;

  return (
    <section id="calculator" className="py-24 md:py-32">
      <div className="max-w-7xl mx-auto px-6 md:px-10">
        <div className="grid grid-cols-1 lg:grid-cols-12 gap-10 mb-12 items-end">
          <div className="lg:col-span-7">
            <span className="font-mono-bt text-[11px] uppercase tracking-[0.22em] text-[#0052FF] font-semibold">
              The math
            </span>
            <h2 className="font-serif-display text-4xl md:text-5xl lg:text-6xl mt-4 leading-[1.05]">
              How much budget drift{" "}
              <span className="italic text-[#52525B]">is costing you</span> right now?
            </h2>
          </div>
          <div className="lg:col-span-5">
            <p className="text-[#52525B] text-lg leading-relaxed">
              Most teams leak 10–18% per month through unflagged overruns. Move two sliders to see
              what catching that drift could be worth.
            </p>
          </div>
        </div>

        <div className="grid grid-cols-1 lg:grid-cols-12 gap-6">
          {/* Controls */}
          <div className="lg:col-span-7 bg-white border border-black/5 rounded-2xl p-8 md:p-10">
            <div className="space-y-12">
              <div>
                <div className="flex justify-between items-baseline mb-4">
                  <label className="font-mono-bt text-[10px] uppercase tracking-[0.22em] text-[#52525B]">
                    Monthly business spend
                  </label>
                  <span className="font-serif-display text-3xl md:text-4xl">
                    ${fmt(spend[0])}
                  </span>
                </div>
                <Slider
                  value={spend}
                  onValueChange={setSpend}
                  min={1000}
                  max={100000}
                  step={500}
                  data-testid={TID.roiSpendSlider}
                  className="[&_[role=slider]]:bg-[#0052FF] [&_[role=slider]]:border-[#0052FF] [&_[role=slider]]:h-5 [&_[role=slider]]:w-5"
                />
                <div className="flex justify-between font-mono-bt text-[10px] text-[#52525B] mt-3">
                  <span>$1k</span><span>$25k</span><span>$50k</span><span>$75k</span><span>$100k</span>
                </div>
              </div>

              <div>
                <div className="flex justify-between items-baseline mb-4">
                  <label className="font-mono-bt text-[10px] uppercase tracking-[0.22em] text-[#52525B]">
                    Estimated monthly drift
                  </label>
                  <span className="font-serif-display text-3xl md:text-4xl">{drift[0]}%</span>
                </div>
                <Slider
                  value={drift}
                  onValueChange={setDrift}
                  min={2}
                  max={35}
                  step={1}
                  data-testid={TID.roiDriftSlider}
                  className="[&_[role=slider]]:bg-[#0A0A0B] [&_[role=slider]]:border-[#0A0A0B] [&_[role=slider]]:h-5 [&_[role=slider]]:w-5"
                />
                <div className="flex justify-between font-mono-bt text-[10px] text-[#52525B] mt-3">
                  <span>2%</span><span>10%</span><span>18% avg</span><span>26%</span><span>35%</span>
                </div>
              </div>
            </div>

            <p className="text-xs text-[#52525B] mt-10 leading-relaxed border-t border-black/5 pt-5">
              Methodology: assumes Budget Tracker recovers ~65% of detected drift through earlier
              alerts and category-level limits. Conservative benchmark for self-serve customers.
            </p>
          </div>

          {/* Output */}
          <div className="lg:col-span-5 relative overflow-hidden rounded-2xl bg-[#0A0A0B] text-white p-8 md:p-10">
            <div className="absolute -top-24 -right-16 w-72 h-72 rounded-full bg-[#0052FF]/35 blur-3xl" />
            <div className="relative">
              <span className="font-mono-bt text-[10px] uppercase tracking-[0.22em] text-[#0052FF]">
                Your projected savings
              </span>
              <div className="mt-6" data-testid={TID.roiOutput}>
                <div className="font-mono-bt text-[10px] uppercase tracking-widest text-white/50">
                  Per month
                </div>
                <div className="font-serif-display text-5xl md:text-6xl leading-none mt-1">
                  ${fmt(monthlySavings)}
                </div>
              </div>

              <div className="mt-8 grid grid-cols-2 gap-4">
                <div className="border-l border-white/15 pl-4">
                  <div className="font-mono-bt text-[10px] uppercase tracking-widest text-white/50">
                    Per year
                  </div>
                  <div className="font-serif-display text-2xl mt-1">${fmt(annualSavings)}</div>
                </div>
                <div className="border-l border-white/15 pl-4">
                  <div className="font-mono-bt text-[10px] uppercase tracking-widest text-white/50">
                    ROI on Growth
                  </div>
                  <div className="font-serif-display text-2xl mt-1">{roi}×</div>
                </div>
              </div>

              <a
                href="https://budget.konticode.com/checkout.php?plan=growth"
                target="_blank"
                rel="noopener noreferrer"
                className="mt-10 inline-flex items-center gap-2 bg-[#0052FF] text-white rounded-full px-6 py-3 font-medium hover:bg-[#0040C5] transition-all hover:-translate-y-0.5"
              >
                Capture this savings — Start Growth
                <ArrowUpRight size={16} />
              </a>
            </div>
          </div>
        </div>
      </div>
    </section>
  );
}
