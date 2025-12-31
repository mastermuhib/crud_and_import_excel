<?php

namespace App\Http\Controllers;

use App\Model\RoleModel;
use App\Model\MemberModel;
use App\Model\DistrictModel;
use App\Model\KlasifikasiModel;
use App\Model\CityModel;
use App\Model\UserModel;
use Maatwebsite\Excel\Facades\Excel;
use App\Model\TemporaryMonggoDB;
use App\Classes\upload;
use App\Traits\Fungsi;
use Auth;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use App\Exports\ExportPairingResults;
use App\Exceptions\Handler;

class MemberController extends Controller
{
    public function index()
    {
        //dd("cukk");
        // dd(Kelas::get());
        //dd(Auth::guard('admin')->user()->id_scholl);
        try {
            $data = parent::sidebar();
            if ($data['access'] == 0) {
                return redirect('/');
            } else {
                $role_id           = Auth::guard('admin')->user()->id_role;
                $data['txt_button'] = "Tambah Member Baru";
                $data['href'] = "member/action/add";
                
                return view('member.index', $data);
            }
        } catch (\Exception $e) {
            $data['code']    = 500;
            $data['message'] = $e->getMessage();
            $data['line'] = $e->getLine();
            $data['controller'] = 'MemberController@index';
            $insert_error = parent::InsertErrorSystem($data);
            $error = parent::sidebar();
            $error['id'] = $insert_error;
            return view('errors.index',$error); // jika Metode Get
            //return response()->json($data); // jika metode Post
        }
    }

    public function import()
    {
        
        try {
            $data = parent::sidebar();
            if ($data['access'] == 0) {
                return redirect('/');
            } else {
                $role_id           = Auth::guard('admin')->user()->id_role;
                
                return view('member.import', $data);
            }
        } catch (\Exception $e) {
            $data['code']    = 500;
            $data['message'] = $e->getMessage();
            $data['line'] = $e->getLine();
            $data['controller'] = 'MemberController@import';
            $insert_error = parent::InsertErrorSystem($data);
            $error = parent::sidebar();
            $error['id'] = $insert_error;
            return view('errors.index',$error); // jika Metode Get
            //return response()->json($data); // jika metode Post
        }
    }

    public function pairing()
    {
        
        try {
            $data = parent::sidebar();
            if ($data['access'] == 0) {
                return redirect('/');
            } else {
                $role_id           = Auth::guard('admin')->user()->id_role;
                //dd($data['id_adm_dept']);
                return view('member.pairing', $data);
            }
        } catch (\Exception $e) {
            $data['code']    = 500;
            $data['message'] = $e->getMessage();
            $data['line'] = $e->getLine();
            $data['controller'] = 'MemberController@pairing';
            $insert_error = parent::InsertErrorSystem($data);
            $error = parent::sidebar();
            $error['id'] = $insert_error;
            return view('errors.index',$error); 
        }
    }

