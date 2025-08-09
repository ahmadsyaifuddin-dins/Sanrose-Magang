<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            {{ __('Dashboard') }}
        </h2>
    </x-slot>

    <div class="py-8">
        <div class="max-w-md mx-auto px-4">
            <div class="bg-gradient-to-br from-white via-gray-50 to-gray-200 dark:from-gray-800 dark:via-gray-700 dark:to-gray-900 rounded-2xl shadow-lg">
                <div class="p-6 text-center">
                    <p class="text-xl font-semibold text-gray-900 dark:text-gray-100 mb-1">Halo, {{ auth()->user()->name }}!</p>
                    <p class="text-gray-600 dark:text-gray-300">Selamat datang di <span class="font-bold text-indigo-600 dark:text-indigo-400" style="text-shadow: 0 0 8px #6366f1, 0 0 16px #6366f1;">Sanrose Magang</span></p>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
