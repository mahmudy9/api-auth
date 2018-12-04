@extends('admin.layout')

@section('content')


<h3>Admin Dashboard</h3>
<br>
@include('errors')
<form action="{{url('/admin/new-client')}}" method="post">
@method('post')
@csrf
<div class="form-group">

<label for="phone" >Phone:</label>
<input type="text" name="phone" class="form-control" >
</div>
<div class="form-group">
<label for="name" >Name:</label>
<input type="text" name="name" class="form-control" >
</div>
<div class="form-group">
<label for="password" >Password:</label>
<input type="password" name="password" class="form-control" >
</div>
<div class="form-group">
<label for="password_confirmation" >Confirm Password:</label>
<input type="password" name="password_confirmation" class="form-control" >
</div>
<div class="form-group">
<input type="submit" class="form-control btn btn-primary" value="Create User" >
</div>

</form>

@endsection
