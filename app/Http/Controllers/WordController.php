<?php

namespace App\Http\Controllers;

use App\Models\Article;
use App\Models\Word;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\View;
use SebastianBergmann\Environment\Console;

class WordController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function index($wordToLoad)
    {
        if ($wordToLoad) {

            if ($wordToLoad == 'history') {
                $words = Word::where('learned', 1)
                    ->where('user_id', Auth::user()->id)
                    ->orderBy('updated_at', 'desc')
                    ->limit(100)
                    ->get();
            } else {
                Word::where('word', $wordToLoad)
                    ->where('user_id', Auth::user()->id)
                    ->update([
                        'learned' => 0
                    ]);

                $words = Word::where('word', $wordToLoad)
                    ->where('user_id', Auth::user()->id)
                    ->get();
            }
        } else {
            $words = Word::where('user_id', Auth::user()->id)
                ->where('learned', 0)
                ->where('deleted', 0)
                ->orderBy('id', 'desc')
                ->get();
        }

        $variables = [
            'words' => $words
        ];

        $response = View::make('wordsTable')->with($variables)->render();
        echo json_encode($response);
    }

    public function store(Request $request)
    {
        if(!$request->articleId){
            $anonymousId = Article::where('title', 'Anonymous')
            ->where('user_id', Auth::user()->id)
            ->pluck('id');

            $articleId = $anonymousId[0];
        }else{
            $articleId = $request->articleId;
        }

        $response = Word::create([
            'user_id' => Auth::user()->id,
            'article_id' => $articleId,
            'word' => $request->word,
            'definition' => $request->definition
        ]);

        echo json_encode($response);
    }

    public function update(Request $request)
    {

        if ($request->requestFor == 'learned') {
            Word::where('id', $request->wordId)->update([
                'learned' => 1,
                'no_of_read' => $request->noOfRead
            ]);
        } elseif ($request->requestFor == 'delete') {
            Word::where('id', $request->wordId)
            ->where('user_id', Auth::user()->id)->delete();
            // ->update([
            //     'deleted' => 1
            // ]);
        } else {
            Word::where('id', $request->wordId)->update([
                'article_id' => $request->articleId,
                'word' => $request->word,
                'definition' => $request->definition

            ]);
        }
    }

    public function markNotLearned(Request $request)
    {
        Word::where('id', $request->wordId)->update([
            'learned' => 0
        ]);
    }

    public function numbers()
    {
        $options = Word::select(DB::raw('distinct no_of_read'))
            ->where('user_id', Auth::user()->id)
            ->orderBy('no_of_read', 'asc')
            ->get();


        $variables = [
            'options' => $options
        ];

        $response = View::make('selectForm')->with($variables)->render();
        echo json_encode($response);
    }

    public function loadWordsOnRead($readNumber)
    {

        $words = Word::where('no_of_read', $readNumber)
            ->where('user_id', Auth::user()->id)
            ->orderBy('updated_at', 'desc')
            ->get();

        $variables = [
            'words' => $words
        ];

        $response = View::make('wordsTable')->with($variables)->render();
        echo json_encode($response);
    }

    public function loadWordsOnArticle($articleId)
    {

        $words = Word::where('article_id', $articleId)
            ->where('user_id', Auth::user()->id)
            ->orderBy('updated_at', 'desc')
            ->get();

        $variables = [
            'words' => $words
        ];

        $response = View::make('wordsTable')->with($variables)->render();
        echo json_encode($response);
    }

    
}
