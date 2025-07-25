@extends('admin.layout')
@section('title', 'Profil')
@section('content')
<div class="min-h-[350px] flex items-center justify-center bg-gray-50 py-10">
    <div class="w-full max-w-2xl bg-white border border-gray-200 shadow-md rounded-xl p-8 flex flex-col gap-8">
        <!-- En-tête profil -->
        <div class="flex flex-col md:flex-row items-center gap-6">
            <div>
                <x-user-avatar :user="Auth::user()" size="28" font="3xl" />
            </div>
            <div class="flex-1 flex flex-col gap-1">
                <span class="text-xl font-semibold text-gray-900">{{ Auth::user()->nom }} {{ Auth::user()->prenom }}</span>
                <div class="flex items-center gap-2 text-gray-700 text-sm">
                    <i data-lucide="badge-check" class="w-4 h-4"></i>
                    <span>Rôle : <strong>Administrateur</strong></span>
                </div>
                <div class="flex items-center gap-2 text-gray-700 text-sm">
                    <i data-lucide="hash" class="w-4 h-4"></i>
                    <span>ID utilisateur : <strong>{{ Auth::user()->id }}</strong></span>
                </div>
                <div class="flex items-center gap-2 text-gray-700 text-sm">
                    <i data-lucide="mail" class="w-4 h-4"></i>
                    <span>{{ Auth::user()->email }}</span>
                </div>
                <div class="flex items-center gap-2 text-gray-700 text-sm">
                    <i data-lucide="phone" class="w-4 h-4"></i>
                    <span>{{ Auth::user()->phone ?? '—' }}</span>
                </div>
                <div class="flex items-center gap-2 text-gray-600 text-xs">
                    <i data-lucide="calendar" class="w-4 h-4"></i>
                    <span>Inscrit le : <strong>{{ Auth::user()->created_at ? Auth::user()->created_at->format('d/m/Y') : '—' }}</strong></span>
                </div>

                <div class="flex items-center gap-2 text-gray-600 text-xs">
                    <i data-lucide="shield-check" class="w-4 h-4"></i>
                    <span>Status : <span class="inline-block px-2 py-0.5 rounded bg-gray-100 text-gray-700 text-xs font-semibold">Actif</span></span>
                </div>
            </div>
            <div class="flex flex-col gap-2 min-w-[120px]">
                <a href="{{ route('admin.profil.edit') }}" class="w-full flex items-center justify-center gap-2 px-4 py-2 bg-blue-700 text-white rounded shadow hover:bg-blue-800 transition font-medium text-sm"><i data-lucide="edit-3" class="w-4 h-4"></i>Modifier le profil</a>
                <a href="{{ route('admin.password') }}" class="w-full flex items-center justify-center gap-2 px-4 py-2 bg-gray-100 text-gray-700 rounded shadow-sm hover:bg-gray-200 transition font-medium text-sm"><i data-lucide="lock" class="w-4 h-4"></i>Changer mot de passe</a>
            </div>
        </div>
        <!-- Section À propos -->
        @if(Auth::user()->bio)
        <div class="bg-gray-50 p-4 rounded border border-gray-100">
            <div class="font-semibold text-gray-800 mb-1 flex items-center gap-1"><i data-lucide="info" class="w-4 h-4"></i>À propos</div>
            <div class="text-gray-700 text-sm">{{ Auth::user()->bio }}</div>
        </div>
        @endif
        <!-- Section activité récente -->
        <div class="bg-white border border-gray-100 rounded p-4">
            <div class="font-semibold text-gray-800 mb-2 flex items-center gap-1"><i data-lucide="activity" class="w-4 h-4"></i>Activité récente</div>
            <ul class="text-gray-700 text-sm list-disc pl-5">
                <li>Dernière connexion : <strong>{{ Auth::user()->last_login_at ? \Carbon\Carbon::parse(Auth::user()->last_login_at)->format('d/m/Y H:i') : '—' }}</strong></li>
                <li>Dernière modification du profil : <strong>{{ Auth::user()->updated_at ? Auth::user()->updated_at->format('d/m/Y H:i') : '—' }}</strong></li>
                <li>Dernière modification du mot de passe : <strong>{{ Auth::user()->password_changed_at ? \Carbon\Carbon::parse(Auth::user()->password_changed_at)->format('d/m/Y H:i') : '—' }}</strong></li>
            </ul>
        </div>
        <!-- Section sécurité -->
        <div class="bg-white border border-gray-100 rounded p-4">
            <div class="font-semibold text-gray-800 mb-2 flex items-center gap-1"><i data-lucide="shield" class="w-4 h-4"></i>Sécurité</div>
            <ul class="text-gray-700 text-sm list-disc pl-5">
                <li>Authentification à deux facteurs (MFA) : <span class="font-semibold">Non activée</span></li>
                <!-- Ajouter plus d'infos sécurité si dispo -->
            </ul>
        </div>
    </div>
</div>
@endsection
@push('scripts')
<script src="{{ asset('js/admin-profil.js') }}"></script>
<script>
    document.addEventListener("DOMContentLoaded", function() {
        if (window.lucide) {
            lucide.createIcons();
        }
    });
</script>
@endpush
