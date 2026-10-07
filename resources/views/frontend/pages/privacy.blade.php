@php $pageKey = 'privacy'; @endphp
@extends('frontend.layout.app')

@section('title', 'Privacy Policy | Xcodrix')
@section('meta_description', 'Privacy policy for Xcodrix. Learn how we collect, use, and protect your personal information.')

@section('content')
<section class="pt-28 pb-20 lg:pt-36 lg:pb-28">
    <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8">
        <h1 class="text-4xl md:text-5xl font-extrabold mb-6">Privacy Policy</h1>
        <p class="text-slate-400 mb-12">Last updated: {{ date('F j, Y') }}</p>

        <div class="prose prose-invert prose-slate max-w-none space-y-8 text-slate-300">
            <section>
                <h2 class="text-2xl font-bold text-white mb-4">Introduction</h2>
                <p>Xcodrix ("we", "us", or "our") respects your privacy and is committed to protecting your personal information. This Privacy Policy explains how we collect, use, and safeguard your data when you visit our website or use our services.</p>
            </section>

            <section>
                <h2 class="text-2xl font-bold text-white mb-4">Information We Collect</h2>
                <p>We collect information you provide directly to us, including:</p>
                <ul class="list-disc pl-6 space-y-2">
                    <li>Name, email address, phone number, and company name when you contact us or book a call</li>
                    <li>Project details and requirements you share during consultations</li>
                    <li>Usage data and analytics when you visit our website</li>
                </ul>
            </section>

            <section>
                <h2 class="text-2xl font-bold text-white mb-4">How We Use Your Information</h2>
                <p>We use the information we collect to:</p>
                <ul class="list-disc pl-6 space-y-2">
                    <li>Respond to your inquiries and provide project proposals</li>
                    <li>Communicate with you about our services</li>
                    <li>Improve our website and services</li>
                    <li>Comply with legal obligations</li>
                </ul>
            </section>

            <section>
                <h2 class="text-2xl font-bold text-white mb-4">Call Recording</h2>
                <p>If we build voice AI or VoIP systems for you, call recordings may be created as part of the service. We will inform you when call recording is in use and obtain any necessary consent as required by applicable laws. Call recording policies vary by jurisdiction (one-party or two-party consent).</p>
            </section>

            <section>
                <h2 class="text-2xl font-bold text-white mb-4">Data Sharing and Disclosure</h2>
                <p>We do not sell your personal information. We may share your information with:</p>
                <ul class="list-disc pl-6 space-y-2">
                    <li>Service providers who assist us in operating our business (e.g., hosting, email)</li>
                    <li>Professional advisors (e.g., lawyers, accountants) when necessary</li>
                    <li>Law enforcement or government authorities when required by law</li>
                </ul>
            </section>

            <section>
                <h2 class="text-2xl font-bold text-white mb-4">Data Security</h2>
                <p>We implement reasonable security measures to protect your information. However, no method of transmission over the internet is 100% secure, and we cannot guarantee absolute security.</p>
            </section>

            <section>
                <h2 class="text-2xl font-bold text-white mb-4">Your Rights</h2>
                <p>Depending on your location, you may have the right to:</p>
                <ul class="list-disc pl-6 space-y-2">
                    <li>Access, correct, or delete your personal information</li>
                    <li>Object to or restrict certain processing activities</li>
                    <li>Request data portability</li>
                    <li>Withdraw consent where we rely on it</li>
                </ul>
                <p class="mt-4">To exercise these rights, contact us at {{ config('xcodrix.email') }}.</p>
            </section>

            <section>
                <h2 class="text-2xl font-bold text-white mb-4">International Data Transfers</h2>
                <p>Your information may be transferred to and processed in Pakistan or other countries where we or our service providers operate. We take steps to ensure your data is protected in accordance with this Privacy Policy.</p>
            </section>

            <section>
                <h2 class="text-2xl font-bold text-white mb-4">Changes to This Policy</h2>
                <p>We may update this Privacy Policy from time to time. We will notify you of significant changes by posting the new policy on this page with an updated date.</p>
            </section>

            <section>
                <h2 class="text-2xl font-bold text-white mb-4">Contact Us</h2>
                <p>If you have questions about this Privacy Policy, please contact us at:</p>
                <p class="mt-4">
                    Email: <a href="mailto:{{ config('xcodrix.email') }}" class="text-xc-cyan hover:underline">{{ config('xcodrix.email') }}</a><br>
                    Address: Multan, Punjab, Pakistan
                </p>
            </section>
        </div>
    </div>
</section>
@endsection
