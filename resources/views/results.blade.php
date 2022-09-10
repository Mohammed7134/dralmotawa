@extends("layouts.app")
@section("content")

<div class="container-fluid shadow-lg p-3 mx-1 my-3 rounded d-flex justify-content-around">
    <div class="label">
        @isset($q)
        <h1>كلمة البحث</h1>
        <h4>{{$q}}</h4>
        @endisset
        @isset($originalId)
        <h1>التصنيف:</h1>
        <h4>{{$categories[$originalId]}}</h4>
        @endisset
    </div>
    <div class="total">
        <h1>عدد النتائج:</h1>
        <h4>{{$wisdoms->total()}}</h4>
    </div>
</div>
<div class="row shadow-lg p-3 mx-1 my-3 rounded blog_post_container short_container" id="current_wisdom">
    @include("shared.blogPost", ["post"=>"post-1","imageDisplay"=>"", "title"=>"المقولة المختارة", "displayed"=>$wisdoms[0]->text, "wisdom"=>$wisdoms[0]])
</div>
<div class="container-fluid shadow-lg p-3 mx-1 my-3 rounded first_son">
    <div class="row">
        @foreach($wisdoms as $wisdom)
        <div class="col-md-4">
            @include("shared.card")
        </div>
        @endforeach
    </div>
    <div class="d-flex">
        <div class="mx-auto">
            {!! $wisdoms->appends($_GET)->links() !!}
        </div>
    </div>
</div>

@endsection