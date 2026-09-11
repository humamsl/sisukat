@extends('layouts.admin')

@section('title', 'Tambah Akun')

@section('content')
<h2 class="mb-5 text-lg font-semibold text-secondary">Tambah Akun</h2>

<form method="POST" action="{{ route('admin.users.store') }}">
    @include('admin.users._form')
</form>
@endsection
