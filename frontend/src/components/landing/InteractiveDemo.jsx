import { useState } from "react";
import { Tabs, TabsList, TabsTrigger, TabsContent } from "@/components/ui/tabs";
import { TID } from "@/lib/testIds";
import { CheckCircle2, AlertTriangle, TrendingDown, TrendingUp } from "lucide-react";

const CATEGORIES = [
  { id: "software", name: "Software", spent: 1240, limit: 1500, trend: -8, txns: 14 },
  { id: "marketing", name: "Marketing", spent: 2780, limit: 3000, trend: 4, txns: 22 },
  { id: "travel", name: "Travel", spent: 540, limit: 1200, trend: -34, txns: 6 },
  { id: "contractors", name: "Contractors", spent: 4220, limit: 4000, trend: 18, txns: 9 },
];

const ALERTS_BY_CAT = {
  software: [
    { type: "ok", text: "Tracking 17% under monthly limit." },
    { type: "info", text: "Auto-tag rule applied to 6 new charges." },
  ],
  marketing: [
    { type: "warn", text: "Reached 92% of monthly limit on day 23." },
    { type: "info", text: "Variance vs. last month: +4%." },
  ],
  travel: [
    { type: "ok", text: "Significant underspend — consider reallocating." },
  ],
  contractors: [
    { type: "alert", text: "Over budget by $220 — paused new approvals." },
    { type: "warn", text: "Two unusual invoices flagged for review." },
  ],
};

const ICONS = {
  ok: <CheckCircle2 size={16} className="text-[#0052FF]" />,
  info: <CheckCircle2 size={16} className="text-[#52525B]" />,
  warn: <AlertTriangle size={16} className="text-[#B45309]" />,
  alert: <AlertTriangle size={16} className="text-[#DC2626]" />,
};

