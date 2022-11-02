@auth
<div class="blog_post {{$post}}">
    <div class="img_pod" style="display: {{$imageDisplay}};">
        <img class="img_post" src="{{asset('/images/DrAbdulazizAlmutawaPicture.jpeg')}}" alt="image">
    </div>
    <div class="container_copy" style="overflow:hidden;">
        <h3></h3>
        <h1 class="wisdom_title">{{$title}}</h1>
        <form action="/changeText" method="post">
            @csrf
            <input type="text" value="{{$wisdom->id}}" name="wisdomId" hidden>
            <textarea name="text" class="displayed_wisdom displayed_wisdom_{{$wisdom->id}}" rows="5"></textarea>
            <div class="d-flex justify-content-between">
                <div class="d-flex">
                    <select id="categories" class="selectMenu-{{$wisdom->id}}" onchange="logValue(this);" multiple>
                        @foreach($categories as $id => $name)
                        <option {{in_array($id, json_decode($wisdom->ids)) ? "selected" : "";}} value="{{$wisdom->id . "-" . $id}}">{{$name}}</option>
                        @endforeach
                    </select>
                </div>
                <div>
                    <input value="تأكيد" class="btn btn-primary" type="submit">
                    <a class="btn btn-danger" onclick="showSnackbar('متأكد؟', {{$wisdom->id}})">مسح</a>
                </div>
            </div>
        </form>
    </div>
</div>
@else
<div class="blog_post {{$post}}">
    <div class="img_pod" style="display: {{$imageDisplay}};">
        <img class="img_post" src="{{asset('/images/DrAbdulazizAlmutawaPicture.jpeg')}}" alt="image">
    </div>
    <div class="container_copy" style="overflow:hidden;">
        <h3></h3>
        <h1 class="wisdom_title">{{$title}}</h1>
        <p class="displayed_wisdom">{{adjustLineBreaks($displayed, false)}}</p>
        <div class="d-flex categories" style="white-space:nowrap;width:70vw;overflow:scroll;">
            @foreach(json_decode($wisdom->ids) as $category_id)
            <a href="/category/{{$category_id}}" style="font-size:1rem;padding:10px">{{$categories[$category_id]}}</a>
            @endforeach
        </div>
    </div>
</div>
@endauth