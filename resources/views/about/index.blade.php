@extends('layouts.app')

@section('title', $page->getContent('title', 'About Family Cloud'))
@section('meta_description', $page->meta_description ?? 'Learn more about Family Cloud and our mission to help families stay connected')

@section('content')
<div class="bg-gradient-to-b from-blue-50 to-white">
    <!-- Hero Section -->
    <div class="relative pt-20 pb-16 overflow-hidden">
        <!-- Decorative elements -->
        <div class="hidden lg:block lg:absolute lg:inset-y-0 lg:h-full lg:w-full">
            <div class="relative h-full text-lg max-w-prose mx-auto" aria-hidden="true">
                <svg class="absolute top-12 left-full transform translate-x-32 opacity-20" width="404" height="384" fill="none" viewBox="0 0 404 384">
                    <defs>
                        <pattern id="74b3fd99-0a6f-4271-bef2-e80eeafdf357" x="0" y="0" width="20" height="20" patternUnits="userSpaceOnUse">
                            <rect x="0" y="0" width="4" height="4" class="text-blue-200" fill="currentColor" />
                        </pattern>
                    </defs>
                    <rect width="404" height="384" fill="url(#74b3fd99-0a6f-4271-bef2-e80eeafdf357)" />
                </svg>
                <svg class="absolute top-1/2 right-full transform -translate-y-1/2 -translate-x-32 opacity-20" width="404" height="384" fill="none" viewBox="0 0 404 384">
                    <defs>
                        <pattern id="f210dbf6-a58d-4871-961e-36d5016a0f49" x="0" y="0" width="20" height="20" patternUnits="userSpaceOnUse">
                            <rect x="0" y="0" width="4" height="4" class="text-blue-200" fill="currentColor" />
                        </pattern>
                    </defs>
                    <rect width="404" height="384" fill="url(#f210dbf6-a58d-4871-961e-36d5016a0f49)" />
                </svg>
            </div>
        </div>

        <!-- Hero content -->
        <div class="relative px-4 sm:px-6 lg:px-8">
            <div class="text-center max-w-4xl mx-auto">
                <div class="animate-fade-in">
                    <span class="inline-flex items-center px-3 py-0.5 rounded-full text-sm font-medium bg-blue-100 text-blue-800 mb-5">
                        <svg class="w-4 h-4 mr-1.5" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z"></path>
                        </svg>
                        About Us
                    </span>
                    <h1 class="text-4xl md:text-5xl font-extrabold text-gray-900 tracking-tight mb-4">
                        {{ $page->getContent('title', 'About Family Cloud') }}
                    </h1>
                    <p class="mt-6 text-xl text-gray-600 leading-8 max-w-3xl mx-auto">
                        {{ $page->getContent('introduction', 'Family Cloud was born from a simple idea: families should have a secure, private space to share their most precious memories without worrying about privacy or data security.') }}
                    </p>
                </div>
            </div>

            <!-- Main content -->
            <div class="mt-12  mx-auto bg-white rounded-xl shadow-lg overflow-hidden">
                <div class="p-8 md:p-12 prose prose-lg prose-blue text-gray-600 mx-auto">
                    {!! $page->getContent('content', '
                    <h2 class="text-3xl font-bold text-gray-800">Our Story</h2>
                    <p>Founded in 2024, Family Cloud emerged from the recognition that families needed a better way to share and preserve their digital memories. Unlike social media platforms where your content becomes public and subject to algorithms, we provide a private sanctuary for your family\'s most important moments.</p>

                    <h2 class="text-3xl font-bold text-gray-800">Our Mission</h2>
                    <p>We believe that family memories deserve the highest level of protection and the easiest sharing experience. Our mission is to provide families with a secure, intuitive platform that brings loved ones closer together through shared experiences and preserved memories.</p>

                    <h2 class="text-3xl font-bold text-gray-800">What Makes Us Different</h2>
                    <p>Privacy is not just a feature for us—it\'s our foundation. Every photo, video, and document you share is encrypted and stored securely. We don\'t sell your data, show advertisements, or use your content for any purpose other than helping your family stay connected.</p>

                    <h2 class="text-3xl font-bold text-gray-800">Our Values</h2>
                    <ul class="space-y-2">
                        <li class="flex items-start">
                            <svg class="h-6 w-6 text-blue-500 mr-2" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
                            </svg>
                            <span><strong class="text-gray-800">Privacy First:</strong> Your family\'s content remains private and secure</span>
                        </li>
                        <li class="flex items-start">
                            <svg class="h-6 w-6 text-blue-500 mr-2" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
                            </svg>
                            <span><strong class="text-gray-800">Simplicity:</strong> Easy to use for all family members, regardless of technical expertise</span>
                        </li>
                        <li class="flex items-start">
                            <svg class="h-6 w-6 text-blue-500 mr-2" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
                            </svg>
                            <span><strong class="text-gray-800">Reliability:</strong> Your memories are safe with our robust backup and security systems</span>
                        </li>
                        <li class="flex items-start">
                            <svg class="h-6 w-6 text-blue-500 mr-2" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
                            </svg>
                            <span><strong class="text-gray-800">Family Focused:</strong> Every feature is designed with families in mind</span>
                        </li>
                    </ul>
                    ') !!}
                </div>

                @if($page->getImagePath('team_image'))
                <div class="px-8 md:px-12 pb-12">
                    <div class="overflow-hidden rounded-xl shadow-lg">
                        <img class="w-full object-cover transform transition-all duration-500 hover:scale-105"
                             src="{{ $page->getImagePath('team_image') }}"
                             alt="Our Team">
                        <div class="p-4 bg-gray-50 text-center text-gray-600 italic">
                            {{ $page->getContent('team_caption', 'Our dedicated team working to keep families connected') }}
                        </div>
                    </div>
                </div>
                @endif
            </div>
        </div>
    </div>

    <!-- Team Section -->
    <div class="py-20 bg-gradient-to-b from-white to-blue-50">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="text-center mb-16">
                <h2 class="text-3xl md:text-4xl font-extrabold text-gray-900 mb-4">Meet Our Team</h2>
                <p class="text-xl text-gray-600 max-w-2xl mx-auto">
                    Passionate individuals dedicated to keeping families connected through secure technology
                </p>
                <div class="mt-6 mx-auto w-24 border-b-4 border-blue-500 rounded"></div>
            </div>

            <div class="mt-12 grid gap-12 lg:grid-cols-3">
                <!-- Team Member 1 -->
                <div class="bg-white rounded-xl shadow-md overflow-hidden transform transition duration-500 hover:scale-105 hover:shadow-lg">
                    <div class="p-1 bg-gradient-to-r from-blue-400 to-indigo-500">
                        <div class="bg-white p-5">
                            <div class="flex flex-col items-center">
                                <div class="relative mb-4">
                                    <div class="h-24 w-24 rounded-full overflow-hidden ring-4 ring-blue-100">
                                        <img class="h-full w-full object-cover" src="https://images.unsplash.com/photo-1494790108755-2616b612b786?ixlib=rb-1.2.1&ixid=eyJhcHBfaWQiOjEyMDd9&auto=format&fit=facearea&facepad=2&w=256&h=256&q=80" alt="Sarah Johnson">
                                    </div>
                                    <div class="absolute -bottom-1 -right-1 bg-blue-500 rounded-full p-1">
                                        <svg class="h-5 w-5 text-white" fill="currentColor" viewBox="0 0 20 20">
                                            <path fill-rule="evenodd" d="M6.267 3.455a3.066 3.066 0 001.745-.723 3.066 3.066 0 013.976 0 3.066 3.066 0 001.745.723 3.066 3.066 0 012.812 2.812c.051.643.304 1.254.723 1.745a3.066 3.066 0 010 3.976 3.066 3.066 0 00-.723 1.745 3.066 3.066 0 01-2.812 2.812 3.066 3.066 0 00-1.745.723 3.066 3.066 0 01-3.976 0 3.066 3.066 0 00-1.745-.723 3.066 3.066 0 01-2.812-2.812 3.066 3.066 0 00-.723-1.745 3.066 3.066 0 010-3.976 3.066 3.066 0 00.723-1.745 3.066 3.066 0 012.812-2.812zm7.44 5.252a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd" />
                                        </svg>
                                    </div>
                                </div>
                                <div class="text-center">
                                    <h3 class="text-xl font-bold text-gray-900">Sarah Johnson</h3>
                                    <div class="text-blue-600 font-medium mb-2">Founder & CEO</div>
                                    <p class="text-gray-600">Passionate about connecting families through technology</p>
                                </div>
                                <div class="mt-4 flex space-x-3">
                                    <a href="#" class="text-gray-400 hover:text-blue-500">
                                        <svg class="h-5 w-5" fill="currentColor" viewBox="0 0 20 20">
                                            <path d="M6.29 18.251c7.547 0 11.675-6.253 11.675-11.675 0-.178 0-.355-.012-.53A8.348 8.348 0 0020 3.92a8.19 8.19 0 01-2.357.646 4.118 4.118 0 001.804-2.27 8.224 8.224 0 01-2.605.996 4.107 4.107 0 00-6.993 3.743 11.65 11.65 0 01-8.457-4.287 4.106 4.106 0 001.27 5.477A4.073 4.073 0 01.8 7.713v.052a4.105 4.105 0 003.292 4.022 4.095 4.095 0 01-1.853.07 4.108 4.108 0 003.834 2.85A8.233 8.233 0 010 16.407a11.616 11.616 0 006.29 1.84"></path>
                                        </svg>
                                    </a>
                                    <a href="#" class="text-gray-400 hover:text-blue-500">
                                        <svg class="h-5 w-5" fill="currentColor" viewBox="0 0 20 20">
                                            <path fill-rule="evenodd" d="M16.338 16.338H13.67V12.16c0-1-.02-2.285-1.39-2.285-1.39 0-1.6 1.087-1.6 2.21v4.253H8.014v-8.59h2.559v1.174h.037c.356-.675 1.227-1.387 2.526-1.387 2.703 0 3.203 1.778 3.203 4.092v4.711zM5.005 6.575a1.548 1.548 0 11-.003-3.096 1.548 1.548 0 01.003 3.096zm-1.337 9.763H6.34v-8.59H3.667v8.59zM17.668 1H2.328C1.595 1 1 1.581 1 2.298v15.403C1 18.418 1.595 19 2.328 19h15.34c.734 0 1.332-.582 1.332-1.299V2.298C19 1.581 18.402 1 17.668 1z" clip-rule="evenodd"></path>
                                        </svg>
                                    </a>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Team Member 2 -->
                <div class="bg-white rounded-xl shadow-md overflow-hidden transform transition duration-500 hover:scale-105 hover:shadow-lg">
                    <div class="p-1 bg-gradient-to-r from-blue-400 to-indigo-500">
                        <div class="bg-white p-5">
                            <div class="flex flex-col items-center">
                                <div class="relative mb-4">
                                    <div class="h-24 w-24 rounded-full overflow-hidden ring-4 ring-blue-100">
                                        <img class="h-full w-full object-cover" src="https://images.unsplash.com/photo-1519244703995-f4e0f30006d5?ixlib=rb-1.2.1&ixid=eyJhcHBfaWQiOjEyMDd9&auto=format&fit=facearea&facepad=2&w=256&h=256&q=80" alt="Michael Chen">
                                    </div>
                                    <div class="absolute -bottom-1 -right-1 bg-blue-500 rounded-full p-1">
                                        <svg class="h-5 w-5 text-white" fill="currentColor" viewBox="0 0 20 20">
                                            <path fill-rule="evenodd" d="M6.267 3.455a3.066 3.066 0 001.745-.723 3.066 3.066 0 013.976 0 3.066 3.066 0 001.745.723 3.066 3.066 0 012.812 2.812c.051.643.304 1.254.723 1.745a3.066 3.066 0 010 3.976 3.066 3.066 0 00-.723 1.745 3.066 3.066 0 01-2.812 2.812 3.066 3.066 0 00-1.745.723 3.066 3.066 0 01-3.976 0 3.066 3.066 0 00-1.745-.723 3.066 3.066 0 01-2.812-2.812 3.066 3.066 0 00-.723-1.745 3.066 3.066 0 010-3.976 3.066 3.066 0 00.723-1.745 3.066 3.066 0 012.812-2.812zm7.44 5.252a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd" />
                                        </svg>
                                    </div>
                                </div>
                                <div class="text-center">
                                    <h3 class="text-xl font-bold text-gray-900">Michael Chen</h3>
                                    <div class="text-blue-600 font-medium mb-2">CTO</div>
                                    <p class="text-gray-600">Ensuring your data stays secure and accessible</p>
                                </div>
                                <div class="mt-4 flex space-x-3">
                                    <a href="#" class="text-gray-400 hover:text-blue-500">
                                        <svg class="h-5 w-5" fill="currentColor" viewBox="0 0 20 20">
                                            <path d="M6.29 18.251c7.547 0 11.675-6.253 11.675-11.675 0-.178 0-.355-.012-.53A8.348 8.348 0 0020 3.92a8.19 8.19 0 01-2.357.646 4.118 4.118 0 001.804-2.27 8.224 8.224 0 01-2.605.996 4.107 4.107 0 00-6.993 3.743 11.65 11.65 0 01-8.457-4.287 4.106 4.106 0 001.27 5.477A4.073 4.073 0 01.8 7.713v.052a4.105 4.105 0 003.292 4.022 4.095 4.095 0 01-1.853.07 4.108 4.108 0 003.834 2.85A8.233 8.233 0 010 16.407a11.616 11.616 0 006.29 1.84"></path>
                                        </svg>
                                    </a>
                                    <a href="#" class="text-gray-400 hover:text-blue-500">
                                        <svg class="h-5 w-5" fill="currentColor" viewBox="0 0 20 20">
                                            <path fill-rule="evenodd" d="M16.338 16.338H13.67V12.16c0-1-.02-2.285-1.39-2.285-1.39 0-1.6 1.087-1.6 2.21v4.253H8.014v-8.59h2.559v1.174h.037c.356-.675 1.227-1.387 2.526-1.387 2.703 0 3.203 1.778 3.203 4.092v4.711zM5.005 6.575a1.548 1.548 0 11-.003-3.096 1.548 1.548 0 01.003 3.096zm-1.337 9.763H6.34v-8.59H3.667v8.59zM17.668 1H2.328C1.595 1 1 1.581 1 2.298v15.403C1 18.418 1.595 19 2.328 19h15.34c.734 0 1.332-.582 1.332-1.299V2.298C19 1.581 18.402 1 17.668 1z" clip-rule="evenodd"></path>
                                        </svg>
                                    </a>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Team Member 3 -->
                <div class="bg-white rounded-xl shadow-md overflow-hidden transform transition duration-500 hover:scale-105 hover:shadow-lg">
                    <div class="p-1 bg-gradient-to-r from-blue-400 to-indigo-500">
                        <div class="bg-white p-5">
                            <div class="flex flex-col items-center">
                                <div class="relative mb-4">
                                    <div class="h-24 w-24 rounded-full overflow-hidden ring-4 ring-blue-100">
                                        <img class="h-full w-full object-cover" src="https://images.unsplash.com/photo-1506794778202-cad84cf45f1d?ixlib=rb-1.2.1&ixid=eyJhcHBfaWQiOjEyMDd9&auto=format&fit=facearea&facepad=2&w=256&h=256&q=80" alt="Emily Rodriguez">
                                    </div>
                                    <div class="absolute -bottom-1 -right-1 bg-blue-500 rounded-full p-1">
                                        <svg class="h-5 w-5 text-white" fill="currentColor" viewBox="0 0 20 20">
                                            <path fill-rule="evenodd" d="M6.267 3.455a3.066 3.066 0 001.745-.723 3.066 3.066 0 013.976 0 3.066 3.066 0 001.745.723 3.066 3.066 0 012.812 2.812c.051.643.304 1.254.723 1.745a3.066 3.066 0 010 3.976 3.066 3.066 0 00-.723 1.745 3.066 3.066 0 01-2.812 2.812 3.066 3.066 0 00-1.745.723 3.066 3.066 0 01-3.976 0 3.066 3.066 0 00-1.745-.723 3.066 3.066 0 01-2.812-2.812 3.066 3.066 0 00-.723-1.745 3.066 3.066 0 010-3.976 3.066 3.066 0 00.723-1.745 3.066 3.066 0 012.812-2.812zm7.44 5.252a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd" />
                                        </svg>
                                    </div>
                                </div>
                                <div class="text-center">
                                    <h3 class="text-xl font-bold text-gray-900">Emily Rodriguez</h3>
                                    <div class="text-blue-600 font-medium mb-2">Head of Design</div>
                                    <p class="text-gray-600">Creating beautiful experiences for families</p>
                                </div>
                                <div class="mt-4 flex space-x-3">
                                    <a href="#" class="text-gray-400 hover:text-blue-500">
                                        <svg class="h-5 w-5" fill="currentColor" viewBox="0 0 20 20">
                                            <path d="M6.29 18.251c7.547 0 11.675-6.253 11.675-11.675 0-.178 0-.355-.012-.53A8.348 8.348 0 0020 3.92a8.19 8.19 0 01-2.357.646 4.118 4.118 0 001.804-2.27 8.224 8.224 0 01-2.605.996 4.107 4.107 0 00-6.993 3.743 11.65 11.65 0 01-8.457-4.287 4.106 4.106 0 001.27 5.477A4.073 4.073 0 01.8 7.713v.052a4.105 4.105 0 003.292 4.022 4.095 4.095 0 01-1.853.07 4.108 4.108 0 003.834 2.85A8.233 8.233 0 010 16.407a11.616 11.616 0 006.29 1.84"></path>
                                        </svg>
                                    </a>
                                    <a href="#" class="text-gray-400 hover:text-blue-500">
                                        <svg class="h-5 w-5" fill="currentColor" viewBox="0 0 20 20">
                                            <path fill-rule="evenodd" d="M16.338 16.338H13.67V12.16c0-1-.02-2.285-1.39-2.285-1.39 0-1.6 1.087-1.6 2.21v4.253H8.014v-8.59h2.559v1.174h.037c.356-.675 1.227-1.387 2.526-1.387 2.703 0 3.203 1.778 3.203 4.092v4.711zM5.005 6.575a1.548 1.548 0 11-.003-3.096 1.548 1.548 0 01.003 3.096zm-1.337 9.763H6.34v-8.59H3.667v8.59zM17.668 1H2.328C1.595 1 1 1.581 1 2.298v15.403C1 18.418 1.595 19 2.328 19h15.34c.734 0 1.332-.582 1.332-1.299V2.298C19 1.581 18.402 1 17.668 1z" clip-rule="evenodd"></path>
                                        </svg>
                                    </a>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Features Section -->
    <div class="py-20 bg-white">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="text-center mb-16">
                <h2 class="text-3xl md:text-4xl font-extrabold text-gray-900 mb-4">Why Choose Family Cloud?</h2>
                <p class="text-xl text-gray-600 max-w-2xl mx-auto">
                    We built Family Cloud with your family's needs in mind
                </p>
                <div class="mt-6 mx-auto w-24 border-b-4 border-blue-500 rounded"></div>
            </div>

            <div class="mt-12 grid gap-8 md:grid-cols-2 lg:grid-cols-3">
                <!-- Feature 1 -->
                <div class="bg-blue-50 rounded-xl p-8 shadow-md transform transition duration-500 hover:shadow-lg hover:-translate-y-1">
                    <div class="h-12 w-12 rounded-full bg-blue-100 flex items-center justify-center mb-5">
                        <svg class="h-6 w-6 text-blue-600" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z" />
                        </svg>
                    </div>
                    <h3 class="text-xl font-bold text-gray-900 mb-3">Secure Sharing</h3>
                    <p class="text-gray-600">End-to-end encryption ensures your family memories stay private and secure from unauthorized access.</p>
                </div>

                <!-- Feature 2 -->
                <div class="bg-blue-50 rounded-xl p-8 shadow-md transform transition duration-500 hover:shadow-lg hover:-translate-y-1">
                    <div class="h-12 w-12 rounded-full bg-blue-100 flex items-center justify-center mb-5">
                        <svg class="h-6 w-6 text-blue-600" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z" />
                        </svg>
                    </div>
                    <h3 class="text-xl font-bold text-gray-900 mb-3">Unlimited Storage</h3>
                    <p class="text-gray-600">Never worry about running out of space for your precious family photos and videos again.</p>
                </div>

                <!-- Feature 3 -->
                <div class="bg-blue-50 rounded-xl p-8 shadow-md transform transition duration-500 hover:shadow-lg hover:-translate-y-1">
                    <div class="h-12 w-12 rounded-full bg-blue-100 flex items-center justify-center mb-5">
                        <svg class="h-6 w-6 text-blue-600" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z" />
                        </svg>
                    </div>
                    <h3 class="text-xl font-bold text-gray-900 mb-3">Family Friendly</h3>
                    <p class="text-gray-600">Simple interfaces designed for family members of all ages and technical abilities.</p>
                </div>
            </div>
        </div>
    </div>

    <!-- Contact CTA -->
    <div class="bg-gradient-to-r from-blue-600 to-indigo-700">
        <div class="max-w-4xl mx-auto text-center py-16 px-4 sm:py-20 sm:px-6 lg:px-8 relative overflow-hidden">
            <!-- Decorative elements -->
            <div class="absolute top-0 left-0 transform -translate-x-1/2 -translate-y-1/2">
                <svg class="h-48 w-48 text-blue-400 opacity-20" viewBox="0 0 200 200" fill="currentColor">
                    <path d="M46.2,-58.4C58.6,-48.3,66.7,-33.4,72.2,-16.8C77.8,-0.1,80.8,18.3,74.9,33.5C68.9,48.7,53.9,60.7,37.5,67.1C21,73.4,3,74.1,-14.7,70.3C-32.5,66.6,-50,58.3,-65.1,44.6C-80.1,31,-92.6,12,-92.6,-7.1C-92.5,-26.1,-79.9,-45.3,-63.6,-55.5C-47.2,-65.7,-27.1,-67,-9.1,-66.2C8.9,-65.3,33.8,-68.5,46.2,-58.4Z" transform="translate(100 100)" />
                </svg>
            </div>
            <div class="absolute bottom-0 right-0 transform translate-x-1/2 translate-y-1/2">
                <svg class="h-48 w-48 text-indigo-400 opacity-20" viewBox="0 0 200 200" fill="currentColor">
                    <path d="M57.7,-57.2C71.5,-45.7,77.8,-23.8,75.3,-4.4C72.8,15,61.5,30,48.4,40.8C35.3,51.6,20.3,58.3,2.4,56.6C-15.5,55,-36.3,45.1,-51.1,29.5C-65.9,13.9,-74.6,-7.3,-70.1,-25.5C-65.6,-43.8,-47.9,-59,-29.9,-70.4C-11.9,-81.7,6.4,-89.3,21.9,-84.3C37.3,-79.2,43.9,-68.7,57.7,-57.2Z" transform="translate(100 100)" />
                </svg>
            </div>

            <div class="relative">
                <h2 class="text-3xl md:text-4xl font-extrabold text-white mb-8">
                    <span class="block">Ready to get started?</span>
                    <span class="block mt-2">Join thousands of families today.</span>
                </h2>
                <p class="mt-4 text-xl leading-6 text-blue-100 mb-10">
                    Create your family cloud and start sharing memories securely.
                </p>
                <div class="flex flex-col sm:flex-row gap-4 justify-center">
                    <a href="{{ route('register') }}" class="px-8 py-3 border border-transparent text-base font-medium rounded-lg bg-white text-blue-700 hover:bg-blue-50 shadow-lg hover:shadow-xl transition duration-300 transform hover:-translate-y-1">
                        Get Started Free
                    </a>
                    <a href="{{ route('contact') }}" class="px-8 py-3 border border-blue-100 text-base font-medium rounded-lg text-white hover:bg-blue-800 hover:border-white transition duration-300 transform hover:-translate-y-1">
                        Contact Us
                    </a>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
