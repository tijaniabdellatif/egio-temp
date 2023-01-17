<?php

namespace App\Http\Controllers\sitemap;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\ads;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\DB;

class SiteMapController extends Controller
{
    public function index() {
        return response()->view('sitemap.index')->header('Content-Type', 'text/xml');

    }

    public function ads() {
        $data = ads::select('ads.id','ads.created_at','cats.slug','cities.name as city','neighborhoods.name as locdept','ads.locdept2')
            ->join('cats','ads.catid','=','cats.id')->leftJoin('cities','ads.loccity','=','cities.id')->leftJoin('neighborhoods','ads.locdept','=','neighborhoods.id')
            ->where('ads.status','=','10')->where('ads.expiredate','>',Carbon::now())->orderBy('ads.id','DESC')->take(2000)->get();
        return response()->view('sitemap.ads', [
            'data' => $data
        ])->header('Content-Type', 'text/xml');
    }

    public function listByCats() {
        $data = DB::select("SELECT c.slug as 'category' FROM `ads` a inner join cats c on a.catid = c.id inner join cities ct on a.loccity = ct.id where a.status = '10' and a.expiredate > CURRENT_TIMESTAMP group by c.slug");
        return response()->view('sitemap.list', [
            'data' => $data
        ])->header('Content-Type', 'text/xml');
    }

    public function listByCatsCities() {
        $data = DB::select("SELECT c.slug as 'category' , ct.name as 'city' FROM `ads` a inner join cats c on a.catid = c.id inner join cities ct on a.loccity = ct.id where a.status = '10' and a.expiredate > CURRENT_TIMESTAMP group by c.slug , ct.name");
        return response()->view('sitemap.list', [
            'data' => $data
        ])->header('Content-Type', 'text/xml');
    }

    public function listByCatsCitiesNeighborhood() {
        $data = DB::select("SELECT c.slug as 'category' , ct.name as 'city' , n.name as 'neighborhood' FROM `ads` a inner join cats c on a.catid = c.id inner join cities ct on a.loccity = ct.id inner join neighborhoods n on a.locdept = n.id where a.status = '10' and a.expiredate > CURRENT_TIMESTAMP group by c.slug , ct.name , n.name");
        return response()->view('sitemap.list', [
            'data' => $data
        ])->header('Content-Type', 'text/xml');
    }

    public function listAll() {
        return response()->view('sitemap.list', [
            'data' => [-1]
        ])->header('Content-Type', 'text/xml');
    }

    public function listByTypes() {
        $data = DB::select("SELECT c.type FROM `ads` a inner join cats c on a.catid = c.id where a.status = '10' and a.expiredate > CURRENT_TIMESTAMP group by c.type");
        return response()->view('sitemap.list', [
            'data' => $data
        ])->header('Content-Type', 'text/xml');
    }

    public function listByCities() {
        $data = DB::select("SELECT ct.name as 'city' FROM `ads` a inner join cities ct on a.loccity = ct.id where a.status = '10' and a.expiredate > CURRENT_TIMESTAMP group by ct.name");
        return response()->view('sitemap.list', [
            'data' => $data
        ])->header('Content-Type', 'text/xml');
    }

    public function listByTypesCities() {
        $data = DB::select("SELECT c.type , ct.name as 'city' FROM `ads` a inner join cats c on a.catid = c.id inner join cities ct on a.loccity = ct.id where a.status = '10' and a.expiredate > CURRENT_TIMESTAMP group by c.type , ct.name");
        return response()->view('sitemap.list', [
            'data' => $data
        ])->header('Content-Type', 'text/xml');
    }

    public function listByCitiesNeighborhoods() {
        $data = DB::select("SELECT ct.name as 'city' , n.name as 'neighborhood' FROM `ads` a inner join cities ct on a.loccity = ct.id inner join neighborhoods n on a.locdept = n.id where a.status = '10' and a.expiredate > CURRENT_TIMESTAMP group by ct.name , n.name");
        return response()->view('sitemap.list', [
            'data' => $data
        ])->header('Content-Type', 'text/xml');
    }

