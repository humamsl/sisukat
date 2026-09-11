@extends('layouts.admin')

@section('title', 'Tambah Instrumen')

@section('content')
<h2 class="mb-5 text-lg font-semibold text-secondary">Tambah Instrumen Supervisi</h2>

<form method="POST" action="{{ route('admin.instruments.store') }}" enctype="multipart/form-data">
    @include('admin.instruments._form')
</form>
@endsection
