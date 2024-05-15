<?php

namespace App\Http\Controllers;

use App\Models\Book;
use Illuminate\Http\Request;
use App\Http\Resources\BookResource;
use App\Http\Requests\StoreBookRequest;

class BookController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        // Get the search query and status from the request
        $status = $request->query('status');
        $search = $request->query('search');

        // Start building the query with pagination
        $query = Book::query();

        // If status is provided, filter by status
        if ($status !== null) {
            $query->where('status', $status);
        }

        // If search query is provided, filter by title or other relevant fields
        if ($search !== null) {
            $query->where(function ($q) use ($search) {
                $q->where('title', 'like', '%' . $search . '%');
            });
        }

        // Paginate the results
        $books = $query->latest()->paginate(10);

        // Return the paginated list of books as a collection of BookResource
        return BookResource::collection($books);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreBookRequest $request)
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
        $book = Book::with('user')->where('id', $id)->get();
        return response()->json($book);
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
