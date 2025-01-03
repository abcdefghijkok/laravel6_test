@extends('layouts.app')

@section('title')
プロフィール編集
@endsection
<!-- http://localhost/a_laravel/public/mypage/edit-profile -->
@section('content')
<div id="profile-edit-form" class="container">
    <div class="row">
        <div class="col-8 offset-2">
            @if (session('status'))
            <div class="alert alert-success" role="alert">
                {{ session('status') }}
            </div>
            @endif
        </div>
    </div>

    <div class="row">
        <div class="col-8 offset-2 bg-white">

            <div class="font-weight-bold text-center border-bottom pb-3 pt-3" style="font-size: 24px">プロフィール編集</div>

            <form method="POST" action="{{ route('mypage.edit-profile') }}" class="p-5" enctype="multipart/form-data">
                @csrf

                {{-- アバター画像 --}}
                <span class="avatar-form image-picker">
                    <!-- ファイル選択ボタンを隠して、ラベルをクリックしたときにファイル選択がされるようにする -->
                    <input type="file" name="avatar" class="d-none" accept="image/png,image/jpeg,image/gif" id="avatar" onchange="updateAvatarPreview()" />
                    <label for="avatar" class="d-inline-block" style="cursor: pointer;">
                        <img id="avatar-preview" src="{{ asset('/images/test0.jpg') }}" class="rounded-circle" style="object-fit: cover; width: 200px; height: 200px;" onclick="changeAvatarImage()" />
                    </label>
                    <!-- 選択された画像のインデックスを送信するための隠しフィールド -->
                    <input type="hidden" name="selected-avatar" id="selected-avatar" value="0">
                </span>

                {{-- ニックネーム --}}
                <div class="form-group mt-3">
                    <label for="name">ニックネーム</label>
                    <input id="name" type="text" class="form-control @error('name') is-invalid @enderror" name="name" value="{{ old('name', $user->name) }}" required autocomplete="name" autofocus>
                    @error('name')
                    <span class="invalid-feedback" role="alert">
                        <strong>{{ $message }}</strong>
                    </span>
                    @enderror
                </div>

                <div class="form-group mb-0 mt-3">
                    <button type="submit" class="btn btn-block btn-secondary">
                        保存
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection

@section('script')
<script>
    let imageIndex = 0; // 初期値としてtest0.jpgを選択

    // 画像のパスを配列に格納
    const imagePaths = [
        "{{ asset('/images/test0.jpg') }}", 
        "{{ asset('/images/test1.jpg') }}", 
        "{{ asset('/images/test2.jpg') }}", 
        "{{ asset('/images/test3.jpg') }}"
    ];

    // 画像が選ばれた場合にプレビューを更新
    function updateAvatarPreview() {
        const fileInput = document.getElementById('avatar');
        const previewImage = document.getElementById('avatar-preview');

        if (fileInput.files && fileInput.files[0]) {
            // ユーザーが画像を選択した場合、その画像をプレビュー
            const reader = new FileReader();
            reader.onload = function(e) {
                previewImage.src = e.target.result;
            };
            reader.readAsDataURL(fileInput.files[0]);
        } else {
            // 画像が選ばれていない場合、test0.jpgをプレビュー
            previewImage.src = imagePaths[imageIndex];
        }
    }

    // 画像をクリックすると次の画像に切り替わる
    function changeAvatarImage() {
        // 次の画像に切り替え、最後に達したら最初に戻る
        imageIndex = (imageIndex + 1) % imagePaths.length; 
        document.getElementById('avatar-preview').src = imagePaths[imageIndex];

        // 隠しフィールドに選択された画像のインデックスをセット
        document.getElementById('selected-avatar').value = imageIndex;
    }

    // 画像の選択を防ぐ（エクスプローラーが開かない）
    document.getElementById('avatar').addEventListener('click', function(event) {
        if (!this.files.length) {
            event.preventDefault(); // ファイルが選択されていなければ、ダイアログを開かない
        }
    });
</script>
@endsection
