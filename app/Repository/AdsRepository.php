<?php


namespace App\Repository;

use App\Models\ads;
use App\Repository\Interfaces\AdsInterface;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Http\Request;

class AdsRepository implements AdsInterface
{

    public function getAdsByCategory($ads, $id)
    {


        $data = $ads::leftJoin('cats','cats.id','=','ads.catid')
        ->join('users','users.id','=','ads.id_user')
        ->with(['medias','cities'])
        ->where('cats.parent_cat','=',$id)->get()->take(5)->pluck('cities');
        return $data;


    }
}
