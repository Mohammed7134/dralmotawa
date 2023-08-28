@extends("layouts.app")
@section('title', 'الدكتور عبدالعزيز فيصل المطوع')
@section('description', 'نبذة مختصرة عن من هو الدكتور عبدالعزيز فيصل المطوع')
@section("content")
<div class="division0">
    <h1> د. عبدالعزيز فيصل المطوع </h1>
    <i> دكتور في التفسير وعلوم القرآن في كلية الشريعة والدراسات الإسلامية بجامعة الكويت سابقا </i>

    <div class="division1">
        <img id="aboutImage" alt="صورة شخصية للدكتور" src="{{asset('/images/DrAbdulazizAlmutawaPicture.jpeg')}}">
        <p style="padding: 0.9%;">صورة شخصية للدكتور</p>
    </div>

    <div class="division2">
        <b style="font-size: 164%;">نبذة عن الموقع</b>
        <ul>
            <p style="font-size: 142%">نشر مفاهيم حياتية إنسانية اجتماعية اخلاقية تربوية<a href="{{ route('login') }}"
                    style="text-decoration: none">.</a></p>
        </ul>
    </div>

</div>
@endsection