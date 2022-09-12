@extends("layouts.app")
@section("content")
@if(isset($q) || isset($originalId))
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
@endif
<div class="container-fluid shadow-lg p-3 mx-1 my-3 rounded first_son">
    <div class="row add_here">
        @foreach($wisdoms as $wisdom)
        @include("shared.card")
        @endforeach
    </div>
</div>
<p class="text-center loading">يرجى الانتظار...</p>
<script type="text/javascript">
    var paginate = 1;
    $(window).scroll(function() {
        if ($(window).scrollTop() + $(window).height() >= $(document).height()) {
            paginate++;
            loadMoreData(paginate);
        }
    });

    function loadMoreData(paginate) {
        var newURL = window.location.pathname.split('/');
        var url = "";
        if (newURL[1] == "search") {
            url = `search?${window.location.search.split('?')[1]}&page=${paginate}`;
        } else if (newURL[1] == "category") {
            url = `/category/${newURL[2]}?page=${paginate}`;
        } else if (newURL[1] == "") {
            url = "?page=" + paginate;
        }
        $.ajax({
                url: url,
                type: 'get',
                datatype: 'html',
                beforeSend: function() {
                    $('.loading').show();
                }
            })
            .done(function(data) {
                if (data.length > 0) {
                    $('.loading').hide();
                    $('.add_here').append(data);
                } else {
                    document.querySelector(".loading").innerText = "لا يوجد نتائج أكثر";
                }
            })
            .fail(function(jqXHR, ajaxOptions, thrownError) {
                alert(thrownError);
            });
    }
</script>

@endsection