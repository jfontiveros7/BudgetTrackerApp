const QUOTES = [
  {
    quote:
      "We cut contractor overruns 23% in our first two months. The alerts pay for the plan ten times over.",
    name: "Maya Lindgren",
    role: "Founder · Northwind Studio",
    avatar:
      "https://images.unsplash.com/photo-1632255657991-ce622acebecd?crop=entropy&cs=srgb&fm=jpg&ixid=M3w3NDQ2NDN8MHwxfHNlYXJjaHw0fHxwcm9mZXNzaW9uYWwlMjBvZmZpY2UlMjBwb3J0cmFpdHxlbnwwfHx8fDE3ODAyMzMwODJ8MA&ixlib=rb-4.1.0&q=85",
  },
  {
    quote:
      "Finally a budget tool that nudges before the damage is done. The AI Coach feels like a junior controller.",
    name: "Daniel Park",
    role: "COO · Helios Labs",
    avatar:
      "https://images.unsplash.com/photo-1632255658477-9ac8f313ea41?crop=entropy&cs=srgb&fm=jpg&ixid=M3w3NDQ2NDN8MHwxfHNlYXJjaHwzfHxwcm9mZXNzaW9uYWwlMjBvZmZpY2UlMjBwb3J0cmFpdHxlbnwwfHx8fDE3ODAyMzMwODJ8MA&ixlib=rb-4.1.0&q=85",
  },
  {
    quote:
      "Onboarding took ten minutes. By month two we'd reclassified a year of expenses and reset our category caps.",
    name: "Priya Ahuja",
    role: "Ops Lead · Foundry Seven",
    avatar:
      "https://images.unsplash.com/photo-1580894732444-8ecded7900cd?crop=entropy&cs=srgb&fm=jpg&ixid=M3w3NDQ2NDN8MHwxfHNlYXJjaHwxfHxwcm9mZXNzaW9uYWwlMjBvZmZpY2UlMjBwb3J0cmFpdHxlbnwwfHx8fDE3ODAyMzMwODJ8MA&ixlib=rb-4.1.0&q=85",
  },
];

export default function Testimonials() {
  return (
    <section className="py-24 md:py-32 bg-[#F9F8F6]">
      <div className="max-w-7xl mx-auto px-6 md:px-10">
        <div className="grid grid-cols-1 lg:grid-cols-12 gap-10 mb-14 items-end">
          <div className="lg:col-span-7">
            <span className="font-mono-bt text-[11px] uppercase tracking-[0.22em] text-[#0052FF] font-semibold">
              Operators on Budget Tracker
            </span>
            <h2 className="font-serif-display text-4xl md:text-5xl lg:text-6xl mt-4 leading-[1.05]">
              Quiet wins.{" "}
              <span className="italic text-[#52525B]">Measurable months.</span>
            </h2>
          </div>
          <div className="lg:col-span-5">
            <p className="text-[#52525B] text-lg leading-relaxed">
              Real notes from founders, ops leads, and finance generalists who got their categories
              under control.
            </p>
          </div>
        </div>

        <div className="grid grid-cols-1 md:grid-cols-3 gap-6">
          {QUOTES.map((q, i) => (
            <figure
              key={i}
              className={`relative rounded-2xl bg-white border border-black/5 p-8 md:p-10 hover:-translate-y-1 hover:shadow-[0_18px_40px_-25px_rgba(0,0,0,0.18)] transition-all ${
                i === 1 ? "md:translate-y-6" : ""
              }`}
            >
              <span
                aria-hidden
                className="font-serif-display absolute top-3 right-6 text-[100px] leading-none text-[#E0E7FF] select-none"
              >
                “
              </span>
              <blockquote className="relative font-serif-display text-2xl leading-snug">
                {q.quote}
              </blockquote>
              <figcaption className="mt-8 flex items-center gap-3 border-t border-black/5 pt-5">
                <img
                  src={q.avatar}
                  alt={q.name}
                  className="w-10 h-10 rounded-full object-cover ring-1 ring-black/10"
                  loading="lazy"
                />
                <div>
                  <div className="text-sm font-medium">{q.name}</div>
                  <div className="text-xs text-[#52525B]">{q.role}</div>
                </div>
              </figcaption>
            </figure>
          ))}
        </div>
      </div>
    </section>
  );
}
