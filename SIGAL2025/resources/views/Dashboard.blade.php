<!-- resources/views/dashboard.blade.php -->
@extends('layouts.app')

@section('title', 'Dashboard - SIGAL')

@section('content')
    <!-- Mensaje de error si existe -->
    @if (isset($error))
        <div class="p-4 mb-4 text-sm text-red-700 bg-red-100 rounded-lg dark:bg-red-200 dark:text-red-800" role="alert">
            {{ $error }}
        </div>
    @endif

    <div class="grid grid-cols-3 gap-4 mb-4">
        <!-- Total de Compras -->
        <div class="flex items-center justify-between h-24 rounded-sm bg-gray-50 dark:bg-gray-800 p-4">
            <div>
                <p class="text-lg font-semibold text-gray-700 dark:text-gray-200">Total de Compras</p>
                <p class="text-2xl text-gray-900 dark:text-white">$ {{ number_format($total_compras, 2) }}</p>
            </div>
            <svg class="w-8 h-8 text-gray-400 dark:text-gray-500" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 3h18v18H3V3zm6 6h6m-6 4h6"/>
            </svg>
        </div>

        <!-- Total de Materiales -->
        <div class="flex items-center justify-between h-24 rounded-sm bg-gray-50 dark:bg-gray-800 p-4">
            <div>
                <p class="text-lg font-semibold text-gray-700 dark:text-gray-200">Total de Materiales</p>
                <p class="text-2xl text-gray-900 dark:text-white">{{ $total_materiales }}</p>
            </div>
            <svg class="w-8 h-8 text-gray-400 dark:text-gray-500" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4h16v16H4V4zm4 4h8m-8 4h8"/>
            </svg>
        </div>

        <!-- Materiales con Stock Bajo -->
        <div class="flex items-center justify-between h-24 rounded-sm bg-gray-50 dark:bg-gray-800 p-4">
            <div>
                <p class="text-lg font-semibold text-gray-700 dark:text-gray-200">Stock Bajo</p>
                <p class="text-2xl text-gray-900 dark:text-white">{{ $stock_bajo }}</p>
            </div>
            <svg class="w-8 h-8 text-gray-400 dark:text-gray-500" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m-8-8h16"/>
            </svg>
        </div>
    </div>

    <div class="flex items-center justify-center h-48 mb-4 rounded-sm bg-gray-50 dark:bg-gray-800">
        <p class="text-2xl text-gray-400 dark:text-gray-500"></p>
    </div>

    <div class="grid grid-cols-2 gap-4 mb-4">
        <div class="flex items-center justify-center rounded-sm bg-gray-50 h-28 dark:bg-gray-800">
            <p class="text-2xl text-gray-400 dark:text-gray-500"></p>
        </div>
        <div class="flex items-center justify-center rounded-sm bg-gray-50 h-28 dark:bg-gray-800">
            <p class="text-2xl text-gray-400 dark:text-gray-500"></p>
        </div>
    </div>
@endsection