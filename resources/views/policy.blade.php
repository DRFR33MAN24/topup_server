<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8"/>
    <meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1"/>
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Privacy Policy</title>
    <link href='//fonts.googleapis.com/css?family=Lato:300,400|Montserrat:700' rel='stylesheet' type='text/css'>

    <script src="{{ asset('assets/themes/deepblue/assets/fontawesome/fontawesomepro.js') }}"></script>
    <link href="{{asset('assets/admin/css/success-failed.css')}}" rel="stylesheet" type="text/css">
</head>
<body>
<header class="site-header" id="header">
    <h1 class="site-header__title" data-lead-id="site-header-title">Privacy Policy</h1>
</header>

<div class="main-content">
    <i class="fa fa-check main-content__checkmark" id="checkmark"></i>
  {!! $policy[0]->value !!}

<p>&nbsp;</p>
</div>

<footer class="site-footer" id="footer">
    <a href="{{ url('/') }}">@lang('Go back to Home')</a>
    <p class="site-footer__fineprint" id="fineprint">@lang('Copyright') ©{{ date('Y') }} | @lang('All Rights Reserved') <a href="{{ url('/') }}" class="site_title">{{ $basic->site_title ?? 'Bug-Finder' }}</a></p>
</footer>
</body>
</html>