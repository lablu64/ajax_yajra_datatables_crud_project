<?php

namespace App\Http\Controllers;

use Illuminate\Support\Str;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Yajra\DataTables\Facades\DataTables;

class ChildcategoryController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function index(Request $request)
    {
    	if ($request->ajax()) {
    		$data=DB::table('child_categories')->leftJoin('categories','child_categories.category_id','categories.id')->leftJoin('sub_categories','child_categories.subcategory_id','sub_categories.id')
    		->select('categories.category_name','sub_categories.subcategory_name','child_categories.*')->get();

    		return DataTables::of($data)
    				->addIndexColumn()
    				->addColumn('action', function($row){

    					$actionbtn='<a href="#" class="btn btn-info btn-sm edit" data-id="'.$row->id.'" data-toggle="modal" data-target="#editModal" ><i class="fas fa-edit"></i></a>
                      	<a href="'.route('childcategory.delete',[$row->id]).'" class="btn btn-danger btn-sm" id="delete"><i class="fas fa-trash"></i>
                      	</a>';

                       return $actionbtn; 	

    				})
    				->rawColumns(['action'])
    				->make(true);		
    	}

        $category=DB::table('categories')->get();
    	return view('admin.category.childcategory.index',compact('category'));
    }


    public function store(Request $request)
    {
       $cat=DB::table('sub_categories')->where('id',$request->subcategory_id)->first();

       $data=array();
       $data['category_id']=$cat->category_id;
       $data['subcategory_id']=$request->subcategory_id;
       $data['childcategory_slug']=Str::slug($request->childcategory_name, '-');
       $data['childcategory_name']=$request->childcategory_name;
       DB::table('child_categories')->insert($data);
       $notification=array('messege' => 'Child-Category Inserted!', 'alert-type' => 'success');
       return redirect()->back()->with($notification);
    }

    public function destory($id)
    {
        DB::table('child_categories')->where('id',$id)->delete();
        $notification=array('messege' => 'Child-Category Deleted!', 'alert-type' => 'success');
        return redirect()->back()->with($notification);

    }

    public function edit($id)
    {
        $category=DB::table('categories')->get();
        $data=DB::table('child_categories')->where('id',$id)->first();
        return view('admin.category.childcategory.edit',compact('category','data'));
    }

    public function update(Request $request)
    {
       $cat=DB::table('sub_categories')->where('id',$request->subcategory_id)->first();

       $data=array();
       $data['category_id']=$cat->category_id;
       $data['subcategory_id']=$request->subcategory_id;
       $data['childcategory_slug']=Str::slug($request->childcategory_name, '-');
       $data['childcategory_name']=$request->childcategory_name;
       DB::table('child_categories')->where('id',$request->id)->update($data);
       $notification=array('messege' => 'Child-Category Updated!', 'alert-type' => 'success');
       return redirect()->back()->with($notification);
    }


}