export default function InteractiveDemo() {
  const [active, setActive] = useState("marketing");
  const cat = CATEGORIES.find((c) => c.id === active);
  const pct = Math.min(120, Math.round((cat.spent / cat.limit) * 100));
  const over = cat.spent > cat.limit;

  return (
    <section className="py-24 md:py-32 bg-[#F1EFEA]/60 border-y border-black/5">
      <div className="max-w-7xl mx-auto px-6 md:px-10">
        <div className="grid grid-cols-1 lg:grid-cols-12 gap-10 mb-12 items-end">
          <div className="lg:col-span-7">
            <span className="font-mono-bt text-[11px] uppercase tracking-[0.22em] text-[#0052FF] font-semibold">
              Live preview
            </span>
            <h2 className="font-serif-display text-4xl md:text-5xl lg:text-6xl mt-4 leading-[1.05]">
              Drift you can see.{" "}
              <span className="italic text-[#52525B]">Action you can take.</span>
            </h2>
          </div>
          <div className="lg:col-span-5">
            <p className="text-[#52525B] text-lg leading-relaxed">
              Pick a category and preview what the dashboard surfaces — thresholds, trend, and the
              alerts your AI Coach turns into a next step.
            </p>
          </div>
        </div>

        <div className="grid grid-cols-1 lg:grid-cols-12 gap-6">
          {/* Category list (tabs) */}
          <div className="lg:col-span-4">
            <Tabs value={active} onValueChange={setActive} data-testid={TID.demoTabs} orientation="vertical">
              <TabsList className="bg-transparent p-0 h-auto flex flex-col items-stretch gap-2 w-full">
                {CATEGORIES.map((c) => {
                  const isActive = c.id === active;
                  const cp = Math.min(120, Math.round((c.spent / c.limit) * 100));
                  const cover = c.spent > c.limit;
                  return (
                    <TabsTrigger
                      key={c.id}
                      value={c.id}
                      data-testid={`${TID.demoCategory}-${c.id}`}
                      className={`group justify-start text-left rounded-2xl border px-5 py-4 transition-all data-[state=active]:shadow-[0_18px_40px_-25px_rgba(0,0,0,0.2)] ${
                        isActive
                          ? "bg-white border-[#0A0A0B]"
                          : "bg-white/50 border-black/5 hover:bg-white"
                      }`}
                    >
                      <div className="w-full">
                        <div className="flex items-center justify-between">
                          <span className={`font-medium ${isActive ? "text-[#0A0A0B]" : "text-[#0A0A0B]/80"}`}>
                            {c.name}
                          </span>
                          <span className={`font-mono-bt text-[11px] ${cover ? "text-[#DC2626]" : "text-[#52525B]"}`}>
                            {cp}%
                          </span>
                        </div>
                        <div className="h-1.5 mt-2 rounded-full bg-[#F1EFEA] overflow-hidden">
                          <div
                            className="h-full rounded-full transition-all"
                            style={{ width: `${Math.min(100, cp)}%`, background: cover ? "#DC2626" : "#0052FF" }}
                          />
                        </div>
                      </div>
                    </TabsTrigger>
                  );
                })}
              </TabsList>
            </Tabs>
          </div>

          {/* Detail panel */}
          <div className="lg:col-span-8">
            <div className="bg-white border border-black/5 rounded-2xl p-7 md:p-10 h-full">
              <div className="flex items-start justify-between flex-wrap gap-4">
                <div>
                  <div className="font-mono-bt text-[10px] uppercase tracking-[0.22em] text-[#0052FF]">
                    Category detail
                  </div>
                  <h3 className="font-serif-display text-3xl md:text-4xl mt-1">{cat.name}</h3>
                  <div className="text-sm text-[#52525B] mt-2">
                    {cat.txns} transactions this month
                  </div>
                </div>
                <div className="text-right">
                  <div className="font-serif-display text-4xl md:text-5xl">
                    ${cat.spent.toLocaleString()}
                  </div>
                  <div className="text-sm text-[#52525B] mt-1">
                    of <span className="text-[#0A0A0B] font-medium">${cat.limit.toLocaleString()}</span>
                  </div>
                </div>
              </div>

              {/* Threshold bar */}
              <div className="mt-7">
                <div className="flex justify-between text-xs text-[#52525B] mb-2">
                  <span>0%</span>
                  <span>70% soft</span>
                  <span>90% hard</span>
                  <span>{pct}%</span>
                </div>
                <div className="relative h-3 rounded-full bg-[#F1EFEA] overflow-hidden">
                  <div className="absolute inset-y-0 left-[70%] w-px bg-black/20" />
                  <div className="absolute inset-y-0 left-[90%] w-px bg-black/30" />
                  <div
                    className="h-full rounded-full transition-all duration-500"
                    style={{
                      width: `${Math.min(100, pct)}%`,
                      background: over ? "#DC2626" : pct > 90 ? "#B45309" : "#0052FF",
                    }}
                  />
                </div>
              </div>

              {/* Trend + alerts */}
              <div className="mt-8 grid grid-cols-1 md:grid-cols-2 gap-5">
                <div className="rounded-xl bg-[#F9F8F6] p-5 border border-black/5">
                  <div className="font-mono-bt text-[10px] uppercase tracking-widest text-[#52525B]">
                    Vs. last month
                  </div>
                  <div className="flex items-center gap-2 mt-2">
                    {cat.trend < 0 ? (
                      <TrendingDown size={20} className="text-[#0052FF]" />
                    ) : (
                      <TrendingUp size={20} className="text-[#DC2626]" />
                    )}
                    <span className="font-serif-display text-3xl">
                      {cat.trend > 0 ? "+" : ""}
                      {cat.trend}%
                    </span>
                  </div>
                  <div className="text-xs text-[#52525B] mt-1">
                    {cat.trend < 0 ? "Trending healthier." : "Drift detected — review needed."}
                  </div>
                </div>

                <div className="rounded-xl bg-[#0A0A0B] text-white p-5">
                  <div className="font-mono-bt text-[10px] uppercase tracking-widest text-white/60">
                    AI Coach signal
                  </div>
                  <ul className="mt-3 space-y-2.5">
                    {(ALERTS_BY_CAT[cat.id] || []).map((a, i) => (
                      <li key={i} className="flex items-start gap-2 text-sm">
                        <span className="mt-0.5">{ICONS[a.type]}</span>
                        <span className="text-white/85">{a.text}</span>
                      </li>
                    ))}
                  </ul>
                </div>
              </div>
            </div>
          </div>
        </div>
      </div>
    </section>
  );
}
