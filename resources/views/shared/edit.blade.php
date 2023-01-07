<div class="blog_post">
    <div>
        <form action="/changeText" method="post">
            @csrf
            <input type="text" value="{{$wisdom->id}}" name="wisdomId" hidden>
            <textarea name="text" class="displayed_wisdom" rows="10" style="max-width: -webkit-fill-available;">{{$displayed}}</textarea>
            <p id="{{$wisdom->id}}" style="display: none">{{$displayed}}</p> {{-- full wisdom text hidden here --}}
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
        {{-- toolbar --}}
        <div class="d-flex toolbar">
            <a class="after-button" href="/after/id/{{$wisdom->id}}"><i class="fa-solid fa-chevron-right"></i></a>
            @auth
                @isset($wisdom->similars)
                    <a href="/getRelatedWisdoms/{{$wisdom->id}}" class="similar-button"><i class="fa-solid fa-plus" style="color:black;"></i></a>
                @endisset
            @endauth
            <button class="add-button"><i id="add-{{$wisdom->id;}}" class="fas far fa-share-square share-icon black" style="color:black;"></i></button>
            <button class="like-button"><i id="like-{{$wisdom->id;}}" class="fa-regular fa-heart like-icon" style="color:black;"></i></button>
            <a class="twitter-button" href='https://twitter.com/intent/tweet?text={{adjustLineBreaks($displayed, true)}}%0A@dralmotawaa' target="_blank"><i class="fa-brands fa-twitter" style="color:black;"></i></a>
            <a class="before-button" href="/before/id/{{$wisdom->id}}"><i class="fa-solid fa-chevron-left"></i></a>
            
        </div>
    </div>
</div>