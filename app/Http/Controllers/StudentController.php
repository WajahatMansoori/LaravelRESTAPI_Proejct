<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use DB;
use App\Models\Student;
use Validator;
class StudentController extends Controller
{
    public function Createstudent(Request $request)
    {   
        try
        {
            session_start();
            if($_SESSION['UserName']!=null)
            {
                $Std=new Student();    
                $Validator=Validator::make($request->all(),[
                    'Name'=>'required',
                    'Email'=>'required',
                    'Address'=>'required',
                ]);
                if($Validator->fails())
                {
                    return response()->json([
                        'Alert'=>'Validation Error'
                        ]);
                }
                $Std->Name=$request->input('Name');
                $Std->Email=$request->input('Email');
                $Std->Address=$request->input('Address');
                $Std->save();
                return response()->json([
                    'Alert'=>'Record has been created'
                    ]);
                
            }
        }
        catch(\Exception $e)
        {
            return response()->json([
                'Alert'=>'UnAuthorize User'
                ]);
        }
        
    }

    public function StudentLogin(Request $request)
    {

    }
}
