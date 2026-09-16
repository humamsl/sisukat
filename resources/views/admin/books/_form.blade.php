@csrf
@isset($book)
    @method('PUT')
@endisset

<div class="grid gap-5 lg:grid-cols-3">
    <div class="space-y-5 lg:col-span-2">
        <div>
            <label for="title" class="mb-1 block text-sm font-medium text-secondary">Judul Buku</label>
            <input id="title" name="title" type="text" value="{{ old('title', $book->title ?? '') }}" required
                   class="w-full rounded-lg border border-ink/15 px-3 py-2 text-sm focus:border-primary focus:outline-none focus:ring-2 focus:ring-primary/20">
            @error('title') <p class="mt-1 text-xs text-red-600">{{ $message }}</p> @enderror
        </div>

        <div>
            <label for="description" class="mb-1 block text-sm font-medium text-secondary">Deskripsi</label>
            <textarea id="description" name="description" rows="5"
                      class="w-full rounded-lg border border-ink/15 px-3 py-2 text-sm focus:border-primary focus:outline-none focus:ring-2 focus:ring-primary/20">{{ old('description', $book->description ?? '') }}</textarea>
            @error('description') <p class="mt-1 text-xs text-red-600">{{ $message }}</p> @enderror
        </div>

        <div class="grid gap-5 sm:grid-cols-3">
            <div>
                <label for="author" class="mb-1 block text-sm font-medium text-secondary">Penulis</label>
                <input id="author" name="author" type="text" value="{{ old('author', $book->author ?? '') }}"
                       class="w-full rounded-lg border border-ink/15 px-3 py-2 text-sm">
                @error('author') <p class="mt-1 text-xs text-red-600">{{ $message }}</p> @enderror
            </div>
            <div>
                <label for="year" class="mb-1 block text-sm font-medium text-secondary">Tahun</label>
                <input id="year" name="year" type="number" value="{{ old('year', $book->year ?? '') }}"
                       class="w-full rounded-lg border border-ink/15 px-3 py-2 text-sm">
                @error('year') <p class="mt-1 text-xs text-red-600">{{ $message }}</p> @enderror
            </div>
            <div>
                <label for="pages_count" class="mb-1 block text-sm font-medium text-secondary">Jumlah Halaman</label>
                <input id="pages_count" name="pages_count" type="number" value="{{ old('pages_count', $book->pages_count ?? '') }}"
                       class="w-full rounded-lg border border-ink/15 px-3 py-2 text-sm">
                @error('pages_count') <p class="mt-1 text-xs text-red-600">{{ $message }}</p> @enderror
            </div>
        </div>
    </div>

    <div class="space-y-5">
        <div>
            <label for="category_id" class="mb-1 block text-sm font-medium text-secondary">Kategori</label>
            <select id="category_id" name="category_id" class="w-full rounded-lg border border-ink/15 px-3 py-2 text-sm">
                <option value="">Tanpa Kategori</option>
                @foreach ($categories as $category)
                    <option value="{{ $category->id }}" @selected(old('category_id', $book->category_id ?? null) == $category->id)>{{ $category->name }}</option>
                @endforeach
            </select>
            @error('category_id') <p class="mt-1 text-xs text-red-600">{{ $message }}</p> @enderror
        </div>

        <div>
            <label for="status" class="mb-1 block text-sm font-medium text-secondary">Status</label>
            <select id="status" name="status" class="w-full rounded-lg border border-ink/15 px-3 py-2 text-sm">
                <option value="draft" @selected(old('status', $book->status ?? 'draft') === 'draft')>Draft</option>
                <option value="published" @selected(old('status', $book->status ?? '') === 'published')>Published</option>
            </select>
            @error('status') <p class="mt-1 text-xs text-red-600">{{ $message }}</p> @enderror
        </div>

        <div>
            <label for="cover" class="mb-1 block text-sm font-medium text-secondary">Cover Buku</label>
            @isset($book)
                @if ($book->cover)
                    <img src="{{ route('books.cover', $book) }}" alt="Cover {{ $book->title }}" class="mb-2 h-28 w-20 rounded-lg object-cover ring-1 ring-ink/10">
                @endif
            @endisset
            <input id="cover" name="cover" type="file" accept="image/png,image/jpeg,image/webp"
                   class="w-full rounded-lg border border-ink/15 px-3 py-2 text-sm file:mr-3 file:rounded-md file:border-0 file:bg-primary/10 file:px-3 file:py-1.5 file:text-primary">
            <p class="mt-1 text-xs text-ink/40">JPG/PNG/WEBP, maks {{ number_format(config('sisukat.uploads.book_cover_max_kb') / 1024, 1) }} MB.</p>
            @error('cover') <p class="mt-1 text-xs text-red-600">{{ $message }}</p> @enderror
        </div>

        <div>
            <label for="file" class="mb-1 block text-sm font-medium text-secondary">File PDF</label>
            @isset($book)
                @if ($book->file)
                    <p class="mb-2 text-xs text-ink/50">File saat ini tersimpan. Unggah file baru untuk menggantinya.</p>
                @endif
            @endisset
            <input id="file" name="file" type="file" accept="application/pdf"
                   class="w-full rounded-lg border border-ink/15 px-3 py-2 text-sm file:mr-3 file:rounded-md file:border-0 file:bg-primary/10 file:px-3 file:py-1.5 file:text-primary">
            <p class="mt-1 text-xs text-ink/40">PDF, maks {{ number_format(config('sisukat.uploads.book_file_max_kb') / 1024, 1) }} MB. Wajib diisi jika Link Baca Online kosong.</p>
            @error('file') <p class="mt-1 text-xs text-red-600">{{ $message }}</p> @enderror
        </div>

        <div>
            <label for="read_url" class="mb-1 block text-sm font-medium text-secondary">Link Baca Online</label>
            <input id="read_url" name="read_url" type="url" value="{{ old('read_url', $book->read_url ?? '') }}" placeholder="https://..."
                   class="w-full rounded-lg border border-ink/15 px-3 py-2 text-sm">
            <p class="mt-1 text-xs text-ink/40">Opsional. Jika diisi, tombol "Baca Online" akan mengarah ke link ini (mis. flipbook eksternal) alih-alih pembaca PDF internal.</p>
            @error('read_url') <p class="mt-1 text-xs text-red-600">{{ $message }}</p> @enderror
        </div>
    </div>
</div>

<div class="mt-6 flex justify-end gap-3">
    <a href="{{ route('admin.books.index') }}" class="rounded-lg px-4 py-2 text-sm font-medium text-ink/60 hover:bg-ink/5">Batal</a>
    <button type="submit" class="rounded-lg bg-primary px-5 py-2 text-sm font-semibold text-white hover:opacity-90">Simpan</button>
</div>
