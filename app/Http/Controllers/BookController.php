<?php

namespace App\Http\Controllers;

use App\Models\Book;
use App\Models\Favourite;
use App\Models\HistoryAddProduct;
use App\Models\Rate;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Storage;

class BookController extends Controller
{
    use ResponseTrait;
    public function index(Request $request){
        try {
            $searchParam = $this->extractSearchParam($request);
            $books = Book::with('category','rate')->
                withFilter($searchParam)->latest()->paginate();
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
                'image' => $request->get('image') ?? "",
            ];
//            $image = $request->file("image") ?? null;
//            if(!is_null($image)){
//                $data['image'] = Storage::disk('public')->putFile("image",$image);
//            }
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
                'image' => $request->get('image') ?? "",
            ];
//            $image = $request->file("image") ?? null;
//            if(!is_null($image)){
//                $data['image'] = Storage::disk('public')->putFile("image",$image);
//            }
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

    public function checkValidate(Request $request){
        try {
            $item = $request->get('item') ?? [];

            foreach ($item as $each){
                $product = Book::where('id', $each['product_id'])->where('quantity','>=', $each["number"])
                    ->where('price', $each["price"])->first();
                if(is_null($product) ){
                    throw new \Exception('Product validation failed');
                }
            }

            return $this->successResponse([],"Successfully");

        }catch (\Exception $e){
            return $this->errorResponse($e->getMessage());

        }
    }

    public function updateProduct(Request $request){
        try {
            $item = $request->get('item') ?? [];

            foreach ($item as $each){
                $product = Book::where('id', $each['product_id'])->first();
                if(is_null($product) ){
                    throw new \Exception('Product validation failed');
                }
                $product->quantity -= $each['number'];
                $product->save();
            }

            return $this->successResponse([],"Successfully");

        }catch (\Exception $e){
            return $this->errorResponse($e->getMessage());

        }
    }


    public function addToFavorite(Request $request){
        try {
            $userLogin = $request->get('user');
            $productId = $request->get('product_id');

            Favourite::create([
                'user_id' => $userLogin['id'],
                'product_id' => $productId,
            ]);
            return response()->json([
                "success" => true,
            ]);
        }catch (\Exception $e){
            return response()->json($e->getMessage(), 500);

        }
    }
    public function removeToFavorite(Request $request){
        try {
            $userLogin = $request->get('user');

            $productId = $request->get('product_id');
            Favourite::where('user_id', $userLogin['id'])
                ->where('product_id', $productId)
                ->delete();
            return response()->json([
                "success" => true,
            ]);
        }catch (\Exception $e){
            return response()->json($e->getMessage(), 500);

        }
    }

    public function getFavourite(Request $request){
        try {
            $userLogin = $request->get('user');

            $products = Favourite::where('user_id', $userLogin['id'])
                ->pluck('product_id')->toArray();
            $data = Book::whereIn('id', $products)->get();
            return response()->json([
                "success" => true,
                'data' => $data
            ]);
        }catch (\Exception $e){
            return response()->json($e->getMessage(), 500);
        }
    }

    public function getListProductBuyMonth(Request $request){
        try {
            $response = Http::withHeaders([
                "Accept" => "application/json",
            ])->get('http://127.0.0.1:5051/api/getProductBuyMonth');
            if($response->status() !== 200){
                return \response()->json([
                    'message' => $response
                ], $response->status());
            }
            $topProduct = $response->json();
            $data = [];
            foreach ($topProduct as $value){
                $book = Book::where('id', $value['product_id'])->first();
                $data[] =[
                    'product_id' => $value['product_id'],
                    'number' => $value['number'],
                    'book' => $book
                ];
            }
            return response()->json($data);
        }catch (\Exception $e){
            return response()->json($e->getMessage(), 500);

        }
    }
    public function comment(Request $request){
        try {
            $rate = $request->get('rate');
            $description = $request->get('description') ?? "";
            $user = $request->get('user');
            $bookId = $request->get('book_id');
            if(is_null($bookId)){
                throw  new \Exception('book not found');
            }
            if($rate === null || ($rate < 0 || $rate > 5)){
                throw new \Exception("Invalid rate");
            }
            $comment = Rate::create([
                'name_user' => $user['name'],
                'rate' => $rate,
                'description' => $description,
                'book_id' => $bookId,
                'user_id' => $user['id'],
            ]);
            return response()->json([
                'success' => true,
                'data' => $comment
            ]);
        }catch (\Exception $e){
            return response()->json($e->getMessage(), 500);
        }
    }

    public function getProductUpdateInMonth(Request $request){
        try {
            $firstDayOfMonth = Carbon::now()->firstOfMonth();
            $endOfMonth = Carbon::now()->endOfMonth();
            $data = Book::
            with('category','rate')->whereBetween('updated_at',[$firstDayOfMonth,$endOfMonth])
                ->orderByDesc('updateed_at')
                ->take(10)
                ->get();
            return response()->json([
                'data' => $data
            ]);
        }catch (\Exception $e){
            return response()->json($e->getMessage(), 500);

        }
    }

    //
}
