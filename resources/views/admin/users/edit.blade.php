@extends('layouts.admin')

@section('title', 'Detail Akun')

@section('content')
<h2 class="mb-5 text-lg font-semibold text-secondary">Detail Akun: {{ $user->name }}</h2>

<form method="POST" action="{{ route('admin.users.update', $user) }}">
    @include('admin.users._form')
</form>
@endsection
