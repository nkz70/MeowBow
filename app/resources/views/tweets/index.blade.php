@extends('layouts.app')

@section('content')
  <div class="container">
    <div class="row justify-content-center">
      <div class="col-md-8 mb-3 text-right">
        <a href="{{ url('users') }}">ユーザー一覧<i class="fas fa-users"></i></a>
      </div>
      @if (isset($timelines))
        @foreach ($timelines as $timeline)
          <div class="col-md-8 mb-3">
            <div class="card">
              <div class="card-header p-3 w-100 d-flex">
                <img src="{{ asset('storage/profile_image/'. $timeline->user->profile_image) }}" class="rounded-circle" width="50" height="50">
                <div class="ml-2 d-flex flex-column">
                  <p class="mb-0">{{ $timeline->user->name }}</p>
                  <a href="{{ url('users/'.$timeline->user->id) }}" class="text-secondary">{{ $timeline->user->screen_name }}</a>
                </div>
                <div class="d-flex justify-content-end flex-grow-1">
                  <p class="mb-0 text-secondary">{{ $timeline->created_at->format('Y-m-d H:i') }}</p>
                </div>
              </div>
              <div class="card-body">
                @if ($timeline->posts_images)
                  @foreach ($timeline->posts_images as $image)
                    <img src="{{ asset('storage/'.$image->image_file) }}" height="300" width="300">
                  @endforeach
                @endif
                {!! nl2br(e($timeline->content)) !!}
              </div>
              <div class="card-footer py-1 d-flex justify-content-end bg-white">
                @if ($timeline->user->id === Auth::user()->id)
                  <div class="dropdown mr-3 d-flex align-items-center">
                    <a href="#" role="button" id="dropdownMenuLink" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                      <i class="fas fa-ellipsis-v fa-fw"></i>
                    </a>
                    <div class="dropdown-menu" aria-labelledby="dropdownMenuLink">
                      <form action="{{ route('tweets.destroy', ['tweet' => $timeline]) }}" method="POST" class="mb-0" onClick="confirm('ツイートを削除してよろしいでしょうか？')">
                        @csrf
                        @method('DELETE')

                        <a href="{{ url('tweets/'.$timeline->id.'/edit') }}" class="dropdown-item">編集</a>
                        <button type="submit" class="dropdown-item del-btn">削除</button>
                      </form>
                    </div>
                  </div>
                @endif
                <div class="mr-3 d-flex align-items-center">
                  <a href="{{ url('tweets/'.$timeline->id) }}"><i class="far fa-comment fa-fw"></i></a>
                  <p class="mb-0 text-secondary">{{ count($timeline->comments) }}</p>
                </div>

                <div class="d-flex align-items-center">
                  @if (!in_array($user->id, array_column($timeline->favorites->toArray(), 'user_id'), TRUE))
                    <form action="{{ url('favorites/') }}" method="POST" class="mb-0">
                      @csrf

                      <input type="hidden" name="tweet_id" value="{{ $timeline->id }}">
                      <button type="submit" class="btn p-0 border-0 text-primary"><i class="far fa-heart fa-fw"></i></button>
                    </form>
                  @else
                    <form action="{{ url('favorites/'.array_column($timeline->favorites->toArray(), 'id', 'user_id')[$user->id]) }}" method="POST" class="mb-0">
                      @csrf
                      @method('DELETE')

                      <button type="submit" class="btn p-0 border-0 text-danger"><i class="fas fa-heart fa-fw"></i></button>
                  </form>
                  @endif
                  <p class="mb-0 text-secondary">{{ count($timeline->favorites) }}</p>
                </div>
              </div>
            </div>
          </div>
        @endforeach
      @endif
    </div>
    <div class="my-4 d-flex justify-content-center">
      {{ $timelines->links() }}
    </div>
  </div>
@endsection