    public function post_impormembers(Request $request){
        
        try {
            
            $csvMimes = array('text/x-comma-separated-values', 'text/comma-separated-values', 'application/octet-stream', 'application/vnd.ms-excel', 'application/x-csv', 'text/x-csv', 'text/csv', 'application/csv', 'application/excel', 'application/vnd.msexcel', 'text/plain','csv, application/vnd.openxmlformats-officedocument.spreadsheetml.sheet','application/vnd.ms-excel');
            //dd($_FILES);
            //(isset($line[])) ? "" : null ; $originalString);
            
            if(empty($_FILES['file']['name'])) {
                $data['code']    = 500;
                $data['message'] = "File Kosong";
            } else {
                if(in_array($_FILES['file']['type'],$csvMimes)){
                    if(is_uploaded_file($_FILES['file']['tmp_name'])){ 
                        // check file size
                        if(filesize($_FILES['file']['tmp_name']) > 51200000000000000) {
                            $data['code']    = 500;
                            $data['message'] = "File Maksimal 500 Mb";
                        } else {
                            //open uploaded csv file with read only mode
                            $csvFile = fopen($_FILES['file']['tmp_name'], 'r');
                            //skip first line
                            fgetcsv($csvFile);
                            //parse data from csv file line by line
                            //$data = fgetcsv($csvFile);
                            while(($line = fgetcsv($csvFile)) !== FALSE){
                                                                                           
                                $nkk = (isset($line[0])) ? $line[0] : null ;
                                $nik = (isset($line[1])) ? $line[1] : null ;  
                                $name = (isset($line[2])) ? $line[2] : null ; 
                                $birthplace = (isset($line[3])) ? $line[3] : null ; 
                                $birthdate = (isset($line[4])) ? $line[4] : null ; 
                                $status = (isset($line[5])) ? $line[5] : null ; 
                                $gender = (isset($line[6])) ? $line[6] : null ; 
                                $address = (isset($line[7])) ? $line[7] : null ; 
                                $rt = (isset($line[8])) ? $line[8] : null ; 
                                $rw = (isset($line[9])) ? $line[9] : null ; 
                                
                                
                                $insert_class = array(
                                    'nkk' => trim(preg_replace('/\s\s+/', ' ',$nkk)),
                                    'nik' => trim(preg_replace('/\s\s+/', ' ',$nik)),
                                    'name' => trim(preg_replace('/\s\s+/', ' ',$name)),
                                    'birthplace' => trim(preg_replace('/\s\s+/', ' ',$birthdate)),
                                    'birthday' => trim(preg_replace('/\s\s+/', ' ',$birthdate)),
                                    'gender' => trim(preg_replace('/\s\s+/', ' ',$gender)),
                                    'address' => trim(preg_replace('/\s\s+/', ' ',$address)),
                                    'rt' => trim(preg_replace('/\s\s+/', ' ',$rt)),
                                    'rw' => trim(preg_replace('/\s\s+/', ' ',$rw)),                                                      
                                    'created_at' => date('Y-m-d H:i:s'),
                                    'updated_at' => date('Y-m-d H:i:s')
                                );
                                $id_class = DB::table('members')->insertGetId($insert_class);                               

                            }
                        }
                        
                    }                   
                    //close opened csv file
                    fclose($csvFile);
        
                    $data['code']    = 200;
                    $data['message'] = "Berhasil Mengimport Data Member";
                } else {
                    $data['code']    = 500;
                    $data['message'] = "Tipe File Tidak Sesuai. Pastikan file bertipe csv";
                }
                return response()->json($data);
            }
        } catch (\Exception $e) {
            $data['code']    = 500;
            $data['message'] = $e->getMessage();
            $data['line'] = $e->getLine();
            $data['controller'] = 'MemberController@post_impormembers';
            $insert_error = parent::InsertErrorSystem($data);
            return response()->json($data); // jika metode Post
        }

    }

    
    public function calculate(Request $request)
    {
        $posts = $this->DataMember($request);

        
        $total = $posts->select('gender')->count();
        $male = $posts->where('gender','L')->count();
        $female = $total - $male;
        $data['male'] = number_format($male);
        $data['female'] = number_format($female);
        $data['total'] = number_format($total);
        return response()->json($data);

    }
    
    public function list_data(Request $request)
    {

        $totalData = DB::table('members')->count();
        $totalFiltered = $totalData;
        $limit = $request->input('length');
        $start = $request->input('start');
        $dir   = $request->input('order.0.dir');
        $search = $request->search;
        $status = $request->status;

        $posts = $this->DataMember($request);

        $posts = $posts->select('p.*','v.name as desa','c.name as kecamatan');
        
        $totalFiltered = $posts->count();
        $posts = $posts->limit($limit)->offset($start)->get();

        $data = array();
        if (!empty($posts)) {
            $no = $start;
            foreach ($posts as $d) {
                $no = $no + 1;

                $action = '<div style="float: left; margin-left: 5px;"><a href="/medical-record/pemeriksaan-tahunan/'.base64_encode($d->dpid).'" >
                                <button type="button" class="btn btn-warning btn-sm" style="min-width: 110px;margin-left: 2px;margin-top:3px;text-align:left"><i class="fa fa-eye"></i> Detail</button></a>
                            </div>';

                $column['no']       = $no;
                $column['kec']      = $d->kecamatan;
                $column['kel']      = $d->desa;
                $column['nik']      = $d->nik;
                $column['name']     = $d->name;
                $column['gender']   = $d->gender;
                $column['status']   = $d->status;
                $column['tps']      = $d->tps;
                $column['actions']  = $action;
                $data[]             = $column;

            }
        }

        $json_data = array(
            "draw"            => intval($request->input('draw')),
            "recordsTotal"    => intval($totalData),
            "recordsFiltered" => intval($totalFiltered),
            "data"            => $data,
        );
        echo json_encode($json_data);
    }

