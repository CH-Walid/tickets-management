@extends('admin.layout')
@section('title', 'Paramètres')
@section('content')
<div class="max-w-2xl mx-auto p-8 bg-white/80 backdrop-blur rounded-3xl shadow-2xl space-y-8 animate-fade-in-up">
    <form method="POST" action="{{ route('admin.admin.parametres.update') }}" class="space-y-8">
        @csrf
        <h2 class="text-2xl font-bold mb-6 flex items-center gap-2 text-blue-900">
            <i data-lucide="settings" class="w-7 h-7 text-blue-700"></i>
            Paramètres administrateur
        </h2>
        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
            <div class="flex flex-col gap-2">
                <label class="block font-medium mb-1" for="lang"><i data-lucide="globe" class="inline w-5 h-5 mr-1 text-blue-500"></i>Langue de l’interface</label>
                <select id="lang" name="lang" class="border border-gray-200 rounded-lg px-3 py-2 w-full focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                    <option value="fr" selected>Français</option>
                    <option value="en">Anglais</option>
                </select>
            </div>
            <div class="flex flex-col gap-2">
                <label class="block font-medium mb-1" for="notif_email"><i data-lucide="mail" class="inline w-5 h-5 mr-1 text-blue-500"></i>Notifications email</label>
                <label class="inline-flex items-center gap-2 cursor-pointer">
                    <input type="checkbox" id="notif_email" name="notif_email" class="rounded border-gray-300 text-blue-600 shadow-sm focus:ring-blue-500" checked>
                    <span>Recevoir les notifications importantes</span>
                </label>
            </div>
        </div>
        <div class="flex gap-2 mt-8 justify-end">
            <button type="submit" class="px-6 py-2 bg-blue-700 text-white rounded-lg shadow hover:bg-blue-800 transition font-semibold flex items-center gap-2"><i data-lucide="save" class="w-5 h-5"></i> Enregistrer</button>
        </div>
        @if(session('success'))
            <div class="mt-4 p-3 bg-green-100 text-green-800 rounded animate-pulse">{{ session('success') }}</div>
        @endif
    </form>
</div>
@endsection
