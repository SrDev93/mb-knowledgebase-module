<?php

namespace Modules\KnowledgeBase\Http\Controllers;

use Illuminate\Contracts\Support\Renderable;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Illuminate\Support\Facades\Auth;
use Modules\KnowledgeBase\Entities\KnowledgeBaseCategory;

class KnowledgeBaseCategoryController extends Controller
{
    /**
     * Display a listing of the resource.
     * @return Renderable
     */
    public function index()
    {
        if (Auth::user()->brand_id) {
            $categories = KnowledgeBaseCategory::where('brand_id', Auth::user()->brand_id)->whereNull('parent_id')->orderBy('sort_id')->get();
        }else {
            $categories = KnowledgeBaseCategory::whereNull('parent_id')->orderBy('sort_id')->get();
        }

        return view('knowledgebase::category.index', compact('categories'));
    }

    /**
     * Show the form for creating a new resource.
     * @return Renderable
     */
    public function create()
    {
        return view('knowledgebase::category.create');
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
        ]);

        try {
            $ac = KnowledgeBaseCategory::create([
                'lang' => $request->lang,
                'brand_id' => $request->brand_id,
                'name' => $request->name
            ]);

            return redirect()->route('KnowledgeBaseCategory.index')->with('flash_message', 'با موفقیت ثبت شد');
        }catch (\Exception $e){
            return redirect()->back()->with('err_message', 'خطایی رخ داده است، لطفا مجددا تلاش نمایید');
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
    public function edit(KnowledgeBaseCategory $KnowledgeBaseCategory)
    {
        return view('knowledgebase::category.edit', compact('KnowledgeBaseCategory'));
    }

    /**
     * Update the specified resource in storage.
     * @param Request $request
     * @param int $id
     * @return Renderable
     */
    public function update(Request $request, KnowledgeBaseCategory $KnowledgeBaseCategory)
    {
        try {
            if ($request->lang) {
                $KnowledgeBaseCategory->lang = $request->lang;
            }
            if ($request->brand_id) {
                $KnowledgeBaseCategory->brand_id = $request->brand_id;
            }
            $KnowledgeBaseCategory->name = $request->name;
            $KnowledgeBaseCategory->save();

            return redirect()->route('KnowledgeBaseCategory.index')->with('flash_message', 'بروزرسانی با موفقیت انجام شد');
        }catch (\Exception $e){
            return redirect()->back()->with('err_message', 'خطایی رخ داده است، لطفا مجددا تلاش نمایید');
        }
    }

    /**
     * Remove the specified resource from storage.
     * @param int $id
     * @return Renderable
     */
    public function destroy(KnowledgeBaseCategory $KnowledgeBaseCategory)
    {
        try {
            $KnowledgeBaseCategory->delete();

            return redirect()->route('KnowledgeBaseCategory.index')->with('flash_message', 'با موفقیت حذف شد');
        }catch (\Exception $e){
            return redirect()->back()->with('err_message', 'خطایی رخ داده است، لطفا مجددا تلاش نمایید');
        }
    }

    /**
     * Sort Item.
     *
     * @param  \Illuminate\Http\Request $request
     */
    public function sort_item(Request $request)
    {
        $category_item_sort = json_decode($request->sort);
        $this->sort_category($category_item_sort, null);
    }

    /**
     * Sort Category.
     *
     *
     * @param $category_items
     * @param $parent_id
     */
    private function sort_category(array $category_items, $parent_id)
    {
        foreach ($category_items as $index => $category_item) {
            $item = KnowledgeBaseCategory::findOrFail($category_item->id);
            $item->sort_id = $index + 1;
            $item->parent_id = $parent_id;
            $item->save();
            if (isset($category_item->children)) {
                $this->sort_category($category_item->children, $item->id);
            }
        }
    }
}
