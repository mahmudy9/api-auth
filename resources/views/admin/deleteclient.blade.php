@extends('admin.layout')

@section('content')


<h3>Admin Dashboard</h3>
<br>
@include('errors')
<form action="{{url('/admin/destroyclient')}}" method="post">
@method('delete')
@csrf
<input type="hidden" name="clientid" value="{{$client->id}}" />
<a href="{{url('/admin')}}" class="btn btn-info">Do not delete</a>
<input type="submit" class="btn btn-danger" value="delete client" />

</form>

@endsection
