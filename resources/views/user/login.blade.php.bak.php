@extends('layout.master')

@section('title')
e-RKB | Login
@endsection
@section('content')

  <div class="cont">
  <div class="demo">
    <div class="container">
      <div class="col-lg-12">     
        <p class="login__signup success_msg" style="color: green;display: none;font-size: 25px!important;">Login Success</p>
        <p class="login__signup redirect_msg" style="display:none;font-size: 25px!important;color: black;text-shadow: 2px 2px white;font-weight: bolder;">Loading...</p>
      </div>
    </div>
    <div class="login" >
      <div align="center" style="padding-top: 50px;">
        <img src="{{asset('/images/abp.png')}}" width="150px" class="img-rounded">
      </div>
      <div class="login__form">
        <form method="post" action="" class="login_form_submit">
        <div class="login__row">
          <svg class="login__icon name svg-icon" viewBox="0 0 20 20">
            <path d="M0,20 a10,8 0 0,1 20,0z M10,0 a4,4 0 0,1 0,8 a4,4 0 0,1 0,-8" />
          </svg>
          <input type="text" class="login__input name" name="username" placeholder="Username" required autofocus />
        </div> 
        <div class="login__row">
          <svg class="login__icon pass svg-icon" viewBox="0 0 20 20">
            <path d="M0,20 20,20 20,8 0,8z M10,13 10,16z M4,8 a6,8 0 0,1 12,0" />
          </svg>
          <input type="password" class="login__input pass" name="password" placeholder="Password" required/>
        </div>
        <button type="submit" class="login__submit">Sign in</button>
        </form>
<p class="login__signup error_msg" style="color: red;display: none;">Username or Password Wrong!</p>
      </div>
    </div>
  </div>
</div>
@endsection

@section('css')
<link rel='stylesheet prefetch' href="{{asset('css/sans.css?family=Open+Sans')}}">
<link rel="stylesheet" href="{{asset('/css/style.css')}}">

<style>
  .cont{
  background: url("{{asset('/images/bg-abp.jpg')}}") no-repeat center center fixed;
  -webkit-background-size: cover;
  -moz-background-size: cover;
  background-size: cover;
  }
</style>

@endsection
@section('js')
<script  src="{{asset('/js/index.js/')}}"></script>
@endsection