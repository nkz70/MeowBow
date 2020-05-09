@extends('layouts.app')

@section('content')
  <div class="container">
    <div class="row justify-content-center">
      <div class="col-md-8">
        <div class="card">
          <div class="card-header">投稿</div>

          <div class="card-body">
            <form action="{{ route('tweets.store') }}" method="POST" enctype="multipart/form-data">
              @csrf

              <div class="form-group row mb-0">
                <div class="col-md-12 p-3 w-100 d-flex">
                  <img src="{{ asset('storage/profile_image/'.$user->profile_image) }}" class="rounded-circle" width="50" height="50">
                  <div class="ml-2 d-flex flex-column">
                    <p class="mb-0">{{ $user->name }}</p>
                    <a href="{{ url('users/'.$user->id) }}" class="text-secondary">{{ $user->screen_name }}</a>
                  </div>
                </div>
                <div class="col-md-12">
                  <textarea class="form-control @error('content') is-invalid @enderror" name="content"  required autocomplete="content" row="4">{{ old('content') }}</textarea>

                  @error ('content')
                    <span class="invalid-feedback" role="alert">
                      <strong>{{ $message }}</strong>
                    </span>
                  @enderror
                </div>
              </div>

              <div class="form-group row mb-0">
                <input type="file" name="image_file">
                <div class="col-md-12 text-right">
                  <p class="mb-4 text-danger">140文字以内</p>
                  <button type="submit" class="btn btn-primary">
                    ツイートする
                  </button>
                </div>
              </div>
            </form>
          </div>
        </div>
      </div>
    </div>
  </div>
@endsection
