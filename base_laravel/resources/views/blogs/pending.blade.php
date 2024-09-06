<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            {{ __('Pending Blog Posts') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900 dark:text-gray-100">
                    <h2 class="text-xl font-bold">Pending Approval Blogs</h2>

                    @foreach($posts->reverse() as $post)
                        <div class="mt-4 p-4 border border-gray-200 rounded-md">
                            <div class="mt-4 p-4 border border-gray-200 rounded-md">
                                <h3 class="text-lg font-semibold">
                                    <a href="{{ route('blogs.show', $post->id) }}">
                                        {{ $post->title }}
                                    </a>
                                </h3>
                                <p>{{ $post->content }}</p>
                                
                                {{-- @if($post->image)
                                    <img src="{{ Storage::disk('minio')->url($post->image) }}" alt="Post Image" class="mt-2">
                                @endif --}}
    
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
    
                                <!-- Edit button -->
                                @php
                                    $policy = app(\App\Policies\PostPolicy::class);
                                    $canUpdate = $policy->update(auth()->user(), $post);
                                @endphp
                                @if($canUpdate)
                                    <div class="mt-4">
                                        <a href="{{ route('blogs.edit', $post->id) }}" class="bg-yellow-500 text-black px-4 py-2 rounded-md hover:bg-yellow-600">
                                            Edit
                                        </a>
                                    </div>
                                @endif

                            <form action="{{ route('blogs.approve', $post->id) }}" method="POST">
                                @csrf
                                @method('PATCH')

                                <button type="submit" class="bg-green-500 text-black px-4 py-2 rounded-md hover:bg-green-600">
                                    Approve
                                </button>
                            </form>
                        </div>
                    @endforeach
                </div>
            </div>
        </div>
    </div>
</x-app-layout>