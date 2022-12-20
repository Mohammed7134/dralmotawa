<div class="blog_post">
    <div>
        <form action="/changeText" method="post">
            @csrf
            <input type="text" value="{{$wisdom->id}}" name="wisdomId" hidden>
            <textarea name="text" class="displayed_wisdom" rows="10" style="max-width: -webkit-fill-available;">{{$displayed}}</textarea>
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