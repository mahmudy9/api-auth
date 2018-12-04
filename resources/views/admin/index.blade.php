@extends('admin.layout')

@section('content')


<h3>Admin Dashboard</h3>
<br>
@include('status')
<table class="table">

<tr>
    <td>ID</td>
    <td>Phone</td>
    <td>Name</td>
    <td>Verified</td>
    <td>Edit</td>
    <td>Delete</td>
</tr>
@foreach($clients as $client)

<tr>
    <td>{{$client->id}}</td>
    <td>{{$client->phone}}</td>
    <td>{{$client->name}}</td>
    <td>@if($client->verified_at != null)
        Yes
        @else
        No
        @endif</td>
    <td><a class="btn btn-info" href="{{url('/admin/updateclient/'.$client->id)}}">Edit</td>
    <td><a class="btn btn-danger" href="{{url('/admin/deleteclient/'.$client->id)}}"> Delete</td>

</tr>

@endforeach

</table>
{{$clients->links()}}
@endsection
