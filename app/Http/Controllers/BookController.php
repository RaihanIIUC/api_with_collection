<?php

namespace App\Http\Controllers;

use App\Http\Resources\BookResource;
use App\Models\Book;
use Illuminate\Http\Request;
use Auth;

class BookController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth:api')->except(['index', 'show']);
    }
    /**
     * Display all books that have been added
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return BookResource::collection(Book::with('ratings')->paginate(25));
    }
    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $this->validate($request, [
            'title' => 'required',
            'description' => 'required',
            'author' => 'required',
        ]);
        $book = new Book;
        $book->user_id = Auth::user()->id;
        $book->title = $request->title;
        $book->description = $request->description;
        $book->author = $request->author;
        $book->save();

        return new BookResource($book);
    }
    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $books = Book::all($id);
        return new BookResource($books);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Book $book)

    {

        // check if currently authenticated user is the owner of the book

        if ($request->user()->id !== $book->user_id) {

            return response()->json(['error' => 'You can only edit your own books.'], 403);
        }



        $book->update($request->only(['title', 'author', 'description']));



        return new BookResource($book);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy(Request $request, Book $book)
    {
        if ($request->user()->id != $book->user_id) {
            return response()->json(['error' => 'You can only delete your own books.'], 403);
        }
        $book->delete();
        return response()->json(null, 204);
    }
}