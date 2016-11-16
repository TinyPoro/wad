<?php
namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Http\Requests;
 
use App\tai_khoan;
use App\giang_vien;
use App\sinh_vien;
use App\linh_vuc;
use App\bo_mon;
use App\ctdt;
use App\de_tai;
use App\khoa_hoc;
use App\linh_vuc_has_giang_vien;
use App\khoa;

use Excel;


class dtbController extends Controller
{
    public function signin() {
      return view('signin');
    }

    public function checkSignin() {
      include "C:/xampp/htdocs/web/resources/views/md5.php";
      $name = $_POST['name'];
      $tai_khoan = tai_khoan::where('username', $name)->first();

      if($tai_khoan == NULL || $tai_khoan->activated == 0) {
        return view('signin');
      }

      $password = encryptIt($_POST['password']);
      if($tai_khoan->password == $password && $tai_khoan->activated == 1) {
        session(['userid' => $tai_khoan->id]);
      } 

      return view('welcome');
    }

    public function logout() {
      session()->forget('userid');

      return view('welcome');
    }

    public function update() {
      $tai_khoan = tai_khoan::where('id', session('userid'))->first();
      $loai_tai_khoan = $tai_khoan->loai_tai_khoan;
      return view('update')->with("loai_tai_khoan", $loai_tai_khoan);
    }

    public function edit() {
      include "C:/xampp/htdocs/web/resources/views/md5.php";
       $tai_khoan = tai_khoan::where('id', session('userid'))->first();
       $tai_khoan->username = $_POST['ma'];
       $tai_khoan->password = encryptIt($_POST['password']);
       $tai_khoan->email = $_POST['email'];

       $tai_khoan->save();

       if($accType == "giang_vien") {
          $giang_vien = giang_vien::where('id', '=', session('userid'))->first();

          $bo_mon = bo_mon::where('ten', '=', $_POST['don_vi'])->first();

          $giang_vien->ho_ten = $_POST['ho_ten'];
          $giang_vien->id_bo_mon = $bo_mon->id;
                                
          $giang_vien->save();
        }

        if($accType == "sinh_vien") {
          $giang_vien = khoa::where('id', '=', session('userid'))->first();

          $khoa = khoa::where('id', '=', $_POST['ctdt'])->first();

          $giang_vien->ho_ten = $_POST['ho_ten'];
          $giang_vien->khoa_hoc = $_POST['khoa_hoc'];
          $giang_vien->id_khoa = $khoa->id;
                                
          $giang_vien->save();
        }                                           


      return view('welcome');
    }

    public function upload()
    {
        $dulieu_tu_input = NULL;
        return view('upload');
    }

    public function create() {
        $khoa_hocs = khoa_hoc::all();
        $ctdts = ctdt::all();
        $bo_mons = bo_mon::all();
        $khoas = khoa::all();
        $khoa_hoc_array = array();
        $ctdt_array = array();
        $bo_mon_array = array();
        $khoa_array = array();

        foreach($khoa_hocs as $x => $x_value) {
          $khoa_hoc_array[$x_value["khoa_hoc"]] = $x_value["khoa_hoc"];
        }


        foreach($ctdts as $x => $x_value) {
          $ctdt_array[$x_value["chuong_trinh"]] = $x_value["chuong_trinh"];
        }

        foreach($bo_mons as $x => $x_value) {
          $bo_mon_array[$x_value["ten"]] = $x_value["ten"];
        }

        foreach($khoas as $x => $x_value) {
          $khoa_array[$x_value["name"]] = $x_value["name"];
        }  
  
        return view('create')->with(["khoa_hoc_array"=>$khoa_hoc_array,
          "ctdt_array"=>$ctdt_array,
          "bo_mon_array"=>$bo_mon_array,
          "khoa_array"=>$khoa_array]);
    }

    public function store(Request $request)
    {
        $du_lieu_tu_input = $request->all();
        var_dump($du_lieu_tu_input);
        
        $tai_khoan = new tai_khoan;
        
        include "C:/xampp/htdocs/web/resources/views/sendMail.php";
        $password = rand_string(8);
        $encryptedPass = encryptIt($password);

        $tai_khoan->username = $du_lieu_tu_input["ma"];
        $tai_khoan->ten_rieng = $du_lieu_tu_input["ho_ten"];
        $tai_khoan->password = $encryptedPass;
        $tai_khoan->email = $du_lieu_tu_input["email"];
        $tai_khoan->loai_tai_khoan = $du_lieu_tu_input["loai_tai_khoan"];
 
        $tai_khoan->save();

        sm($du_lieu_tu_input["email"]);

        //tạo thông tin
        if($du_lieu_tu_input["loai_tai_khoan"] == "giang_vien") {
          $giang_vien = new giang_vien;

          $bo_mon = bo_mon::where('ten', '=', $du_lieu_tu_input['don_vi'])->first();

          $giang_vien->id_bo_mon = $bo_mon->id;
          $giang_vien->id = $tai_khoan->id;                    
          $giang_vien->save();
        }

        if($du_lieu_tu_input["loai_tai_khoan"] == "sinh_vien") {
          $sinh_vien = new sinh_vien;
          $khoa = khoa::where('name', '=', $du_lieu_tu_input['khoa'])->first();
          $sinh_vien->id = $tai_khoan->id;
          $sinh_vien->id_khoa = $khoa->id;  
          $sinh_vien->khoa_hoc = $du_lieu_tu_input["khoa_hoc"];
          $sinh_vien->ctdt = $du_lieu_tu_input["ctdt"];
          $sinh_vien->id_de_tai =1;
                              
          $sinh_vien->save();
        }
        
        return view('welcome');
    }

