@extends('layouts.admin')

@section('title', 'Edit Buku')

@section('content')
<h2 class="mb-5 text-lg font-semibold text-secondary">Edit Buku: {{ $book->title }}</h2>

<form method="POST" action="{{ route('admin.books.update', $book) }}" enctype="multipart/form-data">
    @include('admin.books._form')
</form>
@endsection
