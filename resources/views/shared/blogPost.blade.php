@auth
@include("shared.edit")
@else
<div class="blog_post">
    <div>
        {{-- main text --}}
        <p onclick="buttonPressed(this)" class="displayed_wisdom" id="wisdom-{{$wisdom->id}}">
            {{adjustLineBreaks($displayed, false)}}</p> {{-- show the main wisdom --}}
        <p id="{{$wisdom->id}}" style="display: none">{{$displayed}}</p> {{-- full wisdom text hidden here --}}
        <script>
            document.querySelectorAll(".displayed_wisdom").forEach(element => {element.innerText = text_truncate(element.innerText);});
        </script> {{-- adjust first element to show only part of the text in big screen --}}
        {{-- categories --}}
        <div class="d-flex categories">
            @foreach(json_decode($wisdom->ids) as $category_id)
            <a href="/category/{{$category_id}}" class="category">{{$categories[$category_id]}}</a>
            @endforeach
        </div>
        {{-- toolbar --}}
        <div class="d-flex toolbar" style="padding: 10px">
            <button class="add-button"><i id="add-{{$wisdom->id;}}" class="fas far fa-share-square share-icon black"
                    style="color:black;"></i></button>
            <button class="like-button"><i id="like-{{$wisdom->id;}}" class="fa-regular fa-heart like-icon"
                    style="color:red;"></i></button>
            <a class="twitter-button"
                href='https://twitter.com/intent/tweet?text={{adjustLineBreaks($displayed, true)}}%0A@dralmotawaa'><i
                    class="fa-brands fa-twitter"></i></a>
            <a class="facebook-button" target="_blank"
                href="https://www.facebook.com/sharer/sharer.php?u=https%3A%2F%2Fwww.dralmutawa.com%2Fid%2F{{$wisdom->id;}}&amp;src=sdkpreparse"
                class="fb-xfbml-parse-ignore"><i class="fa-brands fa-facebook"
                    data-href="https://www.dralmutawa.com/id/{{$wisdom->id;}}" data-layout="button"
                    data-size="small"></i></a>
        </div>
    </div>
</div>
@endauth