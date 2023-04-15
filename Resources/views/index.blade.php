@extends('layouts.admin')

@section('content')
    <!-- CONTAINER -->
    <div class="main-container container-fluid">


        <!-- PAGE-HEADER -->
        @include('knowledgebase::partial.header')
        <!-- PAGE-HEADER END -->

        <!-- Row -->
        <div class="row row-sm">
            <div class="col-lg-12">
                <div class="card">
                    <div class="card-header border-bottom">
                        <h3 class="card-title">لیست موضوعات</h3>
                    </div>
                    <div class="card-body">
                        <div class="table-responsive">
                            <table class="table table-bordered text-nowrap border-bottom w-100" id="responsive-datatable">
                                <thead>
                                <tr>
                                    <th class="wd-15p border-bottom-0">زبان</th>
                                    @if(!\Illuminate\Support\Facades\Auth::user()->brand_id)
                                        <th class="wd-15p border-bottom-0">برند</th>
                                    @endif
                                    <th class="wd-15p border-bottom-0">دسته بندی</th>
                                    <th class="wd-15p border-bottom-0">موضوع</th>
                                    <th class="wd-20p border-bottom-0">عملیات</th>
                                </tr>
                                </thead>
                                <tbody>
                                @foreach($items as $item)
                                    <tr>
                                        <td>{{ optional($item->language)->title }}</td>
                                        @if(!\Illuminate\Support\Facades\Auth::user()->brand_id)
                                            <td>{{ optional($item->brand)->name }}</td>
                                        @endif
                                        <td>@if($item->category) {{ $item->category->name }} @elseif($item->parent and $item->parent->category) {{ $item->parent->category->name }} @endif</td>
                                        <td>@if($item->parent) <i class="fa fa-reply"></i> {{ $item->parent->title }} @else {{ $item->title }} @endif</td>
                                        <td>
                                            <a href="{{ route('KnowledgeBase.edit', $item->id) }}" class="btn btn-primary fs-14 text-white edit-icn" title="ویرایش">
                                                <i class="fe fe-edit"></i>
                                            </a>
                                            <button type="submit" onclick="return confirm('برای حذف اطمبنان دارید؟')" form="form-{{ $item->id }}" class="btn btn-danger fs-14 text-white edit-icn" title="حذف">
                                                <i class="fe fe-trash"></i>
                                            </button>
                                            <form id="form-{{ $item->id }}" action="{{ route('KnowledgeBase.destroy', $item->id) }}" method="post">
                                                @csrf
                                                @method('DELETE')
                                            </form>
                                        </td>
                                    </tr>
                                    @if(count($item->children))
                                        @foreach($item->children as $child)
                                            <tr>
                                                <td>{{ optional($child->language)->title }}</td>
                                                @if(!\Illuminate\Support\Facades\Auth::user()->brand_id)
                                                    <td>{{ optional($child->brand)->name }}</td>
                                                @endif
                                                <td>
{{--                                                    @if($child->category) {{ $child->category->name }} @elseif($child->parent and $child->parent->category) {{ $child->parent->category->name }} @endif--}}
                                                </td>
                                                <td>@if($child->parent) <i class="fa fa-reply"></i> {{ $child->parent->title }} @else {{ $child->title }} @endif</td>
                                                <td>
                                                    <a href="{{ route('KnowledgeBase.edit', $child->id) }}" class="btn btn-primary fs-14 text-white edit-icn" title="ویرایش">
                                                        <i class="fe fe-edit"></i>
                                                    </a>
                                                    <button type="submit" onclick="return confirm('برای حذف اطمبنان دارید؟')" form="form-{{ $child->id }}" class="btn btn-danger fs-14 text-white edit-icn" title="حذف">
                                                        <i class="fe fe-trash"></i>
                                                    </button>
                                                    <form id="form-{{ $child->id }}" action="{{ route('KnowledgeBase.destroy', $child->id) }}" method="post">
                                                        @csrf
                                                        @method('DELETE')
                                                    </form>
                                                </td>
                                            </tr>
                                        @endforeach
                                    @endif
                                @endforeach
                                </tbody>
                            </table>
                        </div>
                    </div>
                    <div class="card-footer">
                        <a href="{{ route('KnowledgeBase.create') }}" class="btn btn-primary">افزودن موضوع</a>
                    </div>
                </div>
            </div>
        </div>
        <!-- End Row -->
    </div>
@endsection
