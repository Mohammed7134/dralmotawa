@extends("layouts.app")
@section('title', 'الاشتراك')
@section('description', 'الاشتراك في خدمة رسائل الواتساب')
@section("content")

@if($errors->any())
<script>
    showSnackbar("{{$errors->all()[0]}}");
</script>
@endif
<div class="row justify-content-center">
    @if($errors->any())
    <p>حدث خطأ</p>
    <div class="container shadow-lg p-3 mx-1 my-3 rounded w-75">
        <p>نعتذر منك٬ </p>
        <p>يرجى الإبلاغ عن الخطأ</p>
    </div>
    <div class="d-flex justify-content-center" id="report">
        <a class="btn btn-danger m-3" href="https://forms.gle/b4x6FT8RnUFUBosY6">الإبلاغ عن الخطأ</a>
    </div>
    @else
    <p>تم الاشتراك في خدمة رسائل الواتساب اليومية</p>
    <div class="container shadow-lg p-3 mx-1 my-3 rounded w-75">
        <p>شكرا لك٬ </p>
        <p>ستصلك رسالة تأكيد على الواتساب</p>
        <a href="/">الصفحة الرئيسية</a>
    </div>
    @endif
</div>

@endsection