<div class="jumbotron text-center">
    <a style="font-size:20px;" href="/الدكتور-عبدالعزيز-المطوع">عن الموقع</a>
    @auth
    <form method="get" action="/signout"><button class="logout-button" type="submit" style="margin:auto;">تسجيل
            الخروج</button></form>
    @else
    <p><a href="https://www.instagram.com/dr.almotawa" target="_blank"><img
                src="{{asset('/images/Instagram-Dr-Abdulaziz.png')}}" alt="instagram.com "
                class="social-media-icon"></a><a href="https://www.twitter.com/dralmotawaa" target="_blank" width="640"
            height="360"><img src="{{asset('/images/Twitter-Dr-Abdulaziz.png')}}" alt="twitter.com"
                class="social-media-icon" width="640" height="360"></a></p>
    <a href="https://rapidapi.com/mohammed7134/api/dr-almotawa-quotes" target="_blank">
        <img src="https://storage.googleapis.com/rapidapi-documentation/connect-on-rapidapi-dark.png" width="100"
            alt="Connect on RapidAPI">
    </a>
    @endauth
    <p>موقع فقه الحياة<br> ©<span id="year">2023</span></p>
</div>