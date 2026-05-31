import Header from "@/components/landing/Header";
import Hero from "@/components/landing/Hero";
import LogoStrip from "@/components/landing/LogoStrip";
import Features from "@/components/landing/Features";
import InteractiveDemo from "@/components/landing/InteractiveDemo";
import ROICalculator from "@/components/landing/ROICalculator";
import Pricing from "@/components/landing/Pricing";
import ManagedService from "@/components/landing/ManagedService";
import Testimonials from "@/components/landing/Testimonials";
import LeadCapture from "@/components/landing/LeadCapture";
import FAQ from "@/components/landing/FAQ";
import Footer from "@/components/landing/Footer";

export default function Landing() {
  return (
    <main className="min-h-screen bg-[#F9F8F6] text-[#0A0A0B]">
      <Header />
      <Hero />
      <LogoStrip />
      <Features />
      <InteractiveDemo />
      <ROICalculator />
      <Pricing />
      <ManagedService />
      <Testimonials />
      <LeadCapture />
      <FAQ />
      <Footer />
    </main>
  );
}
