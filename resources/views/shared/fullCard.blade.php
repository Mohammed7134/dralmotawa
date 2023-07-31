<div class="col-12 displayed_wisdom_{{$wisdom->id}}" id="current_wisdom" style="display: none;">
    <blockquote class="quote-box">
        <p class="quotation-mark">“</p>
        <p class="quote-text">{{adjustLineBreaks($wisdom->text, false)}}</p>
        <hr>
        <div class="d-flex justify-content-between">
            @foreach(json_decode($wisdom->ids) as $category_id)
            <a href="/category/{{Str::replace(' ', '-', $categories[$category_id])}}" class="category">
                {{$categories[$category_id]}}
            </a>
            @break
            @endforeach
        </div>
    </blockquote>
</div>