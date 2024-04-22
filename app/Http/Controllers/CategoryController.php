<?php

namespace App\Http\Controllers;

use App\Models\Category;
use Illuminate\Http\Request;

class CategoryController extends Controller
{
    use ResponseTrait;
    public function index(Request $request){
        try {
            $searchName = $request->get('name') ?? "";
            $categories = Category::where('name','like', '%'.$searchName.'%' )
                ->latest()->get();
            return $this->successResponse($categories);
        }catch (\Exception $e){
            return $this->errorResponse($e->getMessage());
        }
    }

    public function store(Request $request){
        try {
            $name = $request->get('name') ?? "";
            if($name === ""){
                throw new \Exception("Not empty name category");
            }
            Category::create([
                'name' => $name,
            ]);
            return $this->successResponse([],"Successfully");
        }catch (\Exception $e){
            return $this->errorResponse($e->getMessage());
        }
    }
    public function update(Request $request, $id){
        try {
            $name = $request->get('name') ?? "";
            if($name === ""){
                throw new \Exception("Not empty name category");
            }
            Category::where('id', $id)->update([
                'name' => $name,
            ]);
            return $this->successResponse([],"Successfully");
        }catch (\Exception $e){
            return $this->errorResponse($e->getMessage());
        }
    }

    public function delete(Request $request, $id){
        try {
            Category::where('id', $id)->delete();
            return $this->successResponse([],"Successfully");
        }catch (\Exception $e){
            return $this->errorResponse($e->getMessage());
        }
    }
    public function get(Request $request, $id){
        try {
            $category = Category::where('id', $id)->firstOrFail();
            return $this->successResponse([$category],"Successfully");
        }catch (\Exception $e){
            return $this->errorResponse($e->getMessage());
        }
    }
    //
}
