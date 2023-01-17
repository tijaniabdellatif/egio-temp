<?php

namespace App\Http\Controllers\api;


use auth;
use Closure;
use App\Models\ads;
use App\Models\cats;
use App\Models\User;
use App\Models\media;
use App\Models\cities;
use App\Models\Region;
use App\Models\options;
use App\Models\Contract;
use App\Lib\CacheHandler;
use App\Models\countries;
use App\Models\provinces;
use App\Models\user_type;
use App\Models\media_type;
use App\Mail\SimpleMail;
use App\Models\option_type;
use Illuminate\Http\Request;
use App\Models\neighborhoods;
use Illuminate\Support\Carbon;
use App\Models\options_catalogue;
use Illuminate\Support\Facades\DB;
use Spatie\Permission\Models\Role;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Cache;
use Spatie\Permission\Traits\HasRoles;
use App\Http\Controllers\ApiController;
use Illuminate\Support\Facades\Validator;
use App\Http\Controllers\api\options as ApiOptions;
use App\Http\Controllers\users;
use App\Lib\DataManager;



class TestController extends ApiController
{
    use DataManager;


    public function promo(Request $request)


    {

        Mail::send(new SimpleMail(
            [
                "to" => $request->email,
                "subject" => "Multilist | Publiez vos annonces sans frais et sans limites !",
                "view" => "emails.Promo.fr",
                "data" => [
                    "title" => "Multilist | Publiez vos annonces sans frais et sans limites !",
                ]
            ]
        ));


    }


    public function getData()
    {

        $options = options::whereExists(
            function ($query) {
                $query->select("option_type.id")
                    ->from('option_type')
                    ->where('option_type.id', '=', 'options.option_id');
            }
        )->exists();

        return $this->showAny($options);
    }

    //Pour les tests
    public function Operating()
    {


        $user = User::all();

        return view('cachetest', compact('user'));

        // $query = DB::select('CALL getALL(?,?)',array($id,$quantity));



        // $users = Cache::remember($this->key,60*60,function(){
        //       return DB::select('CALL getUser');

        // });
        // return view('cachetest',compact($users));

        //   $data = options::has('catalogueOptions')
        //   ->get()
        //   ->load(['catalogueOptions' => function($req){

        //           $req->where('type_id','=',1)->with(['ads'=>function($que){

        //                  $que->with('medias',function($res){

        //                       $res->with('mediatype',function($req){
        //                                $req->where('id',1);

        //                       })->select('extension');

        //                  })->with('cities',function($resultat){

        //                         $resultat->with('provinces');
        //                  });
        //           }]);
        //   }])
        //   ->where('status','10')
        //   ->where('timestamp' ,'<=',Carbon::now()->subDays(7))

        //  $test = option_type::find(2)->catalogue->map(function($cat){

        //       return $cat->option;
        //  });


        //$test = option_type::find(1)->catalogue->flatMap->option;

        //    $test = option_type::withCount('many')->get();


        /**
         * At least one
         */
        // $post = option_type::has('many')->get()->load(['many' => function($q){

        //       $q->has('catalogueOptions')->get()->load(['catalogueOptions']);
        // }]);
        // return $this->showAny($post);


        // $ads = ads::whereHas('optionscatalogue')->get();

        // Ads inner join option where option.status = 10
        // and TIMESTAMPADD(DAY,option_catalogue.duration ,option.timestamp) >
        // CURRENT_TIMESTAMP and option_catalogue.type_id = 1(a la une


    }
    // filter queryra
    /*
    public function filter(Request $request)
    {
        // build query using $data

        $query = Cache::remember($this->key,60*60);

        dd($query);
        // the filter helper function
        $query = queryFilter(
            $request->where, // json data
            $query,
            [
                'user_info' => [
                    'type' => 'leftJoin',
                    'foreign_key' => 'users.id',
                    'primary_key' => 'user_info.id_user',
                    'op' => '='
                ]
            ], // joins
            [
                'users.id' => [
                    'type' => 'int',
                    'operators' => ['=', '!=','LIKE', 'NOT LIKE'],
                ]
            ], // allowed cols to filter by
            true // passing data as json (if true) or php array (if false)
        );

        try{
            $result = [];
            //check if query has sort and order
            if(isset($request->sort) && isset($request->order)){
                $result = $query->orderBy($request->sort, $request->order);
            }

            //check if request has per_page parameter
            if($request->has('per_page')){
                // get the data
                $result = $query->paginate($request->per_page);
            }
            else{
                // get the data
                $result = $query->get();
            }



            // get sql statement
            // $sql = $query->toSql();
            // dd($sql);

            // return success message with data

            return view('cachetest',compact('result'));
            // return response()->json([
            //     'status' => 'success',
            //     'data' => $result
            // ]);
        } catch (\Exception $e) {
            return response()->json(['error' => 'Check your columns or tables names'], 500);
        }

    }
*/



    public function test()
    {

        $data = ads::all();

        $response = array("data" => $data, "id" => $data["id"]);

        $error = true;

        if ($error) {
            return $this->errorResponse("message", 500);
        }

        return $this->showAny($response, 200);
    }



    public function show($id)
    {



        $user = DB::select('CALL getUserByID(?)', [$id]);



        return view('cachin', compact('user'));
    }

    public function mail(){
        try{
            $check = Mail::send(new SimpleMail(
                [
                    "to" => "y.houssaf@multilist.ma",
                    //"to" => "youssef.j.houssaf@gmail.com",
                    "subject" => "Dernière ligne droite pour la nouvelle plateforme Multilist !",
                    "view" => "emails.NewVersion.fr",
                    "data" => [
                        'host_name'=>'https://multilist.immolist.co/'
                    ]
                ]
            ));
            return $check;
        }
        catch(\Exception $e) {
            return $e->getMessage();
        }
    }

    public function promoMail(){
        try{
            $check = Mail::send(new SimpleMail(
                [
                    "to" => "s.souak@multilist.ma",
                    "subject" => "promo test",
                    "view" => "emails.Promo.fr",
                    "data" => [
                        'host_name'=>'https://multilist.immolist.co/'
                    ]
                ]
            ));
            return $check;
        }
        catch(\Exception $e) {
            return $e->getMessage();
        }
    }


}
