{{-- Modal de Detalhes do Abastecimento --}}
<div id="modalFuelingDetails" class="hidden fixed inset-0 bg-gray-600 bg-opacity-50 overflow-y-auto h-full w-full z-50">
    <div class="relative top-20 mx-auto p-5 border w-11/12 max-w-2xl shadow-lg rounded-lg bg-white">
        <div class="flex justify-between items-start mb-4">
            <h3 class="text-xl font-semibold text-gray-900 flex items-center">
                <svg class="w-6 h-6 mr-2 text-blue-600" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M15.75 10.5V6a3.75 3.75 0 1 0-7.5 0v4.5m11.356-1.993 1.263 12c.07.665-.45 1.243-1.119 1.243H4.25a1.125 1.125 0 0 1-1.12-1.243l1.264-12A1.125 1.125 0 0 1 5.513 7.5h12.974c.576 0 1.059.435 1.119 1.007ZM8.625 10.5a.375.375 0 1 1-.75 0 .375.375 0 0 1 .75 0Zm7.5 0a.375.375 0 1 1-.75 0 .375.375 0 0 1 .75 0Z" />
                </svg>
                Detalhes do Abastecimento
            </h3>
            <button onclick="closeFuelingDetailsModal()" class="text-gray-400 hover:text-gray-600">
                <svg class="w-6 h-6" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                </svg>
            </button>
        </div>

        <div id="modalFuelingDetailsContent" class="mt-4">
            {{-- Conteúdo será inserido via JavaScript --}}
            <div class="flex justify-center items-center py-8">
                <svg class="animate-spin h-8 w-8 text-blue-600" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                    <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                    <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                </svg>
            </div>
        </div>

        <div class="flex justify-end mt-6 pt-4 border-t border-gray-200">
            <button onclick="closeFuelingDetailsModal()" class="px-4 py-2 bg-gray-200 text-gray-800 text-sm font-medium rounded-md hover:bg-gray-300 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-gray-400">
                Fechar
            </button>
        </div>
    </div>
</div>

@push('scripts')
<script>
function closeFuelingDetailsModal() {
    document.getElementById('modalFuelingDetails').classList.add('hidden');
    document.body.classList.remove('overflow-hidden');
}

// Fechar modal ao clicar fora
document.getElementById('modalFuelingDetails')?.addEventListener('click', function(e) {
    if (e.target === this) {
        closeFuelingDetailsModal();
    }
});

// Fechar modal com tecla ESC
document.addEventListener('keydown', function(e) {
    if (e.key === 'Escape' && !document.getElementById('modalFuelingDetails').classList.contains('hidden')) {
        closeFuelingDetailsModal();
    }
});
</script>
@endpush
