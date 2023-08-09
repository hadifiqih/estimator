<?php

namespace App\Http\Controllers;

use App\Models\Employee;
use App\Models\Antrian;
use App\Models\Design;
use App\Models\AntrianDesain;
use App\Models\Order;

use Illuminate\Http\Request;

class DesignController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }
    public function index(){
        $antrians = Antrian::where('status', '1')->with('design')->get();
        $designs = Design::all();
        return view('antrian.design.index', compact('antrians', 'designs'));
    }

    public function edit($id){
        $employees = Employee::where('can_design', '1')->get();
        $antrian = Antrian::find($id);
        $designs = Design::with('employee')->get();

        return view('antrian.design.create', compact('employees', 'antrian', 'designs'));
    }

    public function update(Request $request, $id){
        // File Upload
        $file = $request->file('designFile');
        $nama_file = time()."_".$file->getClientOriginalName();
        $tujuan_upload = 'storage/print-file';
        $file->move($tujuan_upload,$nama_file);

        if($file->move($tujuan_upload,$nama_file)){
            "File berhasil diupload";
        }

        // Create Design
        $design = new Design;
        $design->title = $request->title;
        $design->description = $request->description;
        $design->file_name = $nama_file;
        $design->antrian_id = $id;
        $design->employee_id = $request->designer;
        $design->save();

        // Update Antrian
        $antrian = Antrian::find($id);
        $antrian->design_id = $design->id;
        $antrian->save();

        return redirect('/design')->with('success', 'Design berhasil diupload');
    }

    //
}
