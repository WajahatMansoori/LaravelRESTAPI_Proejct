<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use DB;
use Config;
use App\Models\Marks;
use App\Models\Login;

class LoginController extends Controller
{
    public function CreateAccount(Request $request)
    {
        $login=new Login();
        $login->UserName=$request->input('UserName');
        $login->Password=$request->input('Password');
        $login->save();
        return response()->json([
            'Alert'=>"User has Been Created Successfully"
            ]);
    }

    public function Login(Request $request)
    {             
                    $login=Login::all();
                    $login->UserName=$request->input('UserName');
                    $login->Password=$request->input('Password');                    
            
                    $DB_Settings=array(
                        'Host_Name'         => Config::get('constants.Db_Setting.Host_Name'),
                        'Database_Name'     => Config::get('constants.Db_Setting.Database_Name'),
                        'UserName'          => Config::get('constants.Db_Setting.UserName'),
                        'Password'          => Config::get('constants.Db_Setting.Password'),
                    );
                $con=mysqli_connect($DB_Settings['Host_Name'],$DB_Settings['UserName'],$DB_Settings['Password'],$DB_Settings['Database_Name']);        
                $Query="SELECT *from logins where UserName='$login->UserName' && Password='$login->Password'";
                $Row=mysqli_query($con,$Query);
                $TotalRow=mysqli_num_rows($Row);
                if($TotalRow==1)
                {
                    session_start();
                    $_SESSION['UserName']=$login->UserName;
                    return response()->json([
                        'Alert'=>'Login Successfully','UserName'=>$_SESSION['UserName'
                        ]]);
                }
                else
                {
                    return response()->json([
                        'Alert'=>'Invalid Creadentials'
                        ]);
                }
        
      
    }

    public function ForgetPassword(Request $request)
    {
        $login=Login::all();
        $login->UserName=$request->input('UserName');
        $DB_Settings=array(
            'Host_Name' => Config::get('constants.Db_Setting.Host_Name'),
            'Database_Name' => Config::get('constants.Db_Setting.Database_Name'),
            'UserName' => Config::get('constants.Db_Setting.UserName'),
            'Password' => Config::get('constants.Db_Setting.Password'),
        );
        $con=mysqli_connect($DB_Settings['Host_Name'],$DB_Settings['UserName'],$DB_Settings['Password'],$DB_Settings['Database_Name']);
        
        $Query="SELECT *from logins where UserName='$login->UserName' ";
        $Row=mysqli_query($con,$Query);
        $Data=mysqli_fetch_array($Row);
        $TotalRow=mysqli_num_rows($Row);
        if($TotalRow==1)
        {
            $login->Password=$request->input('Password');
           // $login->save();
           $UpdateQuery="UPDATE logins set Password='$login->Password'";
           $Run=mysqli_query($con,$UpdateQuery);
           
           if($Run==true)
           {
               return response()->json([
                   'Alert'=>'Password Updated Successfully'
                   ]);             
           }
           else
           {
               return response()->json([
                   'Alert'=>'Password Not Updated'
                   ]);
           }
            //return response()->json(['Alert'=>'Password Updated']);
        }
        else
        {
            return response()->json([
                'Alert'=>'User Not Found'
                ]);
        }

    }

    public function AddMarks(Request $request)
    {
        try
        {
            session_start();
            if($_SESSION['UserName']!=null)
            {
                $marks=new Marks();
                $marks->Course_Code=$request->input('Course_Code');
                $marks->Total_Marks=$request->input('Total_Marks');
                $marks->Obtained_Marks=$request->input('Obtained_Marks');
                $marks->Percentage=($marks->Obtained_Marks / $marks->Total_Marks)*100;
                if($marks->Percentage>=60)
                {
                    $marks->Status='Pass';
                }
                else
                {
                    $marks->Status='Fail';
                }
                $marks->StudentName=$request->input('StudentName');
                $marks->save();
                return response()->json([
                    'Alert'=>'Marks Uploaded Successfully',
                    'Admin'=>$_SESSION['UserName']
                    ]);
            }
        }
        catch(\Exception $e)
        {
            return response()->json([
                'Alert'=>'Unauthorize User'
                ]);
        }
    }

    public function UpdateMarks(Request $request,$id)
    {
        try
        {
            session_start();
            if($_SESSION['UserName']!=null)
            {
                $marks=Mark::find($id);
                $marks->Course_Code=$request->input('Course_Code');
                $marks->Total_Marks=$request->input('Total_Marks');
                $marks->Obtained_Marks=$request->input('Obtained_Marks');
                $marks->Percentage=($marks->Obtained_Marks/$marks->Total_Marks)*100;
                if($marks->Percentage>=60)
                {
                    $marks->Status='Pass';
                }
                else
                {
                    $marks->Status='Fail';
                }
                $marks->StudentName=$request->input('StudentName');
                $marks->save();
                return response()->json([
                    'Alert'=>'Data Updated Successfully',
                    'By Admin'=>$_SESSION['UserName']
                ]);
            }
        }
        catch(\Exception $e)
        {
            return response()->json([
                'Alert'=>'Unauthorize User'
                ]);
        }
    }

    public function Logout()
    {
        session_start();
        try
        {
            if($_SESSION['UserName']!=null)
            {
                session_destroy();
                return response()->json(['Alert'=>"Logout Successfully"]);               
            }
        }
        catch(\Exception $e)
        {
            return response()->json(['Alert'=>'Your session Aleady Expired']);
        }
       
       
    }

}
