<?php

namespace App\Http\Controllers\Student;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Student;
use Auth;
use Hash;
class StudentController extends Controller
{
    public function index()
    {
        return view('student-index');
    }
    public function login(Request $request)
    {       
        $credentials = 
        [
            'username' => $request->username,
            'password' => $request->password,
        ];
        if(Auth::guard('student')->attempt($credentials))
        {
            return redirect('student/dashboard');
        }
        else
        {
            return redirect()->back()->with('flash','Invalid Username and Password Combination.');
        }
    }
    public function register(Request $request)
    {   
        if($request->isMethod('post'))
        {
           $data = request()->validate(
            [
                'fname' => 'required',
                'lname' => 'required',
                'address' => 'required',
                'contact' => 'required',
                'bday' => 'required',
                'gender' => 'required',
                'course' => 'required',
                'id_number' => 'required',
                'username' => 'required',
                'password' => 'required'
            ],
            [
                'fname.required' => 'Please fill up your First name.',
                'lname.required' => 'Please fill up your Last name.',
                'address.required' => 'Please fill up your Address.',
                'contact.required' => 'Please fill up your Contact number.',
                'bday.required' => 'Please fill up your Birthday.',
                'gender.required' => 'Please fill up your Gender.',
                'course.required' => 'Please fill up your Course.',
                'id_number.required' => 'Please fill up your ID number.',
                'username.required' => 'Please fill up your Username.',
                'password.required' => 'Please fill up your Password.',
                
            ]);
            $data['password'] = Hash::make($request->input('password'));
            Student::create($data);
            return redirect('student/index')->with('succ','You can now login using your login credentials');
        }
        return view('student-register');
    }
    public function dashboard()
    {
        return view('student-dashboard');
    }
    public function profile(Request $request, $id){
        if($request->isMethod('post'))
        {
           $student = Student::findorFail($id);
           $student->fname = $request->fname;
           $student->lname = $request->lname;
           $student->bday = $request->bday;
           $student->address = $request->address;
           $student->contact = $request->contact;
           $student->course = $request->course;
           $student->gender = $request->gender;
           $student->username = $request->username;
           $student->id_number = $request->id_number;
           $student->save();
           return redirect()->back()->with('succ','Profile updated successfully.');
        }
       $student = Student::findorFail($id);
       return view('student-profile')->with('student',$student);
    }
    public function logout(){
       Auth::guard('student')->logout();
       return redirect('student/index')->with('flash','You successfully logged out.');
    }
}
