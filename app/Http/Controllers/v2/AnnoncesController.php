<?php

namespace App\Http\Controllers\v2;

use App\Models\ads;
use App\Models\settings;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Crypt;
use Share;

class annoncesController extends Controller
{
    //get home page
    public function getNewAdPage()
    {
        $user = auth()->user();
        return view('v2.add-ad', ['user' => $user]);
    }

    public function index()
    {
        return view('v2.dashboard.ads');
    }

    public function bannersPage()
    {
        return view('v2.dashboard.banners');
    }

    public function getItemFinal(Request $req){
        $id = $req->id;
        // check if id is exist
        if (!$id) {
            abort(404);
        }

        $ad = ads::find($id);
        if (!$ad) {
            abort(404);
        }
        $user = getCurrentUser();
        if ($ad->status != "10") {
            if (($user && $ad->id_user != $user->id) || $user == null)
                abort(404);
        }

        $settings = settings::first();
        $imageSize = round($settings->image_max_size / 1024);
        $videoSize = round($settings->video_max_size / 1024);

        $seoData = ads::select('cats.title','cats.slug','cities.name as city','neighborhoods.name as locdept','ads.locdept2','ads.price','ads.price_curr','ads.bedrooms')->join('cats','ads.catid','=','cats.id')->leftJoin('cities','ads.loccity','=','cities.id')
            ->leftJoin('neighborhoods','ads.locdept','=','neighborhoods.id')->where('ads.id','=',$id)->first();

        $title = $ad->title;
        if($seoData){
            $title = $seoData->title;
            $title .= ' ' . $seoData->city;
            if($seoData->locdept) $title .= ' ' . $seoData->locdept;
            else if($seoData->locdept2) $title .= ' ' . $seoData->locdept2;
            if($seoData->price) $title .= ' - ' . $seoData->price . ($seoData->price_curr?($seoData->price_curr=='MAD'?'DHS':$seoData->price_curr):'');
            if($seoData->bedrooms) $title .= ' - ' . $seoData->bedrooms . ' chambres';
        }



        $fullUrl = $req->fullUrl();

        return view('v2.item', compact('imageSize', 'videoSize'))
            ->with('id', $id)->with('title', $title)
            ->with('url',$fullUrl);

            // ->with('linkedinShare', $linkedinShare);
            // ->with('twitterShare', $twitterShare);
    }

    // getItem
    public function getItem($id)
    {

        // check if id is exist
        if (!$id) {
            abort(404);
        }

        $ad = ads::find($id);
        if (!$ad) {
            abort(404);
        }
        $user = getCurrentUser();
        if ($ad->status != "10") {
            if (($user && $ad->id_user != $user->id) || $user == null)
                abort(404);
        }

        $settings = settings::first();
        $imageSize = round($settings->image_max_size / 1024);
        $videoSize = round($settings->video_max_size / 1024);

        $seoData = ads::select('cats.title','cats.slug','cities.name as city','neighborhoods.name as locdept','ads.locdept2','ads.price','ads.price_curr','ads.bedrooms')->join('cats','ads.catid','=','cats.id')->leftJoin('cities','ads.loccity','=','cities.id')
            ->leftJoin('neighborhoods','ads.locdept','=','neighborhoods.id')->where('ads.id','=',$id)->first();

        $title = $ad->title;
        if($seoData){
            $link = "/annonce/".$seoData->slug.'/'.$seoData->city.'/'.$id;
            return redirect($link);
        }



        return view('v2.item', compact('imageSize', 'videoSize'))
            ->with('id', $id)->with('title', $title);
    }

    // getItemByID
    public function getItemJustByID($id)
    {
        $title = "";

        // get ads by id
        $annonce = ads::find($id);

        // if not found
        if (!$annonce) {
            abort(404);
        }

        // get title
        $title = $annonce->title;

        // encode the title to be used in the url
        $title_url = urlencode($title);

        $seoData = ads::select('cats.title','cats.slug','cities.name as city','neighborhoods.name as locdept','ads.locdept2','ads.price','ads.price_curr','ads.bedrooms')->join('cats','ads.catid','=','cats.id')->leftJoin('cities','ads.loccity','=','cities.id')
            ->leftJoin('neighborhoods','ads.locdept','=','neighborhoods.id')->where('ads.id','=',$id)->first();

        if($seoData){
            $link = "/annonce/".$seoData->slug.'/'.$seoData->city.'/'.$id;
            return redirect($link);
        }

        // redirect to the item page
        return redirect("/item/$id/$title_url");
    }
}
