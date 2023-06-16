<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Yajra\DataTables\Facades\DataTables;

class PickupController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }


    public function index(Request $request)
    {
        if ($request->ajax()) {
            $data=DB::table('pickups')->latest()->get();
            return DataTables::of($data)
                    ->addIndexColumn()
                    ->addColumn('action', function($row){
                        $actionbtn='<a href="#" class="btn btn-info btn-sm edit" data-id="'.$row->id.'" data-toggle="modal" data-target="#editModal" ><i class="fas fa-edit"></i></a>
                        <a href="'.route('pickup.point.delete',[$row->id]).'"  class="btn btn-danger btn-sm" id="delete_coupon"><i class="fas fa-trash"></i>
                        </a>';
                       return $actionbtn;   
                    })
                    ->rawColumns(['action'])
                    ->make(true);       
        }

        return view('admin.pickup_point.index');
    }

    //store method
    public function store(Request $request)
    {
       $data = array(
            'pickup_point_name' => $request->pickup_point_name,
            'pickup_point_address' => $request->pickup_point_address,
            'pickup_point_phone' => $request->pickup_point_phone,
            'pickup_point_phone_two' => $request->pickup_point_phone_two,
        );
       DB::table('pickups')->insert($data);
       return response()->json('successfully Insert!');
    }

    //edit method
    public function edit($id)
    {
         $data=DB::table('pickups')->where('id',$id)->first();
         return view('admin.pickup_point.edit',compact('data'));
    }

    //update method
    public function update(Request $request)
    {
         $data = array(
            'pickup_point_name' => $request->pickup_point_name,
            'pickup_point_address' => $request->pickup_point_address,
            'pickup_point_phone' => $request->pickup_point_phone,
            'pickup_point_phone_two' => $request->pickup_point_phone_two,
        );
       DB::table('pickups')->where('id',$request->id)->update($data);
       return response()->json('successfully Updated!');
    }

    //delete method
    public function destroy($id)
    {
        DB::table('pickups')->where('id',$id)->delete();
        return response()->json('Successfully delete!');
    }
}