    public function DataMember($request){
        $search      = $request->search;
        
        $posts = DB::table('members as p')->leftJoin('villages as v','v.id','p.id_village')->leftJoin('districts as c','c.id','v.district_id');
        if ($search != null) {
            $posts = $posts->where(function ($query) use ($search,$request) {
                $query->where('p.name','ilike', "%{$search}%");
                $query->orWhere('v.name','ilike', "%{$search}%");
                $query->orWhere('c.name','ilike', "%{$search}%");
                $query->orWhere('nik','ilike', "%{$search}%"); 
                $query->orWhere('nkk','ilike', "%{$search}%");  
            });
        }

        
        if ($request->id_kec != null) {
            $posts = $posts->where('v.district_id',$request->id_kec);
        }

        if ($request->id_kel != null) {
            $posts = $posts->where('id_village',$request->id_kel);
        }

        if ($request->gender != null) {
            $posts = $posts->where('gender',$request->gender);
        }

        if (isset($request->status)) {
            $status      = $request->status;
            if (count($status) > 0) {
                $posts = $posts->whereIn('p.status',$status);
            }
        }

        if (isset($request->klasifikasi)) {
            $klasifikasi = $request->klasifikasi;
            if (count($klasifikasi) > 0) {
                $posts = $posts->whereIn('p.clasification',$klasifikasi);
            }
        }

        return $posts;
    }


    public function add()
    {
        $data = parent::sidebar();
        if ($data['access'] == 0) {
            return redirect('/');
        } else {
            $role_id           = Auth::guard('admin')->user()->id_role;
            $data['data_scholl'] = SchollModel::whereNull('deleted_at')->get();
            $data['data_city'] = CityModel::whereNull('deleted_at')->get();
            $data['header_name'] = "Tambah Siswa Baru";
            //dd($data['id_adm_dept']);
            return view('member.add', $data);
        }
    }

    public function post(Request $request)
    {
        // id bermasalah
        try {
            $input = $request->except('_token');

            if ($request->file('image')) {
                $input['image']  = parent::uploadFileS3($request->file('image'));
            } 

            $input['created_at'] = date('Y-m-d H:i:s');
            $input['updated_at'] = date('Y-m-d H:i:s');
            $insert = DB::table('Members')->insertGetId($input);
            if ($insert) {
                $insert_class = DB::table('Member_class_relations')->insert(['id_Member'=>$insert,'id_class'=>$request->id_class,'tgl_masuk'=>$request->tgl_masuk,'description'=>'Siswa Baru','is_active'=>1,'created_at'=>date('Y-m-d H:i:s'),'updated_at'=>date('Y-m-d H:i:s')]);
                $insert_log      = parent::LogAdmin(\Request::ip(),Auth::guard('admin')->user()->id,'Menambah Data Siswa '.$request->Member_name.'','siswa');
                $data['code']    = 200;
                $data['message'] = 'Berhasil menambah data siswa';
                return response()->json($data);
            } else {
                $data['code']    = 500;
                $data['message'] = 'Maaf Ada yang Error ';
                return response()->json($data);
            }
        } catch (\Exception $e) {
            $data['code']    = 500;
            $data['message'] = $e->getMessage();
            $data['line'] = $e->getLine();
            $data['controller'] = 'MemberController@post';
            $insert_error = parent::InsertErrorSystem($data);
            return response()->json($data); // jika metode Post
        }
    }

