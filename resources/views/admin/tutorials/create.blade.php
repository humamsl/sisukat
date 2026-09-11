@extends('layouts.admin')

@section('title', 'Tambah Tutorial')

@section('content')
<h2 class="mb-5 text-lg font-semibold text-secondary">Tambah Tutorial</h2>

<form method="POST" action="{{ route('admin.tutorials.store') }}" enctype="multipart/form-data">
    @include('admin.tutorials._form')
</form>
@endsection
