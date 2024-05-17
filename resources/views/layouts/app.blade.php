<!DOCTYPE html>
<html lang="ar" dir="rtl">

    <head>
        <meta charset="UTF-8">
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <meta name="csrf-token" content="{{ Session::token() }}">
        <meta name="mobile-web-app-capable" content="yes">
        <meta name="apple-mobile-web-app-capable" content="yes">
        <meta name="msapplication-starturl" content="/">
        <meta name="theme-color" content="#e5e5e5">
        <title>فقه الحياة | @yield('title')</title>
        <meta name="description" content="@yield('description')">
        <link rel="manifest" href="{{ asset('/manifest.json') }}">
        <link rel="apple-touch-icon" href="{{ asset('/images/logo.png') }}">
        @vite(['resources/css/app.css', 'resources/sass/app.scss'])
        <script src="{{ asset('js/truncate.js') }}"></script>

        @isset($category)
        @include('layouts.googleTool')
        @endisset


        @auth
        <script src="{{ asset('js/gtmLoginEvent.js') }}" defer></script>
        @endauth

        <!-- GTM -->
        <script src="{{ asset('js/gtm.js') }}" defer></script>
        <!-- End GTM -->

        @include('layouts.images')
        <style>
            .install-banner {
                display: none;
            }

            .install-banner.show {
                display: flex;
            }
        </style>
    </head>

    <body lang="ar" dir="rtl">
        @include("shared.message")
        <div class="wrapper">
            @include('layouts.sideMenu')
            <div id="content">
                <header>
                    <div class="jumbotron text-center" style="margin-bottom: 0px;">
                        <h1>فقه الحياة</h1>
                        <b>مقولات الدكتور عبدالعزيز فيصل المطوع</b>
                        <p style="font-size: 10px; color:grey;">تم تطوير الموقع بواسطة: <a target="_blank"
                                href="https://bmc.link/mohammed71Q/"> محمد المطوع <i
                                    class="fa-solid fa-mug-hot"></i></a> </p>
                        <button class="subscribe-button" id="subscribe_button">اشترك في خدمة الإشعارات</button><br>
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
        <div id="installBanner" class="install-banner fixed bottom-0 left-0 right-0 p-4 bg-gray-800 text-white flex
        items-center justify-between">
            <div>
                <p>Install this app on your device for a better experience.</p>
                <p>Tap <span class="inline-block bg-white text-black px-2 py-1 rounded">Share</span> and then
                    <span class="inline-block bg-white text-black px-2 py-1 rounded">Add to Home
                        Screen</span>
                </p>
            </div>
            <button id="closeBanner" class="ml-4 bg-red-500 hover:bg-red-700 text-white font-bold py-2 px-4
        rounded">Close</button>
        </div>

        <script>
            // Function to detect iOS
        function isIOS() {
        return /iphone|ipad|ipod/.test(window.navigator.userAgent.toLowerCase());
        }

        // Function to detect if in standalone mode
        function isInStandaloneMode() {
        return ('standalone' in window.navigator) && (window.navigator.standalone);
        }

        // Show the install banner if it's iOS and not in standalone mode
        window.addEventListener('load', function() {
        if (isIOS() &amp;&amp; !isInStandaloneMode()) {
        const installBanner = document.getElementById('installBanner');
        installBanner.classList.add('show');
        }
        });

        // Close the install banner
        document.getElementById('closeBanner').addEventListener('click', function() {
        const installBanner = document.getElementById('installBanner');
        installBanner.classList.remove('show');
        });
        </script>
        <!-- Add to home screen prompt -->
        @include('shared.addToHome')

        <!-- Import jQuery CDN -->
        <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>

        <!-- Import other JavaScript files -->
        @vite(['resources/js/app.js'])
        <script src="{{ asset('js/snackbar.js') }}" defer></script>
        <script src="{{ asset('js/display.js') }}" defer></script>
        <script src="{{ asset('js/changeCategory.js') }}" defer></script>
        <script src="{{ asset('js/changeText.js') }}" defer></script>
        <script src="{{ asset('js/enablePush.js') }}" defer></script>
        <script src="{{ asset('js/animation.js') }}" defer></script>
        <script src="{{ asset('js/copyToClipboard.js') }}" defer></script>
        <script src="https://kit.fontawesome.com/00a20bfa02.js" crossorigin="anonymous" defer></script>


        <!-- Facebook Tag  -->
        <div id="fb-root"></div>
        <script defer crossorigin="anonymous"
            src="https://connect.facebook.net/ar_AR/sdk.js#xfbml=1&version=v16.0&appId=5767579369977319&autoLogAppEvents=1"
            nonce="hCrAcreE"></script>
        <!-- End Facebook Tag -->

        <script src="{{ asset('js/basket.js') }}" defer></script>
        <script src="{{ asset('js/likes.js') }}" defer></script>
        <script src="{{ asset('js/stickNavigationBar.js') }}" defer></script>
        <!-- Google Tag Manager (noscript) -->
        <noscript><iframe src="https://www.googletagmanager.com/ns.html?id=GTM-MT2XGKM" height="0" width="0"
                style="display:none;visibility:hidden"></iframe></noscript>
        <!-- End Google Tag Manager (noscript) -->
    </body>

</html>