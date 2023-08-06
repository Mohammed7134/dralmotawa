<div class="blog_post">
    <div>
        <form id="edit-form" action="/changeText" method="post">
            @csrf
            <input type="text" value="{{$wisdom->id}}" name="wisdomId" hidden>
            <textarea id="myTextarea" name="text" class="displayed_wisdom" rows="10"
                style="max-width: -webkit-fill-available;">{{$displayed}}</textarea>
            <p id="{{$wisdom->id}}" style="display: none">{{$displayed}}</p> {{-- full wisdom text hidden here --}}
            <div class="d-flex justify-content-between">
                <div class="d-flex">
                    <select id="categories" class="selectMenu-{{$wisdom->id}}" onchange="logValue(this);" multiple>
                        @foreach($categories as $category)
                        <option {{in_array($category->id, $wisdom->categories->pluck('id')->toArray())?"selected":"";}}
                            value="{{$wisdom->id."-".$category->id}}">{{$category->category_name}}</option>
                        @endforeach
                    </select>
                </div>
                <div>
                    <button class="btn btn-primary" type="submit">تأكيد</button>
                    <button class="btn btn-danger" onclick="showSnackbar('متأكد؟', {{$wisdom->id}})"
                        type="button">مسح</button>
                </div>
            </div>
        </form>
        {{-- toolbar --}}
        <div class="d-flex toolbar">
            <a class="after-button" href="/after/id/{{$wisdom->id}}"><i class="fa-solid fa-chevron-right"></i></a>
            <a class="notification-button" href="{{route('push', ['id'=>$wisdom->id])}}"><i
                    class="fa-solid fa-wifi"></i></a>
            @isset($wisdom->similars)
            <a href="/getRelatedWisdoms/{{$wisdom->id}}" class="similar-button"><i class="fa-solid fa-plus"
                    style="color:black;"></i></a>
            @endisset
            <button class="add-button"><i id="add-{{$wisdom->id;}}" class="fas far fa-share-square share-icon black"
                    style="color:black;"></i></button>
            <button class="like-button"><i id="like-{{$wisdom->id;}}" class="fa-regular fa-heart like-icon"
                    style="color:red;"></i></button>
            <a class="twitter-button"
                href='https://twitter.com/intent/tweet?text={{adjustLineBreaks($displayed, true)}}%0A@dralmotawaa'
                target="_blank"><i class="fa-brands fa-twitter"></i></a>
            <a class="facebook-button" target="_blank"
                href="https://www.facebook.com/sharer/sharer.php?u=https%3A%2F%2Fwww.dralmutawa.com%2Fid%2F{{$wisdom->id;}}&amp;src=sdkpreparse"
                class="fb-xfbml-parse-ignore"><i class="fa-brands fa-facebook"
                    data-href="https://www.dralmutawa.com/id/{{$wisdom->id;}}" data-layout="button"
                    data-size="small"></i></a>
            <a class="coffee-button w-{{ $wisdom->id }}" href="https://bmc.link/mohammed71Q/" target="_blank"><i
                    class="fa-solid fa-mug-hot"></i></a>
            <a class="before-button" href="/before/id/{{$wisdom->id}}"><i class="fa-solid fa-chevron-left"></i></a>
        </div>
    </div>
</div>