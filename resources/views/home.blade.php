<x-layouts.app>
    <x-slot name="title">
        {{ __('CRM for Educational Institutions | Student Enrollment Software | Leads-Edu') }}
    </x-slot>

    <x-section.hero class="w-full mb-8 md:mb-72">

        <div class="mx-auto text-center h-160 md:h-180 px-4">
            <x-pill class="text-primary-500 bg-primary-50">{{ __('Student Enrollment Made Easy') }}</x-pill>
            <x-heading.h1 class="mt-4 text-primary-50 font-bold">
                {{ __('CRM Software for') }}
                <br class="hidden sm:block">
                {{ __('Educational Institutions') }}

            </x-heading.h1>

            <p class="text-primary-50 m-3">{{ __('Manage more leads and increase conversions with our specialized CRM for Educational Institutions.') }}</p>

            <div class="flex flex-wrap gap-4 justify-center flex-col md:flex-row mt-6">
                <x-effect.glow></x-effect.glow>

                <x-button-link.secondary href="#pricing" class="self-center py-3!" elementType="a">
                    {{ __('Start Free Trial') }}
                </x-button-link.secondary>
                <x-button-link.primary-outline href="#features" class=" bg-transparent self-center py-3! text-white border-white" rel="nofollow" >
                    {{ __('See Features') }}
                </x-button-link.primary-outline>

            </div>

            <x-user-ratings link="#testimonials" class="items-center justify-center mt-6 relative z-40">
                <x-slot name="avatars">
                    <x-user-ratings.avatar src="https://unsplash.com/photos/rDEOVtE7vOs/download?ixid=M3wxMjA3fDB8MXxzZWFyY2h8Mnx8cGVyc29ufGVufDB8fHx8MTcxMzY4NDI1MHww&force=true&w=640" alt="testimonial 1"/>
                    <x-user-ratings.avatar src="https://unsplash.com/photos/c_GmwfHBDzk/download?ixid=M3wxMjA3fDB8MXxzZWFyY2h8M3x8cGVyc29ufGVufDB8fHx8MTcxMzY4NDI1MHww&force=true&w=640" alt="testimonial 2"/>
                    <x-user-ratings.avatar src="https://unsplash.com/photos/QXevDflbl8A/download?ixid=M3wxMjA3fDB8MXxzZWFyY2h8NHx8cGVyc29ufGVufDB8fHx8MTcxMzY4NDI1MHww&force=true&w=640" alt="testimonial 3"/>
                    <x-user-ratings.avatar src="https://unsplash.com/photos/mjRwhvqEC0U/download?ixid=M3wxMjA3fDB8MXxzZWFyY2h8Nnx8cGVyc29ufGVufDB8fHx8MTcxMzY4NDI1MHww&force=true&w=640" alt="testimonial 4"/>
                    <x-user-ratings.avatar src="https://unsplash.com/photos/C8Ta0gwPbQg/download?ixid=M3wxMjA3fDB8MXxzZWFyY2h8MTl8fHBlcnNvbnxlbnwwfHx8fDE3MTM2ODQyNTB8MA&force=true&w=640" alt="testimonial 5"/>
                </x-slot>

                {{ __('Join educational institutions worldwide using Leads-Edu to streamline their enrollment process.') }}
            </x-user-ratings>

            <div class="mx-auto md:max-w-3xl lg:max-w-5xl">
                <img class="drop-shadow-2xl mt-8 transition hover:scale-101 rounded-2xl" src="{{URL::asset('/images/features/hero-image.png')}}" />
            </div>

        </div>
    </x-section.hero>

    <x-section.columns class="max-w-none md:max-w-6xl pt-16" id="features">
        <x-section.column>
            <div x-intersect="$el.classList.add('slide-in-top')">
                <x-heading.h6 class="text-primary-500">
                    {{ __('Complete Lead Management') }}
                </x-heading.h6>
                <x-heading.h2 class="text-primary-900">
                    {{ __('Track Every Prospective Student') }}
                </x-heading.h2>
            </div>

            <p class="mt-4">
                {{ __('Capture, organize, and nurture leads from multiple sources. Track every interaction, manage follow-ups, and never miss an enrollment opportunity. Our intuitive CRM keeps all student information in one centralized platform.') }}
            </p>
            <p class="mt-4">
                {{ __('From first contact to enrollment, manage the entire student journey with powerful tools designed for educational institutions.') }}
            </p>
            <p class="pt-4">
                {{ __('Key Features:') }}
            </p>
            <ul class="list-disc list-inside mt-2 space-y-1">
                <li>{{ __('Lead capture from multiple channels') }}</li>
                <li>{{ __('Automated follow-up reminders') }}</li>
                <li>{{ __('Complete communication history') }}</li>
                <li>{{ __('UTM tracking and campaign analytics') }}</li>
            </ul>
        </x-section.column>

        <x-section.column>
            <img src="{{URL::asset('/images/features/payments.png')}}" dir="right" ></img>
        </x-section.column>

    </x-section.columns>

    <x-section.columns class="max-w-none md:max-w-6xl  flex-wrap-reverse">
        <x-section.column >
            <img src="{{URL::asset('/images/features/colors.png')}}" />
        </x-section.column>

        <x-section.column>
            <div x-intersect="$el.classList.add('slide-in-top')">
                <x-heading.h6 class="text-primary-500">
                    {{ __('Secure & Private') }}
                </x-heading.h6>
                <x-heading.h2 class="text-primary-900">
                    {{ __('Your Data, Your Control') }}
                </x-heading.h2>
            </div>

            <p class="mt-4">
                {{ __('Your institution\'s data remains completely private and secure. Full compliance with data protection regulations including GDPR and LOPD.') }}
            </p>

            <p class="mt-4">
                {{ __('Customize your catalogs, configure your enrollment phases, and manage your admissions team with role-based permissions.') }}
            </p>
        </x-section.column>

    </x-section.columns>

    <x-section.columns class="max-w-none md:max-w-6xl mt-6" >
        <x-section.column>
            <div x-intersect="$el.classList.add('slide-in-top')">
                <x-heading.h6 class="text-primary-500">
                    {{ __('Flexible Configuration') }}
                </x-heading.h6>
                <x-heading.h2 class="text-primary-900">
                    {{ __('Courses, Programs & Catalogs') }}
                </x-heading.h2>
            </div>

            <p class="mt-4">
                {{ __('Manage your complete course catalog with academic areas, business units, durations, campuses, and modalities. Configure everything from a beautiful admin panel tailored for educational institutions.') }}
            </p>

            <p class="mt-4">
                {{ __('Customize sales phases, track lead origins, and manage enrollment reasons - all configurable per tenant to match your institution\'s unique process.') }}
            </p>
        </x-section.column>

        <x-section.column>
            <img src="{{URL::asset('/images/features/plans.png')}}" class="rounded-2xl"/>
        </x-section.column>

    </x-section.columns>

    <x-section.columns class="max-w-none md:max-w-6xl mt-6 flex-wrap-reverse">
        <x-section.column >
            <img src="{{URL::asset('/images/features/checkout.png')}}" class="rounded-2xl" />
        </x-section.column>

        <x-section.column>
            <div x-intersect="$el.classList.add('slide-in-top')">
                <x-heading.h6 class="text-primary-500">
                    {{ __('Streamlined Workflow') }}
                </x-heading.h6>
                <x-heading.h2 class="text-primary-900">
                    {{ __('Efficient Follow-up Process') }}
                </x-heading.h2>
            </div>

            <p class="mt-4">
                {{ __('Track every lead through your custom sales phases. Schedule follow-up calls, meetings, and emails with automated reminders. Document every interaction with notes and events to ensure no opportunity is missed.') }}
            </p>
        </x-section.column>

    </x-section.columns>

    <x-section.columns class="max-w-none md:max-w-6xl mt-6" >
        <x-section.column>
            <div x-intersect="$el.classList.add('slide-in-top')">
                <x-heading.h6 class="text-primary-500">
                    {{ __('Complete Interaction History') }}
                </x-heading.h6>
                <x-heading.h2 class="text-primary-900">
                    {{ __('Lead Notes & Communication Log') }}
                </x-heading.h2>
            </div>

            <p class="mt-4">
                {{ __('Document every phone call, email, meeting, and interaction with prospective students. Keep a detailed history of all communications in one centralized place.') }}
            </p>
            
            <p class="mt-4">
                {{ __('Mark important notes, categorize interactions by type, and schedule follow-up reminders. Never lose track of what was discussed or promised to a lead.') }}
            </p>
            
            <p class="pt-4">
                {{ __('Note Features:') }}
            </p>
            <ul class="list-disc list-inside mt-2 space-y-1">
                <li>{{ __('Categorize by type: call, email, meeting, observation') }}</li>
                <li>{{ __('Mark critical notes as important') }}</li>
                <li>{{ __('Schedule follow-up reminders') }}</li>
                <li>{{ __('Full audit trail with user attribution') }}</li>
            </ul>
        </x-section.column>

        <x-section.column>
            <img src="{{URL::asset('/images/features/plans.png')}}" class="rounded-2xl"/>
        </x-section.column>

    </x-section.columns>

    <x-section.columns class="max-w-none md:max-w-6xl mt-6 flex-wrap-reverse">
        <x-section.column >
            <img src="{{URL::asset('/images/features/checkout.png')}}" class="rounded-2xl" />
        </x-section.column>

        <x-section.column>
            <div x-intersect="$el.classList.add('slide-in-top')">
                <x-heading.h6 class="text-primary-500">
                    {{ __('Never Miss a Follow-up') }}
                </x-heading.h6>
                <x-heading.h2 class="text-primary-900">
                    {{ __('Lead Events & Task Management') }}
                </x-heading.h2>
            </div>

            <p class="mt-4">
                {{ __('Schedule and track all follow-up activities: calls, meetings, emails, WhatsApp messages, and campus visits. Set priorities and get automated reminders before each event.') }}
            </p>
            
            <p class="mt-4">
                {{ __('Assign tasks to team members, track completion status, and document outcomes. Ensure every lead receives timely attention throughout the enrollment journey.') }}
            </p>
            
            <p class="pt-4">
                {{ __('Event Capabilities:') }}
            </p>
            <ul class="list-disc list-inside mt-2 space-y-1">
                <li>{{ __('Multiple event types: call, email, meeting, visit') }}</li>
                <li>{{ __('Priority levels: low, medium, high, urgent') }}</li>
                <li>{{ __('Automated reminders (customizable timing)') }}</li>
                <li>{{ __('Status tracking: pending, in progress, completed') }}</li>
                <li>{{ __('Document results and outcomes') }}</li>
            </ul>
        </x-section.column>

    </x-section.columns>

    
    <div class="text-center mt-16" x-intersect="$el.classList.add('slide-in-top')">
        <x-heading.h6 class="text-primary-500">
            {{ __('Powerful & Intuitive') }}
        </x-heading.h6>
        <x-heading.h2 class="text-primary-900">
            {{ __('Complete CRM Dashboard') }}
        </x-heading.h2>
    </div>

    <p class="text-center py-4">{{ __('Manage leads, courses, contacts, and your entire enrollment process from a beautiful admin panel powered by Filament') }}</p>

    <div class="text-center pt-6 mx-auto max-w-5xl ">
        <img src="{{URL::asset('/images/features/admin-panel.png')}}" >
    </div>

    <x-section.columns class="max-w-none md:max-w-6xl mt-12" >
        <x-section.column>
            <div x-intersect="$el.classList.add('slide-in-top')">
                <x-heading.h6 class="text-primary-500">
                    {{ __('Data-Driven Decisions') }}
                </x-heading.h6>
                <x-heading.h2 class="text-primary-900">
                    {{ __('Enrollment Analytics') }}
                </x-heading.h2>
            </div>

            <p class="mt-4">
                {{ __('Track conversion rates, analyze lead sources, monitor enrollment trends, and measure team performance. Get actionable insights with comprehensive dashboards and reports tailored for educational institutions.') }}
            </p>
        </x-section.column>

        <x-section.column>
            <img src="{{URL::asset('/images/features/stats.png')}}" >
        </x-section.column>

    </x-section.columns>

    <x-section.columns class="max-w-none md:max-w-6xl mt-16 flex-wrap-reverse">
        <x-section.column >
            <img src="{{URL::asset('/images/features/email.png')}}"  />
        </x-section.column>

        <x-section.column>
            <div x-intersect="$el.classList.add('slide-in-top')">
                <x-heading.h6 class="text-primary-500">
                    {{ __('Engage Prospective Students') }}
                </x-heading.h6>
                <x-heading.h2 class="text-primary-900">
                    {{ __('Multi-Channel Communication') }}
                </x-heading.h2>
            </div>

            <p class="mt-4">
                {{ __('Communicate with leads through email, phone, WhatsApp, and SMS. Track all interactions in one place and maintain a complete communication history for each prospective student.') }}
            </p>
            <p class="mt-4">
                {{ __('Integrated with popular email providers like Mailgun, Postmark, and Amazon SES. Send personalized follow-ups and automated notifications to keep leads engaged throughout the enrollment process.') }}
            </p>

            <p class="pt-4">
                {{ __('Supported email providers:') }}
            </p>
            <div class="flex gap-3 pt-1">
                <a href="https://postmarkapp.com/" target="_blank">
                    @svg('colored/postmark', 'h-12 w-12 py-2 px-2 border border-primary-50 rounded-lg')
                </a>

                <a href="https://www.mailgun.com/" target="_blank">
                    @svg('colored/mailgun', 'h-12 w-12 py-2 px-2 border border-primary-50 rounded-lg')
                </a>

                <a href="https://aws.amazon.com/ses/" target="_blank">
                    @svg('colored/ses', 'h-12 w-12 py-2 px-2 border border-primary-50 rounded-lg')
                </a>
            </div>
        </x-section.column>

    </x-section.columns>

    <x-section.columns class="max-w-none md:max-w-6xl" >
        <x-section.column>
            <div x-intersect="$el.classList.add('slide-in-top')">
                <x-heading.h6 class="text-primary-500">
                    {{ __('API Integration') }}
                </x-heading.h6>
                <x-heading.h2 class="text-primary-900">
                    {{ __('Connect Your Systems') }}
                </x-heading.h2>
            </div>

            <p class="mt-4">
                {{ __('Integrate Leads-Edu with your existing systems through our REST API. Capture leads from your website, landing pages, and marketing campaigns automatically.') }}
            </p>
            <p class="mt-4">
                {{ __('Secure API with token-based authentication, rate limiting, and complete tenant isolation. Manage API tokens directly from your admin panel with customizable scopes and permissions.') }}
            </p>
        </x-section.column>

        <x-section.column>
            <img src="{{URL::asset('/images/features/blog.png')}}" />
        </x-section.column>

    </x-section.columns>

    <x-section.columns class="max-w-none md:max-w-6xl mt-16 flex-wrap-reverse">
        <x-section.column >
            <img src="{{URL::asset('/images/features/login.png')}}" />
        </x-section.column>

        <x-section.column>
            <div x-intersect="$el.classList.add('slide-in-top')">
                <x-heading.h6 class="text-primary-500">
                    {{ __('Secure Access Control') }}
                </x-heading.h6>
                <x-heading.h2 class="text-primary-900">
                    {{ __('Role-Based Permissions') }}
                </x-heading.h2>
            </div>

            <p class="mt-4">
                {{ __('Manage your team with three distinct roles: Admin (full access), Manager (team oversight), and Comercial (lead management). Each role has specific permissions to ensure data security and workflow efficiency.') }}
            </p>

            <p class="pt-4">
                {{ __('Supported login providers:') }}
            </p>
            <div class="flex gap-3 pt-1 flex-wrap">
                @svg('colored/google', 'h-12 w-12 py-2 px-2 border border-primary-50 rounded-lg')
                @svg('colored/facebook', 'h-12 w-12 py-2 px-2 border border-primary-50 rounded-lg')
                @svg('colored/twitter-oauth-2', 'h-12 w-12 py-2 px-2 border border-primary-50 rounded-lg')
                @svg('colored/linkedin', 'h-12 w-12 py-2 px-2 border border-primary-50 rounded-lg')
                @svg('colored/github', 'h-12 w-12 py-2 px-2 border border-primary-50 rounded-lg')
                @svg('colored/gitlab', 'h-12 w-12 py-2 px-2 border border-primary-50 rounded-lg')
                @svg('colored/bitbucket', 'h-12 w-12 py-2 px-2 border border-primary-50 rounded-lg')
            </div>
        </x-section.column>

    </x-section.columns>


    <div class="text-center mt-16" x-intersect="$el.classList.add('slide-in-top')">
        <x-heading.h6 class="text-primary-500">
            {{ __('Everything You Need') }}
        </x-heading.h6>
        <x-heading.h2 class="text-primary-900">
            {{ __('Comprehensive CRM Features') }}
        </x-heading.h2>
    </div>

    <x-section.columns class="max-w-none md:max-w-6xl mt-6">
        <x-section.column class="flex flex-col items-center justify-center text-center">
            <x-icon.fancy name="users" class="w-2/5 mx-auto" />
            <x-heading.h3 class="mx-auto pt-2">
                {{ __('Team Management') }}
            </x-heading.h3>
            <p class="mt-2">{{ __('Manage your admissions team with role-based access control (Admin, Manager, Comercial).') }}</p>
        </x-section.column>

        <x-section.column class="flex flex-col items-center justify-center text-center">
            <x-icon.fancy name="translatable" class="w-2/5 mx-auto" />
            <x-heading.h3 class="mx-auto pt-2">
                {{ __('Fully translatable') }}
            </x-heading.h3>
            <p class="mt-2">{{ __('Translate your application to any language you want.') }}</p>
        </x-section.column>

        <x-section.column class="flex flex-col items-center justify-center text-center">
            <x-icon.fancy name="seo" class="w-2/5 mx-auto" />
            <x-heading.h3 class="mx-auto pt-2">
                {{ __('Sitemap & SEO') }}
            </x-heading.h3>
            <p class="mt-2">{{ __('Auto-generated sitemap and SEO optimization out of the box.') }}</p>
        </x-section.column>

    </x-section.columns>

    <x-section.columns class="max-w-none md:max-w-6xl mt-6">
        <x-section.column class="flex flex-col items-center justify-center text-center">
            <x-icon.fancy name="user-dashboard" class="w-2/5 mx-auto" />
            <x-heading.h3 class="mx-auto pt-2">
                {{ __('Lead Tracking') }}
            </x-heading.h3>
            <p class="mt-2">{{ __('Track leads through custom sales phases, from first contact to enrollment completion.') }}</p>
        </x-section.column>

        <x-section.column class="flex flex-col items-center justify-center text-center">
            <x-icon.fancy name="tool" class="w-2/5 mx-auto" />
            <x-heading.h3 class="mx-auto pt-2">
                {{ __('Configurable Catalogs') }}
            </x-heading.h3>
            <p class="mt-2">{{ __('Customize courses, areas, campuses, modalities, and all catalogs from the admin panel.') }}</p>
        </x-section.column>

        <x-section.column class="flex flex-col items-center justify-center text-center">
            <x-icon.fancy name="development" class="w-2/5 mx-auto" />
            <x-heading.h3 class="mx-auto pt-2">
                {{ __('Notes & Events') }}
            </x-heading.h3>
            <p class="mt-2">{{ __('Document every interaction with detailed notes and schedule follow-up events with automated reminders.') }}</p>
        </x-section.column>

    </x-section.columns>


    <div class="text-center mt-24" x-intersect="$el.classList.add('slide-in-top')">
        <x-heading.h6 class="text-primary-500">
            {{ __('Support & Training') }}
        </x-heading.h6>
        <x-heading.h2 class="text-primary-900">
            {{ __('We\'re Here to Help') }}
        </x-heading.h2>
    </div>

    <div class="mx-4">
        <div class="max-w-none md:max-w-6xl mx-auto text-center">
            <p class="mt-4">
                {{ __('Get comprehensive documentation, video tutorials, and dedicated support to help your team make the most of Leads-Edu CRM. We\'re committed to your success.') }}
            </p>
            <x-button-link.primary href="#pricing" class=" mt-8">
                {{ __('Get Started Today') }}
            </x-button-link.primary>
        </div>
    </div>

    <div class="mx-4 mt=16">
        <x-heading.h6 class="text-center mt-20 text-primary-500" id="pricing">
            {{ __('Transform Your Enrollment Process') }}
        </x-heading.h6>
        <x-heading.h2 class="text-primary-900 text-center">
            {{ __('Start Managing Leads Today') }}
        </x-heading.h2>
    </div>

    <div class="pricing">
        <x-plans.all calculate-saving-rates="true" show-default-product="1"/>
        <x-products.all />
    </div>

    <div class="text-center mt-24 mx-4" id="faq">
        <x-heading.h6 class="text-primary-500">
            {{ __('FAQ') }}
        </x-heading.h6>
        <x-heading.h2 class="text-primary-900">
            {{ __('Got a Question?') }}
        </x-heading.h2>
        <p>{{ __('Here are the most common questions to help you with your decision.') }}</p>
    </div>

    <div class="max-w-none md:max-w-6xl mx-auto">
        <x-accordion class="mt-4 p-8">
            <x-accordion.item active="true" name="faqs">
                <x-slot name="title">{{ __('What is Leads-Edu?') }}</x-slot>

                <p>
                    {{ __('Leads-Edu is a specialized CRM platform designed specifically for educational institutions. It helps schools, universities, and training centers manage prospective students (leads), track the enrollment process, schedule follow-ups, and increase conversion rates. Our platform centralizes all student interactions, from first contact to enrollment.') }}
                </p>

            </x-accordion.item>

            <x-accordion.item active="false" name="faqs">
                <x-slot name="title">{{ __('What features does Leads-Edu include?') }}</x-slot>

                <p class="mt-4">
                    {{ __('Leads-Edu includes everything you need to manage your enrollment process:') }}
                </p>

                <ul class="mt-4 list-disc ms-4 ps-4">
                    <li>{{ __('Lead Management: Capture and organize leads from multiple sources with UTM tracking') }}</li>
                    <li>{{ __('Contact Management: Store complete student information including preferences and history') }}</li>
                    <li>{{ __('Course Catalog: Manage courses, programs, areas, campuses, and modalities') }}</li>
                    <li>{{ __('Lead Notes: Document every interaction (calls, emails, meetings) with full audit trail') }}</li>
                    <li>{{ __('Lead Events: Schedule follow-ups with automated reminders and task assignment') }}</li>
                    <li>{{ __('Custom Sales Phases: Configure your enrollment funnel stages') }}</li>
                    <li>{{ __('Team Management: Role-based access (Admin, Manager, Comercial)') }}</li>
                    <li>{{ __('Analytics Dashboard: Track conversion rates, lead sources, and team performance') }}</li>
                    <li>{{ __('Multi-channel Communication: Email, phone, WhatsApp, SMS integration') }}</li>
                    <li>{{ __('API Integration: Connect with your website and marketing tools') }}</li>
                    <li>{{ __('Data Security: GDPR and LOPD compliant with complete data isolation') }}</li>
                    <li>{{ __('Customizable Catalogs: Configure origins, null reasons, provinces, and more') }}</li>
                </ul>

            </x-accordion.item>

            <x-accordion.item active="false" name="faqs">
                <x-slot name="title">{{ __('How does pricing work?') }}</x-slot>

                <p>
                    {{ __('Leads-Edu offers flexible pricing based on the number of leads you manage. We have three plans:') }}
                </p>
                
                <ul class="mt-4 list-disc ms-4 ps-4">
                    <li><strong>{{ __('Starter') }}</strong>: {{ __('$3.99/seat/month - Up to 1,000 leads with unlimited events and notes') }}</li>
                    <li><strong>{{ __('Pro') }}</strong>: {{ __('$5.99/seat/month - Up to 10,000 leads (Most Popular)') }}</li>
                    <li><strong>{{ __('Premium') }}</strong>: {{ __('$8.99/seat/month - Up to 100,000 leads for large institutions') }}</li>
                </ul>
                
                <p class="mt-4">
                    {{ __('All plans include unlimited lead events and notes. You can start for FREE with 50 leads, lead events, and lead notes! Save 17% with yearly billing.') }}
                </p>

            </x-accordion.item>

            <x-accordion.item active="false" name="faqs">
                <x-slot name="title">{{ __('Can I try Leads-Edu before purchasing?') }}</x-slot>

                <p>
                    {{ __('Yes! You can start completely FREE with 50 leads, unlimited lead events, and unlimited lead notes. This allows you to test all features and see how Leads-Edu fits your institution\'s needs before committing to a paid plan.') }}
                </p>

            </x-accordion.item>

            <x-accordion.item active="false" name="faqs">
                <x-slot name="title">{{ __('What counts as a "Lead"?') }}</x-slot>

                <p>
                    {{ __('A Lead is any prospective student record in your CRM system. Each lead represents a potential enrollment opportunity and includes all their contact information, interactions, notes, and events. All leads count toward your plan limit, regardless of their status (new, contacted, interested, enrolled, or lost).') }}
                </p>

            </x-accordion.item>

            <x-accordion.item active="false" name="faqs">
                <x-slot name="title">{{ __('Is my institution\'s data secure?') }}</x-slot>

                <p>
                    {{ __('Absolutely. Leads-Edu implements enterprise-grade security measures. Your institution\'s data is completely isolated and private. We are fully compliant with GDPR and LOPD data protection regulations. All data is encrypted in transit and at rest, and we perform regular security audits.') }}
                </p>

            </x-accordion.item>

            <x-accordion.item active="false" name="faqs">
                <x-slot name="title">{{ __('Can I integrate Leads-Edu with my website?') }}</x-slot>

                <p>
                    {{ __('Yes! Leads-Edu provides a REST API that allows you to integrate with your website, landing pages, and marketing tools. You can automatically capture leads from web forms, track UTM parameters for campaign analytics, and sync data with your existing systems. API tokens are managed directly from your admin panel.') }}
                </p>
                
                <p class="mt-4">
                    {{ __('Additionally, you can easily integrate Leads-Edu with automation platforms like Zapier, Make (formerly Integromat), n8n, and other workflow automation tools. This allows you to connect with thousands of apps and create powerful automations without writing code.') }}
                </p>

            </x-accordion.item>

            <x-accordion.item active="false" name="faqs">
                <x-slot name="title">{{ __('How many team members can use the system?') }}</x-slot>

                <p>
                    {{ __('All plans support unlimited team members with role-based access control. You can assign three different roles: Admin (full access), Manager (team oversight and reporting), and Comercial (lead management). Each user can be configured with specific permissions based on their responsibilities.') }}
                </p>

            </x-accordion.item>

            <x-accordion.item active="false" name="faqs">
                <x-slot name="title">{{ __('What happens if I exceed my lead limit?') }}</x-slot>

                <p>
                    {{ __('If you approach your plan\'s lead limit, we\'ll notify you in advance so you can upgrade seamlessly. You can upgrade to the next plan tier at any time directly from your admin panel. The upgrade is prorated, so you only pay for the remaining time in your billing cycle. All your data, settings, and team configurations remain intact during the upgrade.') }}
                </p>

            </x-accordion.item>

            <x-accordion.item active="false" name="faqs">
                <x-slot name="title">{{ __('Do you offer training and support?') }}</x-slot>

                <p>
                    {{ __('Yes! We provide comprehensive onboarding documentation, video tutorials, and email support to help your team get started. For any questions or issues, contact us at') }} <a href="mailto:{{config('app.support_email')}}" class="text-primary-500 hover:underline">{{config('app.support_email')}}</a>. {{ __('We\'re committed to your success and respond quickly to all support requests.') }}
                </p>

            </x-accordion.item>

            <x-accordion.item active="false" name="faqs">
                <x-slot name="title">{{ __('Can I cancel my subscription anytime?') }}</x-slot>

                <p>
                    {{ __('Yes, you can cancel your subscription at any time from your admin panel. There are no cancellation fees or long-term contracts. If you cancel, you\'ll retain access until the end of your current billing period. You can also export all your lead data before canceling.') }}
                </p>

            </x-accordion.item>
        </x-accordion>
    </div>

</x-layouts.app>
