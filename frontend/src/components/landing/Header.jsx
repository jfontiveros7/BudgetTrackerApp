import { useEffect, useState } from "react";
import { TID } from "@/lib/testIds";

const NAV = [
  { id: "features", label: "Features", tid: TID.navFeatures },
  { id: "calculator", label: "Calculator", tid: TID.navCalculator },
  { id: "pricing", label: "Pricing", tid: TID.navPricing },
  { id: "faq", label: "FAQ", tid: TID.navFaq },
];

export default function Header() {
  const [scrolled, setScrolled] = useState(false);
  useEffect(() => {
    const onScroll = () => setScrolled(window.scrollY > 8);
    onScroll();
    window.addEventListener("scroll", onScroll, { passive: true });
    return () => window.removeEventListener("scroll", onScroll);
  }, []);

  const scrollTo = (id) => (e) => {
    e.preventDefault();
    document.getElementById(id)?.scrollIntoView({ behavior: "smooth", block: "start" });
  };

  return (
    <header
      className={`fixed top-0 left-0 right-0 z-50 transition-all duration-300 ${
        scrolled ? "backdrop-blur-xl bg-[#F9F8F6]/75 border-b border-black/5" : "bg-transparent"
      }`}
    >
      <div className="max-w-7xl mx-auto px-6 md:px-10 h-16 flex items-center justify-between">
        <a href="#top" data-testid={TID.navLogo} className="flex items-center gap-2 group">
          <span className="w-7 h-7 rounded-md bg-[#0A0A0B] flex items-center justify-center">
            <span className="block w-2.5 h-2.5 bg-[#0052FF] rounded-sm rotate-12 group-hover:rotate-45 transition-transform" />
          </span>
          <span className="font-serif-display text-xl tracking-tight">Budget Tracker</span>
        </a>

        <nav className="hidden md:flex items-center gap-9 text-sm text-[#52525B]">
          {NAV.map((n) => (
            <a
              key={n.id}
              href={`#${n.id}`}
              data-testid={n.tid}
              onClick={scrollTo(n.id)}
              className="hover:text-[#0A0A0B] transition-colors"
            >
              {n.label}
            </a>
          ))}
        </nav>

        <a
          href="https://budget.konticode.com/checkout.php?plan=growth"
          target="_blank"
          rel="noopener noreferrer"
          data-testid={TID.navStart}
          className="hidden sm:inline-flex items-center gap-2 bg-[#0A0A0B] text-white text-sm font-medium rounded-full px-5 py-2.5 hover:bg-[#27272A] transition-all hover:-translate-y-0.5"
        >
          Start Growth
          <span aria-hidden>→</span>
        </a>
      </div>
    </header>
  );
}
