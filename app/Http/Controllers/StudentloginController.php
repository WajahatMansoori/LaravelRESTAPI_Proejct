<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\StudentLogin;
use App\Models\Marks;
use Config;

class StudentloginController extends Controller
{
    public function CreateStudentAccount(Request $request)
    {
        try
       {
            session_start();
            if($_SESSION['UserName']!=null)
            {
                $stdLogin=new StudentLogin();
                $stdLogin->UserName=$request->input('UserName');
                $stdLogin->Password=$request->input('Password');
                $AccessCode=rand(1234,9999);
                $stdLogin->save();
                return response()->json([
                    'Alert'=>'Student Account Has been Created','Access Code'=>$AccessCode
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
        $stdLogin=StudentLogin::all();
        $stdLogin->UserName=$request->input('UserName');
        $stdLogin->Password=$request->input('Password');
        
        $Db_Settings=array(
            'Host_Name'         => Config::get('constants.Db_Setting.Host_Name'),
            'Database_Name'     => Config::get('constants.Db_Setting.Database_Name'),
            'UserName'          => Config::get('constants.Db_Setting.UserName'),
            'Password'          => Config::get('constants.Db_Setting.Password'),
        );
        
        $con=mysqli_connect($Db_Settings['Host_Name'],$Db_Settings['UserName'],$Db_Settings['Password'],$Db_Settings['Database_Name']);
        $Query1="SELECT *from student_logins where UserName='$stdLogin->UserName' && Password='$stdLogin->Password'";
        $Row1=mysqli_query($con,$Query1);
        $TotalRowStd=mysqli_num_rows($Row1);
        if($TotalRowStd==1)
        {
            session_start();
            $_SESSION['Student_Name']=$stdLogin->UserName;
            $MarkQuery="SELECT *from marks where StudentName='$stdLogin->UserName'";
            $RunMarkQuery=mysqli_query($con,$MarkQuery);
            $Total=mysqli_num_rows($RunMarkQuery);
            //$Record=mysqli_fetch_array($RunMarkQuery);
            if($Total!=0)
            {
                while($Record=mysqli_fetch_array($RunMarkQuery))
                {
                    $Data=array(
                        "Course_Code" => $Record['Course_Code'],
                        "Total_Marks" => $Record['Total_Marks'],
                        "Obtained_Marks" => $Record['Obtained_Marks'],
                        "Percentage" => $Record['Percentage'],
                        "Status" => $Record['Status'],
                        //"Student_Name" => $Record['StudentName'],
                    );
                    // $Marks=Marks::all();
                    return response()->json([
                        'Alert'=>'Login Successfully',
                        'UserName'=>$_SESSION['Student_Name'],
                        'Details' => $Data             
                    ]);
                }
        }
        }
        else
        {
            return response()->json([
                'Alert'=>'Invalid Cradentials'
                ]);
        }
        
    }
}
