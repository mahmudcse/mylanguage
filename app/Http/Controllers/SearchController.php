<?php

namespace App\Http\Controllers;

use App\Models\Word;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\View;

class SearchController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function index($typedString)
    {

        $wildCard = "%$typedString%";

        $word = Word::where(function ($query) {
            return $query
                ->where('user_id', Auth::user()->id)
                ->where('deleted', '=', 0);
                })
            ->where(function ($query) use ($wildCard) {
                return $query
                    ->where('word','like', $wildCard)
                    ->orWhere('definition', 'like', $wildCard);
                })
            ->get();
            
        echo json_encode($word);
    }

    public function dateSearch($startDate, $endDate)
    {

        if ($startDate != 0 && $endDate == 0) {
            $timeStamp = strtotime($startDate);
            $timeStamp = date("Y-m-d", $timeStamp);

            $wildCard = "$timeStamp%";

            $words = Word::where('user_id', Auth::user()->id)
                ->where('updated_at', 'like', $wildCard)
                ->orderBy('article_id', 'desc')
                ->get();
        } elseif ($startDate == 0 && $endDate != 0) {
            $timeStamp = strtotime($endDate);
            $timeStamp = date("Y-m-d", $timeStamp);

            $wildCard = "$timeStamp%";

            $words = Word::where('user_id', Auth::user()->id)
                ->where('updated_at', 'like', $wildCard)
                ->orderBy('article_id', 'desc')
                ->get();
        } elseif ($startDate != 0 && $endDate != 0) {
            $startTimeStamp = strtotime($startDate);
            $endTimeStamp = strtotime($endDate);
            $startDate = date("Y-m-d 00:00:00", $startTimeStamp);
            $endDate = date("Y-m-d 23:59:59", $endTimeStamp);

            $words = Word::where('user_id', Auth::user()->id)
                ->whereBetween('updated_at', [$startDate, $endDate])
                ->orderBy('article_id', 'desc')
                ->get();
        }

        $variables = [
            'words' => $words
        ];

        $response = View::make('wordsTable')->with($variables)->render();
        echo json_encode($response);
    }
}