    public function update(Request $request)
    {
        try {
        //dd($request->nik);
            $input = $request->except('_token');

            if ($request->file('image')) {
                $input['image']  = parent::uploadFileS3($request->file('image'));
            } 
            $data['updated_at'] = date('Y-m-d H:i:s');

            $insert = MemberModel::where('id', $request->id)->update($input);
            if ($insert) {
                $cek_same_class = DB::table('Member_class_relations')->where('id_Member',$request->id)->where('is_active',1)->pluck('id_class');
                if ($cek_same_class != $request->id_class) {
                    $nonactive = DB::table('Member_class_relations')->where('id_Member',$request->id)->update(['is_active'=>0]);
                    $insert_class = DB::table('Member_class_relations')->insert(['id_Member'=>$request->id,'id_class'=>$request->id_class,'tgl_masuk'=>$request->tgl_masuk,'description'=>'Pindah kelas','is_active'=>1,'created_at'=>date('Y-m-d H:i:s'),'updated_at'=>date('Y-m-d H:i:s')]);
                }
                $insert_log      = parent::LogAdmin(\Request::ip(),Auth::guard('admin')->user()->id,'Mengupdate Data Siswa '.$request->Member_name.'','kelas');
                $data['code']    = 200;
                $data['message'] = 'Berhasil Mengupdate data kelas';
                return response()->json($data);
            } else {
                $data['code']    = 500;
                $data['message'] = 'Maaf Ada yang Error ';
                $data['insert']  = $request->id;
                return response()->json($data);
            }
        } catch (\Exception $e) {
            $data['code']    = 500;
            $data['message'] = $e->getMessage();
            $data['line'] = $e->getLine();
            $data['controller'] = 'MemberController@update';
            $insert_error = parent::InsertErrorSystem($data);
            return response()->json($data); // jika metode Post
        }
    }

    public function detail($ids)
    {
        try {
            $id = base64_decode($ids);
            //dd($id);
            $admin = MemberModel::find($id);
            //dd($admin);
            
            $data = parent::sidebar();
            if ($admin == null) {
                //dd("ID Tidak ditemukan");
                return view('errors.not_found',$data);
            }
            //dd("masuk sini");
            if ($data['access'] == 0) {
                return redirect('/');
            } else {
                $role_id           = Auth::guard('admin')->user()->id_role;
                $data['code']      = 200;
                $data['data']      = $admin;
                $data['data_role'] = RoleModel::whereNull('deleted_at')->where('status', 1)->get();
                $data['data_scholl'] = SchollModel::whereNull('deleted_at')->get();
                $data['data_city'] = CityModel::whereNull('deleted_at')->get();
                $data['id_scholl'] = $this->GetScholl($admin->id,'id_scholl');
                $data['id_class'] = $this->GetClass($admin->id,'id_class');
                $data['tgl_masuk'] = $this->GetClass($admin->id,'tgl_masuk');
                return view('member.dialog_edit', $data);
            }
        } catch (\Exception $e) {
            $data['code']    = 500;
            $data['message'] = $e->getMessage();
            $data['line'] = $e->getLine();
            $data['controller'] = 'MemberController@detail';
            $insert_error = parent::InsertErrorSystem($data);
            $error = parent::sidebar();
            $error['id'] = $insert_error;
            return view('errors.500',$error); // jika Metode Get
            //return response()->json($data); // jika metode Post
        }
    }

    
    public function download_pairing($coloumn){
       
        $data['data'] = TemporaryMonggoDB::select('*')->get()->toArray();
        //dd($data['data']);
        $data['coloumn'] = $coloumn;
        $date = date('d F Y H:i'); 
        TemporaryMonggoDB::whereNotNull('created_at')->delete();   

        return Excel::download(new ExportPairingResults($data), 'Hasil Sanding Data '.$date.'.xlsx');
    }


}