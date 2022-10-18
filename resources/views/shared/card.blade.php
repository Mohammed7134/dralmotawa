@if(Cookie::get('appearance') == "classic")
<div class="col-md-4">
    <blockquote class="quote-box">
        <p class="quotation-mark">“</p>
        <a onclick="buttonPressed(this)" id="{{$wisdom->ids}}-{{$wisdom->id}}" class="card__title quote-text wisdom-space">{{limitStringLength($wisdom->text)[0]}}</a>
        @if(limitStringLength($wisdom->text)[1])
        <p onclick="buttonPressed(this)" id="{{$wisdom->ids}}-{{$wisdom->id}}" class="showMore" style="font-size: 1rem;color:black;padding-right:1rem;padding-top:1rem">أظهر المزيد</p>
        @endif
        <p style="display:none;" id="{{$wisdom->id}}">{{adjustLineBreaks($wisdom->text, true)}}</p>
        <hr style="margin-top:auto;">
        <div class="blog-post-actions d-flex justify-content-between">
            <button class="add-button"><i id="add-{{$wisdom->id;}}" class="fas far fa-share-square share-icon black" aria-hidden="true" style="color:black;"></i></button>
            @foreach(json_decode($wisdom->ids) as $category_id)
            <a type="button" href="/category/{{$category_id}}" class="hashtag {{$category_id}} {{$wisdom->id . '-' . $category_id}} w{{$wisdom->id}}" style="padding: 0vw 3vw;font-size: 18px;">
                {{$categories[$category_id]}}
            </a>
            @break
            @endforeach
        </div>
    </blockquote>
</div>
@else
<div class="col-md-4">
    <div class="card">
        <div class="card__image">
            <img src="https://unsplash.it/400/300?image={{rand(170, 180)}}" alt="" style="min-height:inherit;">
            <div class="card__overlay card__overlay--blue">
                <div class="card__overlay-content">
                    <a onclick="buttonPressed(this)" id="{{$wisdom->ids}}-{{$wisdom->id}}" class="card__title">{{limitStringLength($wisdom->text)[0]}}</a>
                    @if(limitStringLength($wisdom->text)[1])
                    <p onclick="buttonPressed(this)" id="{{$wisdom->ids}}-{{$wisdom->id}}" class="showMore">أظهر المزيد</p>
                    @endif
                    <p style="display:none;" id="{{$wisdom->id}}">{{adjustLineBreaks($wisdom->text, true)}}</p>
                    <div class="d-flex justify-content-between">
                        <button class="add-button"><i id="add-{{$wisdom->id;}}" class="fas far fa-share-square share-icon white" aria-hidden="true"></i></button>
                        <div class="card__meta card__meta--last">
                            @foreach(json_decode($wisdom->ids) as $category_id)
                            <li><a href="/category/{{$category_id}}">{{$categories[$category_id]}}</a></li>
                            @break
                            @endforeach
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endif
<div class="col-12 shadow-lg p-3 mx-1 my-3 rounded blog_post_container short_container_{{$wisdom->id}}" id="current_wisdom" style="display:none;">
    @include("shared.blogPost", ["post"=>"post-1", "imageDisplay"=>"none", "displayed"=>$wisdom->text, "wisdom"=>$wisdom, "title"=>"المقولة المختارة"])
</div>