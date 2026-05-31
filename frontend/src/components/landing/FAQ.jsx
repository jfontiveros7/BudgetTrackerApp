import {
  Accordion,
  AccordionContent,
  AccordionItem,
  AccordionTrigger,
} from "@/components/ui/accordion";
import { TID } from "@/lib/testIds";

const FAQS = [
  {
    q: "Which plan should most people buy?",
    a: "Growth is the strongest default. It includes alerts, AI Coach access, and enough ongoing value to justify a monthly subscription for solo operators and small teams.",
  },
  {
    q: "What is Scale for?",
    a: "Scale is for customers who need faster support, planning help, or a stepping stone into managed finance operations. Weekly check-ins replace the biweekly cadence on Growth.",
  },
  {
    q: "Can I sell both software and services?",
    a: "Yes. The funnel supports low-ticket self-serve subscriptions and a premium service upsell from the same site — managed clients usually graduate from Growth or Scale.",
  },
  {
    q: "What do I need before taking payments?",
    a: "Add your Stripe Payment Links in config/payments.local.php or set the matching BT_STRIPE_*_LINK environment variables. Checkout works the moment links are configured.",
  },
  {
    q: "Can I switch plans later?",
    a: "Yes — upgrades and downgrades take effect on the next billing cycle. No re-onboarding, no lost data.",
  },
  {
    q: "Is there a free trial?",
    a: "Starter is intentionally priced at $5/mo as the trial step. It's lower friction than a free trial and proves the workflow before you commit to Growth.",
  },
];

export default function FAQ() {
  return (
    <section id="faq" className="py-24 md:py-32 bg-[#F1EFEA]/60 border-y border-black/5">
      <div className="max-w-3xl mx-auto px-6 md:px-10">
        <div className="text-center mb-12">
          <span className="font-mono-bt text-[11px] uppercase tracking-[0.22em] text-[#0052FF] font-semibold">
            FAQ
          </span>
          <h2 className="font-serif-display text-4xl md:text-5xl lg:text-6xl mt-4 leading-[1.05]">
            Questions, <span className="italic text-[#52525B]">answered.</span>
          </h2>
        </div>

        <Accordion type="single" collapsible className="space-y-3">
          {FAQS.map((item, i) => (
            <AccordionItem
              key={i}
              value={`item-${i}`}
              data-testid={TID.faqItem(i)}
              className="bg-white border border-black/5 rounded-2xl px-6 data-[state=open]:shadow-[0_18px_40px_-25px_rgba(0,0,0,0.15)] transition-shadow"
            >
              <AccordionTrigger className="py-5 text-left font-medium text-base md:text-lg hover:no-underline">
                {item.q}
              </AccordionTrigger>
              <AccordionContent className="text-[#52525B] leading-relaxed pb-5">
                {item.a}
              </AccordionContent>
            </AccordionItem>
          ))}
        </Accordion>
      </div>
    </section>
  );
}
