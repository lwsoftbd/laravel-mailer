@extends('layouts.app')

@section('content')
<div class="container">
<h4>Laravel Mailer – SMTP Settings</h4>

<table class="table table-bordered">
<thead>
<tr>
 <th>Host</th>
 <th>Queue</th>
 <th>Priority</th>
 <th>Status</th>
</tr>
</thead>
<tbody>
@foreach($smtps as $smtp)
<tr>
 <td>{{ $smtp->host }}</td>
 <td>{{ $smtp->queue ?? 'default' }}</td>
 <td>{{ $smtp->priority }}</td>
 <td>
  <form method="POST" action="{{ route('laravel-mailer.toggle',$smtp->id) }}">
   @csrf
   <button class="btn btn-sm {{ $smtp->active?'btn-success':'btn-danger' }}">
     {{ $smtp->active?'Active':'Inactive' }}
   </button>
  </form>
 </td>
</tr>
@endforeach
</tbody>
</table>
</div>
@endsection
