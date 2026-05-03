@extends('layouts.app')

@section('title', 'Kelola Kategori')

@section('content')
<div class="max-w-7xl mx-auto px-4">
    <div class="grid md:grid-cols-2 gap-8">
        <!-- Form Tambah/Edit Kategori -->
        <div class="bg-white rounded-lg shadow-md p-6">
            <h2 class="text-xl font-bold mb-4">Tambah Kategori Baru</h2>
            
            <form action="{{ route('admin.categories.store') }}" method="POST">
                @csrf
                <div class="mb-4">
                    <label for="name" class="block text-gray-700 font-bold mb-2">Nama Kategori</label>
                    <input type="text" name="name" id="name" value="{{ old('name') }}" 
                        class="w-full px-3 py-2 border border-gray-300 rounded focus:outline-none focus:border-blue-500"
                        required>
                </div>
                
                <div class="mb-4">
                    <label for="description" class="block text-gray-700 font-bold mb-2">Deskripsi (Opsional)</label>
                    <textarea name="description" id="description" rows="3" 
                        class="w-full px-3 py-2 border border-gray-300 rounded focus:outline-none focus:border-blue-500">{{ old('description') }}</textarea>
                </div>
                
                <button type="submit" class="bg-blue-500 text-white px-4 py-2 rounded hover:bg-blue-600">
                    <i class="fas fa-save mr-2"></i> Simpan Kategori
                </button>
            </form>
        </div>
        
        <!-- List Kategori -->
        <div class="bg-white rounded-lg shadow-md p-6">
            <h2 class="text-xl font-bold mb-4">Daftar Kategori</h2>
            
            @if($categories->isEmpty())
                <p class="text-gray-500">Belum ada kategori.</p>
            @else
                <div class="space-y-4">
                    @foreach($categories as $category)
                        <div class="border rounded-lg p-4">
                            <div class="flex justify-between items-start">
                                <div>
                                    <h3 class="font-semibold text-lg">{{ $category->name }}</h3>
                                    @if($category->description)
                                        <p class="text-gray-600 text-sm mt-1">{{ $category->description }}</p>
                                    @endif
                                    <p class="text-xs text-gray-500 mt-2">
                                        <i class="fas fa-box mr-1"></i> {{ $category->products_count }} produk
                                    </p>
                                </div>
                                <div class="flex gap-2">
                                    <button onclick="editCategory({{ $category->id }}, '{{ $category->name }}', `{{ $category->description }}`)"
                                            class="text-yellow-500 hover:text-yellow-700">
                                        <i class="fas fa-edit"></i>
                                    </button>
                                    <form action="{{ route('admin.categories.update', $category) }}" method="POST" class="edit-form-{{ $category->id }}" style="display:none;">
                                        @csrf
                                        @method('PUT')
                                        <input type="hidden" name="name" value="">
                                        <input type="hidden" name="description" value="">
                                    </form>
                                    
                                    @if($category->products_count == 0)
                                        <form action="{{ route('admin.categories.destroy', $category) }}" method="POST" 
                                              onsubmit="return confirm('Yakin ingin menghapus kategori ini?')">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="text-red-500 hover:text-red-700">
                                                <i class="fas fa-trash"></i>
                                            </button>
                                        </form>
                                    @endif
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>
            @endif
        </div>
    </div>
</div>

<script>
function editCategory(id, name, description) {
    let newName = prompt('Edit nama kategori:', name);
    if (newName && newName !== name) {
        let newDescription = prompt('Edit deskripsi kategori:', description);
        let form = document.querySelector(`.edit-form-${id}`);
        form.querySelector('input[name="name"]').value = newName;
        form.querySelector('input[name="description"]').value = newDescription || '';
        form.submit();
    }
}
</script>
@endsection