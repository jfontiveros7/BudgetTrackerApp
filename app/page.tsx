import ContactForm from "./components/ContactForm";

export default function Page() {
  return (
    <main className="min-h-screen bg-[#0A0A0B] px-6 py-20 text-white md:px-10">
      <section className="mx-auto grid max-w-7xl gap-8 lg:grid-cols-12">
        <div className="lg:col-span-5">
          <p className="text-[11px] font-semibold uppercase tracking-[0.22em] text-[#7aa2ff]">
            Managed service
          </p>
          <h1 className="mt-4 text-5xl leading-[0.96] md:text-6xl">
            Need a second pair of eyes?
          </h1>
          <p className="mt-6 max-w-md text-lg leading-relaxed text-white/70">
            Tell us about your team. We&apos;ll point you to the right plan or open a
            managed service conversation if that&apos;s a better fit.
          </p>
          <div className="mt-10 space-y-3 text-sm text-white/70">
            <div>One business day response</div>
            <div>No sales pressure - straight recommendation</div>
            <div>Honest path from self-serve to managed</div>
          </div>
        </div>
        <div className="lg:col-span-7">
          <ContactForm />
        </div>
      </section>
    </main>
  );
}
