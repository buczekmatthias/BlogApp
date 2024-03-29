@extends('base')

@section('title', ucfirst($article->title))

@section('body')
    <div class="px-24 py-10 w-full flex flex-col gap-8">
        @if (
            $article->warrant &&
                !auth()->user()
                    ?->isAdmin())
            <div class="flex flex-col items-center py-24 gap-8">
                <p class="font-semibold text-5xl">
                    <span>This article has been {{ $article->warrant->status }} by</span>
                    <a class='font-bold text-blue-800'
                       href="{{ route('user.profile', $article->warrant->author->username) }}">{{ $article->warrant->author->getName() }}</a>
                </p>
                <p class="text-gray-400 text-2xl">
                    <span>due to</span>
                    <span class="italic font-semibold">{{ $article->warrant->reason }}</span>
                </p>
            </div>
        @else
            <div class="px-16 flex flex-col items-center gap-12">
                @if (
                    $article->warrant &&
                        auth()->user()
                            ?->isAdmin())
                    <p class="text-2xl font-semibold text-red-500 bg-red-500/10 px-4 py-2">This article is under warrant</p>
                @endif
                <div class="relative flex flex-col items-center gap-4 w-full">
                    <div class="flex gap-2 items-center">
                        <a class="underline text-blue-800 font-semibold"
                           href="{{ route('articles.list') }}">Articles</a>
                        <svg class="h-4 fill-blue-800"
                             viewBox="0 0 256 512"
                             xmlns="http://www.w3.org/2000/svg">
                            <path d="M224.3 273l-136 136c-9.4 9.4-24.6 9.4-33.9 0l-22.6-22.6c-9.4-9.4-9.4-24.6 0-33.9l96.4-96.4-96.4-96.4c-9.4-9.4-9.4-24.6 0-33.9L54.3 103c9.4-9.4 24.6-9.4 33.9 0l136 136c9.5 9.4 9.5 24.6.1 34z"></path>
                        </svg>
                        <p class="">{{ $article->title }}</p>
                    </div>
                    <p class="text-gray-400">Published {{ $article->created_at->format('F d, Y') }} by @if ($article->author)
                            <a class="text-blue-800"
                               href="{{ route('user.profile', $article->author()->first()->username) }}">{{ $article->author()->first()->getName() }}</a>
                        @else
                            <span>Deleted user</span>
                        @endif
                    </p>
                    <p class="font-bold text-5xl w-2/3 text-center">{{ ucfirst($article->title) }}</p>
                </div>
                <div class="relative w-full min-h-[20rem] article-bg-gradient rounded-md">
                    @if ($article->thumbnail)
                        <img alt=""
                             class="rounded-md"
                             src="{{ asset('assets/images/' . $article->getStrippedUuid() . '/' . $article->thumbnail) }}">
                    @endif
                    @if (auth()->user())
                        <div class="absolute top-6 right-6 flex gap-4 [&>*]:rounded-md [&>*]:bg-slate-50/30 [&>*]:p-3">
                            @can('update', $article)
                                <a href="{{ route('articles.edit', $article->slug) }}">
                                    <svg class="h-7 fill-blue-100"
                                         viewBox="0 0 512 512"
                                         xmlns="http://www.w3.org/2000/svg">
                                        <path d="M290.74 93.24l128.02 128.02-277.99 277.99-114.14 12.6C11.35 513.54-1.56 500.62.14 485.34l12.7-114.22 277.9-277.88zm207.2-19.06l-60.11-60.11c-18.75-18.75-49.16-18.75-67.91 0l-56.55 56.55 128.02 128.02 56.55-56.55c18.75-18.76 18.75-49.16 0-67.91z"></path>
                                    </svg>
                                </a>
                            @endcan
                            <a href="{{ route('articles.bookmark', $article->slug) }}">
                                <svg class="h-7 fill-blue-100"
                                     viewBox="0 0 384 512"
                                     xmlns="http://www.w3.org/2000/svg">
                                    @if (auth()->user()->bookmarks()->get()->contains($article))
                                        <path d="M0 512V48C0 21.49 21.49 0 48 0h288c26.51 0 48 21.49 48 48v464L192 400 0 512z"></path>
                                    @else
                                        <path d="M336 0H48C21.49 0 0 21.49 0 48v464l192-112 192 112V48c0-26.51-21.49-48-48-48zm0 428.43l-144-84-144 84V54a6 6 0 0 1 6-6h276c3.314 0 6 2.683 6 5.996V428.43z"></path>
                                    @endif
                                </svg>
                            </a>
                            <div class="report-btn cursor-pointer">
                                <svg class="h-7 fill-blue-100"
                                     viewBox="0 0 512 512"
                                     xmlns="http://www.w3.org/2000/svg">
                                    <path
                                          d="M349.565 98.783C295.978 98.783 251.721 64 184.348 64c-24.955 0-47.309 4.384-68.045 12.013a55.947 55.947 0 0 0 3.586-23.562C118.117 24.015 94.806 1.206 66.338.048 34.345-1.254 8 24.296 8 56c0 19.026 9.497 35.825 24 45.945V488c0 13.255 10.745 24 24 24h16c13.255 0 24-10.745 24-24v-94.4c28.311-12.064 63.582-22.122 114.435-22.122 53.588 0 97.844 34.783 165.217 34.783 48.169 0 86.667-16.294 122.505-40.858C506.84 359.452 512 349.571 512 339.045v-243.1c0-23.393-24.269-38.87-45.485-29.016-34.338 15.948-76.454 31.854-116.95 31.854z">
                                    </path>
                                </svg>
                            </div>
                        </div>
                    @endif
                </div>
                <div class="report-container hidden fixed grid place-items-center top-0 left-0 z-40 bg-neutral-800/90 h-screen w-full">
                    <form action="{{ route('submitReport') }}"
                          class="report-form w-1/4 p-8 flex flex-col gap-6 rounded-md bg-slate-50"
                          method="POST">
                        @csrf
                        <p class="text-xl font-semibold">Report article</p>
                        <div class="flex flex-col items-start gap-2">
                            @foreach ($reports as $key => $reason)
                                <div class="flex gap-3 items-center cursor-pointer [&>*]:cursor-pointer">
                                    <input id="reason-{{ $key }}"
                                           name="reason"
                                           required
                                           type="radio"
                                           value="{{ $key }}" />
                                    <label for="reason-{{ $key }}">{{ $reason }}</label>
                                </div>
                            @endforeach
                        </div>
                        <div class="flex items-center justify-end gap-4 w-full">
                            <button class="report-container-close"
                                    type="button">Close</button>
                            <button class="form-btn">Submit</button>
                        </div>
                    </form>
                </div>
                <div class="px-20 text-lg">{!! Markdown::parse(nl2br($article->content)) !!}</div>
                <div class="flex flex-wrap gap-2">
                    @foreach ($article->tags()->get() as $tag)
                        <a class="bg-blue-800/5 text-blue-800 text-lg font-medium px-4 py-2 rounded-md"
                           href="{{ route('tags.view', $tag->name) }}">#{{ $tag->name }}</a>
                    @endforeach
                </div>
                @if (auth()->user())
                    @if ($article->likes()->get()->contains(auth()->user()))
                        <a class="flex gap-3 bg-red-700 text-slate-50 px-6 py-3 rounded-md"
                           href="{{ route('articles.like', $article->slug) }}">
                            <svg class="h-7 fill-slate-50"
                                 viewBox="0 0 512 512"
                                 xmlns="http://www.w3.org/2000/svg">
                                <path d="M462.3 62.6C407.5 15.9 326 24.3 275.7 76.2L256 96.5l-19.7-20.3C186.1 24.3 104.5 15.9 49.7 62.6c-62.8 53.6-66.1 149.8-9.9 207.9l193.5 199.8c12.5 12.9 32.8 12.9 45.3 0l193.5-199.8c56.3-58.1 53-154.3-9.8-207.9z"></path>
                            </svg>
                            <span>You love it!</span>
                        </a>
                    @else
                        <a class="flex gap-3 bg-red-700/10 text-red-700 px-6 py-3 rounded-md"
                           href="{{ route('articles.like', $article->slug) }}">
                            <svg class="h-7 fill-red-700"
                                 viewBox="0 0 512 512"
                                 xmlns="http://www.w3.org/2000/svg">
                                <path d="M458.4 64.3C400.6 15.7 311.3 23 256 79.3 200.7 23 111.4 15.6 53.6 64.3-21.6 127.6-10.6 230.8 43 285.5l175.4 178.7c10 10.2 23.4 15.9 37.6 15.9 14.3 0 27.6-5.6 37.6-15.8L469 285.6c53.5-54.7 64.7-157.9-10.6-221.3zm-23.6 187.5L259.4 430.5c-2.4 2.4-4.4 2.4-6.8 0L77.2 251.8c-36.5-37.2-43.9-107.6 7.3-150.7 38.9-32.7 98.9-27.8 136.5 10.5l35 35.7 35-35.7c37.8-38.5 97.8-43.2 136.5-10.6 51.1 43.1 43.5 113.9 7.3 150.8z"></path>
                            </svg>
                            <span>Love it</span>
                        </a>
                    @endif
                @endif
            </div>
            <div class="px-16 flex flex-col gap-4">
                <p class="font-semibold text-3xl">Comments</p>
                @if (auth()->user())
                    <form action="{{ route('articles.newComment', $article->slug) }}"
                          autocomplete="off"
                          class="w-full border-2 border-solid border-gray-200 rounded-sm p-3 flex flex-wrap items-center justify-between gap-3"
                          method="POST">
                        <p class="font-semibold text-xl">{{ auth()->user()->getName() }} says:</p>
                        <button class="bg-blue-700 text-slate-50 rounded-md px-3 py-2">Share</button>
                        <textarea class="w-full resize-none h-40 border-b-2 border-solid border-gray-150 bg-transparent outline-transparent focus:border-gray-600"
                                  name="content"
                                  placeholder="Your opinion"
                                  required></textarea>
                        @csrf
                    </form>
                @else
                    <p class="text-sm">To comment article you need to <a class="text-blue-700 font-semibold"
                           href="{{ route('security.login') }}">login</a></p>
                @endif
                <div class="flex flex-col gap-6 px-20 pt-8">
                    @foreach ($article->comments()->orderBy('created_at', 'DESC')->get() as $comment)
                        <div class="flex flex-wrap justify-between items-center border-b-[1px] border-solid border-gray-300 pb-6">
                            @if ($comment->author)
                                <a class="flex gap-2 items-center"
                                   href="{{ route('user.profile', $comment->author()->first()->username) }}">
                                    <img alt=""
                                         class="h-10 rounded-md"
                                         src="{{ asset('assets/profileImages/' . $comment->author()->first()->image) }}">
                                    <span class="text-lg">{{ $comment->author()->first()->getName() }}</span>
                                </a>
                            @else
                                <p class="flex gap-2 items-center">
                                    <img alt=""
                                         class="h-10 rounded-md"
                                         src="{{ asset('assets/profileImages/avatar.png') }}">
                                    <span class="text-lg">Deleted user</span>
                                </p>
                            @endif
                            <p class="">{{ $comment->created_at->timezone(auth()->user()?->timezone ?? 'UTC')->format('M d, Y | H:i:s') }}</p>
                            <p class="w-full mt-4 px-12 text-2xl">{!! $comment->content !!}</p>
                            @if (auth()->user()
                                    ?->isAdmin())
                                <div class="ml-12 mt-6 flex gap-4 items-center">
                                    <p class="cursor-pointer report-comment"
                                       data-id="{{ $comment->uuid }}">Report</p>
                                    <a href="{{ route('admin.comments.delete', $comment->uuid) }}">Delete</a>
                                </div>
                            @else
                                <p class="ml-12 mt-4 cursor-pointer report-comment"
                                   data-id="{{ $comment->uuid }}">Report</p>
                            @endif
                        </div>
                    @endforeach
                </div>
            </div>
        @endif
    </div>
@endsection

@section('javascripts')
    @if (auth()->user() && !$article->warrant)
        <script src="{{ asset('js/article.js') }}"></script>
    @endif
@endsection
