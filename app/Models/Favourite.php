<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Favourite extends Model
{
    use HasFactory;
    protected $table="favourite";
    protected $fillable = [
        'user_id',
        'product_id'
    ];
    public function books(){
        return $this->belongsTo(Book::class,'product_id', 'id');
    }
}
