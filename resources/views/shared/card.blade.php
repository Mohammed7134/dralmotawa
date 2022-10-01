<div class="col-md-4">
    <div class="card">
        <div class="card__image">
            <img src="https://unsplash.it/400/300?image={{rand(170, 180)}}" alt="" style="min-height:inherit;">
            <div class="card__overlay card__overlay--blue">
                <div class="card__overlay-content">
                    <!-- <ul class="card__meta">
                        <li><a href="#0">عدد الأحرف</a></li>
                        <li><a href="#0">{{mb_strlen($wisdom->text)}}</a></li>
                    </ul> -->
                    <a onclick="buttonPressed(this)" id="{{$wisdom->ids}}-{{$wisdom->id}}" class="card__title">{{limitStringLength($wisdom->text)[0]}}</a>
                    @if(limitStringLength($wisdom->text)[1])
                    <p onclick="buttonPressed(this)" id="{{$wisdom->ids}}-{{$wisdom->id}}" class="showMore" style="font-size: 1rem;color:white;">أظهر المزيد</p>
                    @endif
                    <p style="display:none;" id="{{$wisdom->id}}">{{adjustLineBreaks($wisdom->text, true)}}</p>
                    <div class="card__meta card__meta--last">
                        @foreach(json_decode($wisdom->ids) as $category_id)
                        <li><a href="/category/{{$category_id}}">{{$categories[$category_id]}}</a></li>
                        @endforeach
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<div class="col-12 shadow-lg p-3 mx-1 my-3 rounded blog_post_container short_container_{{$wisdom->id}}" id="current_wisdom" style="display:none;">
    @include("shared.blogPost", ["post"=>"post-1", "imageDisplay"=>"none", "displayed"=>$wisdom->text, "wisdom"=>$wisdom, "title"=>"المقولة المختارة"])
</div>