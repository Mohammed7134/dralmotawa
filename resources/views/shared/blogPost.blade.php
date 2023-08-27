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
            @foreach($wisdom->categories as $category)
            <a href="/category/{{$category->category_url}}" class="category">{{$category->category_name}}</a>
            @endforeach
        </div>
        {{-- toolbar --}}
        <div class="d-flex toolbar" style="padding: 10px">
            <button id="add_to_basket" class="add-button"><i id="add-{{$wisdom->id;}}"
                    class="fas far fa-share-square share-icon black" style="color:black;"></i></button>
            <button id="like" class="like-button"><i id="like-{{$wisdom->id;}}" class="fa-regular fa-heart like-icon"
                    style="color:red;"></i></button>
            <a class="twitter-button"
                href='https://twitter.com/intent/tweet?text={{adjustLineBreaks($displayed, true)}}%0A@dralmotawaa'><i
                    class="fa-brands fa-twitter"></i></a>
            <a class="facebook-button fb-xfbml-parse-ignore" target="_blank"
                href="https://www.facebook.com/sharer/sharer.php?u=https%3A%2F%2Fwww.dralmutawa.com%2Fid%2F{{$wisdom->id;}}&amp;src=sdkpreparse"><i
                    class="fa-brands fa-facebook" data-href="https://www.dralmutawa.com/id/{{$wisdom->id;}}"
                    data-layout="button" data-size="small"></i></a>
            <a style="visibility:hidden;" class="coffee-button" href="https://bmc.link/mohammed71Q/" target="_blank"><i
                    class="fa-solid fa-mug-hot"></i></a>
        </div>
    </div>
</div>
@endauth