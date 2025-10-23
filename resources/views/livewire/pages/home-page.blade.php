<div class="min-h-screen flex flex-col bg-gradient-to-br from-blue-50 via-white to-blue-100">

    <!-- HEADER -->
    <header class="w-full bg-white shadow-sm">
        <div class="max-w-[1200px] mx-auto flex justify-between items-center px-8 py-4">
            <div class="flex items-center gap-2">
                <img src="{{ asset('images/circle.png') }}" alt="Logo" class="w-12 h-12 rounded-full object-cover">
                <h1 class="text-xl font-semibold text-gray-700">Phenikaa Clinic</h1>
            </div>
            <nav class="space-x-4">
                <a wire:navigate href="{{ route('register') }}" 
                   class="text-gray-700 hover:text-blue-600 font-medium">Đăng ký</a>
                <a wire:navigate href="{{ route('login') }}" 
                   class="px-4 py-2 rounded-full border border-red-400 text-red-500 hover:bg-red-50 font-medium transition">
                   Đăng nhập
                </a>
            </nav>
        </div>
    </header>

    <!--  MAIN CONTENT -->
    <main class="flex-grow flex flex-col items-center justify-center text-center px-6">
        <div class="max-w-[800px]">
            <h2 class="text-5xl font-bold text-gray-800 mb-6 leading-tight">
                Chào mừng đến với <span class="text-red-500">Phenikaa Clinic</span>
            </h2>
            <p class="text-gray-600 text-lg mb-8">
                Nền tảng đặt lịch và chăm sóc sức khỏe thông minh – nhanh chóng, chính xác và an toàn.
            </p>
            <div class="space-x-4">
                <a wire:navigate href="{{ route('login') }}" 
                   class="bg-red-500 text-white px-8 py-3 rounded-full text-lg hover:bg-red-600 shadow-md transition">
                   Đăng nhập
                </a>
                <a wire:navigate href="{{ route('register') }}" 
                   class="bg-white border border-gray-300 text-gray-800 px-8 py-3 rounded-full text-lg hover:bg-gray-50 shadow-sm transition">
                   Đăng ký
                </a>
            </div>
        </div>

        <!--  Hình minh họa -->
        <div class="mt-12">
            <img src="{{ asset('images/clinic-illustration.jpg') }}" alt="Clinic Illustration" class="w-[700px] mx-auto">
        </div>
    </main>

    <!--  FOOTER -->
    <footer class="bg-white border-t border-gray-200 py-6 mt-8">
        <div class="max-w-[1200px] mx-auto text-center text-sm text-gray-500">
            <p>© 2025 Phenikaa Clinic. All rights reserved.</p>
            <p class="mt-1">Giải pháp chăm sóc sức khỏe toàn diện cho mọi người.</p>
        </div>
    </footer>

</div>
