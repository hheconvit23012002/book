<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class HistoryAddProduct extends Model
{
    use HasFactory;
    protected $table ='history_add_product';
    protected $fillable = [
        'number',
        'book_id',
    ];
}
