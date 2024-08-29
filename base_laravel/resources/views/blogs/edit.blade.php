<form action="{{ route('blogs.update', $blog->id) }}" method="POST" enctype="multipart/form-data">
    @csrf
    @method('PUT')

    
    <label for="tags">Tags</label>
    <select name="tags[]" multiple>
        @foreach($tags as $tag)
            <option value="{{ $tag->id }}" {{ in_array($tag->id, $blog->tags->pluck('id')->toArray()) ? 'selected' : '' }}>
                {{ $tag->name }}
            </option>
        @endforeach
    </select>

    <label for="category">Category</label>
    <select name="category_id">
        @foreach($categories as $category)
            <option value="{{ $category->id }}" {{ $category->id == $blog->category_id ? 'selected' : '' }}>
                {{ $category->name }}
            </option>
        @endforeach
    </select>

    <label for="users">Tag Users</label>
    <select name="users[]" multiple>
        @foreach($users as $user)
            <option value="{{ $user->id }}" {{ in_array($user->id, $blog->users->pluck('id')->toArray()) ? 'selected' : '' }}>
                {{ $user->name }}
            </option>
        @endforeach
    </select>

    <button type="submit">Update Blog</button>
</form>