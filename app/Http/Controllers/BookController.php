<?php

namespace App\Http\Controllers;

use App\Models\Book;
use App\Models\HistoryAddProduct;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class BookController extends Controller
{
    use ResponseTrait;
    public function index(Request $request){
        try {
            $searchParam = $this->extractSearchParam($request);
            $books = Book::withFilter($searchParam)->latest()->paginate();
            foreach ($books as $book){
                $book->category = $book->name_category;
            }
            return $this->successResponse($books);
        }catch(Exception $e){
            return $this->errorResponse($e->getMessage());
        }
    }
    public function extractSearchParam($request){
        return [
            'title' => $request->get('title') ?? null,
            'price' => $request->get('price') ?? null,
            'author' => $request->get('author') ?? null,
            'category' => $request->get('category') ?? null,
        ];
    }

    public function store(Request $request){
        try {
            $data = [
                'title' => $request->get('title') ?? null,
                'price' => $request->get('price') ?? 0,
                'quantity' => $request->get('quantity') ?? 0,
                'author' => $request->get('author') ?? "",
                'description' => $request->get('description') ?? "",
                'category_id' => $request->get('category_id') ?? "",
            ];
            $image = $request->file("image") ?? null;
            if(!is_null($image)){
                $data['path'] = Storage::disk('public')->putFile("image",$image);
            }
            Book::create($data);
            return $this->successResponse([],"Successfully");
        }catch (\Exception $e){
            return $this->errorResponse($e->getMessage());
        }
    }

    public function update(Request $request, $id){
        try {
            $data = [
                'title' => $request->get('title') ?? null,
                'price' => $request->get('price') ?? 0,
                'quantity' => $request->get('quantity') ?? 0,
                'author' => $request->get('author') ?? "",
                'description' => $request->get('description') ?? "",
                'category_id' => $request->get('category_id') ?? "",
            ];
            $image = $request->file("image") ?? null;
            if(!is_null($image)){
                $data['path'] = Storage::disk('public')->putFile("image",$image);
            }
            Book::where('id', $id)->update($data);
            return $this->successResponse([],"Successfully");
        }catch (\Exception $e){
            return $this->errorResponse($e->getMessage());
        }
    }

    public function get(Request $request, $id){
        try {
            $book = Book::with('category')->where('id', $id)->firstOrFail();
            return $this->successResponse([$book],"Successfully");
        }catch (\Exception $e){
            return $this->errorResponse($e->getMessage());
        }
    }

    public function delete(Request $request, $id){
        try {
            Book::where('id', $id)->delete();
            return $this->successResponse([],"Successfully");
        }catch (\Exception $e){
            return $this->errorResponse($e->getMessage());
        }
    }

    public function addNumberProduct(Request $request, $id){
        try {
            $number = $request->get('number') ?? 0;
            Book::whereId($id)->incrementEach([
                'quantity' => $number
            ]);
            HistoryAddProduct::create([
                'number' => $number,
                'book_id' => $id
            ]);

            return $this->successResponse([],"Successfully");

        }catch (\Exception $e){
            return $this->errorResponse($e->getMessage());

        }
    }

    //
}
