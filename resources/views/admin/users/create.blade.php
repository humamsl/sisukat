@extends('layouts.admin')

@section('title', 'Tambah Admin')

@section('content')
<h2 class="mb-5 text-lg font-semibold text-secondary">Tambah Admin</h2>

<form method="POST" action="{{ route('admin.users.store') }}">
    @include('admin.users._form')
</form>
@endsection
