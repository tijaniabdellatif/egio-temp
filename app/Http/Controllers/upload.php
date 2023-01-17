<?php

namespace App\Http\Controllers;

use Intervention\Image\ImageManagerStatic as Image;
use Illuminate\Http\Request;
use App\Models\media;

class upload extends Controller
{



    public function uploadImage(Request $request){

        $this->validate($request, [
            'image'=>'mimes:jpeg,png,webp,ico,JPG,JPEG,PNG,WEBP,ICO',
            $messages = [
                'mimes' => 'Only jpeg, png, webp, ico are allowed.'
            ]
        ]);


        if ($request->hasFile('image')) {
            $image = $request->file('image');
            $name = 'img_' . uniqid();
            $path = '/images/';
            $ext = $image->extension();
            $size = $image->getSize();
            $filename = $path . $name . '.' . $ext;
            $image->storePubliclyAs('public', $filename);

            $media = new media();
            $media->path = $path;
            $media->filename = $name;
            $media->filesize = $size;
            $media->extension = $ext;
            $media->media_type = 1;
            $check = $media->save();

            if (!$check) {
                return ['success' => false , 'msg' => 'Operation faild'];
            } else {
                return["success"=>true,"name"=>$filename,"id"=>$media->id];
            }
        } else {
            return["success"=>false];
        }
    }

    public function uploadImages(Request $request){


        $this->validate($request, [
            'images'=>'required|array',
            'images.*'=>'mimes:jpg,jpeg,png,webp,ico,JPG,JPEG,PNG,WEBP,ICO'
        ],[
            'images.*.mimes' => "Les extentions d'images allouées sont : jpg, jpeg, png, webp, ico"
        ]);

        if ($request->hasFile('images')) {
            $images = $request->file('images');
            $path = '/images/';
            $img_objs = [];
            $files = [];

            foreach ($images as $image) {
                $name = 'img_' . uniqid();
                $ext = $image->extension();
                $size = $image->getSize();
                $filename = $path . $name . '.' . $ext;

                $image->storePubliclyAs('public', $filename);

                $media = new media();
                $media->path = $path;
                $media->filename = $name;
                $media->filesize = $size;
                $media->extension = $ext;
                $media->media_type = 1;
                $check = $media->save();

                if (!$check) {
                    return ['success' => false , 'msg' => 'Operation faild'];
                } else {
                    array_push($img_objs, [
                        "id"=>$media->id,
                        "name"=>$filename
                    ]);
                    array_push($files, [
                        "name"=>$name,
                        "size"=>$size,
                        "ext"=>$ext,
                        "filename"=>$filename,
                        "media"=>$media
                    ]);
                }
            }

            $allowed_ext_to_resize = ['jpg', 'jpeg', 'png', 'webp'];


            // resize images and add them to sub folder
            foreach ($files as $file) {

                // if image is not allowed to resize skip it
                if (!in_array(strtolower($file['ext']), $allowed_ext_to_resize)) {
                    continue;
                }

                $img = Image::make(public_path('storage/' . $file['filename']))->resize(340, 230, function ($constraint) {
                    $constraint->aspectRatio();
                });

                // dd(1);

                $path_tn = public_path('storage/images/tn_' . $file['name'] .'.'. $file['ext']);

                $img->save($path_tn);

                // resize image (for display it whene we try to load the main image) to 50x50
                $img = Image::make(public_path('storage/' . $file['filename']))->resize(50, 50, function ($constraint) {
                    $constraint->aspectRatio();
                });

                $path_vs = public_path('storage/images/vs_' . $file['name'] .'.'. $file['ext']);
                $img->save($path_vs);

            }

            return["success"=>true,"images"=>$img_objs];
        } else {
            return["success"=>false];
        }
    }

