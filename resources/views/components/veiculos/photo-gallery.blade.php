@props(['veiculoId'])

<div x-data="photoGallery({{ $veiculoId }})" x-init="loadPhotos()" class="mt-4 sm:mt-6 bg-white shadow-sm sm:rounded-lg p-4 sm:p-6"
    @keydown.escape.window="closeModal()"
    @keydown.arrow-right.window="modalOpen && nextPhoto()"
    @keydown.arrow-left.window="modalOpen && prevPhoto()">
    
    <h3 class="text-lg font-medium text-gray-900 mb-4">Fotos do Veículo</h3>

    <!-- Upload Area -->
    <div class="mb-6">
        <label class="block text-sm font-medium text-gray-700 mb-2">Adicionar Foto</label>
        <div class="flex flex-col sm:flex-row items-start sm:items-center gap-4">
            <input type="file" x-ref="fileInput" @change="handleFileSelect" class="block w-full text-sm text-gray-500
                file:mr-4 file:py-3 file:px-4
                file:rounded-full file:border-0
                file:text-sm file:font-semibold
                file:bg-indigo-50 file:text-indigo-700
                hover:file:bg-indigo-100
                cursor-pointer
            " accept="image/png, image/jpeg, image/jpg">
            
            <button @click="uploadPhoto" 
                    :disabled="!selectedFile || uploading"
                    class="w-full sm:w-auto inline-flex justify-center items-center px-6 py-3 bg-gray-800 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-gray-700 active:bg-gray-900 focus:outline-none focus:border-gray-900 focus:ring ring-gray-300 disabled:opacity-25 transition ease-in-out duration-150">
                <span x-show="!uploading">Enviar</span>
                <span x-show="uploading">Enviando...</span>
            </button>
        </div>
        <p x-show="uploadError" class="mt-2 text-sm text-red-600" x-text="uploadError"></p>
    </div>

    <!-- Gallery Grid -->
    <div class="grid grid-cols-2 md:grid-cols-4 gap-3 sm:gap-4">
        <template x-for="(photo, index) in photos" :key="photo.id">
            <div class="relative group aspect-square bg-gray-100 rounded-lg overflow-hidden cursor-pointer shadow-sm hover:shadow-md transition-shadow">
                <!-- Image Trigger -->
                <button @click="openModal(index)" class="w-full h-full focus:outline-none">
                    <img :src="photo.url" class="object-cover w-full h-full hover:scale-110 transition-transform duration-500" alt="Foto do veículo">
                </button>
                
                <!-- Overlay with Delete Button -->
                <div class="absolute top-2 right-2 opacity-0 group-hover:opacity-100 transition-opacity z-10">
                    <button @click.stop="deletePhoto(photo.id)" class="p-1.5 bg-red-600 text-white rounded-full hover:bg-red-700 focus:outline-none shadow-sm" title="Excluir foto">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path>
                        </svg>
                    </button>
                </div>
            </div>
        </template>
    </div>
    
    <div x-show="photos.length === 0 && !loading" class="text-center text-gray-500 py-8">
        Nenhuma foto cadastrada.
    </div>

    <!-- Full Screen Modal -->
    <template x-teleport="body">
        <div x-show="modalOpen" 
             class="fixed inset-0 z-50 flex items-center justify-center p-4 bg-black bg-opacity-90 backdrop-blur-sm"
             x-transition:enter="transition ease-out duration-300"
             x-transition:enter-start="opacity-0"
             x-transition:enter-end="opacity-100"
             x-transition:leave="transition ease-in duration-200"
             x-transition:leave-start="opacity-100"
             x-transition:leave-end="opacity-0"
             style="display: none;">
            
            <!-- Close Button -->
            <button @click="closeModal()" class="absolute top-4 right-4 text-white hover:text-gray-300 z-50 focus:outline-none">
                <svg class="w-8 h-8 drop-shadow-md" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                </svg>
            </button>

            <!-- Navigation Buttons -->
            <div class="absolute inset-x-0 top-1/2 -translate-y-1/2 flex justify-between px-2 sm:px-8 pointer-events-none z-40" x-show="photos.length > 1">
                <button @click="prevPhoto()" class="pointer-events-auto p-2 rounded-full bg-black bg-opacity-50 text-white hover:bg-opacity-75 transition focus:outline-none">
                    <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"></path></svg>
                </button>
                
                <button @click="nextPhoto()" class="pointer-events-auto p-2 rounded-full bg-black bg-opacity-50 text-white hover:bg-opacity-75 transition focus:outline-none">
                    <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path></svg>
                </button>
            </div>

            <!-- Zoom Controls -->
            <div class="absolute bottom-6 left-1/2 -translate-x-1/2 flex items-center gap-4 z-40 bg-black bg-opacity-50 px-4 py-2 rounded-full">
                <button @click="zoomOut()" class="text-white hover:text-gray-300 focus:outline-none" title="Diminuir Zoom">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 12H4"></path></svg>
                </button>
                <span class="text-white text-sm font-medium w-12 text-center" x-text="Math.round(zoom * 100) + '%'"></span>
                <button @click="zoomIn()" class="text-white hover:text-gray-300 focus:outline-none" title="Aumentar Zoom">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path></svg>
                </button>
            </div>

            <!-- Image Container -->
            <div class="relative w-full h-full flex items-center justify-center overflow-hidden" @click.self="closeModal()">
                <div class="transition-transform duration-200 ease-out"
                     :style="`transform: scale(${zoom})`">
                     <template x-if="photos[currentIndex]">
                        <img :src="photos[currentIndex].url" 
                             class="max-w-full max-h-[90vh] object-contain shadow-2xl rounded-sm select-none" 
                             alt="Visualização ampliada"
                             draggable="false">
                     </template>
                </div>
            </div>
            
            <!-- Counter -->
            <div class="absolute top-4 left-4 text-white text-sm font-medium bg-black bg-opacity-50 px-3 py-1 rounded-full z-40">
                <span x-text="currentIndex + 1"></span> / <span x-text="photos.length"></span>
            </div>
        </div>
    </template>
