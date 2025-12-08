@props(['sinistroId'])

<div x-data="sinistroPhotoGallery({{ $sinistroId }})" x-init="loadPhotos()" class="mt-4 bg-white shadow-sm sm:rounded-lg p-4 sm:p-6"
    @keydown.escape.window="closeModal()"
    @keydown.arrow-right.window="modalOpen && nextPhoto()"
    @keydown.arrow-left.window="modalOpen && prevPhoto()">
    
    <h3 class="text-lg font-medium text-gray-900 mb-4">Fotos do Sinistro</h3>

    <!-- Upload Area -->
    <div class="mb-6">
        <label class="block text-sm font-medium text-gray-700 mb-2">Adicionar Foto ou Documento</label>
        <div class="flex flex-col sm:flex-row items-start sm:items-center gap-4">
            <input type="file" x-ref="fileInput" @change="handleFileSelect" class="block w-full text-sm text-gray-500
                file:mr-4 file:py-3 file:px-4
                file:rounded-full file:border-0
                file:text-sm file:font-semibold
                file:bg-indigo-50 file:text-indigo-700
                hover:file:bg-indigo-100
                cursor-pointer
            " accept="image/png, image/jpeg, image/jpg, application/pdf">
            
            <button @click="uploadPhoto" 
                    :disabled="!selectedFile || uploading"
                    class="w-full sm:w-auto inline-flex justify-center items-center px-6 py-3 bg-gray-800 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-gray-700 active:bg-gray-900 focus:outline-none focus:border-gray-900 focus:ring ring-gray-300 disabled:opacity-25 transition ease-in-out duration-150">
                <span x-show="!uploading">Enviar</span>
                <span x-show="uploading">Enviando...</span>
            </button>
        </div>
        <p x-show="uploadError" class="mt-2 text-sm text-red-600" x-text="uploadError"></p>
    </div>

    <!-- Gallery Grid (Images) -->
    <div x-show="images.length > 0" class="mb-6">
        <h4 class="text-sm font-medium text-gray-700 mb-2">Imagens</h4>
        <div class="grid grid-cols-2 md:grid-cols-4 gap-3 sm:gap-4">
            <template x-for="photo in images" :key="photo.id">
                <div class="relative group aspect-square bg-gray-100 rounded-lg overflow-hidden cursor-pointer shadow-sm hover:shadow-md transition-shadow">
                    <!-- Image Trigger -->
                    <button @click="openModal(photo.id)" class="w-full h-full focus:outline-none">
                        <img :src="photo.url" class="object-cover w-full h-full hover:scale-110 transition-transform duration-500" alt="Foto do sinistro">
                    </button>
                    
                    <!-- Overlay with Delete Button -->
                    <div class="absolute top-2 right-2 opacity-0 group-hover:opacity-100 transition-opacity z-10">
                        <button @click.stop="deletePhoto(photo.id)" class="p-1.5 bg-red-600 text-white rounded-full hover:bg-red-700 focus:outline-none shadow-sm" title="Excluir imagem">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path>
                            </svg>
                        </button>
                    </div>
                </div>
            </template>
        </div>
    </div>

    <!-- Documents List (PDFs) -->
    <div x-show="documents.length > 0" class="mb-4">
        <h4 class="text-sm font-medium text-gray-700 mb-2">Documentos</h4>
        <ul class="border border-gray-200 rounded-md divide-y divide-gray-200">
            <template x-for="doc in documents" :key="doc.id">
                <li class="pl-3 pr-4 py-3 flex items-center justify-between text-sm">
                    <div class="w-0 flex-1 flex items-center">
                        <!-- PDF Icon -->
                        <svg class="flex-shrink-0 h-5 w-5 text-red-500" fill="currentColor" viewBox="0 0 20 20">
                            <path fill-rule="evenodd" d="M4 4a2 2 0 012-2h4.586A2 2 0 0112 2.586L15.414 6A2 2 0 0116 7.414V16a2 2 0 01-2 2H6a2 2 0 01-2-2V4z" clip-rule="evenodd" />
                        </svg>
                        <span class="ml-2 flex-1 w-0 truncate" x-text="doc.original_name"></span>
                    </div>
                    <div class="ml-4 flex-shrink-0 flex space-x-4">
                        <a :href="doc.url" target="_blank" class="font-medium text-indigo-600 hover:text-indigo-500">
                            Baixar
                        </a>
                        <button @click="deletePhoto(doc.id)" class="font-medium text-red-600 hover:text-red-500">
                            Excluir
                        </button>
                    </div>
                </li>
            </template>
        </ul>
    </div>
    
    <div x-show="allFiles.length === 0 && !loading" class="text-center text-gray-500 py-8">
        Nenhum arquivo cadastrado.
    </div>

    <!-- Full Screen Modal -->
    <div x-show="modalOpen" 
            class="fixed inset-0 z-[9999] flex items-center justify-center p-4 bg-black bg-opacity-90 backdrop-blur-sm"
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
        <div class="absolute inset-x-0 top-1/2 -translate-y-1/2 flex justify-between px-2 sm:px-8 pointer-events-none z-40" x-show="images.length > 1">
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
                    <template x-if="images[currentIndex]">
                    <img :src="images[currentIndex].url" 
                            class="max-w-full max-h-[90vh] object-contain shadow-2xl rounded-sm select-none" 
                            alt="Visualização ampliada"
                            draggable="false">
                    </template>
            </div>
        </div>
        
        <!-- Counter -->
        <div class="absolute top-4 left-4 text-white text-sm font-medium bg-black bg-opacity-50 px-3 py-1 rounded-full z-40">
            <span x-text="currentIndex + 1"></span> / <span x-text="images.length"></span>
        </div>
    </div>
