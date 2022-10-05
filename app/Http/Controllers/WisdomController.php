<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreWisdomRequest;
use App\Http\Requests\UpdateWisdomRequest;
use App\Models\User;
use App\Models\Wisdom;

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
        $wisdoms = Wisdom::inRandomOrder()->paginate(7);
        if (request()->ajax()) {
            return $this->ajax($wisdoms);
        }
        return view('home')->with(compact('wisdoms'));
    }
    public function getOneWisdom(Wisdom $wisdom)
    {
        return $wisdom->text;
    }
    public function getWisdomsForCategory(int $originalId)
    {
        $id = '%' . $originalId . '%';
        $wisdoms = Wisdom::where('ids', 'LIKE', $id)->inRandomOrder()->paginate(9);
        if (request()->ajax()) {
            return $this->ajax($wisdoms);
        }
        return view('home')->with(compact('wisdoms'))->with(compact('originalId'));
    }
    public function searchForWisdom()
    {
        $id = (int)request()->q;
        if ($id) {
            $wisdoms = Wisdom::where('id', '=', $id)->paginate(9);;
        } else {
            $q = '%' . request()->q . '%';
            $newSearchText = $this->arabicSearch(request()->q, false);
            $newSearchText2 = $this->arabicSearch(request()->q, true);
            $wisdoms =
                Wisdom::where("search_text", "LIKE", $q)
                ->orWhere("search_text", "REGEXP", $newSearchText)
                ->orWhere("search_text", "REGEXP", $newSearchText2)
                ->where("text", "LIKE", $q)
                ->orWhere("text", "REGEXP", $newSearchText)
                ->orWhere("text", "REGEXP", $newSearchText2)
                ->paginate(9);
        }
        if (request()->ajax()) {
            return $this->ajax($wisdoms);
        }
        return view('home')->with(compact('wisdoms'))->with('q', request()->q);
    }

    public function changeCategory()
    {
        $wisdom = Wisdom::where("id", "=", request()->wisdomId)->first();
        $wisdom->ids = request()->newCategories;
        if ($wisdom->save()) {
            $result['error'] = false;
            return json_encode($result);
        } else {
            $result['error'] = true;
            return json_encode($result);
        }
    }
    public function changeText()
    {
        $wisdom = Wisdom::where("id", "=", request()->wisdomId)->first();
        $wisdom->text = request()->text;
        if ($wisdom->save()) {
            $result['error'] = false;
            return back()->with("success", "done");
        } else {
            $result['error'] = true;
            $wisdoms = Wisdom::where("id", "=", request()->wisdomId)->get();
            return view('home')->with(compact('wisdoms'))->with("error", "fail");
        }
    }
    public function deleteWisdom(Wisdom $wisdom)
    {
        $wisdom->delete();
        $result['error'] = false;
        return back();
    }
    public function getRandomQuote()
    {
        $headers = apache_request_headers();
        $token = $headers['Token'];
        $rapidApi = $headers['X-RapidAPI-Proxy-Secret'] === "4e81b800-7e6a-11ec-bd3d-d70ef1ec455f";
        $response = array();
        $path = public_path() . '/json/categories.json';
        $file = file_get_contents($path);
        $categories = json_decode($file, true);
        if ($_SERVER['REQUEST_METHOD'] == 'GET' && $token === "ABJEDHOWS" && $rapidApi) {
            $response['status'] = 200;
            $wisdom = Wisdom::inRandomOrder()->first();
            $responseWisdom['id'] = $wisdom->id;
            $responseWisdom['text'] = nl2br($wisdom->text . "\n\n" . "د. عبدالعزيز فيصل المطوع");
            $responseWisdom['categories'] = [];
            foreach (json_decode($wisdom->ids) as $id) {
                $responseWisdom['categories'][] = $categories[$id];
            }
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
        $wisdoms = [];
        foreach ($texts as $text) {
            $wisdom = new Wisdom();
            $wisdom->text = $text;
            $wisdom->ids = json_encode(["1467"]);
            $wisdom->save();
            $wisdoms[] = $wisdom;
        }
        session(['lastAddedWisdoms' => $wisdoms[0]->id]);
        return redirect('lastAddedWisdoms');;
    }
    public function lastAddedWisdoms()
    {
        $wisdoms = Wisdom::where("id", ">=", session("lastAddedWisdoms"))->get();
        return view('home')->with(compact('wisdoms'))->with("noajax", true);
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
        if (strpos($originalText, "ه")) {
            $newSearchText = str_replace('ه', '(ة|ه)', $newSearchText);
        }
        if (strpos($originalText, "ة")) {
            $newSearchText = str_replace('ة', '(ة|ه)', $newSearchText);
        }
        if (strpos($originalText, "ا")) {
            $newSearchText = str_replace('ا', '(إ|أ|ا|آ|ء|ئ|ؤ)', $newSearchText);
        }
        if (strpos($originalText, "أ")) {
            $newSearchText = str_replace('أ', '(إ|أ|ا|آ|ء|ئ|ؤ)', $newSearchText);
        }
        if (strpos($originalText, "إ")) {
            $newSearchText = str_replace('إ', '(إ|أ|ا|آ|ء|ئ)', $newSearchText);
        }
        if (strpos($originalText, "آ")) {
            $newSearchText = str_replace('آ', '(إ|أ|ا|آ|ء)', $newSearchText);
        }
        if (strpos($originalText, "ء")) {
            $newSearchText = str_replace('ء', '(أ|ا|آ|ء|ئ|ؤ)', $newSearchText);
        }
        if (strpos($originalText, "و")) {
            $newSearchText = str_replace('و', '(و|ؤ)', $newSearchText);
        }
        if (strpos($originalText, "ي")) {
            $newSearchText = str_replace('ي', '(ئ|ي|ى)', $newSearchText);
        }
        if (strpos($originalText, "ى")) {
            $newSearchText = str_replace('ى', '(ئ|ي|ى)', $newSearchText);
        }
        if (strpos(request()->q, "ئ")) {
            $newSearchText = str_replace('ئ', '(ئ|ي|ى)', $newSearchText);
        }
        return $newSearchText;
    }
}
