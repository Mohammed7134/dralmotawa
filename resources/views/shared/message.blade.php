<button id="btnScrollToTopId"><i class="fas fa-arrow-up"></i></button>
<a class="floating share-link me-3" data-action="share/whatsapp/share"><i class="fab fa-whatsapp fa-2x"
        style="color:black" aria-hidden="true"></i></a>
<p class="number-of-messages"></p>
<div id="snackbar"></div>
@if(session()->has('message'))
<script>
    var message = "{{session('message')}}";
    showSnackbar(message);
</script>
@endif