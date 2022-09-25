<?php

namespace App\Http\Controllers;

use App\Models\playlist;
use App\Models\book;
use App\Models\playlist_book;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class PlaylistBookController extends Controller
{
    public function store(Request $request , playlist $playlist , book $book)
    {
        $check_playlist_book = playlist_book::where('playlist_id', $playlist->id)
        ->where('book_id' , $book->id)
        ->exists();
        if(!$check_playlist_book){
            $playlist_book = playlist_book:: create([
                'playlist_id' => $playlist->id,
                'book_id' => $book->id,
            ]);

            return response()->json([$playlist_book, Response::HTTP_OK]);
        }
        else{
            return response()->json([['message'=>'it is already exist'], Response::HTTP_OK]);
        }
    }

    public function destroy(playlist_book $playlist_book)
    {
        $playlist_book->delete();
        return response()->json($playlist_book,Response::HTTP_NO_CONTENT);
    }

}
