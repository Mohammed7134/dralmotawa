@extends("layouts.app")
@section('title', 'تسجيل الدخول')
@section("content")
@if($errors->any())
<script>
    showSnackbar("{{$errors->all()[0]}}");
</script>
@endif

<div class="row justify-content-center">
    <div class="container shadow-lg p-3 mx-1 my-3 rounded d-flex justify-content-center w-50">
        <form action="signin" method="post">
            @csrf
            <label for="username" class="required">المستخدم</label>
            <input id="username" type="username" name="name" class="form-control">
            <label for="username" class="required">كلمة السر</label>
            <input id="password" type="password" name="password" class="form-control">
            <button class="btn btn-primary m-2">الدخول</button>
        </form>
    </div>
</div>
@endsection