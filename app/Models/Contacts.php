<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Contacts extends Model
{
    use HasFactory;

    protected $guarded = ['id'];


    // This method returns the relationship between user and contacts
    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
