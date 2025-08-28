@extends('layouts.admin')
@section('content')
hello

<form action="{{ route('logout') }}" method="POST" class="d-inline">
    @csrf
    <button type="submit" class="btn btn-danger" onclick="return confirm('Are you sure you want to logout?')">
        <i class="mdi mdi-logout me-1"></i> Logout
    </button>
</form>

@endsection