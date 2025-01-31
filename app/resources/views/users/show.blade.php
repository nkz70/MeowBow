@extends('layouts.app')

@section('content')
  <div class="container">
    <div class="row justify-content-center">
      <div class="col-md-8 mb-3">
        <div class="card">
          <div class="d-inline-flex">
            <div class="p-3 d-flex flex-column">
              <img src="{{ asset('storage/profile_image/'.$user->profile_image) }}" class="rounded-circle" width="100" height="100">
              <div class="mt-3 d-flex flex-column">
                <h4 class="mb-0 font-weight-bold">{{ $user->name }}</h4>
                <span class="text-secondary">{{ $user->screen_name }}</span>
              </div>
            </div>
            <div class="p-3 d-flex flex-column justify-content-between">
              <div class="d-flex">
                <div>
                  @if ($user->id === Auth::user()->id)
                    <a href="{{ route('users.edit',['user' => $user]) }}" class="btn btn-primary">プロフィールを編集する</a>
                    <a href="{{ route('pets.create') }}" class="">ペットを追加する</a>
                  @else
                    @if ($is_following)
                      <form action="{{ route('unfollow',['user' => $user]) }}" method="POST" onClick="return confirm('フォローを解除しますか？')">
                        @csrf
                        @method('DELETE')

                        <button class="btn btn-primary">フォロー中</button>
                      </form>
                    @else
                      <form action="{{ route('follow', ['user' => $user]) }}" method="POST">
                        @csrf

                        <button class="btn btn-primary">フォローする</button>
                      </form>
                    @endif
                    @if ($is_followed)
                      <span class="mt-2 px-1 bg-secondary text-light">フォローされています</span>
                    @endif
                  @endif
                </div>
              </div>
              <div class="d-flex justify-content-end">
                <div class="p-w d-flex flex-column align-items-center">
                  <p class="font-weight-bold">ツイート数</p>
                  <span>{{ $tweet_count }}</span>
                </div>
                <div class="p-w d-flex flex-column align-items-center">
                  <p class="font-weight-bold">フォロー数</p>
                  <span>{{ $follow_count }}</span>
                </div>
                <div class="p-w d-flex flex-column align-items-center">
                  <p class="font-weight-bold">フォロワー数</p>
                  <span>{{ $follower_count }}</span>
                </div>
              </div>
            </div>
          </div>
        </div>
      </div>

      @if (isset($pets))
        @foreach ($pets as $pet)
            <div class="col-md-8 mb-3">
              <div class="card">
                <div class="d-inline-flex">
                  <div class="p-3 d-flex flex-column">
                    <img src="{{ asset('storage/profile_image/'.$user->profile_image) }}" class="rounded-circle" width="100" height="100">
                    <div class="mt-3 d-flex flex-column">
                      <h4 class="mb-0 font-weight-bold">
                        <a href="{{ route('pets.show', ['pet' => $pet]) }}">{{ $pet->name }}</a>
                      </h4>
                    </div>
                  </div>
                  <div class="p-3 d-flex flex-column justify-content-between">
                    <div class="d-flex">
                      <div>
                        {{ $pet->type }}
                        {{ $pet->breed }}
                        {{ $pet->profile_comment }}
                        @if ($user->id === Auth::user()->id)
                          <a href="{{ route('pets.edit', ['pet' => $pet]) }}" class="btn btn-primary">プロフィールを編集する</a>
                        @endif
                      </div>
                    </div>
                  </div>
                </div>
              </div>
            </div>
        @endforeach
      @endif




      @if (isset($timelines))
        @foreach ($timelines as $timeline)
          <div class="col-md-8 mb-3">
            <div class="card">
              <div class="card-header p-3 w-100 d-flex">
                <img src="{{ asset('storage/profile_image/'.$user->profile_image) }}" class="rounded-circle" width="50" height="50">
                <div class="ml-2 d-flex flex-column flex-grow-1">
                  <p class="mb-0">{{ $timeline->user->name }}</p>
                  <a href="{{ route('users.show',['user' => $timeline->user->id]) }}" class="text-secondary">{{ $timeline->user->screen_name }}</a>
                </div>
                <div class="d-flex justify-content-end flex-grow-1">
                  <p class="mb-0 text-secondary">{{ $timeline->created_at->format('Y-m-d H:i') }}</p>
                </div>
              </div>
              <div class="card-body">
                {{ $timeline->content }}
              </div>
              <div class="card-footer py-1 d-flex justify-content-end bg-white">
                @if ($timeline->user->id === Auth::user()->id)
                  <div class="dropdown mr-3 d-flex align-items-center">
                    <a href="#" role="button" id="dropdownMenuLink" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                      <i class="fas fa-ellipsis-v fa-fw"></i>
                    </a>
                    <div class="dropdown-menu" aria-labelledby="dropdownMenuLink">
                      <form action="{{ route('tweets.destroy', ['tweet' => $timeline->id]) }}" method="POST" class="mb-0">
                        @csrf
                        @method('DELETE')

                        <a href="{{ route('tweets.edit', ['tweet' => $timeline->id]) }}" class="dropdown-item">編集</a>
                        <button class="dropdown-item del-btn">削除</button>
                      </form>
                    </div>
                  </div>
                @endif

                <div class="mr-3 d-flex align-items-center">
                  <a href="{{ route('tweets.show', ['tweet' => $timeline->id]) }}"><i class="far fa-comment fa-fw"></i></a>
                  <p class="mb-0 text-secondary">{{ count($timeline->comments) }}</p>
                </div>
                <div class="d-flex align-items-center">
                  @if (!in_array(Auth::user()->id, array_column($timeline->favorites->toArray(), 'user_id'), TRUE))
                    <form action="{{ url('favorites/') }}" method="POST" class="mb-0">
                      @csrf

                      <input type="hidden" name="tweet_id" value="{{ $timeline->id }}">
                      <button class="btn p-0 border-0 text-danger"><i class="fas fa-heart fa-fw"></i></button>
                    </form>
                  @else
                    <form action="{{ url('fovorites/'.array_column($timeline->favorites->toArray(), 'id', 'user_id')[Auth::user()->id]) }}" method="POST" class="mb-0">
                      @csrf
                      @method('DELETE')

                      <button class="btn p-0 border-0 text-danger"><i class="fas fa-heart fa-fw"></i></button>
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
