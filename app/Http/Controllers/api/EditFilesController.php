<?php

namespace App\Http\Controllers\api;

use App\Http\Controllers\Api\ApiController;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class EditFilesController extends ApiController
{

    private $allowedPaths = ["resources/views/emails","resources/views/pages"];

    // get file from current directory
    public function getFile(Request $request)
    {

        // check if path is exists in request validation
        $validator = Validator::make($request->all(), [
            'path' => 'required',
        ]);

        if ($validator->fails()) {
            // return response()->json(['error' => $validator->errors()], 400);
            return $this->errorResponse($validator->errors(), 400);
        }

        $path = $request->path;

        // replace all separator types by DIRECTORY_SEPARATOR
        $path = str_replace('/', DIRECTORY_SEPARATOR, $path);

        $path = base_path() . DIRECTORY_SEPARATOR . $path;

        if (file_exists($path)) {

            // get file text
            $text = file_get_contents($path);

            // return text response
            // return response()->json([
            //     'success' => true,
            //     'data' => $text
            // ]);
            return $this->showAny($text);
        }
        return $this->errorResponse("file not found",404);
    }

    // save file to current directory
    public function postFile(Request $request)
    {

        // check if path is exists in request validation
        $validator = Validator::make($request->all(), [
            'path' => 'required',
            'text' => 'required',
        ]);

        if ($validator->fails()) {
            // return response()->json(['error' => $validator->errors()], 400);
            return $this->errorResponse($validator->errors(), 400);
        }

        $path = $request->path;

        // replace all separator types by DIRECTORY_SEPARATOR
        $path = str_replace('/', DIRECTORY_SEPARATOR, $path);

        $full_path = base_path() . DIRECTORY_SEPARATOR . $path;

        // check if file exists
        if (file_exists($full_path)) {

            // get previous file
            $text = file_get_contents($full_path);

            // get file extension
            $extension = pathinfo($path, PATHINFO_EXTENSION);

            // add timestamp to file name
            $backup_path = base_path() . DIRECTORY_SEPARATOR . 'storage' . DIRECTORY_SEPARATOR . 'buckupfiles' .  DIRECTORY_SEPARATOR  . str_replace('.'.$extension, '_' . time() . '.'.$extension, $path);

            // create folder if it doesn't exist
            if (!file_exists(dirname($backup_path))) {
                mkdir(dirname($backup_path), 0777, true);
            }

            // save backup file to /storage/buckupfiles
            file_put_contents($backup_path, $text);

            // create folder if it doesn't exist
            if (!file_exists(dirname($full_path))) {
                mkdir(dirname($full_path), 0777, true);
            }

            // save file
            file_put_contents($full_path, $request->text);

            // return success message
            // return response()->json([
            //     'success' => true,
            //     'data' => $request->text
            // ]);
            return $this->showAny($request->text);
        }
        else {

            // create folder if it doesn't exist
            if (!file_exists(dirname($full_path))) {
                mkdir(dirname($full_path), 0777, true);
            }

            // create the file
            file_put_contents($full_path, $request->text);

            // return success message
            // return response()->json([
            //     'success' => true,
            //     'data' => $request->text
            // ]);
            return $this->showAny($request->text);
        }
    }

    // rollback to previous version of the file
    public function rollbackFile(Request $request)
    {

        // check if path is exists in request validation
        $validator = Validator::make($request->all(), [
            'path' => 'required',
        ]);

        if ($validator->fails()) {
            // return response()->json(['error' => $validator->errors()], 400);
            $this->errorResponse($validator->errors(), 400);
        }

        $path = $request->path;

        // replace all separator types by DIRECTORY_SEPARATOR
        $path = str_replace('/', DIRECTORY_SEPARATOR, $path);

        // buckupfiles path
        $backup_path = base_path() . DIRECTORY_SEPARATOR . 'storage' . DIRECTORY_SEPARATOR . 'buckupfiles' .  DIRECTORY_SEPARATOR . $path;

        // buckup file path format : C:\Users\zadelkhair\Desktop\hack\multilist\multilist-back\storage\buckupfiles\pathtofile\filename_[time].extension

        // get file path without extension
        $path_without_extension = str_replace('.' . pathinfo($path, PATHINFO_EXTENSION), '', $backup_path);

        // get directory path
        $directory_path = dirname($path_without_extension);

        // if the directory not exists return false
        if (!file_exists($directory_path)) {
            // return response()->json([
            //     'success' => false,
            //     'message' => 'File not found'
            // ], 404);
            return $this->errorResponse('File not found', 404);
        }

        // get all file path in directory
        $files = glob($directory_path . DIRECTORY_SEPARATOR . '*');

        // if files is empty
        if (empty($files)) {
            // return response()->json([
            //     'success' => false,
            //     'message' => 'No backup files found'
            // ]);
            return $this->errorResponse('No backup files found', 404);
        }

        // get latest file
        $latest_file = end($files);

        // get file content
        $text = file_get_contents($latest_file);

        // get full_path
        $full_path = base_path() . DIRECTORY_SEPARATOR . $path;

        // create or replace file
        file_put_contents($full_path, $text);

        // remove the latest file
        // unlink($latest_file);

        // return success message
        // return response()->json([
        //     'success' => true,
        //     'data' => $text
        // ]);
        return $this->showAny($text);

    }

}
