<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\File;
use App\Http\Requests\StoreStudentRequest;
use App\Http\Requests\UpdateStudentRequest;
use App\Models\Student;

class StudentController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $students = Student::all();
        return view('students.index',['students'=>$students]);

        //or
        //return view('students.index', compact('students'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('students.create-student');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \App\Http\Requests\StoreStudentRequest  $request
     * @return \Illuminate\Http\Response
     */
    public function store(StoreStudentRequest $request)
    {
        
        $student = new Student;
        $student->name = $request->name;
        $student->course = $request->course;

        if($request->hasfile('image')){
            $file = $request->file('image');
            $extention = $file->getClientOriginalExtension();
            $filename = time().'.'.$extention;
            $file->move('uploads/students', $filename);
            $student->image = $filename;
        } else {
                dd('No image was found');
            }
        //when you return false you found : 403 THIS ACTION IS UNAUTHORIZED.
        //solved: Reuests > StoreStudentReuest.php > authorize return true

        $student->save();
        return redirect()->back()->with('status', 'Student Added Successfully.');
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Student  $student
     * @return \Illuminate\Http\Response
     */
    public function show(Student $student)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Student  $student
     * @return \Illuminate\Http\Response
     */
    public function edit( $id)
    {
        $studet = Student::find($id);
        return view('students.edit',['student'=>$student]);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \App\Http\Requests\UpdateStudentRequest  $request
     * @param  \App\Models\Student  $student
     * @return \Illuminate\Http\Response
     */
    public function update(UpdateStudentRequest $request, Student $student)
    {
        $student = Student::find($id);
        $student->name = $request->name;
        $student->course = $request->course;

        if($request->hasfile('image')){
            $destination = 'uploads/students'.$student->image;
            if(File::exists($destination)){
                File::delete($destination);
            }

            $file = $request->file('image');
            $extention = $file->getClientOriginalExtension();
            $filename = time().'.'.$extention;
            $file->move('uploads/students', $filename);
            $student->image = $filename;
        } else {
                dd('No image was found');
            }
            $student->update();
        return redirect()->back()->with('status', 'Student update Successfully.');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Student  $student
     * @return \Illuminate\Http\Response
     */
    public function destroy(Student $student)
    {
        $student = Student::find($id);
        $destination = 'uploads/students'.$student->image;
            if(File::exists($destination)){
                File::delete($destination);
            }
            $student->delete();
        return redirect()->back()->with('status', 'Student Deleted Successfully.');
    }
}