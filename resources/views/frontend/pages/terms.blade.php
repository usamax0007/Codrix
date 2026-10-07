@php $pageKey = 'terms'; @endphp
@extends('frontend.layout.app')

@section('title', 'Terms of Service | Xcodrix')
@section('meta_description', 'Terms of Service for Xcodrix. Read our terms and conditions for using our software development services.')

@section('content')
<section class="pt-28 pb-20 lg:pt-36 lg:pb-28">
    <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8">
        <h1 class="text-4xl md:text-5xl font-extrabold mb-6">Terms of Service</h1>
        <p class="text-slate-400 mb-12">Last updated: {{ date('F j, Y') }}</p>

        <div class="prose prose-invert prose-slate max-w-none space-y-8 text-slate-300">
            <section>
                <h2 class="text-2xl font-bold text-white mb-4">Introduction</h2>
                <p>These Terms of Service ("Terms") govern your use of the Xcodrix website and services. By accessing our website or engaging our services, you agree to these Terms. If you do not agree, please do not use our website or services.</p>
            </section>

            <section>
                <h2 class="text-2xl font-bold text-white mb-4">Services</h2>
                <p>Xcodrix provides custom software development services, including AI voice agents, VoIP systems, WhatsApp automation, web applications, and related services. Specific deliverables, timelines, and pricing are outlined in separate project proposals and contracts.</p>
            </section>

            <section>
                <h2 class="text-2xl font-bold text-white mb-4">Project Proposals</h2>
                <p>After an initial consultation, we provide a written project proposal with scope, timeline, and fixed pricing. Work begins only after you accept the proposal and any required deposit is paid.</p>
            </section>

            <section>
                <h2 class="text-2xl font-bold text-white mb-4">Payment Terms</h2>
                <ul class="list-disc pl-6 space-y-2">
                    <li>Projects are typically billed with a deposit upfront and milestone payments or final payment upon completion</li>
                    <li>Usage costs (e.g., Twilio, AI API, hosting) are billed separately at cost</li>
                    <li>Invoices are due within 14 days unless otherwise agreed</li>
                    <li>Late payments may incur interest or suspension of services</li>
                </ul>
            </section>

            <section>
                <h2 class="text-2xl font-bold text-white mb-4">Intellectual Property</h2>
                <p>Upon full payment, you own all custom code and assets we create for your project. We retain the right to use general knowledge, techniques, and frameworks gained during the project. We may use anonymized case studies for marketing purposes.</p>
            </section>

            <section>
                <h2 class="text-2xl font-bold text-white mb-4">Confidentiality</h2>
                <p>We treat all client information as confidential. We sign NDAs upon request. You agree not to disclose any confidential information we share with you during the project.</p>
            </section>

            <section>
                <h2 class="text-2xl font-bold text-white mb-4">Warranties and Disclaimers</h2>
                <p>We provide services with reasonable skill and care. However, software is provided "as is" without warranties of any kind, express or implied. We do not guarantee that software will be error-free or meet your specific requirements beyond what is documented in the project proposal.</p>
            </section>

            <section>
                <h2 class="text-2xl font-bold text-white mb-4">Limitation of Liability</h2>
                <p>To the fullest extent permitted by law, Xcodrix is not liable for any indirect, incidental, special, or consequential damages arising from your use of our services. Our total liability is limited to the amount you paid us for the specific project.</p>
            </section>

            <section>
                <h2 class="text-2xl font-bold text-white mb-4">Call Recording Consent</h2>
                <p>If we build call recording or AI voice systems for you, you are responsible for obtaining proper consent from callers as required by applicable laws. Call recording laws vary by jurisdiction.</p>
            </section>

            <section>
                <h2 class="text-2xl font-bold text-white mb-4">Third-Party Services</h2>
                <p>Our services may integrate with third-party platforms (e.g., Twilio, OpenAI, AWS). Your use of those platforms is subject to their respective terms and conditions. We are not responsible for the availability, performance, or policies of third-party services.</p>
            </section>

            <section>
                <h2 class="text-2xl font-bold text-white mb-4">Termination</h2>
                <p>Either party may terminate a project with written notice. You are responsible for payment for all work completed up to the termination date. We retain the right to terminate services immediately if you breach these Terms.</p>
            </section>

            <section>
                <h2 class="text-2xl font-bold text-white mb-4">Governing Law</h2>
                <p>These Terms are governed by the laws of Pakistan. Any disputes will be resolved in the courts of Multan, Pakistan, or through arbitration as agreed.</p>
            </section>

            <section>
                <h2 class="text-2xl font-bold text-white mb-4">Changes to These Terms</h2>
                <p>We may update these Terms from time to time. We will notify you of significant changes by posting the new Terms on this page with an updated date.</p>
            </section>

            <section>
                <h2 class="text-2xl font-bold text-white mb-4">Contact Us</h2>
                <p>If you have questions about these Terms, please contact us at:</p>
                <p class="mt-4">
                    Email: <a href="mailto:{{ config('xcodrix.email') }}" class="text-xc-cyan hover:underline">{{ config('xcodrix.email') }}</a><br>
                    Address: Multan, Punjab, Pakistan
                </p>
            </section>
        </div>
    </div>
</section>
@endsection
