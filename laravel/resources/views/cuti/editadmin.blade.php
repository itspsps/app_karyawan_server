@extends('layouts.dashboard')
@section('isi')
    <div class="container-fluid">
        <div class="card card-outline card-primary col-lg-12">
            <div class="mt-4 p-3">
                <form method="post" action="{{ url('/data-cuti/edit-proses/'.$data_cuti_karyawan->id) }}">
                    @method('put')
                    @csrf
                    <div class="form-row">
                        <div class="col">
                            <label for="user_id">Nama karyawan</label>
                            <input type="text" disabled class="form-control" value="{{ $data_cuti_karyawan->User->name }}" id="user_id">
                        </div>
                        <div class="col">
                            <label for="nama_cuti">Nama Cuti</label>
                            <input type="text" class="form-control" value="{{ $data_cuti_karyawan->nama_cuti }}" id="nama_cuti" disabled>
                            <input type="hidden" name="nama_cuti" value="{{ $data_cuti_karyawan->nama_cuti }}">
                        </div>
                    </div>
                    <br>
                    <div class="form-row">
                        <div class="col">
                            <label for="tanggal">Tanggal</label>
                            <input type="datetime" class="form-control @error('tanggal') is-invalid @enderror" name="tanggal" id="tanggal" value="{{ $data_cuti_karyawan->tanggal }}">
                            @error('tanggal')
                            <div class="invalid-feedback">
                                {{ $message }}
                            </div>
                            @enderror
                        </div>
                        <div class="col">
                            <label for="alasan_cuti">Alasan Cuti</label>
                            <input type="text" disabled class="form-control" value="{{ $data_cuti_karyawan->alasan_cuti }}">
                        </div>
                    </div>
                    <br>
                    <div class="form-row">
                        @php
                            $status_cuti = array(
                            [
                                "status_cuti" => "Pending"
                            ],
                            [
                                "status_cuti" => "Ditolak"
                            ],
                            [
                                "status_cuti" => "Diterima"
                            ]);
                        @endphp
                        <div class="col">
                            <label for="status_cuti">Status Cuti</label>
                            <select name="status_cuti" class="form-control @error('status_cuti') is-invalid @enderror selectpicker" data-live-search="true" id="status_cuti">
                                @foreach ($status_cuti as $sc)
                                    @if(old('status_cuti', $data_cuti_karyawan->status_cuti) == $sc["status_cuti"])
                                        <option value="{{ $sc["status_cuti"] }}" selected>{{ $sc["status_cuti"] }}</option>
                                    @else
                                        <option value="{{ $sc["status_cuti"] }}">{{ $sc["status_cuti"] }}</option>
                                    @endif
                                @endforeach
                            </select>
                            @error('status_cuti')
                            <div class="invalid-feedback">
                                {{ $message }}
                            </div>
                            @enderror
                        </div>
                        <div class="col">
                            <label for="catatan">Catatan</label>
                            <input type="text" class="form-control @error('catatan') is-invalid @enderror" name="catatan" id="catatan" value="{{ old('catatan', $data_cuti_karyawan->catatan) }}">
                            @error('catatan')
                            <div class="invalid-feedback">
                                {{ $message }}
                            </div>
                            @enderror
                        </div>
                    </div>
                    <br>
                    <input type="hidden" name="jam_absen">
                    <input type="hidden" name="telat">
                    <input type="hidden" name="lat_absen">
                    <input type="hidden" name="long_absen">
                    <input type="hidden" name="jarak_masuk">
                    <input type="hidden" name="foto_jam_absen">
                    <input type="hidden" name="jam_pulang">
                    <input type="hidden" name="pulang_cepat">
                    <input type="hidden" name="foto_jam_pulang">
                    <input type="hidden" name="foto_jam_pulang">
                    <input type="hidden" name="lat_pulang">
                    <input type="hidden" name="long_pulang">
                    <input type="hidden" name="jarak_pulang">
                    <input type="hidden" name="status_absen">
                    <input type="hidden" name="cuti_dadakan">
                    <input type="hidden" name="cuti_bersama">
                    <input type="hidden" name="cuti_menikah">
                    <input type="hidden" name="cuti_diluar_tanggungan">
                    <input type="hidden" name="cuti_khusus">
                    <input type="hidden" name="cuti_melahirkan">
                    <input type="hidden" name="izin_telat">
                    <input type="hidden" name="izin_pulang_cepat">
                    <button type="submit" class="btn btn-primary">Submit</button>
                  </form>
            </div>
        </div>
    </div>
    <br>
@endsection
