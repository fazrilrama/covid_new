@extends('adminlte::master')

@section('adminlte_css')
<link rel="stylesheet" href="{{ asset('vendor/adminlte/plugins/iCheck/square/blue.css') }}">
<link rel="stylesheet" href="{{ asset('vendor/adminlte/css/auth.css') }}">
<style type="text/css">
.field-icon {
  float: right;
  margin-right: 8px;
  margin-top: -25px;
  position: relative;
  z-index: 2;
}
</style>
@yield('css')
@stop

@section('body_class', 'login-page')

@section('body')
<div class="login-box">
    <div class="login-logo">
        <a href="{{ url(config('adminlte.dashboard_url', 'home')) }}"><img src={{ asset('logo.png') }} class="img-responsive"></a>
    </div>
    <!-- /.login-logo -->
    <div class="login-box-body">
        <p class="login-box-msg">{{ trans('adminlte::adminlte.login_message') }}</p>

        @if (session('message'))
        <div class="alert alert-info">
            {{ session('message') }}
        </div>
        @endif

        @if ($errors->has('locked_account'))
        <div class="alert alert-danger">
            {{ $errors->first('locked_account') }}
        </div>
        @endif
        <form action="{{ url(config('adminlte.login_url', 'login')) }}" method="post">
            {!! csrf_field() !!}

            <div class="form-group has-feedback {{ $errors->has('user_id') ? 'has-error' : '' }}">
                <input type="user_id" name="user_id" class="form-control" value="{{ old('user_id') }}"
                placeholder="User ID">
                <span class="glyphicon glyphicon-envelope form-control-feedback"></span>
                @if ($errors->has('user_id'))
                <span class="help-block">
                    <strong>{{ $errors->first('user_id') }}</strong>
                </span>
                @endif
            </div>
            <div class="form-group has-feedback {{ $errors->has('password') ? 'has-error' : '' }}">
                <input type="password" name="password" class="form-control"
                placeholder="{{ trans('adminlte::adminlte.password') }}" id="password_change">
                <!-- <span class="glyphicon glyphicon-lock form-control-feedback" onclick="myFunction()"></span> -->
                <span class="fa fa-fw fa-eye field-icon toggle-password" onclick="myFunction()"></span>
                @if ($errors->has('password'))
                <span class="help-block">
                    <strong>{{ $errors->first('password') }}</strong>
                </span>
                @endif
            </div>

            <p style="text-align:center;">{!! captcha_img('flat') !!}</p>
            <p style="text-align:center;"><input type="text" name="captcha" placeholder="Isi Captcha"></p>
            @if ($errors->has('captcha'))
            <span class="help-block text-center">
                <strong style="color:red;">{{ $errors->first('captcha') }}</strong>
            </span>
            @endif
            
            <div class="row">
                <div class="col-xs-8">
                    <div class="checkbox icheck">
                        <label>
                            <input type="checkbox" name="remember"> {{ trans('adminlte::adminlte.remember_me') }}
                        </label>
                    </div>
                </div>
                <!-- /.col -->
                <div class="col-xs-4">
                    <button type="submit"
                    class="btn btn-primary btn-block btn-flat">{{ trans('adminlte::adminlte.sign_in') }}</button>
                </div>
                <!-- /.col -->
            </div>
        </form>
        <div class="auth-links">
            <a href="{{ url(config('adminlte.password_reset_url', 'password/reset')) }}"
            class="text-center"
            >{{ trans('adminlte::adminlte.i_forgot_my_password') }}</a>
            <br>
            <a href="{{ url('unlock_account') }}" class="text-center">Unlock Account</a>
        </div>
        <br>
        <div class="row">
            <div class="col-xs-12 col-sm-12 col-md-12">
                <strong>© 2020 Powered By <a href="https://www.bgrlogistics.id/">BGR ACCESS</a>.</strong> All rights
            reserved.
            </div>
        </div>
    </div>
    <!-- /.login-box-body -->
</div><!-- /.login-box -->
@stop

@section('adminlte_js')
<script src="{{ asset('vendor/adminlte/plugins/iCheck/icheck.min.js') }}"></script>
<script>
    function myFunction() {
        var x = document.getElementById("password_change");
        if (x.type === "password") {
            x.type = "text";
        } else {
            x.type = "password";
        }
    }
    $(function () {
        $('input').iCheck({
            checkboxClass: 'icheckbox_square-blue',
            radioClass: 'iradio_square-blue',
                increaseArea: '20%' // optional
            });
    });
</script>
@yield('js')
@stop