</div>

<script>
    function photoGallery(veiculoId) {
        return {
            photos: [],
            selectedFile: null,
            uploading: false,
            loading: true,
            uploadError: null,
            
            // Modal States
            modalOpen: false,
            currentIndex: 0,
            zoom: 1,

            loadPhotos() {
                this.loading = true;
                fetch(`/veiculos/${veiculoId}/fotos`)
                    .then(response => response.json())
                    .then(data => {
                        this.photos = data;
                        this.loading = false;
                    });
            },

            handleFileSelect(event) {
                this.selectedFile = event.target.files[0];
                this.uploadError = null;
            },

            uploadPhoto() {
                if (!this.selectedFile) return;

                this.uploading = true;
                this.uploadError = null;
                
                const formData = new FormData();
                formData.append('file', this.selectedFile);

                fetch(`/veiculos/${veiculoId}/fotos`, {
                    method: 'POST',
                    body: formData,
                    headers: {
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                    }
                })
                .then(response => {
                    if (!response.ok) throw new Error('Erro no upload');
                    return response.json();
                })
                .then(() => {
                    this.selectedFile = null;
                    this.$refs.fileInput.value = '';
                    this.loadPhotos();
                })
                .catch(error => {
                    this.uploadError = 'Erro ao enviar foto. Tente novamente.';
                    console.error(error);
                })
                .finally(() => {
                    this.uploading = false;
                });
            },

            deletePhoto(id) {
                if (!confirm('Tem certeza que deseja excluir esta foto?')) return;

                fetch(`/veiculos/fotos/${id}`, {
                    method: 'DELETE',
                    headers: {
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                    }
                })
                .then(response => {
                    if (!response.ok) throw new Error('Erro ao excluir');
                    this.loadPhotos();
                    // Se a foto deletada for a que está aberta, fecha o modal ou muda
                    if (this.modalOpen) {
                         this.closeModal();
                    }
                })
                .catch(error => {
                    console.error(error);
                    alert('Erro ao excluir foto.');
                });
            },

            // Modal Methods
            openModal(index) {
                this.currentIndex = index;
                this.zoom = 1;
                this.modalOpen = true;
                document.body.style.overflow = 'hidden'; // Prevent background scrolling
            },

            closeModal() {
                this.modalOpen = false;
                this.zoom = 1;
                document.body.style.overflow = ''; // Restore scrolling
            },

            nextPhoto() {
                if (this.photos.length === 0) return;
                this.currentIndex = (this.currentIndex + 1) % this.photos.length;
                this.zoom = 1;
            },

            prevPhoto() {
                if (this.photos.length === 0) return;
                this.currentIndex = (this.currentIndex - 1 + this.photos.length) % this.photos.length;
                this.zoom = 1;
            },

            zoomIn() {
                if (this.zoom < 3) {
                    this.zoom += 0.5;
                }
            },

            zoomOut() {
                if (this.zoom > 0.5) {
                    this.zoom -= 0.5;
                }
            }
        }
    }
</script>
