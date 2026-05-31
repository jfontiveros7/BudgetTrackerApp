const CATS = [
  { name: "Software", spent: 1240, limit: 1500, color: "#0052FF" },
  { name: "Marketing", spent: 2780, limit: 3000, color: "#0A0A0B" },
  { name: "Travel", spent: 540, limit: 1200, color: "#0052FF" },
  { name: "Contractors", spent: 4220, limit: 4000, color: "#DC2626" },
];

const BARS = [38, 52, 44, 61, 48, 72, 59, 66, 80, 71, 84, 92];

export default function DashboardMock() {
  return (
    <div className="relative rounded-2xl bg-white border border-black/10 shadow-[0_30px_80px_-30px_rgba(0,0,0,0.25)] p-5 md:p-6">
      {/* Top bar */}
      <div className="flex items-center justify-between mb-5">
        <div className="flex items-center gap-2">
          <div className="flex gap-1.5">
            <span className="w-2.5 h-2.5 rounded-full bg-[#F1EFEA] border border-black/10" />
            <span className="w-2.5 h-2.5 rounded-full bg-[#F1EFEA] border border-black/10" />
            <span className="w-2.5 h-2.5 rounded-full bg-[#F1EFEA] border border-black/10" />
          </div>
          <span className="font-mono-bt text-[10px] uppercase tracking-widest text-[#52525B] ml-2">
            budgettracker / overview
          </span>
        </div>
        <span className="font-mono-bt text-[10px] text-[#52525B]">Dec · 2025</span>
      </div>

      {/* Headline KPI */}
      <div className="flex items-end justify-between border-b border-black/5 pb-5">
        <div>
          <div className="font-mono-bt text-[10px] uppercase tracking-widest text-[#52525B]">
            Monthly spend
          </div>
          <div className="font-serif-display text-3xl md:text-4xl mt-1">$8,780</div>
          <div className="text-xs text-[#52525B] mt-1">
            of <span className="text-[#0A0A0B] font-medium">$9,700</span> budget
          </div>
        </div>
        <div className="text-right">
          <div className="font-mono-bt text-[10px] uppercase tracking-widest text-[#0052FF]">
            On track
          </div>
          <div className="text-xs text-[#52525B] mt-1">9.5% headroom</div>
        </div>
      </div>

      {/* Chart */}
      <div className="mt-5">
        <div className="flex items-end gap-1.5 h-24">
          {BARS.map((h, i) => (
            <div
              key={i}
              className="flex-1 rounded-sm bt-bar"
              style={{
                height: `${h}%`,
                background: i === BARS.length - 1 ? "#0052FF" : "#0A0A0B",
                opacity: i === BARS.length - 1 ? 1 : 0.85 - i * 0.04,
                animationDelay: `${i * 70}ms`,
              }}
            />
          ))}
        </div>
        <div className="flex justify-between mt-2 font-mono-bt text-[9px] text-[#52525B]">
          <span>Jan</span><span>Apr</span><span>Jul</span><span>Oct</span><span>Dec</span>
        </div>
      </div>

      {/* Categories */}
      <div className="mt-5 space-y-3">
        {CATS.map((c) => {
          const pct = Math.min(100, (c.spent / c.limit) * 100);
          const over = c.spent > c.limit;
          return (
            <div key={c.name}>
              <div className="flex items-center justify-between text-xs">
                <span className="font-medium">{c.name}</span>
                <span className={`font-mono-bt ${over ? "text-[#DC2626]" : "text-[#52525B]"}`}>
                  ${c.spent.toLocaleString()} / ${c.limit.toLocaleString()}
                </span>
              </div>
              <div className="h-1.5 mt-1.5 rounded-full bg-[#F1EFEA] overflow-hidden">
                <div
                  className="h-full rounded-full transition-all"
                  style={{
                    width: `${pct}%`,
                    background: over ? "#DC2626" : c.color,
                  }}
                />
              </div>
            </div>
          );
        })}
      </div>
    </div>
  );
}
