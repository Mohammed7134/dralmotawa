@extends("layouts.app")
@section('title', 'اضافة حكمة')
@section("content")
@if(isset($q) || isset($category))
<div class="container-fluid shadow-lg p-3 mx-1 my-3 rounded d-flex justify-content-around">
    <div class="label">
        @isset($q)
        <h1>كلمة البحث</h1>
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
</div>
@endif
<div class="container-fluid shadow-lg p-3 mx-1 my-3 rounded">
    <form id="add-form" method="post" action="/createWisdoms">
        @csrf
        <textarea name="wisdoms" id="txtarea" spellcheck="false" style="height:70vh"
            placeholder="نص الحكمة الجديد..."></textarea>
        <button type="submit" class="btn btn-primary btn-sm w-100 p-6" style="font-size:24px;"
            id="submit">تأكيد</button>
    </form>
</div>

@endsection