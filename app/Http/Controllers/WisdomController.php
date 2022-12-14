<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreWisdomRequest;
use App\Http\Requests\UpdateWisdomRequest;
use App\Models\User;
use App\Models\Wisdom;
use GuzzleHttp\Psr7\Response;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Cookie;

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
        if (Auth::check()) {
            $wisdoms = $this->getSimilarWisdoms($wisdoms);
        }
        if (request()->ajax()) {
            return $this->ajax($wisdoms);
        }
        return view('home')->with(compact('wisdoms'));
    }
    public function blogs()
    {
        $wisdoms = Wisdom::whereRaw("LENGTH(search_text) >= 2000")->inRandomOrder()->paginate(7);
        if (Auth::check()) {
            $wisdoms = $this->getSimilarWisdoms($wisdoms);
        }
        if (request()->ajax()) {
            return $this->ajax($wisdoms);
        }
        return view('home')->with(compact('wisdoms'));
    }
    public function getOneWisdom(Wisdom $wisdom)
    {
        return view('home')->with('wisdoms', [$wisdom])->with("noajax", true);
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
    public function getWisdomsForCategory(int $originalId)
    {
        $id = '%' . $originalId . '%';
        $wisdoms = Wisdom::where('ids', 'LIKE', $id)->paginate(9);
        if (request()->ajax()) {
            return $this->ajax($wisdoms);
        }
        return view('home')->with(compact('wisdoms'))->with(compact('originalId'));
    }
    public function searchForWisdom()
    {
        $id = (int)request()->q;
        if ($id) {
            $wisdoms = Wisdom::where('id', '=', $id)->paginate(9);
        } else {
            if (!str_contains(request()->q, ")") && !str_contains(request()->q, "(")) {
                if (mb_strlen(request()->q) > 3) {
                    $q = '%' . request()->q . '%';
                    $newSearchText = $this->arabicSearch(request()->q, false);
                    $newSearchText2 = $this->arabicSearch(request()->q, true);
                    $wisdoms =
                        Wisdom::where("search_text", "LIKE", $q)
                        ->orWhere("search_text", "REGEXP", $newSearchText)
                        ->orWhere("search_text", "REGEXP", $newSearchText2)
                        ->orWhere("text", "LIKE", $q)
                        ->orWhere("text", "REGEXP", $newSearchText)
                        ->orWhere("text", "REGEXP", $newSearchText2)
                        ->paginate(9);
                } else {
                    return back()->with("message", "?????? ???? ???????? ???? ?????????? ???????? ???? ?? ????????");
                }
            } else {
                return back()->with("message", "???????? ?????? ?????????? ???????? ???? ??????????");
            }
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
        $wisdom->text = $this->cleanText(request()->text);
        $remove = array('??', '??', '??', '??', '??', '??', '??', '??', '??', '??');
        $wisdom->search_text = str_replace($remove, '', request()->text);
        if ($wisdom->save()) {
            $result['error'] = false;
            return back()->with("message", "???? ?????????? ????????");
        } else {
            $result['error'] = true;
            $wisdoms = Wisdom::where("id", "=", request()->wisdomId)->get();
            return view('home')->with(compact('wisdoms'))->with("message", "?????? ??????");
        }
    }
    public function deleteWisdom(Wisdom $wisdom)
    {
        $wisdom->delete();
        $result['error'] = false;
        return back()->with("message", "???? ?????? ????????????");
    }
    public function getRandomQuote()
    {
        $headers = apache_request_headers();
        $rapidApi = $headers['X-RapidAPI-Proxy-Secret'] === "4e81b800-7e6a-11ec-bd3d-d70ef1ec455f";
        $response = array();
        $path = public_path() . '/json/categories.json';
        $file = file_get_contents($path);
        $categories = json_decode($file, true);
        if ($_SERVER['REQUEST_METHOD'] == 'GET' && $rapidApi) {
            $response['status'] = 200;
            if (isset($_GET['limit'])) {
                $wisdom = Wisdom::whereRaw('CHAR_LENGTH(text) <= ?', [$_GET['limit']])->inRandomOrder()->first();
            } else {
                $wisdom = Wisdom::inRandomOrder()->first();
            }
            $responseWisdom['id'] = $wisdom->id;
            $responseWisdom['text'] = $wisdom->text . "\n\n" . "??. ?????????????????? ???????? ????????????";
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
            $wisdom->text = $this->cleanText($text);
            $wisdom->ids = json_encode(["1467"]);
            $wisdom->likes = 0;
            $wisdom->save();
            $wisdoms[] = $wisdom;
        }
        session(['lastAddedWisdoms' => $wisdoms[0]->id]);
        return redirect('lastAddedWisdoms');
    }
    public function lastAddedWisdoms()
    {
        if (session("lastAddedWisdoms")) {
            $wisdoms = Wisdom::where("id", ">=", session("lastAddedWisdoms"))->get();
            return view('home')->with(compact('wisdoms'))->with("noajax", true);
        } else {
            back();
        }
    }
    public function lastAddedWisdom()
    {
        $wisdoms = Wisdom::latest()->paginate(5);
        if (request()->ajax()) {
            return $this->ajax($wisdoms);
        }
        return view('home')->with(compact('wisdoms'));
    }
    public function retrieveWisdoms(Wisdom $wisdom)
    {
        $wisdoms = [];
        if (request()->wisdomsIds) {
            $response = array();
            foreach (request()->wisdomsIds as $id) {
                $wisdoms[] = Wisdom::where('id', '=', $id)->first();
            }
            $response['error'] = false;
            $response['wisdoms'] = $wisdoms;
            return json_encode($response);
        } else {
            $similars = $this->getSimilarWisdoms([$wisdom])[0]->similars;
            foreach ($similars as $key => $value) {
                $wisdoms[] = Wisdom::where('id', '=', $key)->first();
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
            $words = array_unique(explode(" ", $wisdom->search_text, 15));
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
        if (str_contains($originalText, "??")) {
            $newSearchText = str_replace('??', '(??|??)', $newSearchText);
        }
        if (str_contains($originalText, "??")) {
            $newSearchText = str_replace('??', '(??|??)', $newSearchText);
        }
        if (str_contains($originalText, "??")) {
            $newSearchText = str_replace('??', '(??|??|??|??|??|??|??)', $newSearchText);
        }
        if (str_contains($originalText, "??")) {
            $newSearchText = str_replace('??', '(??|??|??|??|??|??|??)', $newSearchText);
        }
        if (str_contains($originalText, "??")) {
            $newSearchText = str_replace('??', '(??|??|??|??|??|??)', $newSearchText);
        }
        if (str_contains($originalText, "??")) {
            $newSearchText = str_replace('??', '(??|??|??|??|??)', $newSearchText);
        }
        if (str_contains($originalText, "??")) {
            $newSearchText = str_replace('??', '(??|??|??|??|??|??)', $newSearchText);
        }
        if (str_contains($originalText, "??")) {
            $newSearchText = str_replace('??', '(??|??)', $newSearchText);
        }
        if (str_contains($originalText, "??")) {
            $newSearchText = str_replace('??', '(??|??|??)', $newSearchText);
        }
        if (str_contains($originalText, "??")) {
            $newSearchText = str_replace('??', '(??|??|??)', $newSearchText);
        }
        if (str_contains($originalText, "??")) {
            $newSearchText = str_replace('??', '(??|??|??)', $newSearchText);
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
        if (str_contains($text, " ??")) {
            $text = str_replace(' ??', '??', $text);
        }
        if (str_contains($text, " .")) {
            $text = str_replace(' .', '.', $text);
        }
        if (str_contains($text, " ??")) {
            $text = str_replace(' ??', '??', $text);
        }
        return $text;
    }
}
