<li data-id="{{ $item->id }}" data-icon="{{ $item->icon }}" data-route="{{ $item->route }}" data-kategori="{{$item->kategori}}" data-parent="{{ $item->parent_id }}">
    <div class="label">
        <span class="drag-handle"><i class="mdi mdi-drag"></i></span>

        @if($item->children->count())
        <span class="toggle-icon"><i class="mdi mdi-chevron-right"></i></span>
        @else
        <span class="toggle-icon"><i class="mdi mdi-circle-small"></i></span>
        @endif

        <i class="mdi {{ $item->icon }}"></i>
        <span class="tree-text">{{ $item->name }}</span>

        <div class="node-actions">
            <button class="btn btn-sm btn-success btn-add-child" data-kategori="{{$item->kategori}}" title="Add child"><i class="mdi mdi-plus"></i></button>
            <button class="btn btn-sm btn-warning btn-edit" title="Edit"><i class="mdi mdi-pencil"></i></button>
            <button class="btn btn-sm btn-danger btn-delete" title="Delete"><i class="mdi mdi-delete"></i></button>
        </div>
    </div>

    @if($item->children->count())
    <ul>
        @foreach($item->children as $child)
        @include('admin.menu.tree_item', ['item' => $child])
        @endforeach
    </ul>
    @endif
</li>