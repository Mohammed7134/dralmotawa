<?php

namespace App\Http\Controllers;

use App\Models\Category;
use App\Models\Wisdom;
use Artesaos\SEOTools\Facades\SEOTools;

class WisdomController extends Controller
{
    public function index()
    {
        SEOTools::setTitle('الحكمة — استكشف الكلمات الحكيمة والرؤى');
        SEOTools::setDescription('اكتشف آلاف الكلمات الحكيمة عبر الفلاسفة، والحياة، والحب، والنجاح. ابحث عن إلهامك اليومي.');
        SEOTools::opengraph()->setUrl(url('/'));

        return view('wisdoms.index');
    }

    public function show(Wisdom $wisdom)
    {
        $preview = mb_substr($wisdom->text, 0, 160);
        SEOTools::setTitle($preview . ' — الحكمة');
        SEOTools::setDescription($preview);
        SEOTools::opengraph()->setUrl(url("/wisdom/{$wisdom->id}"));

        return view('wisdoms.show', compact('wisdom'));
    }

    public function category(Category $category)
    {
        SEOTools::setTitle("{$category->category_name} الحكمة");
        SEOTools::setDescription("استكشف الكلمات الحكيمة في الفئة: {$category->category_name}");

        return view('wisdoms.category', compact('category'));
    }
}
