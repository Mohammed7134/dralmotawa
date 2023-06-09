<nav class="navbar navbar-expand-sm navbar-light bg-light">
    <div class="container-fluid">
        <button class="btn " type="button" id="sidebarCollapse" style="background-color: #9bafca; "><i
                class="fas fa-align-left" aria-hidden="true"></i></button>
        <a class="navbar-brand" href="/">الرئيسية</a>
        <button class="navbar-toggler btn" type="button" data-toggle="collapse" data-target="#navbarSupportedContent"
            aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation">
            <i class="fas fa-search"></i>
        </button>
        <div class="collapse navbar-collapse" id="navbarSupportedContent">
            <ul class="navbar-nav me-auto mb-2 mb-lg-0">
                <li class="nav-item">
                </li>
            </ul>
            <form class="form-inline me-2 me-lg-0" action="/search" method="GET" name="search">
                <div class="search-form"><input class="form-control ms-sm-2" type="search" placeholder="أدخل كلمة البحث"
                        aria-label="بحث" name="q"><button class="btn btn-outline-primary "
                        type="submit">بحث</button>@auth <a href="/lastAddedWisdom" class="btn btn-outline-secondary">آخر
                        حكمة</a>
                    @endauth</div>
            </form>
        </div>
    </div>
</nav>