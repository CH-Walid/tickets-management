@extends('admin.layout')
@section('title', 'Services & Catégories')
@section('content')
<div class="w-full px-2 md:px-8 py-8 bg-white/80 backdrop-blur rounded-3xl shadow-2xl animate-fade-in-up">
    <div class="flex flex-col md:flex-row md:items-center md:justify-between mb-6 gap-4">
        <h2 class="text-2xl font-bold text-blue-700">Services & Catégories</h2>
    </div>
    <div class="flex gap-2 mb-8">
        <button id="tab-services" class="px-6 py-2 rounded-t-lg font-semibold text-blue-700 bg-blue-100 border-b-2 border-blue-700 focus:outline-none">Services</button>
        <button id="tab-categories" class="px-6 py-2 rounded-t-lg font-semibold text-gray-700 bg-gray-100 border-b-2 border-transparent focus:outline-none">Catégories</button>
    </div>
    <div id="content-services">
        @include('admin.parametrage.partials.services')
    </div>
    <div id="content-categories" class="hidden">
        @include('admin.parametrage.partials.categories')
    </div>
</div>
@push('scripts')
<script>
    document.addEventListener('DOMContentLoaded', function() {
        const tabServices = document.getElementById('tab-services');
        const tabCategories = document.getElementById('tab-categories');
        const contentServices = document.getElementById('content-services');
        const contentCategories = document.getElementById('content-categories');
        tabServices.addEventListener('click', function() {
            tabServices.classList.add('text-blue-700', 'bg-blue-100', 'border-blue-700');
            tabServices.classList.remove('text-gray-700', 'bg-gray-100', 'border-transparent');
            tabCategories.classList.remove('text-blue-700', 'bg-blue-100', 'border-blue-700');
            tabCategories.classList.add('text-gray-700', 'bg-gray-100', 'border-transparent');
            contentServices.classList.remove('hidden');
            contentCategories.classList.add('hidden');
        });
        tabCategories.addEventListener('click', function() {
            tabCategories.classList.add('text-blue-700', 'bg-blue-100', 'border-blue-700');
            tabCategories.classList.remove('text-gray-700', 'bg-gray-100', 'border-transparent');
            tabServices.classList.remove('text-blue-700', 'bg-blue-100', 'border-blue-700');
            tabServices.classList.add('text-gray-700', 'bg-gray-100', 'border-transparent');
            contentCategories.classList.remove('hidden');
            contentServices.classList.add('hidden');
        });
    });
</script>
@endpush
<style>@keyframes fade-in-up { from { opacity: 0; transform: translateY(40px); } to { opacity: 1; transform: none; } } .animate-fade-in-up { animation: fade-in-up 0.7s cubic-bezier(.4,2,.6,1) both; }</style>
@endsection 