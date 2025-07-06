@extends('family.layouts.app')

@section('title', $page ? $page->getContent('title', 'Family Dashboard') : 'Family Dashboard')

@section('content')
<div class="mb-8">
    <h1 class="text-2xl font-bold text-gray-900">Family Dashboard</h1>
    <p class="mt-1 text-sm text-gray-600">
        {{ $page ? $page->getContent('description', 'Welcome to the Family Cloud family portal. Share photos, files, and stay connected with your loved ones.') : 'Welcome to the Family Cloud family portal. Share photos, files, and stay connected with your loved ones.' }}
    </p>
</div>

<!-- Recent Galleries -->
<div class="mt-10">
    <h2 class="text-lg font-medium text-gray-900">Recent Galleries</h2>
    <div class="mt-4 grid grid-cols-1 gap-6 sm:grid-cols-2 md:grid-cols-3 lg:grid-cols-4">
        <div class="group relative">
            <div class="aspect-w-4 aspect-h-3 overflow-hidden rounded-lg bg-gray-100">
                <img src="https://placehold.co/600x400" alt="Gallery placeholder" class="object-cover">
                <div class="flex items-end p-4 opacity-0 group-hover:opacity-100" aria-hidden="true">
                    <div class="w-full rounded-md bg-white bg-opacity-75 py-2 px-4 text-center text-sm font-medium text-gray-900 backdrop-blur backdrop-filter">View Gallery</div>
                </div>
            </div>
            <div class="mt-2 flex items-center justify-between">
                <h3 class="text-sm font-medium text-gray-900">
                    <a href="#">
                        <span class="absolute inset-0"></span>
                        Summer Vacation 2024
                    </a>
                </h3>
                <p class="text-sm text-gray-500">32 photos</p>
            </div>
            <p class="mt-1 text-xs text-gray-500">Added July 5, 2025</p>
        </div>
    </div>
</div>

<!-- Recent Files -->
<div class="mt-10">
    <h2 class="text-lg font-medium text-gray-900">Recent Files</h2>
    <div class="mt-4 overflow-hidden shadow ring-1 ring-black ring-opacity-5 sm:rounded-lg">
        <table class="min-w-full divide-y divide-gray-300">
            <thead class="bg-gray-50">
                <tr>
                    <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Name</th>
                    <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Size</th>
                    <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Type</th>
                    <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Added</th>
                    <th scope="col" class="relative px-6 py-3">
                        <span class="sr-only">Download</span>
                    </th>
                </tr>
            </thead>
            <tbody class="bg-white divide-y divide-gray-200">
                <tr>
                    <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900">Family Reunion Plan.docx</td>
                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">2.4 MB</td>
                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">Document</td>
                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">July 6, 2025</td>
                    <td class="px-6 py-4 whitespace-nowrap text-right text-sm font-medium">
                        <a href="#" class="text-indigo-600 hover:text-indigo-900">Download</a>
                    </td>
                </tr>
            </tbody>
        </table>
    </div>
</div>

<!-- Family Announcements -->
<div class="mt-10">
    <h2 class="text-lg font-medium text-gray-900">Family Announcements</h2>
    <div class="mt-4 bg-white shadow overflow-hidden sm:rounded-md">
        <ul role="list" class="divide-y divide-gray-200">
            <li>
                <div class="px-4 py-4 sm:px-6">
                    <div class="flex items-center justify-between">
                        <p class="text-sm font-medium text-indigo-600 truncate">Summer Barbecue</p>
                        <div class="ml-2 flex-shrink-0 flex">
                            <p class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-green-100 text-green-800">New</p>
                        </div>
                    </div>
                    <div class="mt-2 sm:flex sm:justify-between">
                        <div class="sm:flex">
                            <p class="flex items-center text-sm text-gray-500">Posted by Alyssa</p>
                        </div>
                        <div class="mt-2 flex items-center text-sm text-gray-500 sm:mt-0">
                            <svg class="flex-shrink-0 mr-1.5 h-5 w-5 text-gray-400" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor" aria-hidden="true">
                                <path fill-rule="evenodd" d="M6 2a1 1 0 00-1 1v1H4a2 2 0 00-2 2v10a2 2 0 002 2h12a2 2 0 002-2V6a2 2 0 00-2-2h-1V3a1 1 0 10-2 0v1H7V3a1 1 0 00-1-1zm0 5a1 1 0 000 2h8a1 1 0 100-2H6z" clip-rule="evenodd" />
                            </svg>
                            <p>July 15, 2025</p>
                        </div>
                    </div>
                    <div class="mt-2">
                        <p class="text-sm text-gray-600">We'll be having our annual summer barbecue at the park. Everyone is welcome to bring a dish to share!</p>
                    </div>
                </div>
            </li>
        </ul>
    </div>
</div>
@endsection
