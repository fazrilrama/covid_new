@extends('adminlte::master')

@section('adminlte_css')
<link rel="stylesheet" href="{{ asset('vendor/adminlte/css/auth.css') }}">
@yield('css')
<style type="text/css">
.field-icon {
  float: right;
  margin-right: 8px;
  margin-top: -25px;
  position: relative;
  z-index: 2;
}
</style>
@stop

@section('body_class', 'login-page')

@section('body')
<div class="login-box">
    <div class="login-logo">
        <a href="{{ url(config('adminlte.dashboard_url', 'home')) }}"><img src={{ asset('logo.png') }} class="img-responsive"></a>
    </div>
    <!-- /.login-logo -->
    <div class="login-box-body">
        <p class="login-box-msg">{{ trans('adminlte::adminlte.password_reset_message') }}</p>
        <form action="{{ url(config('adminlte.password_reset_url', 'password/reset')) }}" method="post">
            {!! csrf_field() !!}

            <input type="hidden" name="token" value="{{ $token }}">

            <div class="form-group has-feedback {{ $errors->has('email') ? 'has-error' : '' }}">
                <input type="email" name="email" class="form-control" value="{{ isset($email) ? $email : old('email') }}"
                placeholder="{{ trans('adminlte::adminlte.email') }}">
                <span class="glyphicon glyphicon-envelope form-control-feedback"></span>
                @if ($errors->has('email'))
                <span class="help-block">
                    <strong>{{ $errors->first('email') }}</strong>
                </span>
                @endif
            </div>
            <div class="form-group has-feedback {{ $errors->has('password') ? 'has-error' : '' }}">
                <input type="password" name="password" class="form-control"
                placeholder="Masukkan Password Baru" id="password1">
                <!-- <input type="password" name="password" class="form-control"
                placeholder="{{ trans('adminlte::adminlte.password') }}" id="password1"> -->
                <span class="fa fa-fw fa-eye field-icon toggle-password" onclick="password1()"></span>
                <p class="help-block">Password terdiri dari minimal 8 karakter, maksimal 12. Terdiri dari minimal 1 huruf besar, 1 huruf kecil dan 1 angka. Tidak boleh mengandung karakter khusus.</p>
                <!-- <span class="glyphicon glyphicon-lock form-control-feedback"></span> -->
                @if ($errors->has('password'))
                <span class="help-block">
                    <strong>{{ $errors->first('password') }}</strong>
                </span>
                @endif
            </div>
            <div class="form-group has-feedback {{ $errors->has('password_confirmation') ? 'has-error' : '' }}">
                <input type="password" name="password_confirmation" class="form-control"
                placeholder="Konfirmasi Password Baru" id="password2">
                <!-- <input type="password" name="password_confirmation" class="form-control"
                placeholder="{{ trans('adminlte::adminlte.retype_password') }}" id="password2"> -->
                <span class="fa fa-fw fa-eye field-icon toggle-password" onclick="password2()"></span>
                <p class="help-block">Isi dengan password yang sama untuk konfirmasi.</p>
                <!-- <span class="glyphicon glyphicon-log-in form-control-feedback"></span> -->
                @if ($errors->has('password_confirmation'))
                <span class="help-block">
                    <strong>{{ $errors->first('password_confirmation') }}</strong>
                </span>
                @endif
            </div>
            <button type="submit"
            class="btn btn-primary btn-block btn-flat"
            >{{ trans('adminlte::adminlte.reset_password') }}</button>
        </form>
    </div>
    <!-- /.login-box-body -->
</div><!-- /.login-box -->
@stop

@section('adminlte_js')
<script type="text/javascript">
    function password1() {
        var x = document.getElementById("password1");
        if (x.type === "password") {
            x.type = "text";
        } else {
            x.type = "password";
        }
    }

     function password2() {
        var x = document.getElementById("password2");
        if (x.type === "password") {
            x.type = "text";
        } else {
            x.type = "password";
        }
    }
</script>
@yield('js')
@stop
