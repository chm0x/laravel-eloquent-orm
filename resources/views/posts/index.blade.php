<h1>Posts</h1>

@forelse($posts as $post)
    <p>{{ $post->title }}</p>
    <p>{{ $post->slug }}</p>
    <p>{{ $post->excerpt }}</p>
    <p>{{ $post->description }}</p>
    <p>{{ $post->min_to_read }}</p>
    <p>{{ $post->is_published }}</p>
    <hr/>
@empty
    <p>No hay posts</p>
@endforelse