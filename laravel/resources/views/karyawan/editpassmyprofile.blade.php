@extends('layouts.dashboard')
@section('isi')
    <center>
        <div class="containe-fluid">
            <div class="card card-outline card-primary col-lg-5">
                <div class="mt-4 p-3">
                    <form method="post" action="{{ url('/my-profile/edit-password-proses/'.auth()->user()->id) }}">
                        @method('put')
                        @csrf
                            <div class="form-group">
                                <label for="name" class="float-left">Nama</label>
                                <input type="text" class="form-control" value="{{ auth()->user()->name }}" disabled id="name">
                            </div>
                            <div class="form-group">
                                <label for="password" class="float-left">Password</label>
                                <input type="password" class="form-control @error('password') is-invalid @enderror" id="password" name="password">
                                @error('password')
                                <div class="invalid-feedback">
                                    {{ $message }}
                                </div>
                                @enderror
                            </div>
                            <div class="form-group">
                                <label for="password_confirmation" class="float-left">Password Confirmation</label>
                                <input type="password" class="form-control @error('password_confirmation') is-invalid @enderror" id="password_confirmation" name="password_confirmation">
                                @error('password_confirmation')
                                <div class="invalid-feedback">
                                    {{ $message }}
                                </div>
                                @enderror
                            </div>
                        <button type="submit" class="btn btn-primary float-right">Submit</button>
                      </form>
                </div>
            </div>
            <br>
        </div>
    </center>
    <br>
@endsection
