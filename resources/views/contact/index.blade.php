@extends('layouts.app')

@section('title', $page->getContent('title', 'Contact Us'))
@section('meta_description', $page->meta_description ?? 'Get in touch with Family Cloud - We\'re here to help')

@section('content')
<div class="bg-white">
    <div class="max-w-7xl mx-auto py-16 px-4 sm:px-6 lg:px-8">
        <div class="max-w-lg mx-auto md:max-w-none md:grid md:grid-cols-2 md:gap-8">
            <div>
                <h2 class="text-2xl font-extrabold text-gray-900 sm:text-3xl">
                    {{ $page->getContent('title', 'Contact Us') }}
                </h2>
                <div class="mt-3">
                    <p class="text-lg text-gray-500">
                        {{ $page->getContent('description', 'We\'d love to hear from you. Send us a message and we\'ll respond as soon as possible.') }}
                    </p>
                </div>
                <div class="mt-9">
                    <div class="flex">
                        <div class="flex-shrink-0">
                            <svg class="h-6 w-6 text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 5a2 2 0 012-2h3.28a1 1 0 01.948.684l1.498 4.493a1 1 0 01-.502 1.21l-2.257 1.13a11.042 11.042 0 005.516 5.516l1.13-2.257a1 1 0 011.21-.502l4.493 1.498a1 1 0 01.684.949V19a2 2 0 01-2 2h-1C9.716 21 3 14.284 3 6V5z" />
                            </svg>
                        </div>
                        <div class="ml-3 text-base text-gray-500">
                            <p>{{ $page->getContent('phone', '+1 (555) 123-4567') }}</p>
                            <p class="mt-1">{{ $page->getContent('phone_hours', 'Mon-Fri 9am to 6pm PST') }}</p>
                        </div>
                    </div>
                    <div class="mt-6 flex">
                        <div class="flex-shrink-0">
                            <svg class="h-6 w-6 text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 4.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z" />
                            </svg>
                        </div>
                        <div class="ml-3 text-base text-gray-500">
                            <p>{{ $page->getContent('email', 'support@familycloud.com') }}</p>
                            <p class="mt-1">{{ $page->getContent('email_description', 'We\'ll respond within 24 hours') }}</p>
                        </div>
                    </div>
                    <div class="mt-6 flex">
                        <div class="flex-shrink-0">
                            <svg class="h-6 w-6 text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z" />
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z" />
                            </svg>
                        </div>
                        <div class="ml-3 text-base text-gray-500">
                            <p>{{ $page->getContent('address', '123 Family Street, Cloud City, CC 12345') }}</p>
                        </div>
                    </div>
                </div>
            </div>
            <div class="mt-12 sm:mt-16 md:mt-0">
                @if(session('success'))
                    <div class="mb-4 bg-green-50 border border-green-200 text-green-700 px-4 py-3 rounded relative">
                        <strong class="font-bold">Success!</strong>
                        <span class="block sm:inline">{{ session('success') }}</span>
                    </div>
                @endif

                <div id="contact-form-success" class="mb-4 bg-green-50 border border-green-200 text-green-700 px-4 py-3 rounded relative" style="display: none;">
                    <strong class="font-bold">Success!</strong>
                    <span class="block sm:inline">Thank you for your message. We will get back to you soon!</span>
                </div>

                <form id="contact-form" action="{{ route('contact.store') }}" method="POST" class="grid grid-cols-1 gap-y-6">
                    @csrf
                    <div>
                        <label for="name" class="block text-sm font-medium text-gray-700">Name</label>
                        <div class="mt-1">
                            <input type="text" name="name" id="name" value="{{ old('name') }}" class="py-3 px-4 block w-full shadow-sm focus:ring-indigo-500 focus:border-indigo-500 border-gray-300 rounded-md @error('name') border-red-300 @enderror" required>
                        </div>
                        <p class="mt-2 text-sm text-red-600 error-message" id="name-error"></p>
                        @error('name')
                            <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>
                    <div>
                        <label for="email" class="block text-sm font-medium text-gray-700">Email</label>
                        <div class="mt-1">
                            <input type="email" name="email" id="email" value="{{ old('email') }}" class="py-3 px-4 block w-full shadow-sm focus:ring-indigo-500 focus:border-indigo-500 border-gray-300 rounded-md @error('email') border-red-300 @enderror" required>
                        </div>
                        <p class="mt-2 text-sm text-red-600 error-message" id="email-error"></p>
                        @error('email')
                            <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>
                    <div>
                        <label for="subject" class="block text-sm font-medium text-gray-700">Subject</label>
                        <div class="mt-1">
                            <input type="text" name="subject" id="subject" value="{{ old('subject') }}" class="py-3 px-4 block w-full shadow-sm focus:ring-indigo-500 focus:border-indigo-500 border-gray-300 rounded-md @error('subject') border-red-300 @enderror" required>
                        </div>
                        <p class="mt-2 text-sm text-red-600 error-message" id="subject-error"></p>
                        @error('subject')
                            <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>
                    <div>
                        <label for="message" class="block text-sm font-medium text-gray-700">Message</label>
                        <div class="mt-1">
                            <textarea id="message" name="message" rows="4" class="py-3 px-4 block w-full shadow-sm focus:ring-indigo-500 focus:border-indigo-500 border-gray-300 rounded-md @error('message') border-red-300 @enderror" required>{{ old('message') }}</textarea>
                        </div>
                        <p class="mt-2 text-sm text-red-600 error-message" id="message-error"></p>
                        @error('message')
                            <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>
                    <div>
                        <button type="submit" id="submit-btn" class="w-full inline-flex justify-center py-3 px-6 border border-transparent shadow-sm text-base font-medium rounded-md text-white bg-indigo-600 hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">
                            <span>Send Message</span>
                            <svg id="loading-spinner" class="animate-spin ml-2 h-5 w-5 text-white" style="display: none;" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                                <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                                <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                            </svg>
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection

