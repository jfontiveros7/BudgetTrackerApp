import { useState } from "react";
import axios from "axios";
import { toast } from "sonner";
import { Input } from "@/components/ui/input";
import { Textarea } from "@/components/ui/textarea";
import { Label } from "@/components/ui/label";
import {
  Select,
  SelectContent,
  SelectItem,
  SelectTrigger,
  SelectValue,
} from "@/components/ui/select";
import { TID } from "@/lib/testIds";
import { ArrowUpRight, CheckCircle2 } from "lucide-react";

const API = `${process.env.REACT_APP_BACKEND_URL}/api`;

const SIZES = ["Just me", "2–10", "11–50", "51–200", "200+"];
const PLANS = ["Starter", "Growth", "Scale", "Managed Service", "Not sure yet"];

export default function LeadCapture() {
  const [form, setForm] = useState({
    name: "",
    email: "",
    company_size: "",
    plan_interest: "",
    message: "",
  });
  const [loading, setLoading] = useState(false);
  const [done, setDone] = useState(false);

  const update = (k) => (e) =>
    setForm((f) => ({ ...f, [k]: e?.target ? e.target.value : e }));

  const submit = async (e) => {
    e.preventDefault();
    if (!form.name.trim() || !form.email.trim()) {
      toast.error("Name and email are required.");
      return;
    }
    setLoading(true);
    try {
      await axios.post(`${API}/leads`, {
        name: form.name.trim(),
        email: form.email.trim(),
        company_size: form.company_size || null,
        plan_interest: form.plan_interest || null,
        message: form.message.trim() || null,
      });
      setDone(true);
      toast.success("Thanks — we'll be in touch within one business day.");
    } catch (err) {
      const detail = err?.response?.data?.detail || "Something went wrong. Please try again.";
      toast.error(typeof detail === "string" ? detail : "Please check the form and retry.");
    } finally {
      setLoading(false);
    }
  };

  return (
    <section className="py-24 md:py-32">
      <div className="max-w-7xl mx-auto px-6 md:px-10">
        <div className="relative overflow-hidden rounded-3xl bg-[#0A0A0B] text-white">
          <div
            aria-hidden
            className="absolute inset-0 opacity-40 pointer-events-none"
            style={{
              backgroundImage:
                "radial-gradient(circle at 85% 15%, rgba(0,82,255,0.35), transparent 50%)",
            }}
          />
          <div className="relative grid grid-cols-1 lg:grid-cols-12 gap-10 p-10 md:p-16">
            <div className="lg:col-span-5">
              <span className="font-mono-bt text-[11px] uppercase tracking-[0.22em] text-[#0052FF] font-semibold">
                Get in touch
              </span>
              <h2 className="font-serif-display text-4xl md:text-5xl lg:text-6xl mt-5 leading-[1.05]">
                Have a budget that{" "}
                <span className="italic text-white/55">needs a second pair of eyes?</span>
              </h2>
              <p className="text-white/70 mt-6 text-lg leading-relaxed max-w-md">
                Tell us about your team. We'll point you to the right plan — or open a managed
                service conversation if that's a better fit.
              </p>

              <div className="mt-10 space-y-3 text-sm text-white/70">
                <div className="flex items-center gap-2">
                  <CheckCircle2 size={16} className="text-[#0052FF]" />
                  One business day response
                </div>
                <div className="flex items-center gap-2">
                  <CheckCircle2 size={16} className="text-[#0052FF]" />
                  No sales pressure — straight recommendation
                </div>
                <div className="flex items-center gap-2">
                  <CheckCircle2 size={16} className="text-[#0052FF]" />
                  Honest path from self-serve to managed
                </div>
              </div>
            </div>

            <div className="lg:col-span-7">
              {done ? (
                <div className="bg-white/5 border border-white/10 rounded-2xl p-10 h-full flex flex-col items-start justify-center">
                  <CheckCircle2 size={36} className="text-[#0052FF]" />
                  <h3 className="font-serif-display text-3xl mt-5">You're on the list.</h3>
                  <p className="text-white/70 mt-3 max-w-md">
                    We'll be in touch within one business day. In the meantime, you can start
                    Growth instantly — most teams do.
                  </p>
                  <a
                    href="https://budget.konticode.com/checkout.php?plan=growth"
                    target="_blank"
                    rel="noopener noreferrer"
                    className="mt-7 inline-flex items-center gap-2 bg-[#0052FF] text-white rounded-full px-6 py-3 font-medium hover:bg-[#0040C5] transition-all hover:-translate-y-0.5"
                  >
                    Start Growth now <ArrowUpRight size={16} />
                  </a>
                </div>
              ) : (
                <form
                  onSubmit={submit}
                  className="bg-white/5 border border-white/10 rounded-2xl p-7 md:p-10 backdrop-blur-sm"
                >
                  <div className="grid grid-cols-1 md:grid-cols-2 gap-5">
                    <div>
                      <Label htmlFor="lead-name-input" className="text-white/70 font-mono-bt text-[10px] uppercase tracking-widest">
                        Name
                      </Label>
                      <Input
                        id="lead-name-input"
                        data-testid={TID.leadName}
                        value={form.name}
                        onChange={update("name")}
                        placeholder="Avery Tan"
                        required
                        className="mt-2 bg-transparent border-white/20 text-white placeholder:text-white/30 focus-visible:ring-[#0052FF] h-11"
                      />
                    </div>
                    <div>
                      <Label htmlFor="lead-email-input" className="text-white/70 font-mono-bt text-[10px] uppercase tracking-widest">
                        Work email
                      </Label>
                      <Input
                        id="lead-email-input"
                        data-testid={TID.leadEmail}
                        type="email"
                        value={form.email}
                        onChange={update("email")}
                        placeholder="you@company.com"
                        required
                        className="mt-2 bg-transparent border-white/20 text-white placeholder:text-white/30 focus-visible:ring-[#0052FF] h-11"
                      />
                    </div>
                    <div>
                      <Label className="text-white/70 font-mono-bt text-[10px] uppercase tracking-widest">
                        Company size
                      </Label>
                      <Select
                        value={form.company_size}
                        onValueChange={(v) => setForm((f) => ({ ...f, company_size: v }))}
                      >
                        <SelectTrigger
                          data-testid={TID.leadCompanySize}
                          className="mt-2 bg-transparent border-white/20 text-white focus:ring-[#0052FF] h-11"
                        >
                          <SelectValue placeholder="Select team size" />
                        </SelectTrigger>
                        <SelectContent>
                          {SIZES.map((s) => (
                            <SelectItem key={s} value={s}>
                              {s}
                            </SelectItem>
                          ))}
                        </SelectContent>
                      </Select>
                    </div>
                    <div>
                      <Label className="text-white/70 font-mono-bt text-[10px] uppercase tracking-widest">
                        Plan interest
                      </Label>
                      <Select
                        value={form.plan_interest}
                        onValueChange={(v) => setForm((f) => ({ ...f, plan_interest: v }))}
                      >
                        <SelectTrigger
                          data-testid={TID.leadPlan}
                          className="mt-2 bg-transparent border-white/20 text-white focus:ring-[#0052FF] h-11"
                        >
                          <SelectValue placeholder="Pick a plan" />
                        </SelectTrigger>
                        <SelectContent>
                          {PLANS.map((p) => (
                            <SelectItem key={p} value={p}>
                              {p}
                            </SelectItem>
                          ))}
                        </SelectContent>
                      </Select>
                    </div>
                  </div>

                  <div className="mt-5">
                    <Label htmlFor="lead-message-input" className="text-white/70 font-mono-bt text-[10px] uppercase tracking-widest">
                      What are you trying to control?
                    </Label>
                    <Textarea
                      id="lead-message-input"
                      data-testid={TID.leadMessage}
                      value={form.message}
                      onChange={update("message")}
                      placeholder="Contractor spend, marketing drift, monthly close cadence…"
                      rows={4}
                      className="mt-2 bg-transparent border-white/20 text-white placeholder:text-white/30 focus-visible:ring-[#0052FF]"
                    />
                  </div>

                  <div className="mt-7 flex flex-wrap items-center justify-between gap-4">
                    <span className="text-xs text-white/50">
                      We'll never share your details. Reply within one business day.
                    </span>
                    <button
                      type="submit"
                      disabled={loading}
                      data-testid={TID.leadSubmit}
                      className="inline-flex items-center gap-2 bg-[#0052FF] text-white rounded-full px-6 py-3 font-medium hover:bg-[#0040C5] transition-all hover:-translate-y-0.5 disabled:opacity-60 disabled:hover:translate-y-0"
                    >
                      {loading ? "Sending…" : "Send message"} <ArrowUpRight size={16} />
                    </button>
                  </div>
                </form>
              )}
            </div>
          </div>
        </div>
      </div>
    </section>
  );
}
