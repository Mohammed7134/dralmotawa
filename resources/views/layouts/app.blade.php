<!DOCTYPE html>
<html lang="ar" dir="rtl">

    <head>
        <link rel="manifest" href={{ asset("/manifest.json") }}>
        <link rel="apple-touch-icon" href={{ asset("/images/logo.png") }}>

        <meta name="mobile-web-app-capable" content="yes">
        <meta name="apple-mobile-web-app-capable" content="yes">
        <meta name="msapplication-starturl" content="/">
        <meta name="theme-color" content="#e5e5e5">

        <meta charset="UTF-8">
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
        <meta name="viewport" content="width=device-width, initial-scale=1.0 shrink-to-fit=no">
        <meta name="csrf-token" content="{{ Session::token() }}">

        <title>فقه الحياةِ | @yield('title')</title>

        @isset($originalId)
        @include('layouts.googleTool')
        @endisset
        <meta name="description" content="@yield('description')">

        <script src="{{asset('js/snackbar.js')}}"></script>
        <script src="{{asset('js/truncate.js')}}"></script>
        <script src="{{asset('js/display.js')}}"></script>
        <script src="{{asset('js/changeCategory.js')}}"></script>
        <script src="{{ asset('js/enablePush.js') }}" defer></script>
        <script src="https://kit.fontawesome.com/00a20bfa02.js" crossorigin="anonymous" defer></script>

        @vite(['resources/css/app.css', 'resources/sass/app.scss'])

        @auth <script src="{{asset('js/gtmLoginEvent.js')}}" defer></script> @endauth
        <!-- GTM -->
        <script src="{{asset('js/gtm.js')}}" defer></script>
        <!-- End GTM -->

        @include('layouts.images')

    </head>

    <body>
        <!-- Google Tag Manager (noscript) -->
        <noscript><iframe src="https://www.googletagmanager.com/ns.html?id=GTM-MT2XGKM" height="0" width="0"
                style="display:none;visibility:hidden"></iframe></noscript>
        <!-- End Google Tag Manager (noscript) -->

        @include("shared.message")
        <div class="wrapper">
            @include('layouts.sideMenu')
            <div id="content">
                <header>
                    <div class="jumbotron text-center" style="margin-bottom: 0px;">
                        <h1>فقه الحياة</h1>
                        <b>مقولات الدكتور عبدالعزيز فيصل المطوع</b>
                        <p style="font-size: 10px; color:grey;">تم تطوير الموقع بواسطة: <a target="_blank"
                                href="https://bmc.link/mohammed71Q/">محمد المطوع</a></p>
                        <button id="permission-btn">اشترك في خدمة الإشعارات</button><br>
                        @auth
                        <p>عدد المشتركين: <span>{{ $numberOfSubscribers }}</span></p>
                        <a href="/add"><button class="new-wisdom-button" type="submit">إضافة حكمة</button></a><br>
                        @endauth
                    </div>
                    @include('layouts.navigationBar')
                </header>
                @yield("content")
                @show
                <footer>
                    @include('layouts.footer')
                </footer>
            </div>
        </div>
        <!-- Add to home screen prompt -->
        @include('shared.addToHome')

        <!-- Import jQuery CDN -->
        <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>

        <!-- Import other JavaScript files -->
        @vite(['resources/js/app.js'])

        <!-- Facebook Tag  -->
        <div id="fb-root"></div>
        <script defer crossorigin="anonymous"
            src="https://connect.facebook.net/ar_AR/sdk.js#xfbml=1&version=v16.0&appId=5767579369977319&autoLogAppEvents=1"
            nonce="hCrAcreE"></script>
        <!-- End Facebook Tag -->
    </body>
    <!-- basket -->
    <script src="{{asset('js/basket.js')}}" defer></script>
    <script src="{{asset('js/likes.js')}}" defer></script>
    <script src="{{asset('js/stickNavigationBar.js')}}" defer></script>

</html>