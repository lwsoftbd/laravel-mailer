@extends('layouts.app')

@section('content')
<div class="container">
    <h4>SMTP Manager</h4>

    <a href="{{ route('mailer.create') }}" class="btn btn-primary mb-2">
        Add SMTP
    </a>

    <table class="table table-bordered">
        <tr>
            <th>Host</th>
            <th>From</th>
            <th>Default</th>
            <th>Action</th>
        </tr>

        @foreach($smtps as $smtp)
        <tr>
            <td>{{ $smtp->host }}</td>
            <td>{{ $smtp->from_address }}</td>
            <td>
                @if($smtp->is_default)
                    ✅
                @else
                    <form method="POST" action="{{ route('mailer.default', $smtp) }}">
                        @csrf
                        <button class="btn btn-sm btn-success">Set Default</button>
                    </form>
                @endif
            </td>
            <td>
                <a href="{{ route('mailer.edit', $smtp) }}" class="btn btn-sm btn-info">Edit</a>
                <form method="POST" action="{{ route('mailer.delete', $smtp) }}" class="d-inline">
                    @csrf @method('DELETE')
                    <button class="btn btn-sm btn-danger">Delete</button>
                </form>
            </td>
        </tr>
        @endforeach
    </table>
</div>
@endsection
