@extends("layouts.app")
@section('title', 'عن الدكتور')

@section("content")
<div class="division0">
    <h1> د. عبدالعزيز فيصل المطوع </h1>
    <i> دكتور في التفسير وعلوم القرآن في كلية الشريعة والدراسات الإسلامية بجامعة الكويت سابقا </i>

    <div class="division1">
        <img id="aboutImage" src="{{asset('/images/DrAbdulazizAlmutawaPicture.jpeg')}}">
        <p style="padding: 0.9%;">صورة شخصية للدكتور</p>
    </div>

    <div class="division2">
        <!-- <b style="font-size: 164%;">نبذة عن الدكتور</b>
        <ul>
            <p style="font-size: 142%"> كرس الدكتور حياته في نشر العلم النافع في تخصص التفسير وعلوم القرآن، وكذلك في نشر مفاهيم حياتية مستقاة من الدين الإسلامي الحنيف.</p>
        </ul> -->
        <b style="font-size: 164%;">نبذة عن الموقع</b>
        <ul>
            <p style="font-size: 142%">نشر مفاهيم حياتية إنسانية اجتماعية اخلاقية تربوية.</p>
        </ul>
    </div>

</div>
@endsection