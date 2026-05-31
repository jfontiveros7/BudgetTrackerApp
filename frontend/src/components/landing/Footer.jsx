import { ArrowUpRight } from "lucide-react";
import { TID } from "@/lib/testIds";

export default function Footer() {
  return (
    <footer className="bg-[#0A0A0B] text-white pt-24 pb-10">
      <div className="max-w-7xl mx-auto px-6 md:px-10">
        <div className="grid grid-cols-1 lg:grid-cols-12 gap-10 border-b border-white/10 pb-16">
          <div className="lg:col-span-7">
            <span className="font-mono-bt text-[11px] uppercase tracking-[0.22em] text-[#0052FF] font-semibold">
              Ready when you are
            </span>
            <h3 className="font-serif-display text-5xl md:text-7xl lg:text-[88px] leading-[0.95] mt-5">
              Turn budget visibility
              <br />
              into <span className="italic text-white/55">better decisions.</span>
            </h3>
            <div className="mt-9 flex flex-wrap gap-3">
              <a
                href="https://budget.konticode.com/checkout.php?plan=growth"
                target="_blank"
                rel="noopener noreferrer"
                data-testid={TID.footerCta}
                className="inline-flex items-center gap-2 bg-[#0052FF] text-white rounded-full px-6 py-3 font-medium hover:bg-[#0040C5] transition-all hover:-translate-y-0.5"
              >
                Start Growth — $10/mo <ArrowUpRight size={16} />
              </a>
              <a
                href="https://budget.konticode.com/pricing-sheet.php"
                target="_blank"
                rel="noopener noreferrer"
                className="inline-flex items-center gap-2 border border-white/20 rounded-full px-6 py-3 font-medium hover:bg-white/5 transition-colors"
              >
                Pricing sheet
              </a>
            </div>
          </div>

          <div className="lg:col-span-5 grid grid-cols-2 gap-8">
            <div>
              <div className="font-mono-bt text-[10px] uppercase tracking-widest text-white/40 mb-4">
                Product
              </div>
              <ul className="space-y-2.5 text-sm text-white/75">
                <li><a href="#features" className="hover:text-white">Features</a></li>
                <li><a href="#calculator" className="hover:text-white">ROI calculator</a></li>
                <li><a href="#pricing" className="hover:text-white">Pricing</a></li>
                <li>
                  <a
                    href="https://budget.konticode.com/demo-slideshow.php"
                    target="_blank"
                    rel="noopener noreferrer"
                    className="hover:text-white"
                  >
                    Product demo
                  </a>
                </li>
              </ul>
            </div>
            <div>
              <div className="font-mono-bt text-[10px] uppercase tracking-widest text-white/40 mb-4">
                Company
              </div>
              <ul className="space-y-2.5 text-sm text-white/75">
                <li>
                  <a
                    href="https://budget.konticode.com/managed/"
                    target="_blank"
                    rel="noopener noreferrer"
                    className="hover:text-white"
                  >
                    Managed service
                  </a>
                </li>
                <li><a href="mailto:sales@budgettrackerpro.com" className="hover:text-white">Contact sales</a></li>
                <li><a href="#faq" className="hover:text-white">FAQ</a></li>
              </ul>
            </div>
          </div>
        </div>

        <div className="pt-8 flex flex-wrap items-center justify-between gap-4 text-xs text-white/45">
          <div className="flex items-center gap-2">
            <span className="w-6 h-6 rounded-md bg-white/5 border border-white/10 flex items-center justify-center">
              <span className="block w-2 h-2 bg-[#0052FF] rounded-sm rotate-12" />
            </span>
            <span>© {new Date().getFullYear()} Budget Tracker · Konticode</span>
          </div>
          <div className="flex gap-6">
            <a href="https://budget.konticode.com/" target="_blank" rel="noopener noreferrer" className="hover:text-white">
              budget.konticode.com
            </a>
            <span>Built for lean operators.</span>
          </div>
        </div>
      </div>
    </footer>
  );
}
