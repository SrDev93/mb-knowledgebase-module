<?php

namespace Modules\KnowledgeBase\Http\Controllers;

use Illuminate\Contracts\Support\Renderable;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\File;
use Modules\KnowledgeBase\Entities\KnowledgeBase;
use Modules\KnowledgeBase\Entities\KnowledgeBaseAttachment;
use Modules\KnowledgeBase\Entities\KnowledgeBaseCategory;

class KnowledgeBaseController extends Controller
{
    /**
     * Display a listing of the resource.
     * @return Renderable
     */
    public function index()
    {
        if (\request()->session()->has('brand_id')){
            $items = KnowledgeBase::whereNull('parent_id')->where('brand_id', \request()->session()->get('brand_id'))->get();
        }elseif (Auth::user()->brand_id) {
            $items = KnowledgeBase::whereNull('parent_id')->where('brand_id', Auth::user()->brand_id)->get();
        }else {
            $items = KnowledgeBase::whereNull('parent_id')->get();
        }

        return view('knowledgebase::index', compact('items'));
    }

    /**
     * Show the form for creating a new resource.
     * @return Renderable
     */
    public function create()
    {
        if (\request()->session()->has('brand_id')){
            $categories = KnowledgeBaseCategory::where('brand_id', \request()->session()->get('brand_id'))->get();
        }elseif (Auth::user()->brand_id) {
            $categories = KnowledgeBaseCategory::where('brand_id', Auth::user()->brand_id)->get();
        }else {
            $categories = KnowledgeBaseCategory::get();
        }

        return view('knowledgebase::create', compact('categories'));
    }

    /**
     * Store a newly created resource in storage.
     * @param Request $request
     * @return Renderable
     */
    public function store(Request $request)
    {
        $request->validate([
            'lang' => 'required',
            'brand_id' => 'required',
            'title' => 'required',
        ]);
        try {
            $KnowledgeBase = KnowledgeBase::create([
                'lang' => $request->lang,
                'brand_id' => $request->brand_id,
                'user_id' => Auth::id(),
                'category_id' => $request->category_id,
                'title' => $request->title,
                'text' => $request->text,
            ]);

            if (isset($request->attachments)){
                $array = array('jpg','png', 'jpeg');
                foreach ($request->attachments as $attachment){
                    $extension = $attachment->getClientOriginalExtension();
                    if (in_array($extension , $array)) {
                        $attach = KnowledgeBaseAttachment::create([
                            'knowledge_base_id' => $KnowledgeBase->id,
                            'path' => file_store($attachment, 'assets/uploads/photos/knowledge_base_attachments/', 'file_'),
                        ]);
                    }
                }
            }

            return redirect()->route('KnowledgeBase.index')->with('flash_message', 'با موفقیت ثبت شد');
        }catch (\Exception $e){
            return redirect()->back()->withInput()->with('err_message', 'خطایی رخ داده است، لطفا مجددا تلاش نمایید');
        }
    }

    /**
     * Show the specified resource.
     * @param int $id
     * @return Renderable
     */
    public function show($id)
    {
        return view('knowledgebase::show');
    }

    /**
     * Show the form for editing the specified resource.
     * @param int $id
     * @return Renderable
     */
    public function edit(KnowledgeBase $KnowledgeBase)
    {
        if (\request()->session()->has('brand_id')){
            $categories = KnowledgeBaseCategory::where('brand_id', \request()->session()->get('brand_id'))->get();
        }elseif (Auth::user()->brand_id) {
            $categories = KnowledgeBaseCategory::where('brand_id', Auth::user()->brand_id)->get();
        }else {
            $categories = KnowledgeBaseCategory::get();
        }

        return view('knowledgebase::edit', compact('KnowledgeBase', 'categories'));
    }

    /**
     * Update the specified resource in storage.
     * @param Request $request
     * @param int $id
     * @return Renderable
     */
    public function update(Request $request, KnowledgeBase $KnowledgeBase)
    {
        try {
            if ($request->lang) {
                $KnowledgeBase->lang = $request->lang;
            }
            if ($request->brand_id) {
                $KnowledgeBase->brand_id = $request->brand_id;
            }
            $KnowledgeBase->category_id = $request->category_id;
            $KnowledgeBase->title = $request->title;
            $KnowledgeBase->text = $request->text;
            $KnowledgeBase->save();

            if (isset($request->attachments)){
                $array = array('jpg','png', 'jpeg');
                foreach ($request->attachments as $attachment){
                    $extension = $attachment->getClientOriginalExtension();
                    if (in_array($extension , $array)) {
                        $attach = KnowledgeBaseAttachment::create([
                            'knowledge_base_id' => $KnowledgeBase->id,
                            'path' => file_store($attachment, 'assets/uploads/photos/knowledge_base_attachments/', 'file_'),
                        ]);
                    }
                }
            }

            return redirect()->route('KnowledgeBase.index')->with('flash_message', 'با موفقیت بروزرسانی شد');
        }catch (\Exception $e){
            return redirect()->back()->withInput()->with('err_message', 'خطایی رخ داده است، لطفا مجددا تلاش نمایید');
        }
    }

    /**
     * Remove the specified resource from storage.
     * @param int $id
     * @return Renderable
     */
    public function destroy(KnowledgeBase $KnowledgeBase)
    {
        try {
            $KnowledgeBase->delete();

            return redirect()->back()->with('flash_message', 'با موفقیت حذف شد');
        }catch (\Exception $e){
            return redirect()->back()->with('err_message', 'خطایی رخ داده است، لطفا مجددا تلاش نمایید');
        }
    }

    public function attach_delete($id)
    {
        $attachment = KnowledgeBaseAttachment::findOrFail($id);
        try {
            $attachment->delete();

            return redirect()->back()->with('flash_message', 'با موفقیت حذف شد');
        }catch (\Exception $e){
            return redirect()->back()->with('err_message', 'خطایی رخ داده است، لطفا مجددا تلاش نمایید');
        }
    }
}
