@extends('layout.admin.app')
@section('content')
<section>
    @if(session('success'))
        <x-toast-message type="success" :message="session('success')" />
    @endif
</section>
@endsection
