@extends('layouts.app')

@section('title', $page->getContent('title', 'About Family Cloud'))
@section('meta_description', $page->meta_description ?? 'Learn more about Family Cloud and our mission to help families stay connected')

@section('content')
<div class="bg-white">
    <!-- Hero Section -->
    <div class="relative py-16 bg-white overflow-hidden">
        <div class="hidden lg:block lg:absolute lg:inset-y-0 lg:h-full lg:w-full">
            <div class="relative h-full text-lg max-w-prose mx-auto" aria-hidden="true">
                <svg class="absolute top-12 left-full transform translate-x-32" width="404" height="384" fill="none" viewBox="0 0 404 384">
                    <defs>
                        <pattern id="74b3fd99-0a6f-4271-bef2-e80eeafdf357" x="0" y="0" width="20" height="20" patternUnits="userSpaceOnUse">
                            <rect x="0" y="0" width="4" height="4" class="text-gray-200" fill="currentColor" />
                        </pattern>
                    </defs>
                    <rect width="404" height="384" fill="url(#74b3fd99-0a6f-4271-bef2-e80eeafdf357)" />
                </svg>
                <svg class="absolute top-1/2 right-full transform -translate-y-1/2 -translate-x-32" width="404" height="384" fill="none" viewBox="0 0 404 384">
                    <defs>
                        <pattern id="f210dbf6-a58d-4871-961e-36d5016a0f49" x="0" y="0" width="20" height="20" patternUnits="userSpaceOnUse">
                            <rect x="0" y="0" width="4" height="4" class="text-gray-200" fill="currentColor" />
                        </pattern>
                    </defs>
                    <rect width="404" height="384" fill="url(#f210dbf6-a58d-4871-961e-36d5016a0f49)" />
                </svg>
            </div>
        </div>
        <div class="relative px-4 sm:px-6 lg:px-8">
            <div class="text-lg max-w-prose mx-auto">
                <h1>
                    <span class="block text-base text-center text-indigo-600 font-semibold tracking-wide uppercase">About Us</span>
                    <span class="mt-2 block text-3xl text-center leading-8 font-extrabold tracking-tight text-gray-900 sm:text-4xl">
                        {{ $page->getContent('title', 'About Family Cloud') }}
                    </span>
                </h1>
                <p class="mt-8 text-xl text-gray-500 leading-8">
                    {{ $page->getContent('introduction', 'Family Cloud was born from a simple idea: families should have a secure, private space to share their most precious memories without worrying about privacy or data security.') }}
                </p>
            </div>
            <div class="mt-6 prose prose-indigo prose-lg text-gray-500 mx-auto">
                <div class="bg-gray-50 rounded-lg p-8">
                    {!! $page->getContent('content', '
                    <h2>Our Story</h2>
                    <p>Founded in 2024, Family Cloud emerged from the recognition that families needed a better way to share and preserve their digital memories. Unlike social media platforms where your content becomes public and subject to algorithms, we provide a private sanctuary for your family\'s most important moments.</p>

                    <h2>Our Mission</h2>
                    <p>We believe that family memories deserve the highest level of protection and the easiest sharing experience. Our mission is to provide families with a secure, intuitive platform that brings loved ones closer together through shared experiences and preserved memories.</p>

                    <h2>What Makes Us Different</h2>
                    <p>Privacy is not just a feature for us—it\'s our foundation. Every photo, video, and document you share is encrypted and stored securely. We don\'t sell your data, show advertisements, or use your content for any purpose other than helping your family stay connected.</p>

                    <h2>Our Values</h2>
                    <ul>
                        <li><strong>Privacy First:</strong> Your family\'s content remains private and secure</li>
                        <li><strong>Simplicity:</strong> Easy to use for all family members, regardless of technical expertise</li>
                        <li><strong>Reliability:</strong> Your memories are safe with our robust backup and security systems</li>
                        <li><strong>Family Focused:</strong> Every feature is designed with families in mind</li>
                    </ul>
                    ') !!}
                </div>

                @if($page->getImagePath('team_image'))
                <figure>
                    <img class="w-full rounded-lg" src="{{ $page->getImagePath('team_image') }}" alt="Our Team" width="1310" height="873">
                    <figcaption>{{ $page->getContent('team_caption', 'Our dedicated team working to keep families connected') }}</figcaption>
                </figure>
                @endif
            </div>
        </div>
    </div>

    <!-- Team Section -->
    <div class="bg-gray-50 py-16">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="text-center">
                <h2 class="text-3xl font-extrabold text-gray-900">Meet Our Team</h2>
                <p class="mt-4 text-lg text-gray-500">Dedicated to keeping families connected</p>
            </div>

            <div class="mt-12 grid gap-8 lg:grid-cols-3">
                <div class="text-center">
                    <div class="space-y-4">
                        <img class="mx-auto h-20 w-20 rounded-full lg:w-24 lg:h-24" src="https://images.unsplash.com/photo-1494790108755-2616b612b786?ixlib=rb-1.2.1&ixid=eyJhcHBfaWQiOjEyMDd9&auto=format&fit=facearea&facepad=2&w=256&h=256&q=80" alt="">
                        <div class="space-y-2">
                            <div class="text-lg leading-6 font-medium space-y-1">
                                <h3>Sarah Johnson</h3>
                                <p class="text-indigo-600">Founder & CEO</p>
                            </div>
                            <div class="text-lg">
                                <p class="text-gray-500">Passionate about connecting families through technology</p>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="text-center">
                    <div class="space-y-4">
                        <img class="mx-auto h-20 w-20 rounded-full lg:w-24 lg:h-24" src="https://images.unsplash.com/photo-1519244703995-f4e0f30006d5?ixlib=rb-1.2.1&ixid=eyJhcHBfaWQiOjEyMDd9&auto=format&fit=facearea&facepad=2&w=256&h=256&q=80" alt="">
                        <div class="space-y-2">
                            <div class="text-lg leading-6 font-medium space-y-1">
                                <h3>Michael Chen</h3>
                                <p class="text-indigo-600">CTO</p>
                            </div>
                            <div class="text-lg">
                                <p class="text-gray-500">Ensuring your data stays secure and accessible</p>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="text-center">
                    <div class="space-y-4">
                        <img class="mx-auto h-20 w-20 rounded-full lg:w-24 lg:h-24" src="https://images.unsplash.com/photo-1506794778202-cad84cf45f1d?ixlib=rb-1.2.1&ixid=eyJhcHBfaWQiOjEyMDd9&auto=format&fit=facearea&facepad=2&w=256&h=256&q=80" alt="">
                        <div class="space-y-2">
                            <div class="text-lg leading-6 font-medium space-y-1">
                                <h3>Emily Rodriguez</h3>
                                <p class="text-indigo-600">Head of Design</p>
                            </div>
                            <div class="text-lg">
                                <p class="text-gray-500">Creating beautiful experiences for families</p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Contact CTA -->
    <div class="bg-indigo-700">
        <div class="max-w-2xl mx-auto text-center py-16 px-4 sm:py-20 sm:px-6 lg:px-8">
            <h2 class="text-3xl font-extrabold text-white sm:text-4xl">
                <span class="block">Ready to get started?</span>
                <span class="block">Join thousands of families today.</span>
            </h2>
            <p class="mt-4 text-lg leading-6 text-indigo-200">
                Create your family cloud and start sharing memories securely.
            </p>
            <a href="{{ route('contact') }}" class="mt-8 w-full inline-flex items-center justify-center px-5 py-3 border border-transparent text-base font-medium rounded-md text-indigo-600 bg-white hover:bg-indigo-50 sm:w-auto">
                Get in touch
            </a>
        </div>
    </div>
</div>
@endsection
