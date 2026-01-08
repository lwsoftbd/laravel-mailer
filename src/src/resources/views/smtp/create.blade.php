@extends('laravel-mailer::layouts.app')

@section('content')
<div class="container">
    <h4 class="mb-3">➕ Add SMTP</h4>

    @if($errors->any())
        <div class="alert alert-danger">
            <ul class="mb-0">
                @foreach($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <form action="{{ route('mailer.store') }}" method="POST">
        @csrf

        @include('laravel-mailer::smtp.form')

        <button class="btn btn-primary">Save</button>
        <a href="{{ route('mailer.index') }}" class="btn btn-secondary">
            Back
        </a>
    </form>
</div>
@endsection
