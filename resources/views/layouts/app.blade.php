<!DOCTYPE html>
<html lang="ar" dir="rtl">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ Session::token() }}">
    <!-- Google Tag Manager -->
    <script>
        (function(w,d,s,l,i){w[l]=w[l]||[];w[l].push({'gtm.start':
        new Date().getTime(),event:'gtm.js'});var f=d.getElementsByTagName(s)[0],
        j=d.createElement(s),dl=l!='dataLayer'?'&l='+l:'';j.async=true;j.src=
        'https://www.googletagmanager.com/gtm.js?id='+i+dl;f.parentNode.insertBefore(j,f);
        })(window,document,'script','dataLayer','GTM-MT2XGKM');
    </script>
    <!-- End Google Tag Manager -->
    
    <title>فقه الحياة | @yield('title')</title>
    <link href='https://fonts.googleapis.com/css?family=Montserrat:700|Lato:400' rel='stylesheet' type='text/css'>
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/font-awesome/4.4.0/css/font-awesome.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/normalize/5.0.0/normalize.min.css">
    <script src="https://kit.fontawesome.com/00a20bfa02.js" crossorigin="anonymous"></script>
    <!-- Bootstrap CSS CDN -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.rtl.min.css" integrity="sha384-gXt9imSW0VcJVHezoNQsP+TNrjYXoGcrqBZJpry9zJt8PCQjobwmhMGaDHTASo9N" crossorigin="anonymous">
    <!-- Font Awesome JS -->
    <script src="https://kit.fontawesome.com/00a20bfa02.js" crossorigin="anonymous"></script>
    <!-- Axios Library -->
    <script src="https://cdn.jsdelivr.net/npm/axios/dist/axios.min.js"></script>
    <!-- jQuery Library -->
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="{{asset('js/snackbar.js')}}"></script>
    <script src="{{asset('js/truncate.js')}}"></script>
    <script src="{{asset('js/display.js')}}"></script>
    <script src="{{asset('js/changeCategory.js')}}"></script>
    @vite(['resources/css/app.css'])

</head>

<body>
    @auth
        <script>
            dataLayer.push({
                'event':'login',
                'userId' : '123abc'
            });
        </script>
    @endauth
    <!-- Google Tag Manager (noscript) -->
    <noscript><iframe src="https://www.googletagmanager.com/ns.html?id=GTM-MT2XGKM"
    height="0" width="0" style="display:none;visibility:hidden"></iframe></noscript>
    <!-- End Google Tag Manager (noscript) -->
    <button id="btnScrollToTopId"><i class="fas fa-arrow-up"></i></button>
    <a class="floating share-link me-3" data-action="share/whatsapp/share"><i class="fab fa-whatsapp fa-2x" style="color:black" aria-hidden="true"></i></a>
    <p class="number-of-messages"></p>
    <div id="snackbar"></div>
    @include("shared.message")
    <div class="wrapper">
        <nav id="sidebar" class="inactive">
            <div class="sidebar-header">
                <h3>التصنيفـات</h3>
            </div>
            <ul class="list-unstyled components">
                @foreach($categories as $id => $name)
                <li><a href="/category/{{$id}}" class="category-link">{{$name}}</a></li>
                @endforeach
            </ul>
        </nav>
        <button id="xmark" type="button" aria-label="Close" class="btn-close"></button>

        <div id="content">
            <header>
                <div class="jumbotron text-center" style="margin-bottom: 0px;">
                    <h1>فقه الحياة</h1>
                    <p>مقولات الدكتور عبدالعزيز فيصل المطوع</p>
                    @auth
                    <a href="/add"><button class="new-wisdom-button" type="submit" style="margin:5px auto;">إضافة حكمة</button></a>
                    @endauth
                </div>

                <nav class="navbar navbar-expand-lg navbar-light bg-light">
                    <div class="container-fluid">
                        <button class="btn " type="button" id="sidebarCollapse" style="background-color: #9bafca; "><i class="fas fa-align-left" aria-hidden="true"></i></button>
                        <a class="navbar-brand" href="/">الرئيسية</a>
                        <button class="navbar-toggler btn" type="button" data-toggle="collapse" data-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation">
                            <i class="fas fa-search"></i>
                        </button>
                        <div class="collapse navbar-collapse" id="navbarSupportedContent">
                            <ul class="navbar-nav me-auto mb-2 mb-lg-0">
                                <li class="nav-item">
                                </li>
                            </ul>
                            <form class="form-inline me-2 me-lg-0" action="/search" method="GET" name="search">
                                <div class="search-form"><input class="form-control ms-sm-2" type="search" placeholder="أدخل كلمة البحث" aria-label="بحث" name="q"><button class="btn btn-outline-primary " type="submit">بحث</button>@auth <a href="/lastAddedWisdom" class="btn btn-outline-secondary">آخر حكمة</a> @endauth</div>
                            </form>
                        </div>
                    </div>
                </nav>
            </header>
            @yield("content")
            @show
            <footer>
                <div class="jumbotron text-center">
                    <a style="font-size:20px;" href="/الدكتور-عبدالعزيز-المطوع">عن الموقع</a>
                    @auth
                    <form method="get" action="/signout"><button class="logout-button" type="submit" style="margin:auto;">تسجيل الخروج</button></form>
                    @else
                    <p><a href="https://www.instagram.com/dr.almotawa" target="_blank"><img src="{{asset('/images/Instagram-Dr-Abdulaziz.png')}}" alt="instagram.com " class="social-media-icon"></a><a href="https://www.twitter.com/dralmotawaa" target="_blank"><img src="{{asset('/images/Twitter-Dr-Abdulaziz.png')}}" alt="twitter.com" class="social-media-icon"></a></p>
                    <a href="https://rapidapi.com/mohammed7134/api/dr-almotawa-quotes" target="_blank">
                        <img src="https://storage.googleapis.com/rapidapi-documentation/connect-on-rapidapi-dark.png" width="100" alt="Connect on RapidAPI">
                    </a>
                    @endauth
                    <p>موقع فقه الحياة<br> ©<span id="year">2022</span></p>

                </div>
            </footer>
        </div>
    </div>
    @vite(['resources/js/app.js'])
</body>


<!-- jQuery CDN - from googleapis -->
<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.6.0/jquery.min.js"></script>
<!-- Popper.JS -->
<script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.14.0/umd/popper.min.js" integrity="sha384-cs/chFZiN24E4KMATLdqdvsezGxaGsi4hLGOzlXwp5UZB1LY//20VyM2taTB4QvJ" crossorigin="anonymous"></script>
<!-- Bootstrap JS -->
<script src="https://stackpath.bootstrapcdn.com/bootstrap/4.1.0/js/bootstrap.min.js" integrity="sha384-uefMccjFJAIv6A+rW+L4AHf99KvxDjWSu1z9VI8SKNVmz4sk7buKt/6v9KI65qnm" crossorigin="anonymous"></script>
<!-- basket -->
<script src="{{asset('js/basket.js')}}"></script>
<script src="{{asset('js/likes.js')}}"></script>


</html>