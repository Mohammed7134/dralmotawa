@if(session()->has('success'))
<script>
    showSnackbar("تم تعديل النص", );
</script>
@elseif(session()->has('error'))
<script>
    showSnackbar("حدث خطأ");
</script>
@endif