    public function activation() {
        include "C:/xampp/htdocs/web/resources/views/md5.php";

        $email = $_GET['email'];
        $email = str_replace(" ", "+", $email);
        $decryptedEmail = decryptIt($email);
        $msg = "";
        $tai_khoan = tai_khoan::where('email', $decryptedEmail)->first();

        if($tai_khoan == NULL) {
            $msg = "Mã kích hoạt sai!";
        }
        else if($tai_khoan->activated == 1) {
            $msg = "Tài khoản đã được kích hoạt";
        }
            else {
                $tai_khoan->activated = 1;
                $tai_khoan->save();

                $msg = "Kích hoạt tài khoản thành công!";
            }

        return view('activation')->with([
            'tai_khoan' => $tai_khoan,
            'msg' => $msg
        ]);
    }

    public function excelSend() {
      include "C:/xampp/htdocs/web/resources/views/sendMail.php";
  
          if($_FILES['file']['name'] != NULL){ // Đã chọn file
               // Tiến hành code upload file
            if($_FILES['file']['type'] == "application/vnd.openxmlformats-officedocument.spreadsheetml.sheet"){
               // là file excel
               // Tiến hành code upload
               if($_FILES['file']['size'] > 1048576){
                   echo "File không được lớn hơn 1mb";
               }else{
                   // file hợp lệ, tiến hành upload
                   $path = "C:/xampp/htdocs/web/resources/views/fileUpload/"; // ảnh upload sẽ được lưu vào thư mục data
                   $tmp_name = $_FILES['file']['tmp_name'];
                   $name = $_FILES['file']['name'];
                   $type = $_FILES['file']['type']; 
                   $size = $_FILES['file']['size']; 
                   // Upload file
                   move_uploaded_file($tmp_name,$path.$name);
                   echo "File uploaded! <br />";
                   echo "Tên file : ".$name."<br />";
                   echo "Kiểu file : ".$type."<br />";
                   echo "File size : ".$size;

                   //đọc file
                    $GLOBALS['accType'] = $_POST['loai_tai_khoan'];
                    
                    Excel::load('resources/views/fileUpload/'.$name, function($reader) {
                        $reader->each(function($sheet) {
                          $sheet->each(function($row) {

                            // tạo tài khoản
                              $tai_khoan = new tai_khoan;
                              
                              $password = rand_string(8);
                              $encryptedPass = encryptIt($password);

                              $tai_khoan->ten_rieng = $row["ho_ten"];
                              $tai_khoan->username = $row["ma"];
                              $tai_khoan->password = $encryptedPass;
                              $tai_khoan->email = $row["vnu_email"];
                              $tai_khoan->loai_tai_khoan = $GLOBALS['accType'];
                              
                              $tai_khoan->save();

                              //gửi mail kích hoạt
                            sm($row["vnu_email"]);

                            //tạo thông tin
                            if($accType == "giang_vien") {
                              $giang_vien = new giang_vien;

                              $bo_mon = bo_mon::where('ten', '=', $row['don_vi'])->first();

                              $giang_vien->id_bo_mon = $bo_mon->id;
                              
                              $giang_vien->save();
                            }

                            if($accType == "sinh_vien") {
                              $sinh_vien = new sinh_vien;

                              $khoa = khoa::where('name', '=', $row['khoa'])->first();

                              $sinh_vien->ctdt = $row["ctdt"];
                              $sinh_vien->khoa_hoc = $row["khoa_hoc"];
                              $sinh_vien->id_khoa = $khoa->id;
                              
                              $sinh_vien->save();
                            }
                          });
                        }); 
                    });

                    //xóa file sau khi lấy dữ liệu xong
                    unlink($path.$name);
               }
             }else{
               // không phải file excel
               echo "Kiểu file không hợp lệ";
             }
          }else{
               echo "Vui lòng chọn file";
          }
          
      
      return view('upload');
    }

    public function nhap_linh_vuc() {
      $linh_vucs = linh_vuc::all();

      return view('nhap_linh_vuc')->with('linh_vucs', $linh_vucs);
    }

    public function cap_nhat_linh_vuc(Request $request) {
      $du_lieu_tu_input = $request->all();

      // if($du_lieu_tu_input['huong_nghien_cuu'] != "") {
      //   $giang_vien = giang_vien::where('id', '=', session('userid'))->first();
      //   $giang_vien->huong_nghien_cuu = $du_lieu_tu_input['huong_nghien_cuu'];
      //   $giang_vien->save();
      // }

      foreach($du_lieu_tu_input as $x => $x_value) {
          echo "Key=" . $x . ", Value=" . $x_value;
          echo "<br>";

          if($x_value != "") {
            $linh_vuc_has_giang_vien = new linh_vuc_has_giang_vien;

            $linh_vuc_has_giang_vien->id_giang_vien = session('userid');
            $linh_vuc = linh_vuc::where('ten', '=', $x_value)->first();
            $linh_vuc_has_giang_vien->id_linh_vuc = $linh_vuc->id;
          }
      }

      return view('welcome');
    }

    public function search() {
      // $don_vis = don_vi::all();
       $giang_viens = giang_vien::all();
       $linh_vucs = linh_vuc::all();
      return view('search')->with([
        // 'don_vis' => $don_vis,
        'giang_viens' => $giang_viens,
        'linh_vucs' => $linh_vucs,
      ]);
    }

}
