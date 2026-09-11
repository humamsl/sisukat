@csrf
@isset($instrument)
    @method('PUT')
@endisset

<div class="grid gap-5 lg:grid-cols-3">
    <div class="space-y-5 lg:col-span-2">
        <div>
            <label for="title" class="mb-1 block text-sm font-medium text-secondary">Judul Instrumen</label>
            <input id="title" name="title" type="text" value="{{ old('title', $instrument->title ?? '') }}" required
                   class="w-full rounded-lg border border-ink/15 px-3 py-2 text-sm focus:border-primary focus:outline-none focus:ring-2 focus:ring-primary/20">
            @error('title') <p class="mt-1 text-xs text-red-600">{{ $message }}</p> @enderror
        </div>

        <div>
            <label for="description" class="mb-1 block text-sm font-medium text-secondary">Deskripsi</label>
            <textarea id="description" name="description" rows="5"
                      class="w-full rounded-lg border border-ink/15 px-3 py-2 text-sm focus:border-primary focus:outline-none focus:ring-2 focus:ring-primary/20">{{ old('description', $instrument->description ?? '') }}</textarea>
            @error('description') <p class="mt-1 text-xs text-red-600">{{ $message }}</p> @enderror
        </div>

        <div>
            <label for="year" class="mb-1 block text-sm font-medium text-secondary">Tahun</label>
            <input id="year" name="year" type="number" value="{{ old('year', $instrument->year ?? '') }}"
                   class="w-full max-w-[160px] rounded-lg border border-ink/15 px-3 py-2 text-sm">
            @error('year') <p class="mt-1 text-xs text-red-600">{{ $message }}</p> @enderror
        </div>
    </div>

    <div class="space-y-5">
        <div>
            <label for="category_id" class="mb-1 block text-sm font-medium text-secondary">Kategori</label>
            <select id="category_id" name="category_id" class="w-full rounded-lg border border-ink/15 px-3 py-2 text-sm">
                <option value="">Tanpa Kategori</option>
                @foreach ($categories as $category)
                    <option value="{{ $category->id }}" @selected(old('category_id', $instrument->category_id ?? null) == $category->id)>{{ $category->name }}</option>
                @endforeach
            </select>
            @error('category_id') <p class="mt-1 text-xs text-red-600">{{ $message }}</p> @enderror
        </div>

        <div>
            <label for="status" class="mb-1 block text-sm font-medium text-secondary">Status</label>
            <select id="status" name="status" class="w-full rounded-lg border border-ink/15 px-3 py-2 text-sm">
                <option value="draft" @selected(old('status', $instrument->status ?? 'draft') === 'draft')>Draft</option>
                <option value="published" @selected(old('status', $instrument->status ?? '') === 'published')>Published</option>
            </select>
            @error('status') <p class="mt-1 text-xs text-red-600">{{ $message }}</p> @enderror
        </div>

        <div>
            <label for="file" class="mb-1 block text-sm font-medium text-secondary">File Instrumen</label>
            @isset($instrument)
                <p class="mb-2 text-xs text-ink/50">
                    File saat ini: <span class="font-medium uppercase">{{ $instrument->file_type }}</span> &middot; {{ $instrument->file_size_formatted }}.
                    Unggah file baru untuk menggantinya.
                </p>
            @endisset
            <input id="file" name="file" type="file" accept=".pdf,.doc,.docx,.xls,.xlsx"
                   class="w-full rounded-lg border border-ink/15 px-3 py-2 text-sm file:mr-3 file:rounded-md file:border-0 file:bg-primary/10 file:px-3 file:py-1.5 file:text-primary">
            <p class="mt-1 text-xs text-ink/40">PDF/DOC/DOCX/XLS/XLSX, maks {{ number_format(config('sisukat.uploads.instrument_file_max_kb') / 1024, 1) }} MB.</p>
            @error('file') <p class="mt-1 text-xs text-red-600">{{ $message }}</p> @enderror
        </div>
    </div>
</div>

<div class="mt-6 flex justify-end gap-3">
    <a href="{{ route('admin.instruments.index') }}" class="rounded-lg px-4 py-2 text-sm font-medium text-ink/60 hover:bg-ink/5">Batal</a>
    <button type="submit" class="rounded-lg bg-primary px-5 py-2 text-sm font-semibold text-white hover:opacity-90">Simpan</button>
</div>
