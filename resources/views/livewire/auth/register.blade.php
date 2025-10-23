

<div class="flex min-w-[375px] max-w-[1024px] mx-auto bg-white justify-center items-center mt-12 " >
    <div class="w-full max-w-md bg-white shadow-lg rounded-xl p-8" style="background-color: rgba(41, 71, 83, 0.05);">
        <h1 class="text-2xl font-bold mb-2 text-gray-800 text-center">Tạo tài khoản mới</h1>
        <p class="text-sm text-gray-600 text-center mb-6">Điền thông tin để đăng ký</p>

        @if (session('error'))
            <div class="text-red-600 mb-4 text-center bg-red-50 p-2 rounded">
                {{ session('error') }}
            </div>
        @endif

        <form wire:submit.prevent="register" class="space-y-5">
            <!-- Name -->
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Họ và tên</label>
                <input type="text" wire:model="name" placeholder="e.g. Carlo Biado"
                    class="w-full border border-gray-300 rounded-lg px-4 py-2 focus:outline-none focus:ring-2 focus:ring-orange-500 focus:border-orange-500">
                @error('name') <span class="text-red-600 text-xs">{{ $message }}</span> @enderror
            </div>

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

            <!-- Confirm Password -->
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Nhập lại mật khẩu</label>
                <input type="password" wire:model="password_confirmation" placeholder="••••••••"
                    class="w-full border border-gray-300 rounded-lg px-4 py-2 focus:outline-none focus:ring-2 focus:ring-orange-500 focus:border-orange-500">
                @error('password_confirmation') <span class="text-red-600 text-xs">{{ $message }}</span> @enderror
            </div>

            <!-- Submit -->
            <button type="submit"
                class="w-full bg-orange-600 hover:bg-orange-700 text-white font-semibold py-2 rounded-lg shadow-md transition duration-200" wire:loading.attr="disabled">
                <span wire:loading.remove>Đăng ký</span>
                <span wire:loading>Đang xử lý...</span>
            </button>
        </form>

        <!-- Login link -->
        <div class="mt-6 text-center">
            <p class="text-sm text-gray-600">
                Đã có tài khoản?
                <a href="{{ route('login') }}" class="text-orange-600 font-medium hover:underline">Đăng nhập ngay</a>
            </p>
        </div>
    </div>
</div>
