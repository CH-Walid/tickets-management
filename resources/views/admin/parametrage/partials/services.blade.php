@php($services = \App\Models\Service::orderBy('titre')->get())
<div class="mb-6 flex justify-end gap-2">
    <a href="{{ url('admin/services/export') }}" class="px-4 py-2 bg-green-600 text-white rounded-lg shadow hover:bg-green-700 flex items-center gap-2"><i data-lucide="download" class="w-5 h-5"></i>Exporter</a>
    <a href="{{ route('admin.services.create') }}" class="px-4 py-2 bg-blue-700 text-white rounded-lg shadow hover:bg-blue-800 flex items-center gap-2"><i data-lucide="plus" class="w-5 h-5"></i>Ajouter</a>
</div>
<div class="overflow-x-auto rounded-xl border border-gray-100">
    <table class="min-w-full table-auto bg-white rounded-xl">
        <thead class="bg-gray-50">
            <tr>
                <th class="px-4 py-3 text-left text-xs font-semibold text-gray-500 uppercase">Titre</th>
                <th class="px-4 py-3 text-center text-xs font-semibold text-gray-500 uppercase">Actions</th>
            </tr>
        </thead>
        <tbody>
        @forelse($services as $service)
            <tr class="border-t hover:bg-gray-50 transition">
                <td class="px-4 py-2 font-semibold text-gray-800">{{ $service->titre }}</td>
                <td class="px-4 py-2 flex gap-2 justify-center">
                    <a href="{{ route('admin.services.edit', $service->id) }}" class="p-2 rounded hover:bg-blue-50 text-blue-700 transition" title="Modifier"><i data-lucide="pencil" class="w-5 h-5"></i></a>
                    <form action="{{ route('admin.services.destroy', $service->id) }}" method="POST" onsubmit="return confirm('Supprimer ce service ?');">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="p-2 rounded hover:bg-red-50 text-red-600 transition" title="Supprimer"><i data-lucide="trash-2" class="w-5 h-5"></i></button>
                    </form>
                </td>
            </tr>
        @empty
            <tr><td colspan="2" class="text-center py-6 text-gray-400">Aucun service trouvé.</td></tr>
        @endforelse
        </tbody>
    </table>
</div>
@push('scripts')
<script>document.addEventListener('DOMContentLoaded', function() { if (window.lucide) { lucide.createIcons(); } });</script>
@endpush 