<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class UrlAnalytics extends Model
{
    use HasFactory;

      protected $fillable =[
        'short_url_id',
        'ip_address',
        'user_agent',
       
    ];

    public function shortUrl()
{
    return $this->belongsTo(ShortUrl::class);
}

}
