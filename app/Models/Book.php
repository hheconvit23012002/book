<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Book extends Model
{
    use HasFactory;
    protected $table='book';
    protected $fillable =[
        'title',
        'price',
        'quantity',
        'author',
        'description',
        'image',
        'category_id',
    ];

    public function category(){
        return $this->belongsTo(Category::class, 'category_id', 'id');
    }

    public function getNameCategoryAttribute(){
        return $this->category->name;
    }

    public function scopeWithFilter($query, $search){
        [
            'title' => $title,
            'price' => $price,
            'author' => $author,
            'category' => $category,
        ] = $search;

        if($title){
            $query = $query->where('title', 'like', '%'.$title.'%');
        }
        if($price){
            $query = $query->where('price',$price);
        }
        if($author){
            $query = $query->where('author', 'like', '%'.$author.'%');
        }
        if($category){
            $query = $query->where('category_id', $category);
        }

        return $query;
    }
}
