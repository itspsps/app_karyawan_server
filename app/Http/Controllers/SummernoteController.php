<?php

namespace App\Http\Controllers;

use App\Models\FileModel;
use Illuminate\Support\Str;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class SummernoteController extends Controller
{
    public function upload(Request $request)
    {
        $nama_file = Str::replace('assets/pg_image/', '', $request->file('image')->store('assets/pg_image'));
        echo asset('storage/assets/pg_image/' . $nama_file);
    }

    public function delete(Request $request)
    {
        $array = explode('/', $request->src);
        $nama_file = $array[count($array) - 1];
        // dd($nama_file);

        Storage::delete('assets/pg_image/' . $nama_file);
        echo "berhasil di hapus";
    }

    // public function delete_file(Request $request)
    // {
    //     FileModel::where('nama', $request->src)
    //         ->delete();
    //     Storage::delete('assets/pg_image/' . $request->src);
    // }

    // public function unduh($file)
    // {
    //     return Storage::download('assets/pg_image/' . $file);
    // }
}
