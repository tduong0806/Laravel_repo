<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            {{ __('Blog Posts') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900 dark:text-gray-100">

                    <!-- Display existing blog posts -->
                    <h2 class="mt-8 text-xl font-bold">Published Blogs</h2>

                    @foreach($posts->reverse() as $post) 
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
                        </div>
                    @endforeach

                    <!-- Form to create a new blog post -->
                    <h1 class="text-2xl font-bold mt-8">Create a New Blog Post</h1>
    
                    <form action="{{ route('blogs.store') }}" method="POST" enctype="multipart/form-data">
                        @csrf
    
                        <div class="mb-4">
                            <label for="title" class="block text-sm font-medium text-gray-700">Title</label>
                            <input type="text" id="title" name="title" class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm" required>
                        </div>
    
                        <div class="mb-4">
                            <label for="content" class="block text-sm font-medium text-gray-700">Content</label>
                            <textarea id="content" name="content" rows="4" class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm" required></textarea>
                        </div>
    
                        <div class="mb-4">
                            <label for="status" class="block text-sm font-medium text-gray-700">Status</label>
                            <select id="status" name="status" class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm">
                                <option value="draft">Draft</option>
                                <option value="public">Public</option>
                            </select>
                        </div>

                        <div class="mb-4">
                            <label for="category" class="block text-sm font-medium text-gray-700">Category</label>
                            <select id="category" name="category_id" class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm">
                                @foreach($categories as $category)
                                    <option value="{{ $category->id }}">{{ $category->name }}</option>
                                @endforeach
                            </select>
                        </div>

                        <div class="mb-4">
                            <label for="tags" class="block text-sm font-medium text-gray-700">Tags</label>
                            <select id="tags" name="tags[]" multiple class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm">
                                @foreach($tags as $tag)
                                    <option value="{{ $tag->id }}">{{ $tag->name }}</option>
                                @endforeach
                            </select>
                        </div>

                        <div class="mb-4">
                            <label for="users" class="block text-sm font-medium text-gray-700">Tag Users</label>
                            <select id="users" name="users[]" multiple class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm">
                                @foreach($users as $user)
                                    <option value="{{ $user->id }}">{{ $user->name }}</option>
                                @endforeach
                            </select>
                        </div>
    
                        <div class="mb-4">
                            <label for="image" class="block text-sm font-medium text-gray-700">Image</label>
                            <input type="file" id="image" name="image" class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm">
                        </div>
    
                        <div class="flex items-center justify-end">
                            <button type="submit" class="bg-blue-500 text-red px-4 py-2 rounded-md shadow-sm hover:bg-blue-600">
                                Publish
                            </button>
                        </div>
                    </form>
    
                </div>
            </div>
        </div>
    </div>
</x-app-layout>