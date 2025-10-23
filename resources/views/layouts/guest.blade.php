<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="csrf-token" content="{{ csrf_token() }}">

        <title>{{ config('app.name', 'Laravel') }}</title>

        <!-- Fonts -->
        <link rel="preconnect" href="https://fonts.bunny.net">
        <link href="https://fonts.bunny.net/css?family=figtree:400,500,600&display=swap" rel="stylesheet" />

        <!-- Scripts -->
        @vite(['resources/css/app.css', 'resources/js/app.js'])
        @livewireStyles
    </head>
    <body class="font-sans text-gray-900 antialiased bg-white">
        <div class=" flex flex-col justify-center items-center max-w-[1200px] mx-auto">

            <!-- Header -->
            <header class="w-full flex items-center justify-start px-6 py-4">
                <!-- Logo -->
                <div class="relative ">
                    <a href="/">
                         <img src="{{ asset('images/circle.png') }}" 
                alt="Logo" class="w-12 h-12 rounded-full object-cover">
                    </a>
                </div>

                <div class="text-xl font-semibold text-gray-700 ml-2">
                    <a href="/">Phenikaa Clinic</a>
                </div>
            </header>
            
             <!-- Nội dung chính -->
            <main class=" w-full w-[964px] h-[703px] px-4 ">
                {{ $slot }}
            </main>

            <!-- Footer -->
            <footer class="w-full text-center text-xs text-gray-500 px-6 py-4 border-t border-gray-200">
                <p class="max-w-[900px] mx-auto leading-relaxed">
                    REMINDER: Information relating to any Phenikaa Product or Service, including the Phenikaa Dashboard,
                    constitutes Phenikaa's proprietary and/or confidential information. You will not (and will not allow any
                    third party to) access or use Phenikaa’s Products for any purpose other than for those purposes specified
                    in the agreement between you (or your employer) and Phenikaa. You will not (and will not allow any third
                    party to) access the Products in order to research or build a competitive product or service or copy any
                    ideas, features, functions or graphics of the Products.
                </p>
                <p class="mt-2 text-gray-600 font-medium">
                    Copyright © Phenikaa Clinic, Inc.
                </p>
            </footer>

        </div>

        @livewireScripts
    </body>
</html>
