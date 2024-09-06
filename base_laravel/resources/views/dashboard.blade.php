<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            {{ __('Dashboard') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900 dark:text-gray-100">
                    {{ __("Hello!") }}
                </div>
            </div>

            <!-- Phần điểm đến nổi bật -->
            <div class="mt-8">
                <h2 class="text-2xl font-bold text-gray-800 dark:text-gray-200">Điểm Đến Nổi Bật</h2>
                <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mt-4">
                    <!-- Điểm đến 1 -->
                    <div class="bg-white dark:bg-gray-800 rounded-lg shadow-lg">
                        <img src="/img/halong.png" class="w-full h-48 object-cover rounded-t-lg" alt="Điểm đến 1">
                        <div class="p-4">
                            <h3 class="text-xl font-semibold text-gray-900 dark:text-gray-100">Vịnh Hạ Long</h3>
                            <p class="text-gray-600 dark:text-gray-400 mt-2">Vịnh Hạ Long là một trong những kỳ quan thiên nhiên nổi tiếng của Việt Nam, được UNESCO công nhận là Di sản Thế giới</p>
                            <a href="#" class="text-blue-500 hover:underline mt-2 block">Tìm hiểu thêm</a>
                        </div>
                    </div>
                    <!-- Điểm đến 2 -->
                    <div class="bg-white dark:bg-gray-800 rounded-lg shadow-lg">
                        <img src="/img/Sapa.png" class="w-full h-48 object-cover rounded-t-lg" alt="Điểm đến 2">
                        <div class="p-4">
                            <h3 class="text-xl font-semibold text-gray-900 dark:text-gray-100">Sapa</h3>
                            <p class="text-gray-600 dark:text-gray-400 mt-2">Được bao quanh bởi những cánh đồng bậc thang xanh mướt, những ngọn núi cao và khí hậu mát mẻ quanh năm, Sapa là điểm đến lý tưởng cho những ai yêu thích khám phá và nghỉ dưỡng.</p>
                            <a href="#" class="text-blue-500 hover:underline mt-2 block">Tìm hiểu thêm</a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
