<?php

namespace App\Http\Controllers\api;

use App\Models\ads;
use App\Models\User;
use App\Models\media;
use App\Models\cities;
use App\Models\booking;
use App\Mail\SimpleMail;
use Carbon\CarbonPeriod;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Validator;

class ReservationController extends ApiController
{

    public function getAdCalendar(Request $req)
    {

        $validatorArr = [
            'id' => 'required',
            'month' => 'required',
            'year' => 'required',
        ];

        // validate request data
        $validator = Validator::make($req->all(), $validatorArr);

        // check if validation fails
        if ($validator->fails()) {
            return $this->errorResponse($validator->errors(), 400);
        }

        $firstdate = Carbon::create($req->year, $req->month)->toDateString();
        $lastdate = Carbon::create($req->year, $req->month)->endOfMonth()->toDateString();

        $booking = booking::where('ad_id', '=', $req->id)->where('status', '<>', '30')->whereBetween('date_debut', [$firstdate, $lastdate])
            ->orwherebetween('date_fin', [$firstdate, $lastdate])->get();
        $bookedDays = [];
        foreach ($booking as $b) {
            $period = CarbonPeriod::create($b->date_debut, $b->date_fin)->toArray();
            foreach ($period as $day) {
                if (!in_array($day->toDateString(), $bookedDays) && (($day->format('m') * 1) == ($req->month * 1) && ($day->format('Y') * 1) == ($req->year * 1))) {
                    $bookedDays[] = $day->toDateString();
                }
            }
        }
        return $this->showAny($bookedDays);
    }

    public function addBooking(Request $req)
    {
        $validatorArr = [
            'ad_id' => 'required',
            'name' => 'required',
            'email' => 'required',
            'phone' => 'required',
            'startDate' => 'required',
            'endDate' => 'required',
        ];

        // validate request data
        $validator = Validator::make($req->all(), $validatorArr);

        // check if validation fails
        if ($validator->fails()) {
            return $this->errorResponse($validator->errors(), 400);
        }

        $check = booking::where('ad_id', '=', $req->ad_id)->where('status', '<>', '30')->whereBetween('date_debut', [$req->startDate, $req->endDate])
            ->orwherebetween('date_fin', [$req->startDate, $req->endDate])->count();

        if ($check > 0) {
            return $this->errorResponse("Cette date est déjà réservée!", 409);
        }

        // create a new plan
        $booking = new booking();

        // set plan data
        $booking->ad_id = $req->ad_id;
        $booking->name = $req->name;
        $booking->email = $req->email;
        $booking->phone = $req->phone;
        $booking->date_debut = $req->startDate;
        $booking->date_fin = $req->endDate;
        $booking->status = '20';


        $ads = ads::select('title', 'price', 'loccity', 'id_user')->where('id', '=', $req->ad_id)->first();
        $adCity = cities::select('name')->where('id', '=', $ads['loccity'])->first();
        $user = User::select('username', 'email')->where('id', '=', $ads['id_user'])->first();


        // save plan
        $booking->save();


        // get the ad image
        $image = media::select(DB::raw("CONCAT(media.path,media.filename,'.',media.extension) as img"))
            ->join('ad_media', 'ad_media.media_id', '=', 'media.id')
            ->leftjoin('ads', 'ad_media.ad_id', '=', 'ads.id')
            ->where('ads.id', '=', $req->ad_id)
            ->first();

        // Host name
        $host_name = $_SERVER['HTTP_HOST'];

        // in case there is no image
        if (!$image) {
            $img = $host_name . "/assets/img/no-photo.png";
        } else {
            $img = $image->img;
        }

        // get the img
        $img = $image->img;

        // Send email
        Mail::send(new SimpleMail(
            [
                "to" => $req->email,
                "subject" => "Vous avez réservez un bien !",
                "view" => "emails.Reservation.fr",
                "data" => [
                    "title" => $ads['title'],
                    "username" => $req['name'],
                    "price" => $ads['price'],
                    "city" => $adCity['name'],
                    "datedebut" => $req->startDate,
                    "datefin" => $req->endDate,
                    "host_name" => $host_name
                ]
            ]
        ));

        Mail::send(new SimpleMail(
            [
                "to" => $user['email'],
                "subject" => "Vous avez une nouvelle réservation !",
                "view" => "emails.NewReservation.fr",
                "data" => [
                    "username" => $user['username'],
                    "title" => $ads['title'],
                    "name" => $req['name'],
                    "email" => $req['email'],
                    "phone" => $req['phone'],
                    "price" => $ads['price'],
                    "city" => $adCity['name'],
                    "datedebut" => $req->startDate,
                    "datefin" => $req->endDate,
                    "image" => $img
                ]
            ]
        ));



        return $this->showAny(null);
    }

