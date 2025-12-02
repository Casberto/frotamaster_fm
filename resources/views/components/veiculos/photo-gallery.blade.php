@props(['veiculoId'])

<div x-data="photoGallery({{ $veiculoId }})" x-init="loadPhotos()" class="mt-4 sm:mt-6 bg-white shadow-sm sm:rounded-lg p-4 sm:p-6">
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
        <template x-for="photo in photos" :key="photo.id">
            <div class="relative group aspect-square bg-gray-100 rounded-lg overflow-hidden">
                <img :src="photo.url" class="object-cover w-full h-full" alt="Foto do veículo">
                
                <!-- Overlay with Delete Button -->
                <div class="absolute inset-0 bg-black bg-opacity-0 group-hover:bg-opacity-40 transition-all duration-200 flex items-center justify-center opacity-0 group-hover:opacity-100">
                    <button @click="deletePhoto(photo.id)" class="p-2 bg-red-600 text-white rounded-full hover:bg-red-700 focus:outline-none">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
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
</div>

<script>
    function photoGallery(veiculoId) {
        return {
            photos: [],
            selectedFile: null,
            uploading: false,
            loading: true,
            uploadError: null,

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
                })
                .catch(error => {
                    console.error(error);
                    alert('Erro ao excluir foto.');
                });
            }
        }
    }
</script>
