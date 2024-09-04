<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            {{ __('Draft Posts') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900 dark:text-gray-100">

                    <!-- Display draft blog posts -->
                    <h2 class="mt-8 text-xl font-bold">Draft Blogs</h2>

                    @foreach($posts as $post)
                        <div class="mt-4 p-4 border border-gray-200 rounded-md">
                            <h3 class="text-lg font-semibold">
                                <a href="{{ route('blogs.show', $post->id) }}">
                                    {{ $post->title }}
                                </a>
                            </h3>
                            <p>{{ $post->content }}</p>

                            <div class="mt-2">
                                <strong>Category:</strong> {{ $post->category->name }}
                            </div>

                            <div class="mt-2">
                                <strong>Tags:</strong>
                                @foreach($post->tags as $tag)
                                    <span class="bg-gray-200 text-gray-700 p-1 rounded">{{ $tag->name }}</span>
                                @endforeach
                            </div>

                            <div class="mt-2">
                                <strong>Tagged Users:</strong>
                                @foreach($post->taggedUsers as $user)
                                    <span class="bg-blue-200 text-blue-700 p-1 rounded">{{ $user->name }}</span>
                                @endforeach
                            </div>

                            <span class="text-sm text-gray-500">{{ $post->status }}</span>
                            <p><strong>Views:</strong> {{ $post->views }}</p>
                            <p class="text-sm text-gray-500">Author: {{ $post->author->name ?? 'Unknown' }}</p>
                        </div>
                    @endforeach

                </div>
            </div>
        </div>
    </div>
</x-app-layout>