    // filter
    public function filter(Request $req)
    {
        // build query using $data
        $query = booking::select('booking.*', 'ads.title as annonce')
            ->leftJoin('ads', 'ads.id', '=', 'booking.ad_id')
            ->orderBy('booking.id', 'desc');

        // the filter helper function
        $query = queryFilter(
            $req->where, // json data
            $query,
            [], // joins
            [
                'booking.id' => [
                    'type' => 'int',
                    'operators' => ['=', '!=', 'LIKE', 'NOT LIKE'],
                ],
                'booking.id_user' => [
                    'type' => 'int',
                    'operators' => ['=', '!=', 'LIKE', 'NOT LIKE'],
                ],
                'booking.ad_id' => [
                    'type' => 'int',
                    'operators' => ['=', '!=', 'LIKE', 'NOT LIKE'],
                ],
                'booking.name' => [
                    'type' => 'string',
                    'operators' => ['=', '!=', 'LIKE', 'NOT LIKE'],
                ],
                'booking.phone' => [
                    'type' => 'string',
                    'operators' => ['=', '!=', 'LIKE', 'NOT LIKE'],
                ],
                'booking.email' => [
                    'type' => 'string',
                    'operators' => ['=', '!=', 'LIKE', 'NOT LIKE'],
                ],
                'booking.date_debut' => [
                    'type' => 'string',
                    'operators' => ['=', '!=', 'LIKE', 'NOT LIKE', '>', '<'],
                ],
                'booking.date_fin' => [
                    'type' => 'string',
                    'operators' => ['=', '!=', 'LIKE', 'NOT LIKE', '>', '<'],
                ],
                'booking.status' => [
                    'type' => 'string',
                    'operators' => ['=', '!=', 'LIKE', 'NOT LIKE'],
                ],
                'ads.id' => [
                    'type' => 'int',
                    'operators' => ['=', '!=', 'LIKE', 'NOT LIKE'],
                ],
                'ads.id_user' => [
                    'type' => 'string',
                    'operators' => ['=', '!=', 'LIKE', 'NOT LIKE'],
                ],
                'ads.title' => [
                    'type' => 'string',
                    'operators' => ['=', '!=', 'LIKE', 'NOT LIKE'],
                ],
            ], // allowed cols to filter by
            true // passing data as json (if true) or php array (if false)
        );

        try {
            $result = [];
            //check if query has sort and order
            if (isset($req->sort) && isset($req->order)) {
                $result = $query->orderBy($req->sort, $req->order);
            }

            //check if request has per_page parameter
            if ($req->has('per_page')) {
                // get the data
                $result = $query->paginate($req->per_page);
            } else {
                // get the data
                $result = $query->get();
            }

            // get sql statement
            // $sql = $query->toSql();
            // dd($sql);

            // ddQuery($query);

            // return success message with data
            // return response()->json([
            //     'status' => 'success',
            //     'data' => $result
            // ]);
            return $this->showAny($result);
        } catch (\Exception $e) {
            // return response()->json(['error' => 'Check your columns or tables names'], 400);
            return $this->errorResponse("erreur de serveur", 500);
        }
    }

    public function changeBookingStatus(Request $req)
    {
        try {
            $validatorArr = [
                'id' => 'required',
                'status' => 'required',
            ];

            // validate request data
            $validator = Validator::make($req->all(), $validatorArr);

            // check if validation fails
            if ($validator->fails()) {
                return $this->errorResponse($validator->errors(), 400);
            }

            booking::where('id', '=', $req->id)->update([
                "status" => $req->status,
            ]);

            return $this->showAny(null);
        } catch (\Exception $e) {
            return $this->errorResponse("erreur de serveur", 500);
        }
    }
}