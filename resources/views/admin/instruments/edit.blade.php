@extends('layouts.admin')

@section('title', 'Edit Instrumen')

@section('content')
<h2 class="mb-5 text-lg font-semibold text-secondary">Edit Instrumen: {{ $instrument->title }}</h2>

<form method="POST" action="{{ route('admin.instruments.update', $instrument) }}" enctype="multipart/form-data">
    @include('admin.instruments._form')
</form>
@endsection
