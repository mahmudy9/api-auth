@extends('admin.layout')

@section('content')


<h3>Admin Dashboard</h3>
<br>
@include('errors')
<form action="{{url('/admin/updateclientdata')}}" method="post">
@method('put')
@csrf
<div class="form-group">

<label for="phone" >Phone:</label>
<input type="text" name="phone" class="form-control" value="{{$client->phone}}">
</div>
<div class="form-group">
<label for="name" >Name:</label>
<input type="text" name="name" class="form-control" value="{{$client->name}}" >
</div>
<input type="hidden" name="clientid" value="{{$client->id}}" />
<div class="form-group">
<label for="password" >Password:</label>
<input type="password" name="password" class="form-control" >
</div>
<div class="form-group">
<label for="password_confirmation" >Confirm Password:</label>
<input type="password" name="password_confirmation" class="form-control" >
</div>
<div class="form-group">
<label for="verified" >verified:</label>
<input type="radio" name="verified" value="1" @if($client->verified_at != null) checked @endif > Yes
<input type="radio" name="verified" value="0" @if($client->verified_at == null) checked @endif  > No

</div>

<div class="form-group">
<input type="submit" class="form-control btn btn-primary" value="update client" >
</div>

</form>

@endsection
