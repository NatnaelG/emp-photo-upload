<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\EmployeeFile;
use Illuminate\Support\Facades\Storage;

class EmployeeFileController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $emp_code = EmployeeFile::find(1);
        $photo = Storage::disk('local')->get('photos/1234.png');
        
        // return mime_content_type($photo);
        // return response(Storage::disk('local')->get('photos/1234.png'), 200)->header('Content-Type', 'image/png');
        return EmployeeFile::all();
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        // return $request->emp_code;
        $form = $request->validate([
            'emp_code' => 'required',
            'photo' => 'required'
        ]);

        $employee = EmployeeFile::where('emp_code', $form['emp_code'])->first();
        // return $employee;
        if ($employee->photo_uploaded) {
            return "Already uploaded";
        }

        $filePath = Storage::disk('public')->putFileAs('photos', $form['photo'], $form['emp_code'].'.'.$form['photo']->getClientOriginalExtension());

        // $filePath = Storage::putFileAs(
        //     'photos', $form['photo'], $form['emp_code'].'.'.$form['photo']->getClientOriginalExtension()
        // );

        if ($filePath) {
            
            $employee->photo_uploaded = true;
            $employee->save();

            return 'Successfully saved';
        }

        return 'Something went wrong';
        
    }


    public function bulkStore(Request $request)
    {
        // return $request->employees;
     
        foreach ($request->employees as $employee) {
            
            $newEmployee = new EmployeeFile();
            $newEmployee->emp_code = $employee['em_code'];
            $newEmployee->emp_full_name = $employee['first_name'] . " " . $employee['middle_name'] . " " . $employee['last_name'];
            $newEmployee->photo_uploaded = false;

            $newEmployee->save();
        }
        return $newEmployee;

    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\EmployeeFile  $empFile
     * @return \Illuminate\Http\Response
     */
    public function show(EmployeeFile $empFile)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  Illuminate\Http\Request  $request
     * @param  \App\Models\EmployeeFile  $empFile
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, EmployeeFile $empFile)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\EmployeeFile  $empFile
     * @return \Illuminate\Http\Response
     */
    public function destroy(EmployeeFile $empFile)
    {
        //
    }
}
