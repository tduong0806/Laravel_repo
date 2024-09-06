<form action="{{ route('blogs.store') }}" method="POST" enctype="multipart/form-data">
    @csrf
    
    <label for="tags">Tags</label>
    <select name="tags[]" multiple>
        @foreach($tags as $tag)
            <option value="{{ $tag->id }}">{{ $tag->name }}</option>
        @endforeach
    </select>

    <label for="category">Category</label>
    <select name="category_id">
        @foreach($categories as $category)
            <option value="{{ $category->id }}">{{ $category->name }}</option>
        @endforeach
    </select>

    <label for="users">Tag Users</label>
    <select name="users[]" multiple>
        @foreach($users as $user)
            <option value="{{ $user->id }}">{{ $user->name }}</option>
        @endforeach
    </select>

    <button type="submit">Create Blog</button>
</form>