<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Rating extends Model
{
    use HasFactory;
    protected $fillable = ['book_id', 'user_id', 'rating'];

    /**
     * this rate is for this book 
     * means 200tk is belongsTO book 5 am club
     */
    public function book()
    {
        return $this->belongsTo(Book::class);
    }
}
