<div class="blog_post {{$post}}">
    <div class="img_pod" style="display: {{$imageDisplay}};">
        <img class="img_post" src="{{asset('/images/DrAbdulazizAlmutawaPicture.jpeg')}}" alt="image">
    </div>
    <div class="container_copy" style="overflow:hidden;">
        <h3></h3>
        <h1 class="wisdom_title">{{$title}}</h1>
        <p class="displayed_wisdom">{{adjustLineBreaks($displayed)}}</p>
        <div class="d-flex categories">
            @foreach(json_decode($wisdom->ids) as $category_id)
            <a class="btn_primary" href="/category/{{$category_id}}">{{$categories[$category_id]}}</a>
            @endforeach
        </div>

    </div>
</div>