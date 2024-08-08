<?php

namespace App\Http\Controllers;

use App\Mail\Email;
use App\Models\Sip;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Storage;
use App\Models\ActivityLog;
use Illuminate\Support\Facades\Auth;

class DokumenController extends Controller
{
    public function index()
    {
        $holding = request()->segment(count(request()->segments()));
        return view('dokumen.index', [
            'title' => 'Data Dokumen Karyawan',
            'holding' => $holding,
            'data_dokumen' => Sip::all()
        ]);
    }

    public function tambah()
    {
        $holding = request()->segment(count(request()->segments()));
        return view('dokumen.tambah', [
            'title' => 'Tambah Data Dokumen',
            'holding' => $holding,
            'data_user' => User::all()
        ]);
    }

    public function tambahProses(Request $request)
    {
        $validatedData = $request->validate([
            'user_id' => 'required',
            'nama_dokumen' => 'required',
            'tanggal_berakhir' => 'required',
            'file' => 'mimes:doc,docx,pdf,xls,xlsx,ppt,pptx'
        ]);

        if ($request->file('file')) {
            $validatedData['file'] = $request->file('file')->store('file');
        }

        Sip::create($validatedData);
        ActivityLog::create([
            'user_id' => Auth::user()->id,
            'activity' => 'create',
            'description' => 'Menambahkan data dokumen karyawan dengan nama dokumen ' . $request->nama_dokumen
        ]);
        return redirect('/dokumen')->with('success', 'Dokumen Berhasil Ditambahkan');
    }

    public function edit($id)
    {
        return view('dokumen.edit', [
            'title' => "Edit Data Dokumen",
            'data_user' => User::all(),
            'data_dokumen' => Sip::findOrFail($id)
        ]);
    }

    public function editProses(Request $request, $id)
    {
        $validatedData = $request->validate([
            'user_id' => 'required',
            'nama_dokumen' => 'required',
            'tanggal_berakhir' => 'required',
            'file' => 'mimes:doc,docx,pdf,xls,xlsx,ppt,pptx'
        ]);

        if ($request->file('file')) {
            if ($request->file_lama) {
                Storage::delete($request->file_lama);
            }
            $validatedData['file'] = $request->file('file')->store('file');
        }

        Sip::where('id', $id)->update($validatedData);
        ActivityLog::create([
            'user_id' => Auth::user()->id,
            'activity' => 'edit',
            'description' => 'Mengedit data dokumen karyawan dengan nama dokumen ' . $request->nama_dokumen
        ]);
        return redirect('/dokumen')->with('success', 'Dokumen Berhasil Diupdate');
    }

    public function delete($id)
    {
        $dokumen = Sip::findOrFail($id);
        $dokumen->delete();
        Storage::delete($dokumen->file);
        ActivityLog::create([
            'user_id' => Auth::user()->id,
            'activity' => 'delete',
            'description' => 'Menghapus data dokumen karyawan dengan nama dokumen ' . $dokumen->nama_dokumen
        ]);
        return redirect('/dokumen')->with('success', 'Dokumen Berhasil Didelete');
    }

    public function myDokumen()
    {
        $holding = request()->segment(count(request()->segments()));
        return view('dokumen.mydokumen', [
            'title' => 'Data Dokumen Saya',
            'holding' => $holding,
            'data_dokumen' => Sip::where('user_id', auth()->user()->id)->get()
        ]);
    }

    public function myDokumenTambah()
    {
        $holding = request()->segment(count(request()->segments()));
        return view('dokumen.mydokumentambah', [
            'holding' => $holding,
            'title' => 'Tambah Data Dokumen'
        ]);
    }

    public function myDokumenTambahProses(Request $request)
    {
        $validatedData = $request->validate([
            'user_id' => 'required',
            'nama_dokumen' => 'required',
            'tanggal_berakhir' => 'required',
            'file' => 'mimes:doc,docx,pdf,xls,xlsx,ppt,pptx'
        ]);

        if ($request->file('file')) {
            $validatedData['file'] = $request->file('file')->store('file');
        }

        Sip::create($validatedData);
        ActivityLog::create([
            'user_id' => Auth::user()->id,
            'activity' => 'create',
            'description' => 'Menambahkan data dokumen karyawan dengan nama dokumen ' . $request->nama_dokumen
        ]);
        return redirect('/my-dokumen')->with('success', 'Dokumen Berhasil Ditambahkan');
    }

    public function myDokumenEdit($id)
    {
        return view('dokumen.mydokumenedit', [
            'title' => "Edit Data Dokumen",
            'data_dokumen' => Sip::findOrFail($id)
        ]);
    }
    public function myDokumenEditProses(Request $request, $id)
    {
        $validatedData = $request->validate([
            'nama_dokumen' => 'required',
            'tanggal_berakhir' => 'required',
            'file' => 'mimes:doc,docx,pdf,xls,xlsx,ppt,pptx'
        ]);

        if ($request->file('file')) {
            if ($request->file_lama) {
                Storage::delete($request->file_lama);
            }
            $validatedData['file'] = $request->file('file')->store('file');
        }

        Sip::where('id', $id)->update($validatedData);
        ActivityLog::create([
            'user_id' => Auth::user()->id,
            'activity' => 'edit',
            'description' => 'Mengedit data dokumen karyawan dengan nama dokumen ' . $request->nama_dokumen
        ]);
        return redirect('/my-dokumen')->with('success', 'Dokumen Berhasil Diupdate');
    }

    public function myDokumenDelete($id)
    {
        $dokumen = Sip::findOrFail($id);
        $dokumen->delete();
        Storage::delete($dokumen->file);
        ActivityLog::create([
            'user_id' => Auth::user()->id,
            'activity' => 'delete',
            'description' => 'Menghapus data dokumen karyawan dengan nama dokumen ' . $dokumen->nama_dokumen
        ]);
        return redirect('/my-dokumen')->with('success', 'Dokumen Berhasil Didelete');
    }
}
