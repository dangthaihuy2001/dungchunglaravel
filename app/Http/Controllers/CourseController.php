<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Course;
use Illuminate\Support\Facades\DB;
use Helper;

class CourseController extends Controller
{
    //duyệt tất cả
    public function index(Request $request)
    {

        $per_page = $request->input('per_page');
        $course = Course::all();
        $course = Course::paginate($per_page);
        $data = [
            "course" => $course,
        ];

        return view('courses', $data);
    }
    //them khoa hoc
    public function store(Request $req)
    {
        $course = Course::create([
            'name' => $req->input('name'),
            'description' => $req->input('description'),
            'image' =>  Helper::uploadImage($req->file('image'), 'product'),
        ]);

        $course->save();
        return redirect('course');
    }
    public function editCourse(Request $req)
    {
        //dd($req->file('image'));
        Course::where('id', $req['id'])->update([
            'name' => $req->input('name'),
            'description' => $req->input('description'),
            'image' =>  Helper::uploadImage($req->file('image'), 'product'),
        ]);

        return redirect('course');
    }
    public function getCourseByID($id)
    {
        $course = Course::find($id);
        return view('editcourse',  $course);
    }
    public function destroy($id)
    {
        // dd($id);
        $course = Course::find($id);
        $course->delete();
        return redirect('course');
    }
}
