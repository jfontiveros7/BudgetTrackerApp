"use client";

import { useState } from "react";

export default function ContactForm() {
  const [form, setForm] = useState({
    name: "",
    email: "",
    company_size: "",
    plan_interest: "",
    message: "",
  });
  const [status, setStatus] = useState("");

  async function handleSubmit(e: React.FormEvent) {
    e.preventDefault();
    setStatus("Sending...");

    const res = await fetch("/api/contact", {
      method: "POST",
      headers: { "Content-Type": "application/json" },
      body: JSON.stringify(form),
    });

    if (res.ok) {
      setStatus("Message sent.");
      setForm({
        name: "",
        email: "",
        company_size: "",
        plan_interest: "",
        message: "",
      });
    } else {
      const data = await res.json();
      setStatus(data.error || "Something went wrong.");
    }
  }

  return (
    <form onSubmit={handleSubmit} className="rounded-2xl border border-white/10 bg-white/5 p-7 backdrop-blur-sm md:p-10">
      <div className="grid grid-cols-1 gap-5 md:grid-cols-2">
        <div>
          <label htmlFor="name" className="text-[10px] uppercase tracking-widest text-white/70">
            Name
          </label>
          <input
            id="name"
            type="text"
            placeholder="Avery Tan"
            value={form.name}
            onChange={(e) => setForm({ ...form, name: e.target.value })}
            className="mt-2 w-full rounded-2xl border border-white/20 bg-transparent px-4 py-3 text-white outline-none"
          />
        </div>
        <div>
          <label htmlFor="email" className="text-[10px] uppercase tracking-widest text-white/70">
            Work email
          </label>
          <input
            id="email"
            type="email"
            placeholder="you@company.com"
            value={form.email}
            onChange={(e) => setForm({ ...form, email: e.target.value })}
            className="mt-2 w-full rounded-2xl border border-white/20 bg-transparent px-4 py-3 text-white outline-none"
          />
        </div>
        <div>
          <label htmlFor="company_size" className="text-[10px] uppercase tracking-widest text-white/70">
            Company size
          </label>
          <select
            id="company_size"
            value={form.company_size}
            onChange={(e) => setForm({ ...form, company_size: e.target.value })}
            className="mt-2 w-full rounded-2xl border border-white/20 bg-transparent px-4 py-3 text-white outline-none"
          >
            <option value="">Select team size</option>
            <option value="1-5">1-5</option>
            <option value="6-15">6-15</option>
            <option value="16-50">16-50</option>
            <option value="50+">50+</option>
          </select>
        </div>
        <div>
          <label htmlFor="plan_interest" className="text-[10px] uppercase tracking-widest text-white/70">
            Plan interest
          </label>
          <select
            id="plan_interest"
            value={form.plan_interest}
            onChange={(e) => setForm({ ...form, plan_interest: e.target.value })}
            className="mt-2 w-full rounded-2xl border border-white/20 bg-transparent px-4 py-3 text-white outline-none"
          >
            <option value="">Pick a plan</option>
            <option value="Starter">Starter</option>
            <option value="Growth">Growth</option>
            <option value="Scale">Scale</option>
            <option value="Managed service">Managed service</option>
          </select>
        </div>
      </div>
      <div className="mt-5">
        <label htmlFor="message" className="text-[10px] uppercase tracking-widest text-white/70">
          What are you trying to control?
        </label>
        <textarea
          id="message"
          placeholder="Contractor spend, marketing drift, monthly close cadence..."
          value={form.message}
          onChange={(e) => setForm({ ...form, message: e.target.value })}
          className="mt-2 min-h-32 w-full rounded-2xl border border-white/20 bg-transparent px-4 py-3 text-white outline-none"
        />
      </div>
      <div className="mt-7 flex flex-wrap items-center justify-between gap-4">
        <div>
          <span className="text-xs text-white/50">We&apos;ll never share your details. Reply within one business day.</span>
          <p className="mt-2 text-sm text-white/70">{status}</p>
        </div>
        <button type="submit" className="rounded-full bg-[#0052FF] px-6 py-3 text-sm font-medium text-white">
          Send message
        </button>
      </div>
    </form>
  );
}
