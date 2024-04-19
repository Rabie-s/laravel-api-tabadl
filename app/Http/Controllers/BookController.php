<?php

namespace App\Http\Controllers;

use App\Models\Book;
use Illuminate\Http\Request;

class BookController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        return Book::all();
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {

        $getFileExtension = $request->file('image_path')->getClientOriginalExtension(); //get extension of file
        $newFileName = (string)rand() . '.' . $getFileExtension; //merge random string and file extension
        //$request->file('image_path')->storeAs('public/images',$newFileName);//save file
        $request->file('image_path')->move('images', $newFileName);

        $book = new Book();
        $book->title = $request->title;
        $book->image_path = $newFileName;
        $book->status = $request->status;
        $book->description = $request->description;
        $book->user_id = $request->user_id;
        $book->save();

        return response()->json(['success' => 'Created successful'], 201);
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        return Book::findOrFail($id);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        $book = Book::findOrFail($id);
        $book->update($request->only([
            'title',
            'image_path',
            'status',
            'description',
            'active',
            'done',
        ]));
        return response()->json(['success' => 'Updated successful'], 201);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        Book::findOrFail($id)->delete();
        return response()->json(['success' => 'Deleted successful'], 201);
    }
}