@section('scripts')
<script>
    document.addEventListener('DOMContentLoaded', function() {
        const contactForm = document.getElementById('contact-form');
        const contactSuccess = document.getElementById('contact-form-success');
        const submitBtn = document.getElementById('submit-btn');
        const loadingSpinner = document.getElementById('loading-spinner');
        const errorMessages = document.querySelectorAll('.error-message');

        if (contactForm) {
            contactForm.addEventListener('submit', function(e) {
                e.preventDefault();

                // Clear previous error messages
                errorMessages.forEach(el => el.textContent = '');

                // Show loading state
                submitBtn.disabled = true;
                submitBtn.querySelector('span').textContent = 'Sending...';
                loadingSpinner.style.display = 'inline-block';

                // Get form data
                const formData = new FormData(contactForm);

                // Send AJAX request
                fetch(contactForm.action, {
                    method: 'POST',
                    body: formData,
                    headers: {
                        'X-Requested-With': 'XMLHttpRequest'
                    }
                })
                .then(response => response.json())
                .then(data => {
                    if (data.success) {
                        // Show success message
                        contactSuccess.style.display = 'block';
                        contactForm.reset();

                        // Scroll to success message
                        contactSuccess.scrollIntoView({ behavior: 'smooth', block: 'start' });
                    } else if (data.errors) {
                        // Show validation errors
                        Object.keys(data.errors).forEach(field => {
                            const errorEl = document.getElementById(field + '-error');
                            if (errorEl) {
                                errorEl.textContent = data.errors[field][0];
                            }
                        });
                    }
                })
                .catch(error => {
                    console.error('Error:', error);
                })
                .finally(() => {
                    // Reset loading state
                    submitBtn.disabled = false;
                    submitBtn.querySelector('span').textContent = 'Send Message';
                    loadingSpinner.style.display = 'none';
                });
            });
        }
    });
</script>
@endsection
