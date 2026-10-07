<?php

return [
    'name' => 'Xcodrix',
    'domain' => config('app.url'),
    'email' => 'info@xcoderix.com',

    'social' => [
        'linkedin' => 'https://linkedin.com/company/xcodrix',
        'linkedin_founder' => 'https://www.linkedin.com/in/usama-tahir-0508661a9',
    ],

    'services' => [
        [
            'slug' => 'ai-voice-agents',
            'title' => 'AI Voice Agents & Receptionists',
            'icon' => 'ai',
            'popular' => true,
            'summary' => 'AI voice agents that answer calls 24/7, qualify leads, book appointments, and transfer to your team.',
            'what' => 'Xcodrix builds AI receptionists and voice agents that handle customer calls naturally. Answer every call, collect information, book appointments, and route calls to the right person, all while sounding human.',
            'benefits' => ['Never miss a customer call again', 'Qualify and route leads automatically', 'Answer common questions 24/7', 'Book appointments without human intervention'],
            'technologies' => ['Retell', 'Vapi', 'ElevenLabs', 'OpenAI', 'Twilio', 'LiveKit'],
            'why' => 'Voice AI is transforming how startups handle calls. We build reliable, natural-sounding agents that work on your existing phone number and integrate with your CRM.',
        ],
        [
            'slug' => 'voip-call-center',
            'title' => 'VoIP & Call Center Systems',
            'icon' => 'twilio',
            'popular' => true,
            'summary' => 'Custom VoIP platforms, IVR systems, call tracking, and PBX solutions built on Twilio and SignalWire.',
            'what' => 'We build VoIP and call center systems from the ground up. IVR menus, call routing, call recording, real-time dashboards, CRM integration, and number management on Twilio, SignalWire, or FreeSWITCH.',
            'benefits' => ['Track which calls came from which ads', 'Route calls based on caller data or time of day', 'Record and analyze every conversation', 'Scale to thousands of simultaneous calls'],
            'technologies' => ['Twilio', 'SignalWire', 'FreeSWITCH', 'Laravel', 'WebSockets', 'Redis'],
            'why' => 'Xcodrix has years of experience building VoIP systems for call centers, lead tracking, and customer support. We know the Twilio and SignalWire platforms inside and out.',
        ],
        [
            'slug' => 'whatsapp-sms-automation',
            'title' => 'WhatsApp & SMS Automation',
            'icon' => 'mobile',
            'popular' => true,
            'summary' => 'WhatsApp Business API, SMS workflows, two-way messaging, and automated follow-ups.',
            'what' => 'Automated WhatsApp and SMS messaging for appointments, reminders, support, marketing, and two-factor authentication. Send messages, receive replies, and trigger workflows based on customer responses.',
            'benefits' => ['Reach customers where they already are', 'Automate appointment reminders and follow-ups', 'Two-way conversations, not just broadcasts', 'Integrate with your CRM and calendar'],
            'technologies' => ['Twilio', 'WhatsApp Business API', 'Laravel', 'Redis', 'Webhooks'],
            'why' => 'WhatsApp has 80%+ open rates. We help you use it legally and effectively for customer communication.',
        ],
        [
            'slug' => 'custom-saas-development',
            'title' => 'Custom Software & SaaS',
            'icon' => 'saas',
            'popular' => false,
            'summary' => 'Full-stack web apps, APIs, dashboards, and SaaS platforms built with Laravel, Vue.js, and modern PHP.',
            'what' => 'When you need custom software beyond voice and messaging, Xcodrix builds complete web applications, SaaS products, APIs, admin panels, and integrations using Laravel, Vue.js, and Nuxt.js.',
            'benefits' => ['Full-stack capability: backend, frontend, database, DevOps', 'Clean, maintainable code you can build on', 'API-first architecture for integrations', 'Designed to scale from MVP to enterprise'],
            'technologies' => ['Laravel', 'PHP', 'Vue.js', 'Nuxt.js', 'Tailwind CSS', 'PostgreSQL', 'Redis', 'AWS'],
            'why' => 'Laravel and Vue.js are our core tools. We follow best practices and ship reliable, well-tested code.',
        ],
    ],

    'why_choose_us' => [
        ['title' => 'Voice & VoIP Expertise', 'description' => 'Years of experience building Twilio, SignalWire, and AI voice systems for call tracking, IVR, and automated receptionists.', 'icon' => 'team'],
        ['title' => 'Transparent Process', 'description' => 'Weekly demos, clear timelines, and direct communication. You always know where your project stands.', 'icon' => 'process'],
        ['title' => 'Realistic Timelines', 'description' => 'We commit to realistic deadlines based on the actual scope, not what sounds good in a pitch.', 'icon' => 'delivery'],
        ['title' => 'Full-Stack Capability', 'description' => 'Backend, frontend, VoIP, AI integration, and DevOps. No coordination with multiple vendors.', 'icon' => 'stack'],
        ['title' => 'Fixed-Price Proposals', 'description' => 'After a discovery call, you get a written fixed-price proposal within 48 hours.', 'icon' => 'partnership'],
        ['title' => 'You Own the Code', 'description' => 'Full ownership of all code, documentation, and assets. No vendor lock-in.', 'icon' => 'scale'],
    ],

    'process' => [
        ['step' => '01', 'title' => 'Discovery & Strategy', 'description' => 'We learn your business goals, users, and technical requirements. You receive a detailed project proposal within 48 hours.'],
        ['step' => '02', 'title' => 'Design & Prototyping', 'description' => 'Wireframes and interactive prototypes align the team on UX before a single line of code is written.'],
        ['step' => '03', 'title' => 'Agile Development', 'description' => 'Two-week sprints with weekly demos. You see progress continuously and can adjust priorities in real time.'],
        ['step' => '04', 'title' => 'Testing & QA', 'description' => 'Automated tests, manual QA, performance audits, and security reviews before every release.'],
        ['step' => '05', 'title' => 'Launch & Deployment', 'description' => 'Zero-downtime deployment to production with monitoring, documentation, and team training.'],
        ['step' => '06', 'title' => 'Support & Growth', 'description' => 'Post-launch maintenance, feature iterations, and scaling support as your product grows.'],
    ],

    'industries' => [
        ['name' => 'SaaS & Startups', 'description' => 'MVPs, subscription platforms, and growth-stage product development for SaaS companies.', 'icon' => 'saas'],
        ['name' => 'Healthcare', 'description' => 'HIPAA-aware patient portals, telehealth platforms, and medical practice management systems.', 'icon' => 'health'],
        ['name' => 'FinTech', 'description' => 'Payment processing, financial dashboards, and secure transaction systems.', 'icon' => 'fintech'],
        ['name' => 'E-Commerce', 'description' => 'Custom storefronts, inventory management, and marketplace platforms.', 'icon' => 'ecommerce'],
        ['name' => 'Real Estate', 'description' => 'Property listing platforms, CRM for agents, and virtual tour integrations.', 'icon' => 'realestate'],
        ['name' => 'Education', 'description' => 'Learning management systems, student portals, and EdTech platforms.', 'icon' => 'education'],
        ['name' => 'Logistics', 'description' => 'Fleet tracking, route optimization, and supply chain management tools.', 'icon' => 'logistics'],
        ['name' => 'Telecommunications', 'description' => 'Twilio-powered voice systems, call centers, and SMS notification platforms.', 'icon' => 'telecom'],
    ],

    'technologies' => [
        'Backend' => ['Laravel', 'PHP', 'Node.js', 'Python', 'REST APIs', 'GraphQL'],
        'Frontend' => ['Vue.js', 'Nuxt.js', 'React', 'TypeScript', 'Tailwind CSS', 'Alpine.js'],
        'Mobile' => ['React Native', 'Flutter', 'iOS', 'Android'],
        'Database' => ['MySQL', 'PostgreSQL', 'Redis', 'MongoDB', 'SQLite'],
        'Cloud & DevOps' => ['AWS', 'GCP', 'Docker', 'Kubernetes', 'GitHub Actions', 'Nginx'],
        'Integrations' => ['Twilio', 'Stripe', 'OpenAI', 'Claude', 'SendGrid', 'Firebase'],
    ],

    'portfolio' => [],

    'testimonials' => [],

    'faq' => [
        ['q' => 'What does Xcodrix do?', 'a' => 'Xcodrix builds AI voice agents, VoIP and call tracking systems, WhatsApp and SMS automation, and custom web applications. We specialize in Twilio, SignalWire, LiveKit, Retell, Vapi, Laravel, and Vue.js for startups and small businesses in the US, UK, Canada, and the Gulf.'],
        ['q' => 'How human does an AI voice agent sound?', 'a' => 'Very human. Modern voice AI uses ElevenLabs and OpenAI voices that sound natural with proper pacing, emotion, and filler words. Most callers cannot tell they are speaking to AI.'],
        ['q' => 'What does it cost per minute for AI voice calls?', 'a' => 'Usage costs for voice AI are typically $0.10 to $0.25 per minute depending on the provider (Retell, Vapi, or custom). We bill usage costs at cost, so you see exactly what you pay for.'],
        ['q' => 'Can the AI agent transfer calls to my team?', 'a' => 'Yes. AI agents can collect information, qualify the lead, and transfer the call to the right person or department based on your rules.'],
        ['q' => 'Can I keep my existing phone number?', 'a' => 'Yes. We port your existing number to Twilio or SignalWire. The porting process takes about 7 to 14 days in the US.'],
        ['q' => 'How much does a project cost?', 'a' => 'Project costs depend on scope. After a free discovery call, we provide a fixed-price proposal within 48 hours. Usage costs (call minutes, phone numbers, AI) are billed separately at cost.'],
        ['q' => 'How long does a typical project take?', 'a' => 'An AI receptionist pilot can be live in about 2 weeks. Full VoIP or call center systems take 4 to 12 weeks depending on features. We provide realistic timelines in the proposal.'],
        ['q' => 'Do you work with startups?', 'a' => 'Yes. Many of our clients are startups and small businesses launching their first voice AI or call tracking system.'],
        ['q' => 'Do you provide ongoing support after launch?', 'a' => 'Yes. We offer maintenance plans, prompt tuning, new workflows, and on-demand support. Most clients continue working with us after the initial launch.'],
        ['q' => 'Can you integrate with our CRM or calendar?', 'a' => 'Yes. We regularly integrate with Salesforce, HubSpot, Pipedrive, Google Calendar, Calendly, and custom APIs.'],
        ['q' => 'What is your development process?', 'a' => 'Discovery call, written proposal in 48 hours, design and development with weekly demos, testing, launch, and ongoing support.'],
        ['q' => 'Do you sign NDAs?', 'a' => 'Yes. We sign NDAs before any project discussion and treat all client information as confidential.'],
        ['q' => 'How do I get started?', 'a' => 'Book a free 20-minute call through our contact page or email info@xcoderix.com. We respond within 24 hours.'],
        ['q' => 'Where is Xcodrix located?', 'a' => 'Xcodrix is a software studio in Multan, Pakistan. We work with clients in the US, UK, Canada, and the Gulf. Our hours overlap with US East, UK, and Gulf time zones.'],
        ['q' => 'Should I use Retell, Vapi, or build a custom voice agent?', 'a' => 'Retell and Vapi are great for fast launches and standard use cases. Custom builds give you full control and can be more cost-effective at scale. We help you choose based on your needs.'],
        ['q' => 'Is call recording legal where I am?', 'a' => 'Call recording laws vary by location. In the US, most states allow one-party consent, but some require two-party consent. We can add consent announcements and disclaimers as needed.'],
    ],

    'pages' => [
        'home' => [
            'title' => 'Xcodrix | AI Voice Agents, VoIP and Twilio Call Systems for Startups',
            'description' => 'Xcodrix builds AI receptionists, VoIP and call center systems, call tracking and WhatsApp automation on Twilio, LiveKit and SignalWire. Book a free 20-minute call.',
        ],
        'about' => [
            'title' => 'About Xcodrix — Software Studio in Multan, Pakistan',
            'description' => 'Xcodrix is a software studio founded by Usama Tahir, building AI voice agents, VoIP systems, and web applications for clients in the US, UK, Canada and the Gulf.',
        ],
        'services' => [
            'title' => 'Our Services — AI Voice Agents, VoIP, WhatsApp & Custom Software | Xcodrix',
            'description' => 'AI voice agents and receptionists, VoIP and call center systems, WhatsApp and SMS automation, and custom SaaS and web applications.',
        ],
        'why-choose-us' => [
            'title' => 'Why Choose Xcodrix — Voice AI & VoIP Development',
            'description' => 'Xcodrix brings years of Twilio and voice AI experience, transparent process, fixed-price proposals, and full code ownership.',
        ],
        'process' => [
            'title' => 'Our Development Process — How Xcodrix Works',
            'description' => 'Discovery call, fixed-price proposal in 48 hours, weekly demos, testing, launch, and ongoing support.',
        ],
        'industries' => [
            'title' => 'Industries We Serve — Startups, Healthcare, Professional Services | Xcodrix',
            'description' => 'Xcodrix builds voice AI and VoIP systems for startups, healthcare, professional services, and SMBs.',
        ],
        'portfolio' => [
            'title' => 'Work — Xcodrix Case Studies (Coming Soon)',
            'description' => 'Case studies and project examples from Xcodrix are coming soon.',
        ],
        'technologies' => [
            'title' => 'Technologies We Use — Twilio, LiveKit, Retell, Vapi, Laravel, Vue | Xcodrix',
            'description' => 'Xcodrix tech stack: Twilio, SignalWire, LiveKit, Retell, Vapi, ElevenLabs, OpenAI, Laravel, Vue.js, and more.',
        ],
        'testimonials' => [
            'title' => 'Client Reviews — Xcodrix',
            'description' => 'Real client reviews and testimonials for Xcodrix.',
        ],
        'faq' => [
            'title' => 'FAQ — Voice AI & VoIP Questions | Xcodrix',
            'description' => 'Common questions about AI voice agents, VoIP systems, Twilio development, pricing, and how to get started.',
        ],
        'blog' => [
            'title' => 'Blog & Insights — Voice AI & VoIP Articles | Xcodrix',
            'description' => 'Articles on AI voice agents, VoIP, Twilio, WhatsApp automation, and startup development.',
        ],
        'contact' => [
            'title' => 'Contact Xcodrix — Book a Free 20-Minute Call',
            'description' => 'Get in touch with Xcodrix for a free project consultation. We respond within 24 hours.',
        ],
    ],
];
