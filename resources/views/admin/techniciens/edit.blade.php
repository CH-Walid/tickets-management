@extends('admin.layout')
@section('title', 'Modifier technicien')
@section('content')
<div class="max-w-2xl mx-auto p-8 bg-white/80 backdrop-blur rounded-3xl shadow-2xl space-y-8 animate-fade-in-up">
    <form action="{{ route('admin.techniciens.techniciens.update', $technicien->id) }}" method="POST" enctype="multipart/form-data" class="space-y-8">
        @csrf
        @method('PUT')
        <div class="flex flex-col items-center gap-4 mb-8">
            <div class="relative group">
                <div id="avatar-preview-container">
                    <x-user-avatar :user="$technicien->user" size="24" font="2xl" class="w-24 h-24 rounded-full object-cover border-4 border-blue-200 shadow cursor-pointer transition-transform group-hover:scale-105" id="profile-photo-preview" :cacheBuster="time()" />
                </div>
                <input type="file" id="photo-input" name="photo" accept="image/*" class="hidden">
                <button type="button" id="change-photo-btn" class="absolute inset-0 flex items-center justify-center opacity-0 group-hover:opacity-100 transition bg-black/30 rounded-full" title="Changer la photo">
                    <i data-lucide="pencil" class="w-7 h-7 text-white"></i>
                </button>
                @if($technicien->user->photo)
                    <button type="button" id="delete-photo-btn" class="absolute -bottom-3 right-0 bg-white rounded-full shadow p-1 border border-gray-200 hover:bg-red-50 transition flex items-center" title="Supprimer la photo">
                        <i data-lucide="trash-2" class="w-5 h-5 text-red-500"></i>
                    </button>
                @endif
                <input type="hidden" name="delete_photo" id="delete-photo-hidden" value="0">
            </div>
            <div class="text-xs text-gray-400">Photo de profil</div>
        </div>
        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
            <div>
                <label class="block mb-1 text-sm font-medium text-gray-700">Nom</label>
                <input type="text" name="nom" value="{{ old('nom', $technicien->user->nom ?? $technicien->nom) }}" class="border border-gray-200 rounded-lg px-3 py-2 w-full focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
            </div>
            <div>
                <label class="block mb-1 text-sm font-medium text-gray-700">Prénom</label>
                <input type="text" name="prenom" value="{{ old('prenom', $technicien->user->prenom ?? $technicien->prenom) }}" class="border border-gray-200 rounded-lg px-3 py-2 w-full focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
            </div>
            <div class="md:col-span-2">
                <label class="block mb-1 text-sm font-medium text-gray-700">Email</label>
                <input type="email" name="email" value="{{ old('email', $technicien->user->email ?? $technicien->email) }}" class="border border-gray-200 rounded-lg px-3 py-2 w-full focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
            </div>
            <div class="md:col-span-2">
                <label class="block mb-1 text-sm font-medium text-gray-700">Téléphone</label>
                <input type="text" name="phone" value="{{ old('phone', $technicien->user->phone ?? $technicien->phone) }}" class="border border-gray-200 rounded-lg px-3 py-2 w-full focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
            </div>
            <div class="md:col-span-2">
                <label class="block mb-1 text-sm font-medium text-gray-700">Nouveau mot de passe <span class="text-gray-400 text-xs">(laisser vide pour ne pas changer)</span></label>
                <input type="password" name="password" class="border border-gray-200 rounded-lg px-3 py-2 w-full focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
            </div>
            <div class="md:col-span-2">
                <label class="block mb-1 text-sm font-medium text-gray-700">Confirmer le mot de passe</label>
                <input type="password" name="password_confirmation" class="border border-gray-200 rounded-lg px-3 py-2 w-full focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
            </div>
            <div class="md:col-span-2">
                <label class="block mb-1 text-sm font-medium text-gray-700">Service</label>
                <select name="service_id" class="border border-gray-200 rounded-lg px-3 py-2 w-full focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                    <option value="">-- Choisir un service --</option>
                    @foreach($services as $service)
                        <option value="{{ $service->id }}" @if(old('service_id', $technicien->service_id ?? ($technicien->service->id ?? null)) == $service->id) selected @endif>{{ $service->titre }}</option>
                    @endforeach
                </select>
            </div>
        </div>
        <div class="flex gap-2 mt-6 justify-end">
            <button type="submit" class="px-6 py-2 bg-blue-700 text-white rounded-lg shadow hover:bg-blue-800 transition font-semibold">Enregistrer</button>
            <a href="{{ route('admin.techniciens.techniciens.index') }}" class="px-6 py-2 bg-gray-200 text-gray-700 rounded-lg shadow hover:bg-gray-300 transition font-semibold">Annuler</a>
        </div>
    </form>
</div>
<style>
@keyframes fade-in-up { from { opacity: 0; transform: translateY(40px); } to { opacity: 1; transform: none; } }
.animate-fade-in-up { animation: fade-in-up 0.7s cubic-bezier(.4,2,.6,1) both; }
</style>
<script>
document.addEventListener('DOMContentLoaded', function() {
    const changeBtn = document.getElementById('change-photo-btn');
    const fileInput = document.getElementById('photo-input');
    const avatarContainer = document.getElementById('avatar-preview-container');
    const deleteBtn = document.getElementById('delete-photo-btn');
    const deleteHidden = document.getElementById('delete-photo-hidden');
    let originalAvatar = avatarContainer.innerHTML;
    if(changeBtn && fileInput && avatarContainer) {
        changeBtn.addEventListener('click', function(e) {
            e.preventDefault();
            fileInput.click();
        });
        fileInput.addEventListener('change', function(e) {
            const file = e.target.files[0];
            if(file) {
                const reader = new FileReader();
                reader.onload = function(evt) {
                    avatarContainer.innerHTML = `<img src='${evt.target.result}' alt='Avatar' class='w-24 h-24 rounded-full object-cover border-4 border-blue-200 shadow cursor-pointer transition-transform group-hover:scale-105'>`;
                    if(deleteHidden) deleteHidden.value = '0';
                    if(deleteBtn) deleteBtn.style.display = 'none';
                };
                reader.readAsDataURL(file);
            }
        });
    }
    if(deleteBtn && avatarContainer && deleteHidden) {
        deleteBtn.addEventListener('click', function(e) {
            e.preventDefault();
            avatarContainer.innerHTML = `@php echo str_replace("'", "\'", view('components.user-avatar', ['user' => $technicien->user, 'size' => 24, 'font' => '2xl', 'class' => 'w-24 h-24 rounded-full object-cover border-4 border-blue-200 shadow cursor-pointer transition-transform group-hover:scale-105', 'id' => 'profile-photo-preview'])->render()); @endphp`;
            if(fileInput) fileInput.value = '';
            deleteHidden.value = '1';
            deleteBtn.style.display = 'none';
        });
    }
});
</script>
@endsection