    public function listByTypesCitiesNeighborhoods() {
        $data = DB::select("SELECT c.type , ct.name as 'city' , n.name as 'neighborhood' FROM `ads` a inner join cats c on a.catid = c.id inner join cities ct on a.loccity = ct.id inner join neighborhoods n on a.locdept = n.id where a.status = '10' and a.expiredate > CURRENT_TIMESTAMP group by c.type , ct.name , n.name");
        return response()->view('sitemap.list', [
            'data' => $data
        ])->header('Content-Type', 'text/xml');
    }





    ///////

    public function mapByCats() {
        $data = DB::select("SELECT c.slug as 'category' FROM `ads` a inner join cats c on a.catid = c.id inner join cities ct on a.loccity = ct.id where a.status = '10' and a.expiredate > CURRENT_TIMESTAMP group by c.slug");
        return response()->view('sitemap.map', [
            'data' => $data
        ])->header('Content-Type', 'text/xml');
    }

    public function mapByCatsCities() {
        $data = DB::select("SELECT c.slug as 'category' , ct.name as 'city' FROM `ads` a inner join cats c on a.catid = c.id inner join cities ct on a.loccity = ct.id where a.status = '10' and a.expiredate > CURRENT_TIMESTAMP group by c.slug , ct.name");
        return response()->view('sitemap.map', [
            'data' => $data
        ])->header('Content-Type', 'text/xml');
    }

    public function mapByCatsCitiesNeighborhood() {
        $data = DB::select("SELECT c.slug as 'category' , ct.name as 'city' , n.name as 'neighborhood' FROM `ads` a inner join cats c on a.catid = c.id inner join cities ct on a.loccity = ct.id inner join neighborhoods n on a.locdept = n.id where a.status = '10' and a.expiredate > CURRENT_TIMESTAMP group by c.slug , ct.name , n.name");
        return response()->view('sitemap.map', [
            'data' => $data
        ])->header('Content-Type', 'text/xml');
    }

    public function mapAll() {
        return response()->view('sitemap.map', [
            'data' => [-1]
        ])->header('Content-Type', 'text/xml');
    }

    public function mapByTypes() {
        $data = DB::select("SELECT c.type FROM `ads` a inner join cats c on a.catid = c.id where a.status = '10' and a.expiredate > CURRENT_TIMESTAMP group by c.type");
        return response()->view('sitemap.map', [
            'data' => $data
        ])->header('Content-Type', 'text/xml');
    }

    public function mapByCities() {
        $data = DB::select("SELECT ct.name as 'city' FROM `ads` a inner join cities ct on a.loccity = ct.id where a.status = '10' and a.expiredate > CURRENT_TIMESTAMP group by ct.name");
        return response()->view('sitemap.map', [
            'data' => $data
        ])->header('Content-Type', 'text/xml');
    }

    public function mapByTypesCities() {
        $data = DB::select("SELECT c.type , ct.name as 'city' FROM `ads` a inner join cats c on a.catid = c.id inner join cities ct on a.loccity = ct.id where a.status = '10' and a.expiredate > CURRENT_TIMESTAMP group by c.type , ct.name");
        return response()->view('sitemap.map', [
            'data' => $data
        ])->header('Content-Type', 'text/xml');
    }

    public function mapByCitiesNeighborhoods() {
        $data = DB::select("SELECT ct.name as 'city' , n.name as 'neighborhood' FROM `ads` a inner join cities ct on a.loccity = ct.id inner join neighborhoods n on a.locdept = n.id where a.status = '10' and a.expiredate > CURRENT_TIMESTAMP group by ct.name , n.name");
        return response()->view('sitemap.map', [
            'data' => $data
        ])->header('Content-Type', 'text/xml');
    }

    public function mapByTypesCitiesNeighborhoods() {
        $data = DB::select("SELECT c.type , ct.name as 'city' , n.name as 'neighborhood' FROM `ads` a inner join cats c on a.catid = c.id inner join cities ct on a.loccity = ct.id inner join neighborhoods n on a.locdept = n.id where a.status = '10' and a.expiredate > CURRENT_TIMESTAMP group by c.type , ct.name , n.name");
        return response()->view('sitemap.map', [
            'data' => $data
        ])->header('Content-Type', 'text/xml');
    }
}
