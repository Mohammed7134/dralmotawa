<?php

namespace App\Http\Controllers;

use App\Events\WisdomUpdated;
use App\Http\Requests\StoreWisdomRequest;
use App\Http\Requests\UpdateWisdomRequest;
use App\Models\Category;
use App\Models\Wisdom;
use App\Rules\CustomRule;
use Illuminate\Database\QueryException;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Str;


class WisdomController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    private function ajax($wisdoms)
    {
        $html = "";
        foreach ($wisdoms->items() as $wisdom) {
            $html .= view('shared.card')->with(compact('wisdom'))->render();
        }
        return $html;
    }
    public function index()
    {
        $expirationTime = 60; // Cache expires after 60 minutes
        $wisdoms = Cache::remember('wisdoms', $expirationTime, function () {
            // Retrieve data from the database or perform expensive computation
            // Here's an example of retrieving data from the database
            return Wisdom::inRandomOrder()->paginate(7);
        });
        if (Auth::check()) {
            $wisdoms = $this->getSimilarWisdoms($wisdoms);
        }
        if (request()->ajax()) {
            $wisdoms = Wisdom::inRandomOrder()->paginate(7);
            return $this->ajax($wisdoms);
        }
        return view('home')->with(compact('wisdoms'));
    }
    public function getOneWisdom(int $id)
    {
        try {
            $title = request()->title;
            $wisdom = Wisdom::findOrFail($id);
            $wisdoms = $this->getSimilarWisdoms([$wisdom]);
            return view('home')->with('wisdoms', $wisdoms)->with("noajax", true)->with("title", empty($title) ? "حكمة" : $title)->with('description', Str::limit($wisdom->text, 120, '...'));
        } catch (\Illuminate\Database\Eloquent\ModelNotFoundException $e) {
            return redirect('/');
        }
    }
    public function getAfterWisdom(Wisdom $wisdom)
    {
        $id = $wisdom->id;
        do {
            $id += 1;
            $afterWisdom = Wisdom::where('id', "=", $id)->first();
        } while (!$afterWisdom);
        return redirect('/id/' . $afterWisdom->id);
    }
    public function getBeforeWisdom(Wisdom $wisdom)
    {
        $id = $wisdom->id;
        do {
            $id -= 1;
            $beforeWisdom = Wisdom::where('id', "=", $id)->first();
        } while (!$beforeWisdom);
        return redirect('/id/' . $beforeWisdom->id);
    }
    public function getWisdomsForCategory($category)
    {
        if (is_numeric($category)) {
            $category = Category::where('id', '=', $category)->first();
            return redirect('/category/' . urlencode($category->category_url));
        }
        $category = Category::where('category_url', '=', $category)->orWhere('id', '=', $category)->first();
        $wisdoms = Wisdom::whereHas('categories', function ($query) use ($category) {
            $query->where('categories.id', $category->id);
        })->paginate(9);

        if (request()->ajax()) {
            return $this->ajax($wisdoms);
        }
        return view('home')->with(compact('wisdoms'))->with(compact('category'));
    }

    public function searchForWisdom()
    {
        $validatedData = request()->validate([
            'q' => ['required', new CustomRule(3)],
        ]);

        $query = Wisdom::query();

        $id = (int)request()->q;

        if ($id) {
            $wisdoms = Wisdom::where('id', '=', $id)->paginate(9);
        } else {
            $query->where(function ($query) {
                $q = '%' . request()->q . '%';
                $newSearchText = $this->arabicSearch(request()->q, false);
                $newSearchText2 = $this->arabicSearch(request()->q, true);
                $query->where("search_text", "LIKE", $q)
                    ->orWhere("search_text", "REGEXP", $newSearchText)
                    ->orWhere("search_text", "REGEXP", $newSearchText2)
                    ->orWhere("text", "LIKE", $q)
                    ->orWhere("text", "REGEXP", $newSearchText)
                    ->orWhere("text", "REGEXP", $newSearchText2);
            });
            if (request()->c) {
                $query->whereHas('categories', function ($query) {
                    $query->where('categories.id', request()->c);
                });
            }
            $wisdoms = $query->paginate(9);
        }
        if (request()->ajax()) {
            return $this->ajax($wisdoms);
        }
        return view('home')->with(compact('wisdoms'))->with('q', request()->q)->with('c', request()->c);
    }

    public function changeCategory()
    {
        $result = array();
        try {
            $wisdom = Wisdom::where("id", "=", request()->wisdomId)->first();
            if ($wisdom) {
                $wisdom->categories()->sync(request()->updatedCategories, ['created_at' => now(), 'updated_at' => now()]);
                $wisdom->updated_at = now();
                $wisdom->save();
                event(new WisdomUpdated($wisdom));
                $result['error'] = false;
            } else {
                $result['error'] = true;
            }
        } catch (QueryException $e) {
            $result['error'] = true;
        }
        return json_encode($result);
    }

    public function changeText()
    {
        $wisdom = Wisdom::where("id", "=", request()->wisdomId)->first();
        $newCleanText = $this->cleanText(request()->text);
        $remove = array('ِ', 'ُ', 'ٓ', 'ٰ', 'ْ', 'ٌ', 'ٍ', 'ً', 'ّ', 'َ');
        $newCleanSearchText = str_replace($remove, '', $newCleanText);
        if ($newCleanText != $wisdom->text) {
            $wisdom->text = $newCleanText;
            $wisdom->search_text = $newCleanSearchText;
            if ($wisdom->save()) {
                $result['error'] = false;
                $result['message'] = "تم تعديل النص";
                $result['text'] = $newCleanText;
                event(new WisdomUpdated($wisdom));
            } else {
                $result['error'] = true;
                $result['message'] = "حدث خطأ";
            }
        } else {
            $result['error'] = true;
            $result['message'] = "لم يتغير شيء";
        }
        return json_encode($result);
    }
    public function deleteWisdom(Wisdom $wisdom)
    {
        $wisdom->delete();
        return back()->with("message", "تم حذف الحكمة");
    }
    public function getRandomQuote()
    {
        $headers = apache_request_headers();
        $rapidApi = $headers['X-RapidAPI-Proxy-Secret'] === "4e81b800-7e6a-11ec-bd3d-d70ef1ec455f";
        $response = array();
        if ($rapidApi) {
            $response['status'] = 200;
            $wisdom = null;
            while (!$wisdom) {
                $ran = rand(1, 45000);
                if (isset($_GET['limit'])) {
                    $wisdom = Wisdom::where('id', '=', $ran)->whereRaw('CHAR_LENGTH(text) <= ?', [$_GET['limit']])->first();
                } else {
                    $wisdom = Wisdom::where('id', '=', $ran)->first();
                }
            }

            $responseWisdom['id'] = $wisdom->id;
            $responseWisdom['text'] = $wisdom->text . "\n\n" . "د. عبدالعزيز فيصل المطوع";
            $responseWisdom['categories'] = $wisdom->categories;
            $response['wisdom'] = $responseWisdom;
        } else {
            $response['status'] = 400;
            $response['message'] = "Invalid Request";
        }
        return $response;
    }

    public function createWisdoms()
    {
        $texts = explode("||", request()->wisdoms);
        foreach ($texts as $text) {
            $wisdom = new Wisdom();
            $wisdom->text = $this->cleanText($text);
            $wisdom->likes = 0;
            $wisdom->save();
            $wisdom->categories()->attach($this->autoCategorize($wisdom->text), ['created_at' => now(), 'updated_at' => now()]);
        }
        $category = Category::where('id', '=', 1467)->first();

        $newWisdoms = Wisdom::whereHas('categories', function ($query) use ($category) {
            $query->where('categories.id', $category->id);
        })->get();
        foreach ($newWisdoms as $wisdom) {
            $wisdom->categories()->sync([$this->autoCategorize($wisdom->text)], ['created_at' => now(), 'updated_at' => now()]);
            $wisdom->updated_at = now();
            $wisdom->save();
            event(new WisdomUpdated($wisdom));
        }
        $date = now()->format('Y-m-d');
        $wisdoms = Wisdom::whereDate('created_at', '>=', $date)->paginate(7);
        return view('home')->with(compact('wisdoms'));
    }

    public function retrieveWisdoms(Wisdom $wisdom)
    {
        $wisdoms = [];
        if (request()->wisdomsIds) {
            $response = array();
            foreach (request()->wisdomsIds as $id) {
                $wisdoms[] = Wisdom::findOrFail($id);
            }
            $response['error'] = false;
            $response['wisdoms'] = $wisdoms;
            return json_encode($response);
        } else {
            $similars = $this->getSimilarWisdoms([$wisdom])[0]->similars;
            foreach ($similars as $key => $value) {
                $wisdoms[] = Wisdom::findOrFail($key);;
            }
            return view('home')->with(compact('wisdoms'))->with("noajax", true);
        }
    }

    public function likeWisdom(Wisdom $wisdom)
    {
        $wisdom->likes += 1;
        $wisdom->save();
        $result['error'] = false;
        return json_encode($result);
    }
    public function removeLike(Wisdom $wisdom)
    {
        $wisdom->likes -= 1;
        $wisdom->save();
        $result['error'] = false;
        return json_encode($result);
    }

    //use search functions or similar to them
    public function getSimilarWisdoms($wisdoms)
    {
        foreach ($wisdoms as $wisdom) {
            $result = array();
            $words = array_unique(explode(" ", $wisdom->search_text));
            $similars = [];
            foreach ($words as $word) {
                if (mb_strlen($word) > 3) {
                    $simlarIds = Wisdom::where("search_text", 'LIKE', '% ' . $word . ' %')->get()->pluck('id')->toArray();
                    $similars = array_merge($simlarIds, $similars);
                }
            }
            // $similars = array_diff($similars, array($wisdom->id));
            $similars = array_count_values($similars);
            arsort($similars);
            foreach (array_slice($similars, 0, 10, true) as $key => $val) {
                // wisdoms where you found 4 words or more that are also present in the current wisdoms
                if ($val >= 4) {
                    $result[$key] = $val;
                }
            }
            if (count($result) > 1) {
                $wisdom->similars = $result;
            }
        }
        return $wisdoms;
    }


    // AI Categorisation 
    public function getWisdoms()
    {
        $wisdoms = Wisdom::with('categories')->take(12000)->get();
        $wisdomsArray = [];
        foreach ($wisdoms as $wisdom) {
            $wis = array();
            $wis['id'] = $wisdom->id;
            $wis['wisdom'] = $wisdom->text;
            $wis['categories'] = $wisdom->categories->pluck('id');
            $wisdomsArray[] = $wis;
        }
        return $wisdomsArray;
    }
    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \App\Http\Requests\StoreWisdomRequest  $request
     * @return \Illuminate\Http\Response
     */
    public function store(StoreWisdomRequest $request)
    {
        //
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Wisdom  $wisdom
     * @return \Illuminate\Http\Response
     */
    public function show(Wisdom $wisdom)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Wisdom  $wisdom
     * @return \Illuminate\Http\Response
     */
    public function edit(Wisdom $wisdom)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \App\Http\Requests\UpdateWisdomRequest  $request
     * @param  \App\Models\Wisdom  $wisdom
     * @return \Illuminate\Http\Response
     */
    public function update(UpdateWisdomRequest $request, Wisdom $wisdom)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Wisdom  $wisdom
     * @return \Illuminate\Http\Response
     */
    public function destroy(Wisdom $wisdom)
    {
        //
    }
    private function arabicSearch($originalText, $regularSpace)
    {
        $newSearchText = $originalText;
        if ($regularSpace) {
            $newSearchText = str_replace(' ', "\xc2\xa0", $originalText);
        }
        if (str_contains($originalText, "ه")) {
            $newSearchText = str_replace('ه', '(ة|ه)', $newSearchText);
        }
        if (str_contains($originalText, "ة")) {
            $newSearchText = str_replace('ة', '(ة|ه)', $newSearchText);
        }
        if (str_contains($originalText, "ا")) {
            $newSearchText = str_replace('ا', '(إ|أ|ا|آ|ء|ئ|ؤ)', $newSearchText);
        }
        if (str_contains($originalText, "أ")) {
            $newSearchText = str_replace('أ', '(إ|أ|ا|آ|ء|ئ|ؤ)', $newSearchText);
        }
        if (str_contains($originalText, "إ")) {
            $newSearchText = str_replace('إ', '(إ|أ|ا|آ|ء|ئ)', $newSearchText);
        }
        if (str_contains($originalText, "آ")) {
            $newSearchText = str_replace('آ', '(إ|أ|ا|آ|ء)', $newSearchText);
        }
        if (str_contains($originalText, "ء")) {
            $newSearchText = str_replace('ء', '(أ|ا|آ|ء|ئ|ؤ)', $newSearchText);
        }
        if (str_contains($originalText, "و")) {
            $newSearchText = str_replace('و', '(و|ؤ)', $newSearchText);
        }
        if (str_contains($originalText, "ي")) {
            $newSearchText = str_replace('ي', '(ئ|ي|ى)', $newSearchText);
        }
        if (str_contains($originalText, "ى")) {
            $newSearchText = str_replace('ى', '(ئ|ي|ى)', $newSearchText);
        }
        if (str_contains($originalText, "ئ")) {
            $newSearchText = str_replace('ئ', '(ئ|ي|ى)', $newSearchText);
        }
        return $newSearchText;
    }
    private function cleanText($text)
    {
        if (str_contains($text, "  ")) {
            $text = str_replace('  ', ' ', $text);
        }
        if (str_contains($text, " :")) {
            $text = str_replace(' :', ':', $text);
        }
        if (str_contains($text, " ،")) {
            $text = str_replace(' ،', '،', $text);
        }
        if (str_contains($text, " .")) {
            $text = str_replace(' .', '.', $text);
        }
        if (str_contains($text, " ؟")) {
            $text = str_replace(' ؟', '؟', $text);
        }
        if (str_contains($text, " )")) {
            $text = str_replace(' )', ')', $text);
        }
        if (str_contains($text, "( ")) {
            $text = str_replace('( ', '(', $text);
        }
        if (str_contains($text, " !")) {
            $text = str_replace(' !', '!', $text);
        }
        return $text;
    }
    private function autoCategorize($wisdom)
    {
        $curl = curl_init();

        curl_setopt_array($curl, array(
            CURLOPT_URL => 'https://us-central1-aiplatform.googleapis.com/v1/projects/186748023883/locations/us-central1/endpoints/8664716775851556864:predict',
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_ENCODING => '',
            CURLOPT_MAXREDIRS => 10,
            CURLOPT_TIMEOUT => 0,
            CURLOPT_FOLLOWLOCATION => true,
            CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
            CURLOPT_CUSTOMREQUEST => 'POST',
            CURLOPT_POSTFIELDS => '{"instances": [{"text": ' . json_encode($wisdom) . '}]}',
            CURLOPT_HTTPHEADER => array(
                'Authorization: Bearer ya29.a0AfB_byCqc0iUJYRk-0Qa-hESWOCvkUK_TvZDAVSBmKfah3nqmmW8BiNotFB3UxuJV85j3SLJAbgiOqEUgy2Le-eZFOTxPVe4SbTEHbxs0jOvehP3Cpl0wdZYowu_s3FP09RvqwnFR2tD_bfQWVRO0Mht-4hMxHCNONoaCgYKARoSAQ4SFQGOcNnCRxZiKOHcRaq7nvRkYvXqfA0170',
                'Content-Type: application/json'
            ),
        ));

        $response = curl_exec($curl);

        curl_close($curl);
        $predictions = array();
        Log::debug($response);
        foreach (json_decode($response)->predictions[0]->classes as $index => $class) {
            $row = array();
            $row['class'] = $class;
            $row['score'] = json_decode($response)->predictions[0]->scores[$index];
            $predictions[] = $row;
        }
        usort($predictions, function ($a, $b) {
            return $a['score'] < $b['score'];
        });
        return Category::where('category_name', '=', $predictions[0]['class'])->first()->id;
    }
}
