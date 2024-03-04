<div @if ($page->is_container) class="container" @endif>
    @if ($page->name)
        <h1><a href="{{ $page->getSeoCanonicalUrl() }}" title="{{ $page->name }}">{{ $page->name }}</a></h1>
    @endif
    {!! $page->content !!}
</div>
