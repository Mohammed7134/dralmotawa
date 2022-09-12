@extends("layouts.app")
@section("content")

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
    loadMoreData(paginate);
    $(window).scroll(function() {
        if ($(window).scrollTop() + $(window).height() >= $(document).height()) {
            paginate++;
            loadMoreData(paginate);
        }
    });

    function loadMoreData(paginate) {
        $.ajax({
                url: '?page=' + paginate,
                type: 'get',
                datatype: 'html',
                beforeSend: function() {
                    $('.loading').show();
                }
            })
            .done(function(data) {
                // if (data.length == 0) {
                $('.loading').hide();
                $('.add_here').append(data);
                // }
            })
            .fail(function(jqXHR, ajaxOptions, thrownError) {
                alert(thrownError);
            });
    }
</script>

@endsection