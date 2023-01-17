<?php

namespace App\Http\Controllers\api;

use App\Models\ads;
use App\Models\cities;
use App\Models\countries;
use App\Models\provinces;
use Illuminate\Http\Request;
use App\Models\neighborhoods;
use App\Models\Region as regions;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Validator;
use App\Http\Controllers\Api\ApiController;
use Grimzy\LaravelMysqlSpatial\Types\Point;
use Grimzy\LaravelMysqlSpatial\Types\Polygon;
use Grimzy\LaravelMysqlSpatial\Types\Geometry;
use Grimzy\LaravelMysqlSpatial\Types\LineString;
use Grimzy\LaravelMysqlSpatial\Types\MultiPolygon;

class localisationController extends ApiController
{
    // filter
    public function regionFilter(Request $request){

        // build query using $data
        $query = regions::select('regions.*','countries.name as country','countries.id as country_id')
                    ->join('countries', 'countries.id', '=', 'regions.country_id');

        // the filter helper function
        $query = queryFilter(
            $request->where, // json data
            $query,
            [], // joins
            [
                'regions.id' => [
                    'name' => 'int',
                    'operators' => ['=', '!=','LIKE', 'NOT LIKE'],
                ],
                'regions.name' => [
                    'operators' => ['=', '!=','LIKE', 'NOT LIKE'],
                ],
                'regions.country_id' => [
                    'operators' => ['=', '!=','LIKE', 'NOT LIKE'],
                ],
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
            return $this->showAny($result);
        } catch (\Exception $e) {
            return $this->errorResponse($e->getMessage(),500);
        }


    }

    // create a new plan
    public function createRegion(Request $request){

        try{
            // validate request
            $validator = Validator::make($request->all(), [
                'name' => 'required|string|max:100',
                'coordinates' => 'nullable',
                'country_id' => 'required|integer'
            ]);

            // check if validation fails
            if ($validator->fails()) {
                return $this->errorResponse($validator->errors(),400);
            }

            // create a new plan
            $region = new regions();

            if($request->coordinates){
                $coordinates = Geometry::fromJson($request->coordinates);
            }

            // set plan data
            $region->name = $request->name;
            $region->coordinates = $request->coordinates?$coordinates:null;
            $region->country_id = $request->country_id;

            // save plan
            $region->save();

            $newData = regions::select('regions.*','countries.name as country')
                    ->join('countries', 'countries.id', '=', 'regions.country_id')
                    ->where('regions.id','=',$region->id)->first();

            return $this->showAny($newData);
        }catch(\Throwable $th){
            return $this->errorResponse($th->getMessage(),500);
        }

    }

    // update a plan
    public function updateRegion(Request $request){

        try{
            // validate request
            $validator = Validator::make($request->all(), [
                'id' => 'required|integer',
                'name' => 'required|string|max:100',
                'coordinates' => 'nullable',
                'country_id' => 'required|integer'
            ]);

            // check if validation fails
            if ($validator->fails()) {
                return $this->errorResponse($validator->errors(),400);
            }

            // get cat
            $region = regions::find($request->id);

            // check if plan exists
            if(!$region){
                return $this->errorResponse('Region not found',404);
            }

            if($request->coordinates){
                $coordinates = Geometry::fromJson($request->coordinates);
            }

            // set plan data
            $region->name = $request->name;
            $region->coordinates = $request->coordinates?$coordinates:null;
            $region->country_id = $request->country_id;
            if(!$region->isDirty()){
                return $this->errorResponse('Rien a modifier',409);
            }
            // save plan
            $region->save();

            $newData = regions::select('regions.*','countries.name as country')
                ->join('countries', 'countries.id', '=', 'regions.country_id')
                        ->where('regions.id','=',$region->id)->first();

            return $this->showAny($newData);
        }catch(\Throwable $th){
            return $this->errorResponse($th->getMessage(),500);
        }

    }

    public function deleteRegion(Request $request, regions $region) {

        try {
            $region = regions::find($request->id);

            if(!$region){
                return $this->errorResponse('Region not found',404);
            }

            $check = provinces::where('region_id','=',$request->id);
            if($check) {
                return $this->errorResponse('Cette région est déjà utilisée',401);
            }

            $region->delete();
        }catch(\Throwable $th) {
            return $this->errorResponse($th->getMessage(),500);
        }

        return $this->showAny($region);

    }

    public function getCountries(Request $request){
        $data = countries::all();
        return $this->showAny($data);
    }


    // filter
    public function provinceFilter(Request $request){

        // build query using $data
        $query = provinces::select('provinces.*','regions.name as region')
                    ->join('regions', 'regions.id', '=', 'provinces.region_id');

        // the filter helper function
        $query = queryFilter(
            $request->where, // json data
            $query,
            [], // joins
            [
                'provinces.id' => [
                    'name' => 'int',
                    'operators' => ['=', '!=','LIKE', 'NOT LIKE'],
                ],
                'provinces.name' => [
                    'operators' => ['=', '!=','LIKE', 'NOT LIKE'],
                ],
                'provinces.region_id' => [
                    'operators' => ['=', '!=','LIKE', 'NOT LIKE'],
                ],
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
            return $this->showAny($result);
        } catch (\Exception $e) {
            return $this->errorResponse($e->getMessage(),500);
        }


    }

    // create a new plan
    public function createProvince(Request $request){

        try{
            // validate request
            $validator = Validator::make($request->all(), [
                'name' => 'required|string|max:100',
                'coordinates' => 'nullable',
                'region_id' => 'required|integer'
            ]);

            // check if validation fails
            if ($validator->fails()) {
                return $this->errorResponse($validator->errors(),400);
            }

            // create a new plan
            $province = new provinces();

            if($request->coordinates){
                $coordinates = Geometry::fromJson($request->coordinates);
            }

            // set plan data
            $province->name = $request->name;
            $province->coordinates = $request->coordinates?$coordinates:null;
            $province->region_id = $request->region_id;

            // save plan
            $province->save();

            $newData = provinces::select('provinces.*','regions.name as region')
                    ->join('regions', 'regions.id', '=', 'provinces.region_id')
                    ->where('provinces.id','=',$province->id)->first();

            return $this->showAny($newData);
        }catch(\Throwable $th){
            return $this->errorResponse($th->getMessage(),500);
        }

    }

    // update a plan
    public function updateProvince(Request $request){

        try{
            // validate request
            $validator = Validator::make($request->all(), [
                'id' => 'required|integer',
                'name' => 'required|string|max:100',
                'coordinates' => 'nullable',
                'region_id' => 'required|integer'
            ]);

            // check if validation fails
            if ($validator->fails()) {
                return $this->errorResponse($validator->errors(),400);
            }

            // get cat
            $province = provinces::find($request->id);

            // check if plan exists
            if(!$province){
                return $this->errorResponse('Province not found',404);
            }

            if($request->coordinates){
                $coordinates = Geometry::fromJson($request->coordinates);
            }

            // set plan data
            $province->name = $request->name;
            $province->coordinates = $request->coordinates?$coordinates:null;
            $province->region_id = $request->region_id;
            if(!$province->isDirty()){
                return $this->errorResponse('Rien a modifier',409);
            }
            // save plan
            $province->save();

            $newData = provinces::select('provinces.*','regions.name as region')
                        ->join('regions', 'regions.id', '=', 'provinces.region_id')
                        ->where('provinces.id','=',$province->id)->first();

            return $this->showAny($newData);
        }catch(\Throwable $th){
            return $this->errorResponse($th->getMessage(),500);
        }

    }

    // Delete Province
    public function deleteProvince(Request $request, provinces $province) {

        try {
            $province = provinces::find($request->id);

            if(!$province){
                return $this->errorResponse('Province not found',404);
            }

            // Check if province is used before delete
            $check = cities::where('province_id','=',$request->id)->exists();
            if($check) {
                return $this->errorResponse('Province déjà utilisée',401);
            }

            $province->delete();
        }catch(\Throwable $th) {
            return $this->errorResponse($th->getMessage(),500);
        }

        return $this->showAny($province);

    }

     // filter
     public function cityFilter(Request $request){

        // build query using $data
        $query = cities::select('cities.*','provinces.name as province')
                    ->join('provinces', 'provinces.id', '=', 'cities.province_id');

        // the filter helper function
        $query = queryFilter(
            $request->where, // json data
            $query,
            [], // joins
            [
                'cities.id' => [
                    'name' => 'int',
                    'operators' => ['=', '!=','LIKE', 'NOT LIKE'],
                ],
                'cities.name' => [
                    'operators' => ['=', '!=','LIKE', 'NOT LIKE'],
                ],
                'cities.province_id' => [
                    'operators' => ['=', '!=','LIKE', 'NOT LIKE'],
                ],
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
            return $this->showAny($result);
        } catch (\Exception $e) {
            return $this->errorResponse($e->getMessage(),500);
        }


    }

    // create a new plan
    public function createCity(Request $request){

        try{
            // validate request
            $validator = Validator::make($request->all(), [
                'name' => 'required|string|max:100',
                'coordinates' => 'nullable',
                'province_id' => 'required|integer'
            ]);

            // check if validation fails
            if ($validator->fails()) {
                return $this->errorResponse($validator->errors(),400);
            }

            // create a new plan
            $city = new cities();

            if($request->coordinates){
                $coordinates = Geometry::fromJson($request->coordinates);
            }

            // set plan data
            $city->name = $request->name;
            $city->coordinates = $request->coordinates?$coordinates:null;
            $city->province_id = $request->province_id;

            // save plan
            $city->save();

            $newData = cities::select('cities.*','provinces.name as province')
                    ->join('provinces', 'provinces.id', '=', 'cities.province_id')
                    ->where('cities.id','=',$city->id)->first();

            return $this->showAny($newData);
        }catch(\Throwable $th){
            return $this->errorResponse($th->getMessage(),500);
        }

    }

    // update a plan
    public function updateCity(Request $request){

        try{
            // validate request
            $validator = Validator::make($request->all(), [
                'id' => 'required|integer',
                'name' => 'required|string|max:100',
                'coordinates' => 'nullable',
                'province_id' => 'required|integer'
            ]);

            // check if validation fails
            if ($validator->fails()) {
                return $this->errorResponse($validator->errors(),400);
            }

            // get cat
            $city = cities::find($request->id);

            // check if plan exists
            if(!$city){
                return $this->errorResponse('City not found',404);
            }

            if($request->coordinates){
                $coordinates = Geometry::fromJson($request->coordinates);
            }

            // set plan data
            $city->name = $request->name;
            $city->coordinates = $request->coordinates?$coordinates:null;
            $city->province_id = $request->province_id;
            if(!$city->isDirty()){
                return $this->errorResponse('Rien a modifier',409);
            }
            // save plan
            $city->save();

            $newData = cities::select('cities.*','provinces.name as province')
                        ->join('provinces', 'provinces.id', '=', 'cities.province_id')
                        ->where('cities.id','=',$city->id)->first();

            return $this->showAny($newData);
        }catch(\Throwable $th){
            return $this->errorResponse($th->getMessage(),500);
        }

    }

    // Delete a city
    public function deleteCity(Request $request, cities $city) {

        try {
            $city = cities::find($request->id);

            if(!$city){
                return $this->errorResponse('City not found',404);
            }

            // Check if the deleted city has an associated neighborhood
            $check1 = neighborhoods::where('city_id', '=',$request->id)->exists();
            $check2 = ads::where('loccity', '=', $request->id)->exists();

            if($check1 or $check2) {
                return $this->errorResponse('Ville déjà utilisée !',401);
            }

            // delete the city
            $city->delete();

        }catch(\Throwable $th) {
            return $this->errorResponse($th->getMessage(),500);
        }

        return $this->showAny($city);

    }

    // filter
    public function neighborhoodFilter(Request $request){

        // build query using $data
        $query = neighborhoods::select('neighborhoods.*','cities.name as city')
                    ->join('cities', 'cities.id', '=', 'neighborhoods.city_id');

        // the filter helper function
        $query = queryFilter(
            $request->where, // json data
            $query,
            [], // joins
            [
                'neighborhoods.id' => [
                    'name' => 'int',
                    'operators' => ['=', '!=','LIKE', 'NOT LIKE'],
                ],
                'neighborhoods.name' => [
                    'operators' => ['=', '!=','LIKE', 'NOT LIKE'],
                ],
                'neighborhoods.city_id' => [
                    'operators' => ['=', '!=','LIKE', 'NOT LIKE'],
                ],
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
            return $this->showAny($result);
        } catch (\Exception $e) {
            return $this->errorResponse($e->getMessage(),500);
        }


    }

    // create a new plan
    public function createNeighborhood(Request $request){

        try{
            // validate request
            $validator = Validator::make($request->all(), [
                'name' => 'required|string|max:100',
                'coordinates' => 'nullable',
                'city_id' => 'required|integer'
            ]);

            // check if validation fails
            if ($validator->fails()) {
                return $this->errorResponse($validator->errors(),400);
            }

            // create a new plan
            $neighborhood = new neighborhoods();

            if($request->coordinates){
                $coordinates = Geometry::fromJson($request->coordinates);
            }

            // set plan data
            $neighborhood->name = $request->name;
            $neighborhood->coordinates = $request->coordinates?$coordinates:null;
            $neighborhood->city_id = $request->city_id;

            // save plan
            $neighborhood->save();

            $newData = neighborhoods::select('neighborhoods.*','cities.name as city')
                    ->join('cities', 'cities.id', '=', 'neighborhoods.city_id')
                    ->where('neighborhoods.id','=',$neighborhood->id)->first();

            return $this->showAny($newData);
        }catch(\Throwable $th){
            return $this->errorResponse($th->getMessage(),500);
        }

    }

    // update a plan
    public function updateNeighborhood(Request $request){

        try{
            // validate request
            $validator = Validator::make($request->all(), [
                'id' => 'required|integer',
                'name' => 'required|string|max:100',
                'coordinates' => 'nullable',
                'city_id' => 'required|integer'
            ]);

            // check if validation fails
            if ($validator->fails()) {
                return $this->errorResponse($validator->errors(),400);
            }

            // get cat
            $neighborhood = neighborhoods::find($request->id);

            // check if plan exists
            if(!$neighborhood){
                return $this->errorResponse('Neighborhood not found',404);
            }

            if($request->coordinates){
                $coordinates = Geometry::fromJson($request->coordinates);
            }

            // set plan data
            $neighborhood->name = $request->name;
            $neighborhood->coordinates = $request->coordinates?$coordinates:null;
            $neighborhood->city_id = $request->city_id;
            if(!$neighborhood->isDirty()){
                return $this->errorResponse('Rien a modifier',409);
            }
            // save plan
            $neighborhood->save();

            $newData = neighborhoods::select('neighborhoods.*','cities.name as city')
                        ->join('cities', 'cities.id', '=', 'neighborhoods.city_id')
                        ->where('neighborhoods.id','=',$neighborhood->id)->first();

            return $this->showAny($newData);
        }catch(\Throwable $th){
            return $this->errorResponse($th->getMessage(),500);
        }

    }

    // delete a neighborhood
    public function deleteNeighborhood(Request $request, neighborhoods $neighborhood) {

        try {
            $neighborhood = neighborhoods::find($request->id);

            if(!$neighborhood){
                return $this->errorResponse('Neighborhood not found',404);
            }

            // Check if the neighborhood exists in an ad before delete
            $check = ads::where('locdept','=',$request->id)->exists();
            if($check){
                return $this->errorResponse('Ce quartier est déjà utilisé !',401);
            }

            $neighborhood->delete();
        }catch(\Throwable $th) {
            return $this->errorResponse($th->getMessage(),500);
        }

        return $this->showAny($neighborhood);

    }

    public function getRegions(Request $request)
    {
        $data = regions::all();
        return $this->showAny($data);
    }

    public function getProvinces(Request $req)
    {
        $data = provinces::where("region_id","=",$req->id)->get();
        return $this->showAny($data);
    }

    public function getCities(Request $req)
    {
        $data = cities::where("province_id","=",$req->id)->get();
        return $this->showAny($data);
    }

    public function getAllCities(Request $req){
        $data = cities::select("id","name")->get();
        return $this->showAny($data);
    }

    public function getCityById(Request $req){
        $data = cities::select("id","name")->where("id","=",$req->id)->first();
        return $this->showAny($data);
    }

    public function getCityCoordinatesById(Request $req){
        $data = cities::select("id","name","lat","lng","coordinates")->where("id","=",$req->id)->first();
        return $this->showAny($data);
    }

    public function getNeighborhoodById(Request $req){
        $data = neighborhoods::select("id","name")->where("id","=",$req->id)->first();
        return $this->showAny($data);
    }

    public function dataRegion(Request $req){
        $data = file_get_contents(storage_path('finalLocsData/dataRegion.json'));

        // check if data contains a valid json
        if(!json_decode($data)){
            return $this->errorResponse('Invalid json',500);
        }

        $data = json_decode($data);

        $Region = [];

        // loop through $data and add Commune to Region
        foreach ($data as $key => $value) {
            // add Commune to Region
            $Region[$value->Region][] = $value->Commune;
        }

        $RegionAsKeyValue = [];

        // RegionAsKeyValue
        foreach ($Region as $key => $value) {

            // change comune array to key value object
            $comunesKeyValue = [];
            foreach ($value as $key2 => $value2) {
                $comunesKeyValue[] = [
                    'name' => $value2
                ];
            }

            $RegionAsKeyValue[] = [
                'region' => $key,
                'communes' => $comunesKeyValue
            ];
        }

        return $this->showAny($RegionAsKeyValue);
    }

}
