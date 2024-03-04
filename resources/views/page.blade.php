<div @if ($page->is_container) class="container" @endif>
    @if ($page->name)
        <h1><a href="{{ $page->getSeoCanonicalUrl() }}" title="{{ $page->name }}">{{ $page->name }}</a></h1>
    @endif
    {!! $page->content !!}
    <div class="d-flex ms-3">
        <livewire:comment::action :model="$page" />
        <livewire:comment::action :model="$page" type="favorites" />
        <livewire:comment::view-count :model="$page" />
    </div>
    <div class="my-3 py-3 bg-white rounded border">
        <livewire:comment::rate :model="$page" />
    </div>
    <livewire:comment::comments :model="$page">
</div>
