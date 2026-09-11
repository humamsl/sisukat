@csrf
@isset($tutorial)
    @method('PUT')
@endisset

<div x-data="{ type: '{{ old('type', $tutorial->type ?? 'article') }}' }" class="grid gap-5 lg:grid-cols-3">
    <div class="space-y-5 lg:col-span-2">
        <div>
            <label for="title" class="mb-1 block text-sm font-medium text-secondary">Judul Tutorial</label>
            <input id="title" name="title" type="text" value="{{ old('title', $tutorial->title ?? '') }}" required
                   class="w-full rounded-lg border border-ink/15 px-3 py-2 text-sm focus:border-primary focus:outline-none focus:ring-2 focus:ring-primary/20">
            @error('title') <p class="mt-1 text-xs text-red-600">{{ $message }}</p> @enderror
        </div>

        <div>
            <label for="description" class="mb-1 block text-sm font-medium text-secondary">Deskripsi Singkat</label>
            <textarea id="description" name="description" rows="3"
                      class="w-full rounded-lg border border-ink/15 px-3 py-2 text-sm focus:border-primary focus:outline-none focus:ring-2 focus:ring-primary/20">{{ old('description', $tutorial->description ?? '') }}</textarea>
            @error('description') <p class="mt-1 text-xs text-red-600">{{ $message }}</p> @enderror
        </div>

        <div x-show="type === 'article'">
            <label class="mb-1 block text-sm font-medium text-secondary">Konten Artikel</label>
            <div class="rounded-lg border border-ink/15">
                <x-admin.rich-editor name="content" :value="$tutorial->content ?? ''" />
            </div>
        </div>

        <div x-show="type === 'video'" x-cloak>
            <label for="video_url" class="mb-1 block text-sm font-medium text-secondary">URL Video (YouTube/Vimeo)</label>
            <input id="video_url" name="video_url" type="text" value="{{ old('video_url', $tutorial->video_url ?? '') }}"
                   class="w-full rounded-lg border border-ink/15 px-3 py-2 text-sm">
            @error('video_url') <p class="mt-1 text-xs text-red-600">{{ $message }}</p> @enderror
        </div>

        <div x-show="type === 'link'" x-cloak>
            <label for="external_url" class="mb-1 block text-sm font-medium text-secondary">URL Eksternal</label>
            <input id="external_url" name="external_url" type="text" value="{{ old('external_url', $tutorial->external_url ?? '') }}"
                   class="w-full rounded-lg border border-ink/15 px-3 py-2 text-sm">
            @error('external_url') <p class="mt-1 text-xs text-red-600">{{ $message }}</p> @enderror
        </div>

        <div x-show="type === 'pdf' || type === 'image'" x-cloak>
            <label for="file" class="mb-1 block text-sm font-medium text-secondary">File (<span x-text="type === 'pdf' ? 'PDF' : 'Gambar'"></span>)</label>
            @isset($tutorial)
                @if ($tutorial->file)
                    <p class="mb-2 text-xs text-ink/50">File saat ini tersimpan. Unggah file baru untuk menggantinya.</p>
                @endif
            @endisset
            <input id="file" name="file" type="file"
                   class="w-full rounded-lg border border-ink/15 px-3 py-2 text-sm file:mr-3 file:rounded-md file:border-0 file:bg-primary/10 file:px-3 file:py-1.5 file:text-primary">
            @error('file') <p class="mt-1 text-xs text-red-600">{{ $message }}</p> @enderror
        </div>
    </div>

    <div class="space-y-5">
        <div>
            <label for="type" class="mb-1 block text-sm font-medium text-secondary">Tipe Tutorial</label>
            <select id="type" name="type" x-model="type" class="w-full rounded-lg border border-ink/15 px-3 py-2 text-sm">
                <option value="article">Artikel</option>
                <option value="video">Video</option>
                <option value="pdf">PDF</option>
                <option value="image">Gambar</option>
                <option value="link">Link Eksternal</option>
            </select>
            @error('type') <p class="mt-1 text-xs text-red-600">{{ $message }}</p> @enderror
        </div>

        <div>
            <label for="category_id" class="mb-1 block text-sm font-medium text-secondary">Kategori</label>
            <select id="category_id" name="category_id" class="w-full rounded-lg border border-ink/15 px-3 py-2 text-sm">
                <option value="">Tanpa Kategori</option>
                @foreach ($categories as $category)
                    <option value="{{ $category->id }}" @selected(old('category_id', $tutorial->category_id ?? null) == $category->id)>{{ $category->name }}</option>
                @endforeach
            </select>
            @error('category_id') <p class="mt-1 text-xs text-red-600">{{ $message }}</p> @enderror
        </div>

        <div>
            <label for="status" class="mb-1 block text-sm font-medium text-secondary">Status</label>
            <select id="status" name="status" class="w-full rounded-lg border border-ink/15 px-3 py-2 text-sm">
                <option value="draft" @selected(old('status', $tutorial->status ?? 'draft') === 'draft')>Draft</option>
                <option value="published" @selected(old('status', $tutorial->status ?? '') === 'published')>Published</option>
            </select>
            @error('status') <p class="mt-1 text-xs text-red-600">{{ $message }}</p> @enderror
        </div>

        <div>
            <label for="thumbnail" class="mb-1 block text-sm font-medium text-secondary">Thumbnail</label>
            @isset($tutorial)
                @if ($tutorial->thumbnail)
                    <img src="{{ route('tutorials.thumbnail', $tutorial) }}" alt="" class="mb-2 h-24 w-full rounded-lg object-cover ring-1 ring-ink/10">
                @endif
            @endisset
            <input id="thumbnail" name="thumbnail" type="file" accept="image/png,image/jpeg,image/webp"
                   class="w-full rounded-lg border border-ink/15 px-3 py-2 text-sm file:mr-3 file:rounded-md file:border-0 file:bg-primary/10 file:px-3 file:py-1.5 file:text-primary">
            @error('thumbnail') <p class="mt-1 text-xs text-red-600">{{ $message }}</p> @enderror
        </div>
    </div>
</div>

<div class="mt-6 flex justify-end gap-3">
    <a href="{{ route('admin.tutorials.index') }}" class="rounded-lg px-4 py-2 text-sm font-medium text-ink/60 hover:bg-ink/5">Batal</a>
    <button type="submit" class="rounded-lg bg-primary px-5 py-2 text-sm font-semibold text-white hover:opacity-90">Simpan</button>
</div>
