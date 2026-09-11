@extends('layouts.admin')

@section('title', 'Tambah Buku')

@section('content')
<h2 class="mb-5 text-lg font-semibold text-secondary">Tambah Buku Saku</h2>

<form method="POST" action="{{ route('admin.books.store') }}" enctype="multipart/form-data">
    @include('admin.books._form')
</form>
@endsection
