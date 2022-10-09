<option value="0">Refer your word</option>

@if (count($articles) > 0)
    @foreach ($articles as $article)
        <option value="{{ $article->id }}"
            {{ $article->id == $articleId?'selected':'' }}
            >{{ $article->title }}</option>
    @endforeach
@else
    <p>No Options Found</p>
@endif

