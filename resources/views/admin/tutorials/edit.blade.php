@extends('layouts.admin')

@section('title', 'Edit Tutorial')

@section('content')
<h2 class="mb-5 text-lg font-semibold text-secondary">Edit Tutorial: {{ $tutorial->title }}</h2>

<form method="POST" action="{{ route('admin.tutorials.update', $tutorial) }}" enctype="multipart/form-data">
    @include('admin.tutorials._form')
</form>
@endsection
