<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Administrator;
use Auth;
use Redirect;
use Illuminate\Support\MessageBag;
use DB;
use App\Exceptions\Handler;
use Illuminate\Support\Facades\Hash;
use App\Model\ParticipantModel;
use App\Traits\Fungsi;

class LoginController extends Controller
{
    public function login(Request $request)
    {
        //dd("okay");
        try {
            //dd("masuk sinni");
            //dd($request->username);
            if ($request->username == null || $request->username == '') {
                //dd("masuk sinni");
                $errors = new MessageBag(['username' => ['Username wajib diisi']]);
                return Redirect::back()->withErrors($errors);
            }
            //dd("masuk sanna");

            if ($request->password == null || $request->password == '') {
                $errors = new MessageBag(['password' => ['Password wajib diisi']]);
                return Redirect::back()->withErrors($errors);
            }

            // dd(Administrator::get());
            // dd($request->all());
            $cek = Administrator::where('email', $request->username)->whereNull('deleted_at')->get();
            // $cekok = Administrator::get();
            //dd($cek);
            if ($cek->isNotEmpty()) {
                $role = $cek[0]->id_role;
                //dd($role);
                // $status = DB::table('roles')->where('id',$role)->pluck('slug');

                // if ($status[0] != 'company') {
                if (Auth::guard('admin')->attempt(['email' => $request->username, 'password' => $request->password])) {
                    // dd("success");
                    $insert_log      = parent::LogAdmin(\Request::ip(),Auth::guard('admin')->user()->id,'Login ke System','Login');
                    //dd("sukses");
                    return redirect()->intended('/member/list');

                } else {
                    dd("eror");
                    $errors = new MessageBag(['password' => ['Your Email Or password invalid!. Please Check and Try Again!!!!']]);
                     return Redirect::back()->withErrors($errors);
                }
                // } else {
                //      $errors = new MessageBag(['password' => ['Email Tidak Terdaftar di System']]);
                //      return Redirect::back()->withErrors($errors);
                // }
            } else {
                dd("sukses eror");
                 $errors = new MessageBag(['password' => ['Email Tidak Terdaftar di System']]);
                 return Redirect::back()->withErrors($errors);
            }
        // dd($request->all());
        } catch (\Exception $e) {
            $data['code']    = 500;
            $data['message'] = $e->getMessage();
            $data['line'] = $e->getLine();
            $data['controller'] = 'LoginController@login';
            $insert_error = parent::InsertErrorSystem($data);
            $error = parent::sidebar();
            $error['id'] = $insert_error;
            return view('errors.index',$error); // jika Metode Get
            //return response()->json($data); // jika metode Post
        }
    }

    public function logout(){
        try {
            $id_admin = Auth::guard('admin')->user()->id;
            date_default_timezone_set('Asia/Jakarta');
            $insert_log      = parent::LogAdmin(\Request::ip(),Auth::guard('admin')->user()->id,'Keluar dari System','Log Out');
            Auth::guard('admin')->logout();
            return redirect('/');
        } catch (\Exception $e) {
            $data['code']    = 500;
            $data['message'] = $e->getMessage();
            $data['line'] = $e->getLine();
            $data['controller'] = 'LoginController@logout';
            $insert_error = parent::InsertErrorSystem($data);
            $error = parent::sidebar();
            $error['id'] = $insert_error;
            return view('errors.index',$error); // jika Metode Get
            //return response()->json($data); // jika metode Post
        }
    }
 
    public function randomPassword() {
        $alphabet = 'abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ1234567890!#$';
        $pass = array(); //remember to declare $pass as an array
        $alphaLength = strlen($alphabet) - 1; //put the length -1 in cache
        for ($i = 0; $i < 11; $i++) {
            $n = rand(0, $alphaLength);
            $pass[] = $alphabet[$n];
        }
        return implode($pass); //turn the array into a string
    }
    
}
