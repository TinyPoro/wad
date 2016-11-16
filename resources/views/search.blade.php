<?php
// Start the session
session_start();
?>

@extends('templates.template')
 
@section('content')
<html>
<h1>Browser</h1>
<div class="dropdown">
        <button class="btn btn-primary dropdown-toggle" id="don_vi" type="button" data-toggle="dropdown">Choose type:</button>
        <span class="caret"></span></button>
        <ul class="dropdown-menu">
          @foreach($linh_vucs as $linh_vuc)
            <li><a onclick = "myfunction(this)" href="#">{{$linh_vuc->ten}}</a></li>
          
            {!! Form::hidden($linh_vuc->ten, "", ['id' => $linh_vuc->ten]) !!} </br>
          @endforeach
        </ul>
</div>

<div class="dropdown">
        <button class="btn btn-primary dropdown-toggle" id="giang_vien" type="button" data-toggle="dropdown">Choose type:</button>
        <span class="caret"></span></button>
        <ul class="dropdown-menu">
          @foreach($linh_vucs as $linh_vuc)
            <li><a onclick = "myfunction(this)" href="#">{{$linh_vuc->ten}}</a></li>
          
            {!! Form::hidden($linh_vuc->ten, "", ['id' => $linh_vuc->ten]) !!} </br>
          @endforeach
        </ul>
</div>

<div class="dropdown">
        <button class="btn btn-primary dropdown-toggle" id="linh_vuc" type="button" data-toggle="dropdown">Choose type:</button>
        <span class="caret"></span></button>
        <ul class="dropdown-menu">
          @foreach($linh_vucs as $linh_vuc)
            <li><a onclick = "myfunction(this)" href="#">{{$linh_vuc->ten}}</a></li>
          
            {!! Form::hidden($linh_vuc->ten, "", ['id' => $linh_vuc->ten]) !!} </br>
          @endforeach
          
        </ul>
</div>

<div class="dropdown">
        <button class="btn btn-primary dropdown-toggle" id="chu_de_nghien_cuu" type="button" data-toggle="dropdown">Choose type:</button>
        <span class="caret"></span></button>
        <ul class="dropdown-menu">
          <li><a onclick = "checkType(this)" href="#">Giảng viên</a></li>
          <li><a onclick = "checkType(this)" href="#">Sinh viên</a></li>
        </ul>
</div>
	{!! Form::open() !!}
    {!! Form::label('huong_nghien_cuu','Hướng nghiên cứu:') !!}</br>
 		{!! Form::textarea('huong_nghien_cuu') !!}</br>
	 	
    @foreach($linh_vucs as $linh_vuc)
    
    {{$linh_vuc->ten}}:
  
    {!! Form::checkbox($linh_vuc->ten, $linh_vuc->ten, false, ['onclick' => 'myfunction(this)']) !!}</br>
    {!! Form::hidden($linh_vuc->ten, "", ['id' => $linh_vuc->ten]) !!} </br>
    @endforeach
    

		{!! Form::submit('Them moi')!!}
	{!! Form::close() !!}
	
</html>

<script type="text/javascript">
    var myfunction = function(checkbox) {
      var name = checkbox.name;
      var input = document.getElementById(name);
      input.value = name;
    }
</script>

@stop