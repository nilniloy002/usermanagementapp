<?php

namespace Vanguard\Http\Controllers\Web;
use Illuminate\Http\Request;
use \Vanguard\Admission;
use \Vanguard\Course;
use \Vanguard\Batch;
use \Vanguard\Payment;
use Vanguard\Http\Controllers\Controller;
use Illuminate\Support\Facades\Storage;

class CourseController extends Controller
{
    public function index()
    {
        $courses = Course::all();
        return view('courses.index', compact('courses'));
    }

    public function create()
    {
        $courses = Course::all();
        return view('courses.add-edit', compact('courses'),['edit' => false]);
    }

    public function store(Request $request)
    {
        // Validation logic here

        Course::create($request->all());

        return redirect()->route('courses.index')->with('success', 'Course created successfully.');
    }

    public function edit(Course $course)
    {
        return view('courses.add-edit', compact('course'),['edit' => true]);
    }

    public function update(Request $request, Course $course)
    {
        // Validation logic here

        $course->update($request->all());

        return redirect()->route('courses.index')->with('success', 'Course updated successfully.');
    }

    public function destroy(Course $course)
    {
        $course->delete();

        return redirect()->route('courses.index')->with('success', 'Course deleted successfully.');
    }
}
