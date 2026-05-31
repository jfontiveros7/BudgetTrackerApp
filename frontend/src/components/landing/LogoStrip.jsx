const LOGOS = [
  "Northwind Studio",
  "Helios Labs",
  "Atlas & Co.",
  "Pinepoint",
  "Foundry Seven",
  "Mercatus",
  "Ember Holdings",
  "Quintile",
];

export default function LogoStrip() {
  const all = [...LOGOS, ...LOGOS];
  return (
    <section className="border-y border-black/5 bg-[#F1EFEA]/60 py-6 overflow-hidden">
      <div className="max-w-7xl mx-auto px-6 md:px-10">
        <div className="flex items-center gap-4">
          <span className="font-mono-bt text-[10px] uppercase tracking-[0.22em] text-[#52525B] shrink-0">
            Trusted by lean operators
          </span>
          <div className="relative overflow-hidden flex-1">
            <div className="bt-marquee-track flex gap-12 whitespace-nowrap">
              {all.map((l, i) => (
                <span
                  key={i}
                  className="font-serif-display text-xl md:text-2xl text-[#0A0A0B]/55 hover:text-[#0A0A0B] transition-colors"
                >
                  {l}
                </span>
              ))}
            </div>
            <div className="absolute inset-y-0 left-0 w-16 bg-gradient-to-r from-[#F1EFEA] to-transparent pointer-events-none" />
            <div className="absolute inset-y-0 right-0 w-16 bg-gradient-to-l from-[#F1EFEA] to-transparent pointer-events-none" />
          </div>
        </div>
      </div>
    </section>
  );
}