</div>

<script>
    if (typeof sinistroPhotoGallery !== 'function') {
        window.sinistroPhotoGallery = function(sinistroId) {
            return {
                allFiles: [],
                selectedFile: null,
                uploading: false,
                loading: true,
                uploadError: null,
                
                // Modal States
                modalOpen: false,
                currentIndex: 0,
                zoom: 1,

                // Computed
                get images() {
                    return this.allFiles.filter(f => f.is_image);
                },

                get documents() {
                    return this.allFiles.filter(f => !f.is_image);
                },

                loadPhotos() {
                    this.loading = true;
                    fetch(`/seguros/sinistros/${sinistroId}/fotos`)
                        .then(response => {
                             if (!response.ok) throw new Error('Erro ao carregar fotos');
                             return response.json();
                        })
                        .then(data => {
                            if (Array.isArray(data)) {
                                this.allFiles = data;
                            } else {
                                console.error('Formato de dados inválido:', data);
                                this.allFiles = Object.values(data); // Fallback
                            }
                            this.loading = false;
                        })
                        .catch(err => {
                            console.error('Erro loadPhotos:', err);
                            this.loading = false;
                            alert('Erro ao carregar galeria.');
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

                    fetch(`/seguros/sinistros/${sinistroId}/fotos`, {
                        method: 'POST',
                        body: formData,
                        headers: {
                            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
                            'Accept': 'application/json' // Fix for JSON parse error
                        }
                    })
                    .then(async response => {
                        const data = await response.json();
                        if (!response.ok) {
                            let errorMsg = data.error || data.message || 'Erro no upload';
                            if (data.errors) {
                                const firstKey = Object.keys(data.errors)[0];
                                errorMsg = data.errors[firstKey][0];
                            }
                            throw new Error(errorMsg);
                        }
                        return data;
                    })
                    .then(() => {
                        this.selectedFile = null;
                        this.$refs.fileInput.value = '';
                        this.loadPhotos();
                    })
                    .catch(error => {
                        this.uploadError = error.message;
                        console.error(error);
                    })
                    .finally(() => {
                        this.uploading = false;
                    });
                },

                deletePhoto(id) {
                    if (!confirm('Tem certeza que deseja excluir este arquivo?')) return;

                    fetch(`/seguros/sinistros/fotos/${id}`, {
                        method: 'DELETE',
                        headers: {
                            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
                            'Accept': 'application/json'
                        }
                    })
                    .then(response => {
                        if (!response.ok) throw new Error('Erro ao excluir');
                        this.loadPhotos();
                        if (this.modalOpen) {
                             this.closeModal();
                        }
                    })
                    .catch(error => {
                        console.error(error);
                        alert('Erro ao excluir arquivo.');
                    });
                },

                // Modal Methods (Images Only)
                openModal(photoId) {
                    // Find index in the images array, not allFiles
                    const index = this.images.findIndex(p => p.id === photoId);
                    if (index === -1) return;
                    
                    this.currentIndex = index;
                    this.zoom = 1;
                    this.modalOpen = true;
                    document.body.style.overflow = 'hidden';
                },

                closeModal() {
                    this.modalOpen = false;
                    this.zoom = 1;
                    document.body.style.overflow = '';
                },

                nextPhoto() {
                    if (this.images.length === 0) return;
                    this.currentIndex = (this.currentIndex + 1) % this.images.length;
                    this.zoom = 1;
                },

                prevPhoto() {
                    if (this.images.length === 0) return;
                    this.currentIndex = (this.currentIndex - 1 + this.images.length) % this.images.length;
                    this.zoom = 1;
                },

                zoomIn() {
                    if (this.zoom < 3) this.zoom += 0.5;
                },

                zoomOut() {
                    if (this.zoom > 0.5) this.zoom -= 0.5;
                }
            }
        }
    }
</script>
