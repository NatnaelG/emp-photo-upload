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
    public function index(Request $request)
    {
        if ($request->filter) {
            return EmployeeFile::where('photo_uploaded', false)->get();
        }
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
        $form = $request->validate([
            'emp_code' => 'required',
            'photo' => 'required|file'
        ]);

        $employee = EmployeeFile::where('emp_code', $form['emp_code'])->first();

        if ($employee->photo_uploaded) {
            return response()->json(
                [
                    'errors' => [
                        'emp_code' => 'Already uploaded',
                    ]
                ],
                400
            );
        }

        $filePath = Storage::disk('public')->putFileAs('photos', $form['photo'], $form['emp_code'].'.'.$form['photo']->getClientOriginalExtension());

        if ($filePath) {
            
            $employee->photo_uploaded = true;
            $employee->save();

            return response()->json(
                [
                    'data' => [
                        'message' => 'Successfully saved',
                    ]
                ],
                200
            );
        }

        return response()->json(
            [
                'errors' => [
                    'emp_code' => 'Something went wrong',
                ]
            ],
            400
        );
        
    }


    public function bulkStore(Request $request)
    {
        foreach ($request->employees as $employee) {
            
            $newEmployee = new EmployeeFile();
            $newEmployee->emp_code = $employee['em_code'];
            $newEmployee->emp_full_name = $employee['first_name'] . " " . $employee['middle_name'] . " " . $employee['last_name'];

            $newEmployee->save();
        }

        return response()->json(
            [
                'data' => [
                    'last_employee' => $newEmployee,
                    'message' => 'Successfully saved',
                ]
            ],
            200
        );

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
