@extends('base')

@section('title', 'Create article')

@section('body')
    <div class="w-4/5 py-10 px-8 my-10 bg-white text-natural-900 flex flex-col items-center gap-8 rounded-md shadow-md">
        <p class="font-bold text-4xl">Build layout</p>
        <form class="flex flex-col gap-3 w-full"
              id="article-create"
              method="post">
            @include('components.form-box', [
                'id' => 'title',
                'label' => 'Title',
                'name' => 'title',
                'error' => $errors->has('title') ? $errors->first('title') : null,
                'extra' => "<span class='input-help'>Title will be used as identifier</span>",
                'value' => session()->get('article.title'),
            ])
            <div class="flex gap-3">
                <div class="w-3/4 flex flex-col gap-3">
                    <div class="flex justify-end items-center gap-3">
                        <button class="bold-text styling-btn font-bold"
                                type='button'>Bold</button>
                        <button class="italic-text styling-btn italic"
                                type='button'>Italic</button>
                        <button class="link-text styling-btn"
                                type='button'>Link</button>
                        <button class="place-img styling-btn"
                                type='button'>Image</button>
                    </div>
                    <div class=""></div>
                    @include('components.form-box', [
                        'item' => 'textarea',
                        'id' => 'content',
                        'label' => 'Content',
                        'name' => 'content',
                        'error' => $errors->has('content') ? $errors->first('content') : null,
                        'extra' => "<span class='input-help'>You will be able to add images in further steps</span>",
                        'value' => session()->get('article.content'),
                    ])
                </div>
                <div class="flex flex-col w-1/4 gap-3 h-[45.5rem] overflow-auto px-1">
                    @foreach (session()->get('uploaded') as $key => $img)
                        <div class="flex flex-col items-start gap-2">
                            <p class="text-sm">{{ $img }}</p>
                            <img alt=""
                                 class=""
                                 src="{{ asset('assets/uploads/' . $img) }}">
                            <div class="flex items-center gap-2 cursor-pointer [&>*]:cursor-pointer">
                                <input id="image-{{ $key }}"
                                       name="thumbnail"
                                       required
                                       type="radio"
                                       value="{{ $img }}" />
                                <label for="image-{{ $key }}">Use as thumbnail</label>
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>
            <div class="tags-container flex flex-col gap-2">
                <label class="text-lg">Tags <sup class="text-red-600">*</sup></label>
                <div class="w-full flex items-center gap-2 relative outline-transparent"
                     tabindex="0">
                    <input autocomplete="off"
                           class="form-input w-1/3"
                           id="tags">
                    <div class="list flex flex-wrap gap-2 w-2/3 [&>*]:tag-listed"></div>
                    <div class="results absolute left-0 bottom-full z-10 bg-slate-100 rounded-md h-0 max-h-[50vh] w-1/3 overflow-auto">
                        @foreach ($tags as $tag)
                            <p class="tag-suggestion">{{ $tag }}</p>
                        @endforeach
                    </div>
                </div>
                <span class='input-help'>First tag will be representing tag, so pick it wise. At least 1 tag required</span>
                @error('tags')
                    <p class="font-medium text-red-500">{{ $errors->first('tags') }}</p>
                @enderror
            </div>
            <div class="flex justify-between">
                <a class="form-btn self-start"
                   href="{{ route('articles.create') }}">Back to images</a>
                <button class="form-btn">Create article</button>
            </div>
            @csrf
        </form>
    </div>
@endsection

@section('javascripts')
    <script src="{{ asset('js/createLayout.js') }}"></script>
@endsection
