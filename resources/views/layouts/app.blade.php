<!DOCTYPE html>
<html lang="ar" dir="rtl">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
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
    <script>
        function buttonPressed(e) {
            const displayedWisdom = document.querySelector(".displayed_wisdom");
            const wisdomId = e.id.split("-")[1];
            const fullText = document.getElementById(wisdomId).innerHTML;
            displayedWisdom.innerHTML = fullText;
            const categoriesDiv = document.querySelector('.categories');
            const categories = e.id.split("-")[0];
            let ConvertStringToHTML = function(str) {
                let parser = new DOMParser();
                let doc = parser.parseFromString(str, 'text/html');
                return doc.body;
            };
            fetch('json/categories.json')
                .then((res) => {
                    return res.json();
                })
                .then((data) => {
                    categoriesDiv.innerHTML = "";
                    JSON.parse(categories).forEach(function(category) {
                        categoriesDiv.append(ConvertStringToHTML(`<a class="btn_primary" href='/category/${category}'>${data[category]}</a>`));
                    });
                });
        }
    </script>
    @vite(['resources/css/sidebar.css'])
    @vite(['resources/css/card.css'])
    @vite(['resources/css/app.css'])
    @vite(['resources/css/blogPost.css'])
</head>

<body>
    <button id="btnScrollToTopId"><i class="fas fa-arrow-up"></i></button>
    <div class="wrapper">
        <nav id="sidebar" class="inactive">
            <div class="sidebar-header">
                <h3>التصنيفات</h3>
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
                    <a href="/add"><button class="new-wisdom-button" type="submit" style="margin:10px auto;">إضافة حكمة</button></a>
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
                                <div class="search-form"><input class="form-control ms-sm-2" type="search" placeholder="أدخل كلمة البحث" aria-label="بحث" name="q"><button class="btn btn-outline-primary " type="submit">بحث</button></div>
                            </form>
                        </div>
                    </div>
                </nav>

            </header>

            @yield("content")
            @show
            <footer>
                <div class="jumbotron text-center">
                    <a href="/الدكتور-عبدالعزيز-المطوع">عن الموقع</a>
                    @auth
                    <form method="get" action=""><button class="logout-button" type="submit" style="margin:auto;">تسجيل الخروج</button></form>
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
</body>
@vite(['resources/js/sidebar.js'])
@vite(['resources/js/app.js'])

<!-- jQuery CDN - from googleapis -->
<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.6.0/jquery.min.js"></script>
<!-- Popper.JS -->
<script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.14.0/umd/popper.min.js" integrity="sha384-cs/chFZiN24E4KMATLdqdvsezGxaGsi4hLGOzlXwp5UZB1LY//20VyM2taTB4QvJ" crossorigin="anonymous"></script>
<!-- Bootstrap JS -->
<script src="https://stackpath.bootstrapcdn.com/bootstrap/4.1.0/js/bootstrap.min.js" integrity="sha384-uefMccjFJAIv6A+rW+L4AHf99KvxDjWSu1z9VI8SKNVmz4sk7buKt/6v9KI65qnm" crossorigin="anonymous"></script>


</html>