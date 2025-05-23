<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ShortUrl extends Model
{
    use HasFactory;

    protected $fillable =[
        'user_id',
        'original_url',
        'short_code',
        'is_active',
        'expires_at',
        'notified',
        'is_expired'
    ];
    
    protected $dates = ['expires_at'];

    public function user()
{
    return $this->belongsTo(User::class);
}

}
