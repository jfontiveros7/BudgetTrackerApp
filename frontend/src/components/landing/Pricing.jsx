import { Check, Sparkles, ArrowUpRight } from "lucide-react";
import { TID } from "@/lib/testIds";

const PLANS = [
  {
    id: "starter",
    name: "Starter",
    tag: "01 / Try the core",
    price: "$5",
    period: "/ month",
    desc: "Low-risk plan for users who want dashboard visibility and monthly accountability.",
    features: [
      "Monthly budget health report",
      "One optimization pass per month",
      "Core dashboard access",
      "Limited alerts",
      "AI Coach not included",
    ],
    cta: "Start Starter",
    href: "https://budget.konticode.com/checkout.php?plan=starter",
    tid: TID.pricingStarter,
  },
  {
    id: "growth",
    name: "Growth",
    tag: "02 / Best value",
    price: "$10",
    period: "/ month",
    desc: "The main revenue tier — alerts, AI Coach, and a stronger operating rhythm.",
    features: [
      "Biweekly spend and variance reviews",
      "Alert tuning and threshold updates",
      "Monthly action plan",
      "Full dashboard alerts",
      "AI Coach visibility and chat",
    ],
    cta: "Start Growth",
    href: "https://budget.konticode.com/checkout.php?plan=growth",
    tid: TID.pricingGrowth,
    highlight: true,
  },
  {
    id: "scale",
    name: "Scale",
    tag: "03 / Premium support",
    price: "$19.99",
    period: "/ month",
    desc: "Weekly advisor check-ins, forecasting, and a stepping stone into managed services.",
    features: [
      "Weekly advisor check-ins",
      "Forecasting and scenario planning",
      "Priority support lane",
      "Custom workflow guidance",
      "Strongest path into managed services",
    ],
    cta: "Start Scale",
    href: "https://budget.konticode.com/checkout.php?plan=scale",
    tid: TID.pricingScale,
  },
];

export default function Pricing() {
  return (
    <section id="pricing" className="py-24 md:py-32 bg-[#F1EFEA]/60 border-y border-black/5">
      <div className="max-w-7xl mx-auto px-6 md:px-10">
        <div className="grid grid-cols-1 lg:grid-cols-12 gap-10 mb-14 items-end">
          <div className="lg:col-span-7">
            <span className="font-mono-bt text-[11px] uppercase tracking-[0.22em] text-[#0052FF] font-semibold">
              Pricing
            </span>
            <h2 className="font-serif-display text-4xl md:text-5xl lg:text-6xl mt-4 leading-[1.05]">
              Three buying paths.{" "}
              <span className="italic text-[#52525B]">One default everyone picks.</span>
            </h2>
          </div>
          <div className="lg:col-span-5">
            <p className="text-[#52525B] text-lg leading-relaxed">
              Growth is the strongest default — enough value to feel meaningful, still priced for
              easy self-serve purchase.
            </p>
          </div>
        </div>

        <div className="grid grid-cols-1 md:grid-cols-3 gap-6">
          {PLANS.map((p) => (
            <article
              key={p.id}
              className={`relative rounded-2xl p-8 md:p-9 flex flex-col transition-all hover:-translate-y-1 ${
                p.highlight
                  ? "bg-white border-2 border-[#0052FF] shadow-[0_24px_60px_-30px_rgba(0,82,255,0.45)] md:scale-[1.03]"
                  : "bg-white border border-black/5 hover:shadow-[0_18px_40px_-25px_rgba(0,0,0,0.18)]"
              }`}
            >
              {p.highlight && (
                <span className="absolute -top-3 left-8 inline-flex items-center gap-1.5 bg-[#0052FF] text-white text-[11px] font-medium rounded-full px-3 py-1">
                  <Sparkles size={12} /> Best value
                </span>
              )}
              <div className="font-mono-bt text-[10px] uppercase tracking-[0.22em] text-[#0052FF]">
                {p.tag}
              </div>
              <h3 className="font-serif-display text-3xl md:text-4xl mt-3">{p.name}</h3>
              <div className="mt-3 flex items-baseline gap-2">
                <span className="font-serif-display text-5xl">{p.price}</span>
                <span className="text-sm text-[#52525B]">{p.period}</span>
              </div>
              <p className="text-[#52525B] mt-4 text-sm leading-relaxed">{p.desc}</p>

              <ul className="mt-7 space-y-3 flex-1">
                {p.features.map((f) => (
                  <li key={f} className="flex items-start gap-2.5 text-sm">
                    <Check
                      size={16}
                      className={`mt-0.5 shrink-0 ${
                        p.highlight ? "text-[#0052FF]" : "text-[#0A0A0B]"
                      }`}
                    />
                    <span className="text-[#0A0A0B]/85">{f}</span>
                  </li>
                ))}
              </ul>

              <a
                href={p.href}
                target="_blank"
                rel="noopener noreferrer"
                data-testid={p.tid}
                className={`mt-8 inline-flex items-center justify-center gap-2 rounded-full px-6 py-3 font-medium transition-all hover:-translate-y-0.5 ${
                  p.highlight
                    ? "bg-[#0052FF] text-white hover:bg-[#0040C5]"
                    : "bg-[#0A0A0B] text-white hover:bg-[#27272A]"
                }`}
              >
                {p.cta} <ArrowUpRight size={16} />
              </a>
            </article>
          ))}
        </div>
      </div>
    </section>
  );
}
