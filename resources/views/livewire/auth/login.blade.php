<div class="flex min-w-[375px] max-w-[1024px] mx-auto bg-white justify-center items-center mt-6 ">

    <!-- Bên trái - Logo -->
    <div class="flex-col  hidden md:flex w-1/2 bg-white justify-center items-center">
        <p class="text-3xl text-gray-600">
            Có gì mới tại
            <span class="text-orange-600 font-medium hover:no-underline">P-Clinic</span>
        </p>
        <div class="bg-white rounded-3xl overflow-hidden flex justify-center items-center 
                    border border-[#d4dadd] m-5 w-[450px] h-[430px]">
            <img src="{{ asset('images/slogan.png') }}" 
                alt="Logo"
                class="max-w-full max-h-full object-contain">
        </div>
    </div>


    <!-- Bên phải - Form đăng nhập -->
    <div class="w-full max-w-md bg-white  shadow-lg rounded-xl p-8 w-[390px] h-[430px] mt-8" style="background-color: rgba(41, 71, 83, 0.05);">
        <h1 class="text-2xl font-bold mb-2 text-gray-800 text-center">Welcome Back!</h1>
        <p class="text-sm text-gray-600 text-center mb-6">Đăng nhập để tiếp tục</p>

        @if (session('error'))
            <div class="text-red-600 mb-4 text-center bg-red-50 p-2 rounded">
                {{ session('error') }}
            </div>
        @endif

        <form wire:submit.prevent="login" class="space-y-5">
            <!-- Email -->
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Email</label>
                <input type="email" wire:model="email" placeholder="e.g. abc@email.com"
                    class="w-full border border-gray-300 rounded-lg px-4 py-2 focus:outline-none focus:ring-2 focus:ring-orange-500 focus:border-orange-500">
                @error('email') <span class="text-red-600 text-xs">{{ $message }}</span> @enderror
            </div>

            <!-- Password -->
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Mật khẩu</label>
                <input type="password" wire:model="password" placeholder="••••••••"
                    class="w-full border border-gray-300 rounded-lg px-4 py-2 focus:outline-none focus:ring-2 focus:ring-orange-500 focus:border-orange-500">
                @error('password') <span class="text-red-600 text-xs">{{ $message }}</span> @enderror
            </div>

            <!-- Remember me + Forgot password -->
            <div class="flex items-center justify-between">
                <label class="inline-flex items-center">
                    <input type="checkbox" wire:model="remember"
                        class="rounded border-gray-300 text-orange-600 focus:ring-orange-500">
                    <span class="ml-2 text-sm text-gray-600">Ghi nhớ đăng nhập</span>
                </label>
                <!-- <a href="#" class="text-sm text-orange-600 hover:underline">Quên mật khẩu?</a> -->
                <a href="{{ route('password.request') }}" class="text-sm text-orange-600 hover:underline">Quên mật khẩu?</a>
            </div>

            <!-- Submit -->
            <button type="submit"
                class="w-full bg-orange-600 hover:bg-orange-700 text-white font-semibold py-2 rounded-lg shadow-md transition duration-200">
                Đăng nhập
            </button>
        </form>

        <!-- Register link -->
        <div class="mt-6 text-center">
            <p class="text-sm text-gray-600">
                Chưa có tài khoản?
                <a href="{{ route('register') }}" class="text-orange-600 font-medium hover:underline">Đăng ký ngay</a>
            </p>
        </div>
    </div>
</div>
