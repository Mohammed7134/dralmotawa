<div class="col-12 displayed_wisdom_{{$wisdom->id}}" id="current_wisdom" style="display: none;">
    <blockquote class="quote-box">
        <p class="quotation-mark">“</p>
        <p class="quote-text">{{adjustLineBreaks($wisdom->text, false)}}</p>
        <hr>
        <div class="d-flex justify-content-between">
            @foreach(json_decode($wisdom->categories) as $category)
            <a href="/category/{{$category->category_url}}" class="category">
                {{$category->category_name}}
            </a>
            @break
            @endforeach
        </div>
    </blockquote>
</div>