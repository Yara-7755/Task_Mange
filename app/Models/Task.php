<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Task extends Model
{
    protected $fillable=[

        'name',
        'description',
        'date',
        'user_id',
        'category_id',
        'completed',



    ];
    public function user(){

        return $this->belongsTo(User::class);
    }


    public function category(){
        return $this->belongsTo(Category::class);
    }



}
