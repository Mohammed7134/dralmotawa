<div class="col-md-4">
    <div class="card">
        <div class="card__image">
            <img src="https://unsplash.it/400/300?image={{rand(350, 400)}}" alt="" style="min-height:inherit;">
            <div class="card__overlay card__overlay--blue">
                <div class="card__overlay-content">
                    <ul class="card__meta">
                        <li><a href="#0">عدد الأحرف</a></li>
                        <li><a href="#0">{{mb_strlen($wisdom->text)}}</a></li>
                    </ul>
                    <a onclick="buttonPressed(this)" href="#current_wisdom" id="{{$wisdom->ids}}-{{$wisdom->id}}" class="card__title">{{limitStringLength($wisdom->text)}}</a>
                    <p style="display:none;" id="{{$wisdom->id}}">{{adjustLineBreaks($wisdom->text)}}</p>
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