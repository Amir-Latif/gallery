<?php

namespace App\Http\Controllers;

use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Routing\Controller as BaseController;
use Illuminate\Http\Request;
use App\Models\Album;
use App\Models\Picture;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Storage;

class GalleryController extends BaseController
{
    use ValidatesRequests;

    public function get_album_names()
    {
        $names = Album::pluck('name')->toArray();
        sort($names);
        return $names;
    }

    public function create_album(Request $request)
    {
        // Validate the request data
        $request->validate(Album::$rules);
        // Create a new Picture model and save it to the database
        $album = new Album([
            'name' => $request->name,
        ]);

        try {
            $album->save();
            return ['status' => 'Album created successfully'];
        } catch (\Throwable $th) {
            return ['status' => strpos($th->getMessage(), "Integrity constraint violation") == true ? "Duplicate album name" : ""];
        }
    }

    // uplaod pictures
    public function upload_file(Request $request)
    {
        $files = $request->allFiles();

        if (empty($files)) {
            abort(422, 'No files were uploaded.');
        }

        // if (count($files) > 1) {
        //     abort(422, 'Only 1 file can be uploaded at a time.');
        // }

        $requestKey = array_key_first($files);
        $file = is_array($request->input($requestKey))
            ? $request->file($requestKey)[0]
            : $request->file($requestKey);

        return $file->store(
            path: 'tmp/' . now()->timestamp . '-' . Str::random(20)
        );
    }
    // rettract pictures
    public function retract_file(Request $request)
    {
        $path_arr = explode("/", $request->getContent());
        $directory = $path_arr[0] . "/" . $path_arr[1];

        if (Storage::exists($directory)) {
            Storage::deleteDirectory($directory);
            return 'Directory deleted successfully';
        } else {
            return 'Directory not found';
        }
    }

    // upload picture to album 
    public function submit_files(Request $request)
    {
        // Validate the request data
        if (!$request->name) abort(400,  "Incorrect Album name");
        $album_id = Album::where("name", $request->name)->first()->id;
        if (!$album_id) abort(400,  "Incorrect Album name");
        $request->validate(Picture::$rules);

        // move directory from tmp to media library
        $file_arr = explode(".", $request->picture);
        $file_name = now()->timestamp . '-' . substr($file_arr[0], strlen($file_arr[0]) - 10) . '.' . $file_arr[1];
        // add picture record
        $picture = new Picture([
            'name' => $file_name,
            'album_id' => $album_id
        ]);

        $picture->save();
        $picture->addMedia(str_replace("/", "\\", storage_path("app/" . $request->picture)))->usingFileName($file_name)->toMediaCollection('pictures');

        // clear tmp
        $directories = Storage::directories("tmp");
        foreach ($directories as $directory) {
            Storage::deleteDirectory($directory);
        }

        return "pictures uploaded successfully";
    }

    //edit album
    public function edit_album(Request $request)
    {
        // Validate the request data
        $request->validate([
            'old_name' => 'required|string|max:255',
            'new_name' => 'required|string|max:255',
        ]);
        $album = Album::where('name', $request->old_name)->first();

        if ($album) {
            $album->name = $request->new_name;
            $album->save();

            return ['message' => 'Album name edited successfully', "status" => "success"];
        } else {
            return ['message' => 'Album not found', "status" => "failure"];
        }
    }

    //delete album
    public function delete_album(Request $request)
    {
        // Validate the request data
        $request->validate([
            'name' => 'required|string|max:255',
        ]);
        // Create a new Picture model and save it to the database
        $album = Album::where('name', $request->input('name'))->first();
        if (!$album)
            abort(405, 'Album not found');
        else if ($album && $album->pictures->isEmpty()) {

            $album->delete();
            return 'Album deleted successfully';
        } else {
            abort(405, 'Album has pictures/' . $request->name);
        }
    }

    //delete album options
    public function delete_album_options(Request $request)
    {
        // Validate the request data
        $request->validate([
            'option' => 'required|string|max:255',
        ]);

        if ($request->old_album_name === $request->new_album && $request->option === "move")
            abort(405, "New Album cannot be the same as the old album");

        $album = Album::where('name', $request->old_album_name)->first();

        if ($album && $request->option === "delete") {
            $album->delete();
            return 'Album deleted successfully';
        } else {
            $new_album = Album::where('name', $request->new_album)->first();
            $pictures = Picture::Where("album_id", $album->id)->get();
            foreach ($pictures as $picture) {
                $picture->album_id = $new_album->id;
                $picture->save();
            }
            $album->delete();
            return 'Pictures moved and album deleted';
        }
    }
}
