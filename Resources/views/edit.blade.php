@extends('layouts.admin')

@push('stylesheets')
    <style>
        .select2-container {
            width: 100% !important;
        }
        input[type="file"] {
            display: none;
        }
        .custom-file-upload {
            border: 1px solid #ccc;
            display: inline-block;
            padding: 6px 12px;
            cursor: pointer;
        }
        ul.attachments-list li {
            width: 50%;
            display: block;
            float: right;
            margin-bottom: 0.5rem;
            line-height: 28px;
        }
        .remove-file {
            border: 1px solid red;
            display: inline-block;
            padding: 0px 9px;
            cursor: pointer;
            color: red;
            float: left;
            margin-left: 40%;
        }
        .remove-file i {
            color: red;
        }
    </style>
@endpush

@section('content')
    <!-- CONTAINER -->
    <div class="main-container container-fluid">
        <!-- PAGE-HEADER -->
    @include('knowledgebase::partial.header')
    <!-- PAGE-HEADER END -->

        <!-- ROW -->
        <div class="row">
            <div class="col-lg-12 col-md-12">
                <div class="card">
                    <div class="card-header border-bottom">
                        <h3 class="card-title">ویرایش موضوع</h3>
                    </div>
                    <div class="card-body">
                        <form action="{{ route('KnowledgeBase.update', $KnowledgeBase->id) }}" method="post" enctype="multipart/form-data" class="row g-3 needs-validation" novalidate>
                            @include('admin.partial.lang')
                            @include('admin.partial.brand')
                            <div class="col-md-12">
                                <label for="title" class="form-label">دسته بندی</label>
                                <select name="category_id" class="form-control">
                                    @foreach($categories as $category)
                                        <option value="{{ $category->id }}" @if($KnowledgeBase->category_id == $category->id) selected @endif>{{ $category->name }}</option>
                                    @endforeach
                                </select>
                                <div class="invalid-feedback">لطفا دسته بندی را انتخاب کنید</div>
                            </div>
                            <div class="col-md-12">
                                <label for="title" class="form-label">موضوع</label>
                                <input type="text" name="title" class="form-control" id="title" required value="{{ $KnowledgeBase->title }}">
                                <div class="invalid-feedback">لطفا موضوع را وارد کنید</div>
                            </div>

                            <div class="col-md-12">
                                <label for="editor1" class="form-label">متن</label>
                                <textarea id="editor1" name="text" class="cke_rtl" required>{{ $KnowledgeBase->text }}</textarea>
                                <div class="invalid-feedback">لطفا متن را وارد کنید</div>
                            </div>

                            <div class="attachments row">
                                <label for="editor1" class="form-label">پیوست فایل:</label>
                                <div class="col-md-12">
                                    <label class="custom-file-upload">
                                        <input type="file" name="attachments[]" multiple/>
                                        <span class="file_name"><i class="fa fa-upload"></i> انتخاب فایل ها</span>
                                    </label>
                                </div>

                                @if(count($KnowledgeBase->attachments))
                                    <hr>
                                    <label for="editor1" class="form-label">فایل های پیوست شده :</label>
                                    <ul class="attachments-list">
                                        @foreach($KnowledgeBase->attachments as $attach)
                                            <li><a href="{{ url($attach->path) }}" target="_blank"><i class="fa fa-download"></i> {{ basename($attach->path) }} </a> <a href="{{ route('KnowledgeBase-attach-delete', $attach->id) }}" class="remove-file" onclick="return confirm('برای حذف اطمینان دارید؟');"><i class="fa fa-trash"></i> </a> </li>
                                        @endforeach
                                    </ul>
                                @endif
                            </div>

                            <div class="col-12">
                                <button class="btn btn-primary" type="submit">بروزرسانی</button>
                                @csrf
                                @method('PATCH')
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
        <!-- ROW CLOSED -->

    </div>

    @push('scripts')
        @include('ckfinder::setup')
        <script>
            var editor = CKEDITOR.replace('editor1', {
                // Define the toolbar groups as it is a more accessible solution.
                toolbarGroups: [
                    {
                        "name": "basicstyles",
                        "groups": ["basicstyles"]
                    },
                    {
                        "name": "links",
                        "groups": ["links"]
                    },
                    {
                        "name": "paragraph",
                        "groups": ["list", "blocks"]
                    },
                    {
                        "name": "document",
                        "groups": ["mode"]
                    },
                    {
                        "name": "insert",
                        "groups": ["insert"]
                    },
                    {
                        "name": "styles",
                        "groups": ["styles"]
                    },
                    {
                        "name": "about",
                        "groups": ["about"]
                    },
                    {   "name": 'paragraph',
                        "groups": ['list', 'blocks', 'align', 'bidi']
                    }
                ],
                // Remove the redundant buttons from toolbar groups defined above.
                removeButtons: 'Underline,Strike,Subscript,Superscript,Anchor,Styles,Specialchar,PasteFromWord'
            });
            CKFinder.setupCKEditor( editor );
        </script>

    @endpush
@endsection
