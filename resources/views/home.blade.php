@extends("layouts.app")
@isset($title)
@section('title',$title)
@else
@section('title', isset($q) ? "البحث" : (isset($category) ? $category->category_name : "الرئيسية"))
@endisset
@isset($description)
@section('description',$description)
@else
@section('description', isset($q) ? "ابحث عن اي حكمة عن الحياة مع قصص متنوعة وفوائد ممتعة" : (isset($category) ?
$category->category_name : "الرئيسية"))
@endisset
@isset($category)
@section("style")
<style>
    .outer {
        position: relative;
        background-image: url('/images/{{ $category->category_name }}.jpeg');
        /* Replace with your image URL */
        background-size: cover;
        background-position: center;
        width: 100%;
        /* Adjust the height as needed */
        color: white;
        padding: 20px;
        border-radius: 10px;
        display: flex;
        flex-direction: column;
        justify-content: center;
        align-items: flex-start;
    }

    .label,
    .total {
        background-color: rgba(0, 0, 0, 0.5);
        /* Optional: add a semi-transparent background to the text */
        padding: 20px;
        border-radius: 10px;
        text-align: center;
        margin: 10px 0;
    }
</style>
@endsection
@endisset

@section("content")
<div>
    @if ($errors->any())
    <div class="alert alert-danger">
        <ul>
            @foreach ($errors->all() as $error)
            <li>{{ $error }}</li>
            @endforeach
        </ul>
    </div>
    @endif
</div>
@if(isset($q) || isset($category))
<div class="container-fluid shadow-lg p-3 mx-1 my-3 rounded outer">
    {{-- @isset($category)
    <img src="/images/{{ $category->category_name }}.jpeg" alt="{{ $category->category_name }}"
        style="position:fixed;display:flex;z-index:0;width:inherit;">
    @endisset --}}
    <div class="label">
        @isset($q)
        <h1>نص البحث: </h1>
        <h4>{{$q}}</h4>
        @endisset
        @isset($category)
        <h1>التصنيف: </h1>
        <h4>{{$category->category_name}}</h4>
        @endisset
    </div>
    <div class="total">
        <h1>عدد النتائج:</h1>
        <h4>{{$wisdoms->total()}}</h4>
    </div>
    @isset($q)
    <div>
        <form id="filterForm" method="GET" action="{{ url()->current() }}">
            <input type="text" name="q" value="{{ $q }}" hidden>
            <select class="form-control" name="c" id="category-select">
                <option value="" {{ $c ? '' : 'selected' }}>جميع التصانيف</option>
                @foreach($categories as $category)
                <option value="{{ $category->id }}" {{$c==$category->id?'selected':''}}>{{$category->category_name}}
                </option>
                @endforeach
            </select>
        </form>
    </div>
    @endisset
</div>
@endif
@if( !isset($noajax) && $wisdoms->total() > 30000)
<div class="love_counter d-flex align-items-center justify-content-between">
    <p>مجموع الحكم: </p>
    <div class="love_count"> {{$wisdoms->total()}}</div>
</div>
@endif
<div class="container-fluid shadow-lg p-3 mx-1 my-3 rounded">
    @if(count($wisdoms) > 0)
    <div class="row add_here animated-list">
        @foreach($wisdoms as $wisdom)
        @include("shared.card")
        @endforeach
    </div>
    @else
    <p class="text-center loading">لا توجد نتائج</p>
    @endif
</div>
@if(count($wisdoms) > 6 && gettype($wisdoms) !== "array" && !isset($noajax))
<p class="text-center loading" style="display: none"> يرجى الانتظار...</p>
<script type="text/javascript" defer>
    var paginate = 1;
    var load = true;
    window.addEventListener('scroll', function() {
        if ($(window).scrollTop() + $(window).height() + 100 >= $(document).height()) {
            if (load) {
                paginate++;
                loadMoreData(paginate);
            }
        }
    });

    function loadMoreData(paginate) {
        var newURL = window.location.pathname.split('/');
        var url = "";
        if (newURL[1] == "search") {
            url = `search?${window.location.search.split('?')[1]}&page=${paginate}`;
        } else if (newURL[1] == "category") {
            url = `/category/${newURL[2].replace(/\s+/g, '-')}?page=${paginate}`;
        } else if (newURL[1] == "") {
            url = "?page=" + paginate;
        }
        $.ajax({
                url: url,
                type: 'get',
                datatype: 'html',
                beforeSend: function() {
                    $('.loading').show();
                    load = false;
                }
            })
            .done(function(data) {
                if (data.length > 0) {
                    $('.loading').hide();
                    $('.add_here').append(data);
                    likeColorCheck();
                    checkColor();
                    load = true;
                    applyAnimation();
                    addChangingTextFunctionality();
                } else {
                    document.querySelector(".loading").innerText = "لا يوجد نتائج أكثر";
                }
            })
            .fail(function(jqXHR, ajaxOptions, thrownError) {
                alert(thrownError);
                load = true;
            });
    }
</script>
@endif
@endsection