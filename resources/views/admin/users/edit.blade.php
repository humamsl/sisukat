@extends('layouts.admin')

@section('title', 'Edit Admin')

@section('content')
<h2 class="mb-5 text-lg font-semibold text-secondary">Edit Admin: {{ $user->name }}</h2>

<form method="POST" action="{{ route('admin.users.update', $user) }}">
    @include('admin.users._form')
</form>
@endsection
