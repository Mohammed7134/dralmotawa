<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreCategoryRequest;
use App\Http\Requests\UpdateCategoryRequest;
use App\Models\Category;
use App\Models\Wisdom;
use Illuminate\Support\Str;


class CategoryController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $path = public_path() . '/json/categories.json';
        $file = file_get_contents($path);
        $categories = json_decode($file, true);
        foreach ($categories as $key => $value) {
            if (!Category::find($key)) {
                $categoryData = [
                    'id' => $key,
                    'category_name' => $value,
                    'category_url' =>  Str::replace(' ', '-', $value),
                ];
                $category = Category::create($categoryData);
                $wisdomIds = Wisdom::where('ids', 'LIKE', '%' . $key . '%')->get()->pluck('id')->toArray();
                if ($key == 1467) {
                    $arrays = array_chunk($wisdomIds, 5000);
                    foreach ($arrays as $array) {
                        $category->wisdoms()->attach($array, ['created_at' => now(), 'updated_at' => now()]);
                    }
                } else {
                    $category->wisdoms()->attach($wisdomIds, ['created_at' => now(), 'updated_at' => now()]);
                }
            }
        }
        $category = Category::find('1467');
        return $category->wisdoms->count();
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \App\Http\Requests\StoreCategoryRequest  $request
     * @return \Illuminate\Http\Response
     */
    public function store(StoreCategoryRequest $request)
    {
        //
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Category  $category
     * @return \Illuminate\Http\Response
     */
    public function show(Category $category)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Category  $category
     * @return \Illuminate\Http\Response
     */
    public function edit(Category $category)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \App\Http\Requests\UpdateCategoryRequest  $request
     * @param  \App\Models\Category  $category
     * @return \Illuminate\Http\Response
     */
    public function update(UpdateCategoryRequest $request, Category $category)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Category  $category
     * @return \Illuminate\Http\Response
     */
    public function destroy(Category $category)
    {
        //
    }
}
