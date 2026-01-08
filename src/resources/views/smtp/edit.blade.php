@extends('laravel-mailer::layouts.app')

@section('content')
<div class="container">
    <h4 class="mb-3">✏️ Edit SMTP</h4>

    @if(session('success'))
        <div class="alert alert-success">{{ session('success') }}</div>
    @endif

    @if($errors->any())
        <div class="alert alert-danger">
            <ul class="mb-0">
                @foreach($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <form action="{{ route('mailer.update', $smtp) }}" method="POST">
        @csrf
        @method('PUT')

        @include('laravel-mailer::smtp.form', ['smtp' => $smtp])

        <button class="btn btn-primary">Update</button>
        <a href="{{ route('mailer.index') }}" class="btn btn-secondary">
            Back
        </a>
    </form>
</div>
@endsection
