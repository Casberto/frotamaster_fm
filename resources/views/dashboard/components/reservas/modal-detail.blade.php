{{-- Modal de Detalhes da Reserva --}}
<div id="modalReservationDetails" class="hidden fixed inset-0 bg-gray-600 bg-opacity-50 overflow-y-auto h-full w-full z-50" aria-labelledby="modal-title" role="dialog" aria-modal="true">
    <div class="relative top-20 mx-auto p-5 border w-full max-w-2xl shadow-lg rounded-md bg-white">
        <div class="flex justify-between items-start mb-4">
            <h3 class="text-lg leading-6 font-medium text-gray-900 flex items-center" id="modal-title">
                <svg class="w-5 h-5 mr-2 text-blue-600" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M6.75 3v2.25M17.25 3v2.25M3 18.75V7.5a2.25 2.25 0 0 1 2.25-2.25h13.5A2.25 2.25 0 0 1 21 7.5v11.25m-18 0A2.25 2.25 0 0 0 5.25 21h13.5A2.25 2.25 0 0 0 21 18.75m-18 0v-7.5A2.25 2.25 0 0 1 5.25 9h13.5A2.25 2.25 0 0 1 21 11.25v7.5" />
                </svg>
                Detalhes da Reserva
            </h3>
            <button type="button" onclick="closeReservationModal()" class="text-gray-400 hover:text-gray-500">
                <span class="sr-only">Fechar</span>
                <svg class="h-6 w-6" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12" />
                </svg>
            </button>
        </div>
        
        <div id="modalReservationDetailsContent" class="mt-4">
            {{-- Conteúdo será preenchido via JavaScript --}}
        </div>

        <div class="mt-6 flex justify-end">
            <button type="button" onclick="closeReservationModal()" class="px-4 py-2 bg-gray-200 text-gray-800 text-sm font-medium rounded-md hover:bg-gray-300 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-gray-500">
                Fechar
            </button>
        </div>
    </div>
</div>

@push('scripts')
<script>
function closeReservationModal() {
    document.getElementById('modalReservationDetails').classList.add('hidden');
    document.body.classList.remove('overflow-hidden');
}

// Fechar ao clicar fora do modal
document.addEventListener('click', function(event) {
    const modal = document.getElementById('modalReservationDetails');
    if (event.target === modal) {
        closeReservationModal();
    }
});

// Fechar com ESC
document.addEventListener('keydown', function(event) {
    if (event.key === 'Escape') {
        closeReservationModal();
    }
});
</script>
@endpush
