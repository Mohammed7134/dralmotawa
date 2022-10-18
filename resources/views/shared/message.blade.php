@if(session()->has('message'))
<script>
    var message = "{{session('message')}}";
    showSnackbar(message);
</script>
@endif