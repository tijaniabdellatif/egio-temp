<?php

namespace App\Http\Controllers\api;

use App\Http\Controllers\Controller;
use App\Models\cats;
use App\Models\cities;
use App\Models\neighborhoods;
use App\Models\places_type;
use App\Models\standings;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class MultilistFilterFromController extends ApiController
{
    // get
    public function get(Request $request)
    {

        // validate request
        $validatedData = $request->validate([
            'type' => 'nullable|string',
            'univer' => 'nullable|integer',
        ]);

        // get all cities
        $cities = DB::select('SELECT id , `name` FROM `cities` order by `order` desc , `name` asc');

        // get all standings
        $standings = standings::all();

        // get all categories
        $categories = new cats();

        $places_type = places_type::all();

        // categories where parent_id is not null ( we don't want to show parent categories )
        $categories = $categories->where('parent_cat', '!=', null);

        // if univer is set ( parent_cat ) then get all categories where parent_cat is equal to univer
        if ($request->univer) {
            $categories = $categories->where('parent_cat', $request->univer);
        }

        // check if type is set
        if ($request->type) {
            // if type is set then get all categories where type is equal to type
            $categories = $categories->where('type', $request->type);
        }

        // ddQuery($categories);

        // get all categories
        $categories = $categories->get();

        // return response
        return $this->showAny([
            'cities' => $cities,
            'categories' => $categories,
            'standings' => $standings,
            'places_type' => $places_type
        ]);
    }

    public function neighborhoodsbycity(Request $request)
    {

        // validate request
        $validatedData = $request->validate([
            'city' => 'required|integer',
        ]);

        //check if not valid
        if (!$validatedData) {
            return $this->errorResponse('Validation Error', 422);
        }

        $data = neighborhoods::select("neighborhoods.id", "neighborhoods.name")
                ->where('neighborhoods.city_id', '=', $request->city)
                ->groupBy('neighborhoods.id')
                ->orderBy('neighborhoods.name')
                ->get();

        return $this->showAny($data);
    }
}
