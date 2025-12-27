<form method="POST" action="{{ route('admin.licencas.update', $licenca) }}">
    @csrf
    @method('PUT')
    
    @include('admin.licencas._form', ['licenca' => $licenca])

    <div class="mt-6 flex justify-end">
        <button type="submit" class="px-6 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700 transition font-semibold shadow-sm">
            Atualizar Licença
        </button>
    </div>
</form>
