<?php

namespace App\Http\Controllers\flux;

use App\Http\Controllers\Controller;
use App\Models\ad_media;
use App\Models\ads;
use App\Models\cats;
use App\Models\settings;
use App\Models\cities;
use App\Models\media;
use App\Models\neighborhoods;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Carbon;
use Intervention\Image\ImageManagerStatic as Image;

class fluxController extends Controller
{
    public function importRssFeed(Request $req){
        try{

            $user_id = $req->user_id;
            if(!isset($user_id)){
                return ["error"=>"user_id not found!"];
            }
            $xmlString = file_get_contents($req->link);
            $xmlObject = simplexml_load_string($xmlString, 'SimpleXMLElement', LIBXML_NOCDATA);
            $json = json_encode($xmlObject);
            /*$json = str_replace('<![CDATA[','',$json);
            $json = str_replace(']]>','',$json);*/
            $data = json_decode($json, true);
            if(isset($data['annonce'])&&is_array($data['annonce'])) $data = $data['annonce'];
            else return ["error"=>"format incorrect!"];

            // for($y = 0 ; $y < count($data) ; $y++){
            //     for($k = 0 ; $k < count($data[$y]) ; $k++){
            //         if(empty($data[$y][$k])){
            //             $data[$y][$k] = null;
            //         }
            //     }
            // }


            $finalTime = null;

            $settings = settings::first();

            if ($settings) {
                $finalTime = Carbon::now()->addDays($settings->ads_expire_duration);
            } else {
                return ["error"=>'Something wrong! settings not found'];
            }

            $ids = [];



            foreach($data as $d){

                foreach($d as $key => $value){
                      if(empty($value)){
                        $d[$key] = null;
                      }
                }
                $d = (object)$d;



                $ids [] = $d->id;
                if(
                    isset($d->id) &&
                    isset($d->titre) &&
                    isset($d->surface) &&
                    isset($d->description) &&
                    isset($d->type) &&
                    isset($d->type_de_bien) &&
                    isset($d->prix) &&
                    isset($d->prix_devise) &&
                    isset($d->ville) &&
                    isset($d->quartier)
                ){
                    $id = $d->id;
                    $catid = $this->getCatId($d->type,$d->type_de_bien);
                    $city = $this->getCity($d->ville);
                    $neighborhood = $this->getNeighborhood($d->quartier);

                    $user = User::find($user_id);

                    if(!$user){
                        $logdate = date('Y-m-d h:i:s');
                        error_log("[$logdate] --> $user_id :: $d->titre . ' / ' . $id User not found!\n",3,'debug.log');
                    }
                    else if(!$catid){
                        $logdate = date('Y-m-d h:i:s');
                        error_log("[$logdate] --> $user_id :: $d->titre . ' / ' . $id Invalid Category!\n",3,'debug.log');
                    }
                    else if(!$city){
                        $logdate = date('Y-m-d h:i:s');
                        error_log("[$logdate] --> $user_id :: $d->titre . ' / ' . $id Invalid City!\n",3,'debug.log');
                    }
                    else{
                        $checkadifexist = ads::where('rssId','=',$id)->where('id_user','=',$user_id)->first();
                        $is_modification = false;
                        if($checkadifexist){
                            $ads = ads::find($checkadifexist->id);
                            $is_modification = true;
                        }
                        else
                            $ads = new ads();

                        $ads->rssId = $id;
                        $ads->id_user = $user_id;
                        $ads->title = $d->titre;
                        $ads->description = $d->description;
                        $ads->price = str_replace(' ','',$d->prix);
                        $ads->price_curr = $d->prix_devise;
                        $ads->surface = $d->surface;
                        $ads->catid = $catid;
                        $ads->loccity = $city;
                        $ads->locdept = $neighborhood;
                        $ads->locdept2 = $neighborhood ? null : $d->quartier;
                        $ads->ref = $d->reference??null;
                        $ads->loclat = $d->latitude??null;
                        $ads->loclng = $d->longitude??null;
                        $ads->rooms = $d->pieces??null;
                        $ads->bathrooms = $d->salle_de_bain??null;
                        $ads->bedrooms = $d->chambres??null;
                        $ads->etage = $d->etage??null;
                        $ads->built_year = $d->date_de_construction??null;
                        $ads->parking = isset($d->parking)&&$d->parking=="oui"?true:false;
                        $ads->jardin = isset($d->jardin)&&$d->jardin=="oui"?true:false;
                        $ads->piscine = isset($d->piscine)&&$d->piscine=="oui"?true:false;
                        $ads->meuble = isset($d->meuble)&&$d->meuble=="oui"?true:false;
                        $ads->terrace = isset($d->terrace)&&$d->terrace=="oui"?true:false;
                        $ads->climatise = isset($d->climatise)&&$d->climatise=="oui"?true:false;
                        $ads->syndic = isset($d->syndic)&&$d->syndic=="oui"?true:false;
                        $ads->cave = isset($d->cave)&&$d->cave=="oui"?true:false;
                        $ads->ascenseur = isset($d->ascenseur)&&$d->ascenseur=="oui"?true:false;
                        $ads->securite = isset($d->securite)&&$d->securite=="oui"?true:false;
                        $ads->expiredate = $finalTime;
                        $ads->phone = -1;
                        $ads->email = -1;
                        $ads->status = "10";

                        $checkad = $ads->save();

                        if(!$checkad){
                            $logdate = date('Y-m-d h:i:s');
                            error_log("[$logdate] --> $user_id :: $d->titre . ' / ' . $id operation failed on add ad!\n",3,'debug.log');
                        }
                        else{
                            $logdate = date('Y-m-d h:i:s');
                            if($is_modification)
                                error_log("[$logdate] --> $user_id :: $d->titre . ' / ' . $id succefully updated!\n",3,'debug.log');
                            else
                                error_log("[$logdate] --> $user_id :: $d->titre . ' / ' . $id succefully added!\n",3,'debug.log');


                            if (isset($d->photos['photo']) && is_array($d->photos['photo']) && !$is_modification ) {


                                $i=0;

                                foreach ($d->photos['photo'] as $imgkey => $img) {
                                    $imgname=trim($img);
                                    if($this->get_http_response_code($imgname) && $this->validImage($imgname)){
                                        $contents = file_get_contents($imgname);
                                        $name = 'img_' . uniqid();
                                        $path = '/flux/';
                                        $ext = File::extension($imgname);
                                        $filename = '/public' . $path . $name . '.' . $ext;
                                        Storage::put($filename, $contents);
                                        $img = Image::make(public_path('storage/' . $path . $name . '.' . $ext))->resize(340, 230, function ($constraint) {
                                            $constraint->aspectRatio();
                                        });
                                        $path_tn = public_path('storage/flux/tn_' . $name .'.'. $ext);
                                        $img->save($path_tn);
                                        // resize image (for display it whene we try to load the main image) to 50x50
                                        $img = Image::make(public_path('storage/' . $path . $name . '.' . $ext))->resize(50, 50, function ($constraint) {
                                            $constraint->aspectRatio();
                                        });
                                        $path_vs = public_path('storage/flux/vs_' . $name .'.'. $ext);
                                        $img->save($path_vs);

                                        $media = new media();
                                        $media->path = $path;
                                        $media->filename = $name;
                                        $media->filesize = 0;
                                        $media->extension = $ext;
                                        $media->media_type = 1;
                                        $check = $media->save();
                                        if ($check) {
                                            $mediaad = new ad_media();
                                            $mediaad->ad_id = $ads->id;
                                            $mediaad->media_id = $media->id;
                                            $mediaad->order = $i++;
                                            $mediaad->save();
                                        }
                                    }
                                }
                            } elseif (is_array($d->photos)&&!$is_modification) {
                                $i=0;
                                foreach ($d->photos as $imgkey => $img) {
                                    if (isset($img['photo'])) {
                                        $imgname=trim($img);
                                        if($this->get_http_response_code($imgname) && $this->validImage($imgname)){
                                            $contents = file_get_contents($imgname);
                                            $name = 'img_' . uniqid();
                                            $path = '/flux/';
                                            $ext = File::extension($imgname);
                                            $filename = '/public' . $path . $name . '.' . $ext;
                                            Storage::put($filename, $contents);
                                            $img = Image::make(public_path('storage/' . $path . $name . '.' . $ext))->resize(340, 230, function ($constraint) {
                                                $constraint->aspectRatio();
                                            });
                                            $path_tn = public_path('storage/flux/tn_' . $name .'.'. $ext);
                                            $img->save($path_tn);
                                            // resize image (for display it whene we try to load the main image) to 50x50
                                            $img = Image::make(public_path('storage/' . $path . $name . '.' . $ext))->resize(50, 50, function ($constraint) {
                                                $constraint->aspectRatio();
                                            });
                                            $path_vs = public_path('storage/flux/vs_' . $name .'.'. $ext);
                                            $img->save($path_vs);
                                            $media = new media();
                                            $media->path = $path;
                                            $media->filename = $name;
                                            $media->filesize = 0;
                                            $media->extension = $ext;
                                            $media->media_type = 1;
                                            $check = $media->save();
                                            if ($check) {
                                                $media = new ad_media();
                                                $media->ad_id = $ads->id;
                                                $media->media_id = $media->id;
                                                $media->order = $i++;
                                                $media->save();
                                            }
                                        }
                                    }
                                }
                            }
                        }
                    }
                }
                else{
                    $logdate = date('Y-m-d h:i:s');
                    error_log("[$logdate] --> $user_id :: Invalid properties\n",3,'debug.log');
                }
            }
            ads::where('id_user','=',$user_id)->whereNotNull('rssId')->whereNotIn('rssId', $ids)->delete();
            return "done!";
        }
        catch(\Throwable $th){
            $logdate = date('Y-m-d h:i:s');
            error_log("[$logdate] --> $user_id :: ".$th->getMessage()."\n",3,'debug.log');
            return ["error"=>$th->getTrace()];
        }

    }

    private function getCatId($type,$property_type){
        $property_type = strtolower($property_type);
        $type = strtolower($type);

        $cat = cats::select('id')->where('type','like',$type)->where('property_type','like',$property_type)->first();
        if($cat){
            return $cat->id;
        }
        return null;
    }

    private function getCity($city){
        $city = strtolower($city);

        $data = cities::select('id')->where('name','like',$city)->first();
        if($data){
            return $data->id;
        }
        return null;
    }

    private function getNeighborhood($neighborhood){
        $neighborhood = strtolower($neighborhood);

        $data = neighborhoods::select('id')->where('name','like',$neighborhood)->first();
        if($data){
            return $data->id;
        }
        return null;
    }

    private function validImage($file) {
        $size = getimagesize($file);
        if(gettype($size['mime'])=="string")
        return (strtolower(substr($size['mime'], 0, 5)) == 'image' ? true : false);
        else return false;
     }

     private function get_http_response_code($url){
        $headers = get_headers($url);
        if($headers && (strpos( $headers[0], '404')||strpos( $headers[0], '400'))) return false;
        else return true;
     }



}
