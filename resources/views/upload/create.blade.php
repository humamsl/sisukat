@extends('layouts.app')

@section('title', 'Upload Dokumen')

@section('content')
<section class="bg-secondary py-12 text-white">
    <div class="mx-auto max-w-3xl px-4 sm:px-6 lg:px-8">
        <h1 class="text-2xl font-extrabold sm:text-3xl">Upload Dokumen</h1>
        <p class="mt-2 text-white/70">Kirimkan dokumen terkait kegiatan supervisi akademik Anda. Dokumen akan diverifikasi oleh admin.</p>
    </div>
</section>

<section class="mx-auto max-w-3xl px-4 py-10 sm:px-6 lg:px-8">
    <div
        x-data="{
            submitting: false,
            progress: 0,
            error: null,
            fileInfo: null,
            maxKb: {{ config('sisukat.uploads.document_max_kb') }},
            onFileChange(e) {
                const f = e.target.files[0];
                this.fileInfo = f ? `${f.name} (${(f.size / 1024 / 1024).toFixed(2)} MB)` : null;
            },
            submit(e) {
                this.submitting = true;
                this.progress = 0;
                this.error = null;
                const form = e.target;
                const xhr = new XMLHttpRequest();
                xhr.open('POST', form.action);
                xhr.setRequestHeader('Accept', 'application/json');
                xhr.upload.addEventListener('progress', (ev) => {
                    if (ev.lengthComputable) this.progress = Math.round((ev.loaded / ev.total) * 100);
                });
                xhr.onload = () => {
                    this.submitting = false;
                    if (xhr.status >= 200 && xhr.status < 300) {
                        const resp = JSON.parse(xhr.responseText);
                        window.location.href = resp.redirect;
                    } else if (xhr.status === 422) {
                        const resp = JSON.parse(xhr.responseText);
                        this.error = Object.values(resp.errors).flat().join(' ');
                    } else {
                        this.error = 'Terjadi kesalahan. Silakan coba lagi.';
                    }
                };
                xhr.onerror = () => { this.submitting = false; this.error = 'Koneksi gagal. Silakan coba lagi.'; };
                xhr.send(new FormData(form));
            }
        }"
        class="rounded-2xl bg-white p-6 shadow-sm ring-1 ring-ink/5 sm:p-8"
    >
        <template x-if="error">
            <div class="mb-5 rounded-lg bg-red-50 px-4 py-3 text-sm text-red-700" x-text="error"></div>
        </template>

        <form method="POST" action="{{ route('upload.store') }}" enctype="multipart/form-data" @submit.prevent="submit">
            @csrf
            <div class="grid gap-5 sm:grid-cols-2">
                <div class="sm:col-span-2">
                    <label for="name" class="mb-1 block text-sm font-medium text-secondary">Nama Lengkap</label>
                    <input id="name" name="name" type="text" required class="w-full rounded-lg border border-ink/15 px-3 py-2 text-sm focus:border-primary focus:outline-none focus:ring-2 focus:ring-primary/20">
                </div>

                <div>
                    <label for="email" class="mb-1 block text-sm font-medium text-secondary">Email</label>
                    <input id="email" name="email" type="email" required class="w-full rounded-lg border border-ink/15 px-3 py-2 text-sm">
                </div>

                <div>
                    <label for="identity_number" class="mb-1 block text-sm font-medium text-secondary">NIP <span class="text-ink/40">(opsional)</span></label>
                    <input id="identity_number" name="identity_number" type="text" class="w-full rounded-lg border border-ink/15 px-3 py-2 text-sm">
                </div>

                <div>
                    <label for="position" class="mb-1 block text-sm font-medium text-secondary">Jabatan</label>
                    <input id="position" name="position" type="text" required placeholder="cth. Guru, Kepala Sekolah, Pengawas" class="w-full rounded-lg border border-ink/15 px-3 py-2 text-sm">
                </div>

                <div>
                    <label for="school" class="mb-1 block text-sm font-medium text-secondary">Nama Sekolah</label>
                    <input id="school" name="school" type="text" required class="w-full rounded-lg border border-ink/15 px-3 py-2 text-sm">
                </div>

                <div class="sm:col-span-2">
                    <label for="document_type" class="mb-1 block text-sm font-medium text-secondary">Jenis Dokumen</label>
                    <select id="document_type" name="document_type" required class="w-full rounded-lg border border-ink/15 px-3 py-2 text-sm">
                        <option value="">Pilih jenis dokumen</option>
                        <option>Rencana Pelaksanaan Pembelajaran (RPP)</option>
                        <option>Laporan Hasil Supervisi</option>
                        <option>Instrumen Terisi</option>
                        <option>Dokumen Tindak Lanjut</option>
                        <option>Lainnya</option>
                    </select>
                </div>

                <div class="sm:col-span-2">
                    <label for="description" class="mb-1 block text-sm font-medium text-secondary">Keterangan <span class="text-ink/40">(opsional)</span></label>
                    <textarea id="description" name="description" rows="3" class="w-full rounded-lg border border-ink/15 px-3 py-2 text-sm"></textarea>
                </div>

                <div class="sm:col-span-2">
                    <label for="file" class="mb-1 block text-sm font-medium text-secondary">File Dokumen</label>
                    <input id="file" name="file" type="file" required @change="onFileChange"
                           accept=".pdf,.doc,.docx,.xls,.xlsx,.jpg,.jpeg,.png"
                           class="w-full rounded-lg border border-ink/15 px-3 py-2 text-sm file:mr-3 file:rounded-md file:border-0 file:bg-primary/10 file:px-3 file:py-1.5 file:text-primary">
                    <p class="mt-1 text-xs text-ink/40">
                        Format: PDF, DOC, DOCX, XLS, XLSX, JPG, PNG. Maksimal <span x-text="(maxKb / 1024).toFixed(1)"></span> MB.
                        <span x-show="fileInfo" x-text="'Dipilih: ' + fileInfo" class="ml-1 text-ink/60"></span>
                    </p>
                </div>

                <div class="sm:col-span-2">
                    <label class="flex items-start gap-2 text-sm text-ink/70">
                        <input type="checkbox" name="agreement" required class="mt-0.5 rounded border-ink/30 text-primary focus:ring-primary/30">
                        Saya menyatakan bahwa dokumen yang dikirimkan adalah benar dan dapat dipertanggungjawabkan.
                    </label>
                </div>
            </div>

            <template x-if="submitting">
                <div class="mt-5">
                    <div class="h-2 w-full overflow-hidden rounded-full bg-ink/10">
                        <div class="h-full bg-primary transition-all" :style="`width: ${progress}%`"></div>
                    </div>
                    <p class="mt-1 text-xs text-ink/50">Mengunggah... <span x-text="progress"></span>%</p>
                </div>
            </template>

            <button type="submit" :disabled="submitting"
                    class="mt-6 flex w-full items-center justify-center gap-2 rounded-lg bg-primary px-4 py-2.5 text-sm font-semibold text-white transition hover:opacity-90 disabled:opacity-60">
                <x-lucide-loader-2 x-show="submitting" class="h-4 w-4 animate-spin" />
                <span x-text="submitting ? 'Mengunggah...' : 'Upload Dokumen'"></span>
            </button>
        </form>
    </div>
</section>
@endsection
