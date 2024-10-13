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
        @yield("style")
        @show
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
            {{-- <div id="installBanner" class="install-banner ">
                <div class="KtirmdPyAoRKsFL no-blurring" dir="rtl">
                    <div class="KtirmdPyAoRKsFL-inner iossafari">
                        <div class="KtirmdPyAoRKsFL-header">
                            <div class="KtirmdPyAoRKsFL-title">تثبيت التطبيق</div>
                            <div class="KtirmdPyAoRKsFL-close"><svg xmlns="http://www.w3.org/2000/svg" width="24"
                                    height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"
                                    stroke-linecap="round" stroke-linejoin="round">
                                    <line x1="18" y1="6" x2="6" y2="18"></line>
                                    <line x1="6" y1="6" x2="18" y2="18"></line>
                                </svg></div>
                        </div>
                        <div class="KtirmdPyAoRKsFL-details">
                            <div class="KtirmdPyAoRKsFL-img bordered"><img alt="app icon"
                                    style="color:transparent !important;"
                                    src="https://pwa.xyz/v0/b/pwaa-8d87e.appspot.com/o/JwUMjuIlWnAK04DaLTxA%2FZLkSduBnYTEamqD.png?alt=media&amp;token=4a721c1b-8a6d-4d43-bc98-1001d191c3f9">
                            </div>
                            <div class="KtirmdPyAoRKsFL-text">
                                <div>تجهيزات</div>
                                <div>www.xn--mgbfbg4a0jva.com</div>
                            </div>
                        </div>
                        <div class="KtirmdPyAoRKsFL-content">
                            <div class="KtirmdPyAoRKsFL-li" data-li="1.">
                                <div>اضغط على <div class="apple-share-sheet-icon"><svg
                                            xmlns="http://www.w3.org/2000/svg"
                                            xmlns:xlink="http://www.w3.org/1999/xlink" style="isolation:isolate"
                                            viewBox="0 0 24 24" width="24pt" height="24pt">
                                            <defs>
                                                <clipPath id="_clipPath_1sSIM60gYe6pwlZAeBP3QLTk5j8EPtsR">
                                                    <rect width="24" height="24"></rect>
                                                </clipPath>
                                            </defs>
                                            <g clip-path="url(#_clipPath_1sSIM60gYe6pwlZAeBP3QLTk5j8EPtsR)">
                                                <clipPath id="_clipPath_6qRU20enGGXkl32V2q7xhx6EpHZn10bb">
                                                    <rect x="0" y="0" width="24" height="24"
                                                        transform="matrix(1,0,0,1,0,0)" fill="rgb(255,255,255)"></rect>
                                                </clipPath>
                                                <g clip-path="url(#_clipPath_6qRU20enGGXkl32V2q7xhx6EpHZn10bb)">
                                                    <g></g>
                                                </g>
                                                <path
                                                    d=" M 15.7 4.98 L 12.033 2.36 L 8.344 4.99 L 6.761 3.84 L 12.022 0.07 L 17.416 3.86 L 15.7 4.98 Z "
                                                    fill="rgb(0,122,255)"></path>
                                                <path
                                                    d=" M 11.481 2.884 L 12.519 2.884 L 12.519 13.766 L 11.481 13.766 L 11.481 2.884 Z "
                                                    fill="rgb(0,122,255)" vector-effect="non-scaling-stroke"
                                                    stroke-width="1" stroke="rgb(0,122,255)" stroke-linejoin="miter"
                                                    stroke-linecap="butt" stroke-miterlimit="4"></path>
                                                <path
                                                    d=" M 18.603 23.687 L 5.397 23.687 C 4.741 23.687 4.114 23.427 3.649 22.963 C 3.193 22.489 2.936 21.851 2.946 21.184 L 2.946 9.093 C 2.946 8.435 3.202 7.797 3.658 7.324 C 4.124 6.86 4.751 6.599 5.397 6.599 L 8.819 6.599 L 8.819 8.909 L 5.397 8.909 C 5.349 8.909 5.302 8.928 5.264 8.957 C 5.235 8.996 5.216 9.044 5.216 9.093 L 5.216 21.184 C 5.216 21.232 5.235 21.281 5.264 21.319 C 5.302 21.358 5.349 21.377 5.397 21.377 L 18.603 21.377 C 18.603 21.377 18.613 21.377 18.613 21.377 C 18.66 21.377 18.708 21.358 18.746 21.329 C 18.774 21.29 18.793 21.252 18.784 21.203 C 18.784 21.194 18.784 21.194 18.784 21.184 L 18.784 9.093 C 18.784 9.044 18.765 8.996 18.736 8.957 C 18.698 8.928 18.651 8.909 18.603 8.909 L 15.273 8.909 L 15.273 6.599 L 18.603 6.599 C 19.259 6.589 19.886 6.85 20.351 7.314 C 20.807 7.788 21.064 8.435 21.054 9.093 L 21.054 21.184 C 21.064 21.851 20.807 22.489 20.351 22.963 C 19.886 23.427 19.259 23.687 18.603 23.687 Z "
                                                    fill="rgb(0,122,255)"></path>
                                            </g>
                                        </svg></div> في قائمة المتصفح</div>
                            </div>
                            <div class="KtirmdPyAoRKsFL-li" data-li="2.">
                                <div>قم بالتمرير لأسفل وحدد <span>أضف إلى الشاشة الرئيسية</span></div>
                            </div>
                            <div class="KtirmdPyAoRKsFL-li" data-li="3.">
                                <div>ابحث عن الرمز <img alt="app icon" class="KtirmdPyAoRKsFL-inline-logo"
                                        src="https://pwa.xyz/v0/b/pwaa-8d87e.appspot.com/o/JwUMjuIlWnAK04DaLTxA%2FZLkSduBnYTEamqD.png?alt=media&amp;token=4a721c1b-8a6d-4d43-bc98-1001d191c3f9"
                                        style="color:transparent !important">
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div> --}}
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
        if (isIOS() && !isInStandaloneMode()) {
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
        <script src=" https://code.jquery.com/jquery-3.6.0.min.js"></script>

        <!-- Import other JavaScript files -->
        @vite(['resources/js/app.js'])
        <script src="{{ asset('js/snackbar.js') }}" defer></script>
        <script src="{{ asset('js/display.js') }}" defer></script>
        <script src="{{ asset('js/changeCategory.js') }}" defer></script>
        <script src="{{ asset('js/changeText.js') }}" defer></script>
        <script src="{{ asset('js/enablePush.js') }}" defer></script>
        <script src="{{ asset('js/animation.js') }}" defer></script>
        <script src="{{ asset('js/copyToClipboard.js') }}" defer></script>
        <script src="https://kit.fontawesome.com/00a20bfa02.js" crossorigin="anonymous" defer>
        </script>


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