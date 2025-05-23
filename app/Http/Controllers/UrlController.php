<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\ShortUrl;
use Illuminate\Support\str;
use Carbon\Carbon;
class UrlController extends Controller
{
    public function index(){
        $urls = ShortUrl::where('user_id',auth()->id())->get();
        return view('shorturl.index',compact('urls'));
    }
    // public function edit($id){
    //     $url_id = ShortUrl::findOrFail($id);
    //     return view('shorturl.edit',compact('url_id'));
    // }
    public function delete($id){
        $url_id = ShortUrl::findOrFail($id);
        $url_id->delete();
        return redirect('home')->with('success','URL deleted!');
    }
    public function disable($id){
        $url_id = ShortUrl::findOrFail($id);
        $url_id->is_active = false;
        $url_id->save();
        return redirect('home')->with('success','URL disabled!');
    }

    public function edit(Request $request, $id)
{
    $request->validate([
        'original_url' => 'required|url',
    ]);

    $shortUrl = ShortUrl::where('id', $id)->where('user_id', auth()->id())->firstOrFail();

    $shortUrl->update([
        'original_url' => $request->original_url,
        'expires_at' =>now()->addDays(7)
    ]);

    return redirect()->route('home')->with('success', 'URL updated successfully!');
}



    public function store(Request $request){
        $request->validate([
            'url' => 'required|url'
        ]);

        $code = Str::random(6);

        shorturl::create([
            'user_id' => auth()->id(),
            'original_url'=>$request->url,
            'short_code'=>$code,
            'expires_at' =>now()->addDays(7)
        ]);

        return redirect('home')->with('success','URL created!');
    }

    public function redirect($shortenedUrl){
    $url = shorturl::where('short_code',$shortenedUrl)->firstOrFail();
    if($url->expires_at && Carbon::now()->greaterThan($url->expires_at)){
        abort(404, 'This link has expired.');
    }
    return redirect($url->original_url);
    }
}
