<form action="{{ route('admin.empresas.update', $empresa) }}" method="POST">
    @csrf @method('PUT')
    
    {{-- Reutiliza o formulário existente --}}
    @include('admin.empresas._form')

</form>
