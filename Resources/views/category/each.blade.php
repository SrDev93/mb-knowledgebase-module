@if($category->children->count() > 0)
    <ol class="dd-list">
        @foreach($category->children as $category)
            <li class="dd-item" data-id="{{ $category->id }}">
                <div class="dd-handle">@if(!\Illuminate\Support\Facades\Auth::user()->brand_id) {{ optional($category->brand)->name }} - @endif {{ $category->name }}</div>
                <div class="btn-inline">
                    <a class="btn btn-primary" href="{{ route('KnowledgeBaseCategory.edit', $category->id) }}" title="ویرایش"><i class="fa fa-edit"></i></a>
                    <form action="{{ route('KnowledgeBaseCategory.destroy', $category->id) }}" method="post" class="delete-form">
                        <button class="delete btn btn-danger" onclick="return confirm('برای حذف اطمینان دارید؟')"><i class="fa fa-trash"></i></button>
                        @csrf
                        @method('DELETE')
                    </form>
                </div>
                @include('knowledgebase::category.each', $category)
            </li>
        @endforeach
    </ol>
@endif
