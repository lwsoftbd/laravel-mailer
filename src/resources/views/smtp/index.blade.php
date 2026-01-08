@extends('laravel-mailer::layouts.app')

@section('content')
<div class="container">
    <div class="d-flex justify-content-between mb-3">
        <h4>SMTP Manager</h4>
        <a href="{{ route('mailer.create') }}" class="btn btn-primary">
            ➕ Add SMTP
        </a>
    </div>

    @if(session('success'))
        <div class="alert alert-success">{{ session('success') }}</div>
    @endif

    <table class="table table-bordered table-striped">
        <thead>
            <tr>
                <th>Host</th>
                <th>Port</th>
                <th>From</th>
                <th>Encryption</th>
                <th>Default</th>
                <th width="200">Action</th>
            </tr>
        </thead>
        <tbody>
            @forelse($smtps as $smtp)
                <tr>
                    <td>{{ $smtp->host }}</td>
                    <td>{{ $smtp->port }}</td>
                    <td>{{ $smtp->from_address }}</td>
                    <td>{{ strtoupper($smtp->encryption ?? '-') }}</td>
                    <td>
                        @if($smtp->is_default)
                            <span class="badge bg-success">Default</span>
                        @else
                            <form action="{{ route('mailer.default', $smtp) }}" method="POST">
                                @csrf
                                <button class="btn btn-sm btn-outline-success">
                                    Set Default
                                </button>
                            </form>
                        @endif
                    </td>
                    <td>
                        <a href="{{ route('mailer.edit', $smtp) }}" class="btn btn-sm btn-info">
                            Edit
                        </a>

                        <form action="{{ route('mailer.delete', $smtp) }}"
                              method="POST"
                              class="d-inline"
                              onsubmit="return confirm('Are you sure?')">
                            @csrf
                            @method('DELETE')
                            <button class="btn btn-sm btn-danger">
                                Delete
                            </button>
                        </form>
                    </td>
                </tr>
            @empty
                <tr>
                    <td colspan="6" class="text-center text-muted">
                        No SMTP configured yet
                    </td>
                </tr>
            @endforelse
        </tbody>
    </table>
</div>

<div class="card mb-4">
    <div class="card-header">
        🧪 Send a Test Email
    </div>
    <div class="card-body">
        @if(session('test_success'))
            <div class="alert alert-success">{{ session('test_success') }}</div>
        @endif

        @if(session('test_error'))
            <div class="alert alert-danger">{{ session('test_error') }}</div>
        @endif

        <form action="{{ route('mailer.test') }}" method="POST">
            @csrf

            <div class="row">
                <div class="col-md-6 mb-3">
                    <label for="test_email">Recipient Email</label>
                    <input type="email" name="email" id="test_email"
                           class="form-control" placeholder="example@test.com" required>
                </div>
                <div class="col-md-6 mb-3 d-flex align-items-end">
                    <button class="btn btn-success w-100">Send</button>
                </div>
            </div>
        </form>
    </div>
</div>

@endsection