    public function uploadVideo(Request $request)
    {
        $this->validate($request, [
            'video'=>'mimes:mp4,mov,ogg',
            $messages = [
                'mimes' => 'Only mp4, mov, ogg are allowed.'
            ]
        ]);
        if ($request->hasFile('video')) {
            $video = $request->file('video');
            $name = 'vid_' . uniqid();
            $path = '/videos/';
            $ext = $video->extension();
            $size = $video->getSize();
            $filename = $path . $name . '.' . $ext;
            $video->storePubliclyAs('public', $filename);

            $media = new media();
            $media->path = $path;
            $media->filename = $name;
            $media->filesize = $size;
            $media->extension = $ext;
            $media->media_type = 2;
            $check = $media->save();

            if (!$check) {
                return ['success' => false , 'msg' => 'Operation faild'];
            } else {
                return["success"=>true,"name"=>$filename,"id"=>$media->id];
            }
        } else {
            return["success"=>false];
        }
    }

    public function uploadVideos(Request $request)
    {
        $this->validate($request, [
            'videos'=>'required|array',
            'videos.*'=>'mimes:mp4,mov,ogg',
            $messages = [
                'mimes' => 'Only mp4, mov, ogg are allowed.'
            ]
        ]);

        if ($request->hasFile('videos')) {
            $videos = $request->file('videos');
            $path = '/videos/';
            $media_objs = [];

            foreach ($videos as $video) {
                $name = 'vid_' . uniqid();
                $ext = $video->extension();
                $size = $video->getSize();
                $filename = $path . $name . '.' . $ext;
                $video->storePubliclyAs('public', $filename);

                $media = new media();
                $media->path = $path;
                $media->filename = $name;
                $media->filesize = $size;
                $media->extension = $ext;
                $media->media_type = 2;
                $check = $media->save();

                if (!$check) {
                    return ['success' => false , 'msg' => 'Operation faild'];
                } else {
                    array_push($media_objs, [
                        "id"=>$media->id,
                        "name"=>$filename
                    ]);
                }
            }

            return["success"=>true,"videos"=>$media_objs];
        } else {
            return["success"=>false];
        }
    }

    public function uploadAudio(Request $request)
    {
        $this->validate($request, [
            'audio'=>'mimes:audio/mpeg,mpga,mp3,wav,aac',
            $messages = [
                'mimes' => 'Only mpeg, mpga, mp3, wav, aac are allowed.'
            ]
        ]);
        if ($request->hasFile('audio')) {
            $audio = $request->file('audio');
            $name = 'aud' . uniqid();
            $path = '/audios/';
            $ext = $audio->extension();
            $size = $audio->getSize();
            $filename = $path . $name . '.' . $ext;
            $audio->storePubliclyAs('public', $filename);

            $media = new media();
            $media->path = $path;
            $media->filename = $name;
            $media->filesize = $size;
            $media->extension = $ext;
            $media->media_type = 3;
            $check = $media->save();

            if (!$check) {
                return ['success' => false , 'msg' => 'Operation faild'];
            } else {
                return["success"=>true,"name"=>$filename,"id"=>$media->id];
            }
        } else {
            return["success"=>false];
        }
    }

    public function uploadAudios(Request $request)
    {
        $this->validate($request, [
            'audios'=>'required|array',
            'audios.*'=>'mimes:audio/mpeg,mpga,mp3,wav,aac',
            $messages = [
                'mimes' => 'Only mpeg, mpga, mp3, wav, aac are allowed.'
            ]
        ]);

        if ($request->hasFile('audios')) {
            $audios = $request->file('audios');
            $path = '/audios/';
            $img_objs = [];

            foreach ($audios as $audio) {
                $name = 'aud_' . uniqid();
                $ext = $audio->extension();
                $size = $audio->getSize();
                $filename = $path . $name . '.' . $ext;
                $audio->storePubliclyAs('public', $filename);

                $media = new media();
                $media->path = $path;
                $media->filename = $name;
                $media->filesize = $size;
                $media->extension = $ext;
                $media->media_type = 3;
                $check = $media->save();

                if (!$check) {
                    return ['success' => false , 'msg' => 'Operation faild'];
                } else {
                    array_push($img_objs, [
                        "id"=>$media->id,
                        "name"=>$filename
                    ]);
                }
            }

            return["success"=>true,"audios"=>$img_objs];
        } else {
            return["success"=>false];
        }
    }

}
