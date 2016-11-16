<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>Upload file</title>
</head>

<body>
<h3>Upload excel file</h3>
<?php
	require_once 'PHPExcel/PHPExcel.php';

  if(isset($_POST['ok'])){ // Người dùng đã ấn submit
      if($_FILES['file']['name'] != NULL){ // Đã chọn file
           // Tiến hành code upload file
		   if($_FILES['file']['type'] == "application/vnd.openxmlformats-officedocument.spreadsheetml.sheet"){
			  // là file ảnh
			  // Tiến hành code upload
			  if($_FILES['file']['size'] > 1048576){
			      echo "File không được lớn hơn 1mb";
			  }else{
			      // file hợp lệ, tiến hành upload
			      $path = "fileUpload/"; // ảnh upload sẽ được lưu vào thư mục data
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

			      //doc readfile
			      $filename = 'fileUpload/'.$name;
					$inputFileType = PHPExcel_IOFactory::identify($filename);
					$objReader = PHPExcel_IOFactory::createReader($inputFileType);

					$objReader->setReadDataOnly(true);

					/**  Load $inputFileName to a PHPExcel Object  **/
					$objPHPExcel = $objReader->load("$filename");

					$total_sheets=$objPHPExcel->getSheetCount();

					$allSheetName=$objPHPExcel->getSheetNames();
					$objWorksheet  = $objPHPExcel->setActiveSheetIndex(0);
					$highestRow    = $objWorksheet->getHighestRow();
					$highestColumn = $objWorksheet->getHighestColumn();
					$highestColumnIndex = PHPExcel_Cell::columnIndexFromString($highestColumn);
					$arraydata = array();
					for ($row = 2; $row <= $highestRow;++$row)
					{
						for ($col = 0; $col <$highestColumnIndex;++$col)
						{
							$value=$objWorksheet->getCellByColumnAndRow($col, $row)->getValue();
							$arraydata[$row-2][$col]=$value;
						}
					}

					echo '<pre>';
					print_r($arraydata);
					echo '</pre>';

					// unlink($path.$name);
			  }
			}else{
			  // không phải file ảnh
			  echo "Kiểu file không hợp lệ";
			}
	  }else{
           echo "Vui lòng chọn file";
      }
  }
?>
<form action="upload.php" method="post" enctype="multipart/form-data">
	Select file : <input type="file" name="file" size="20" /><br />
    <input type="submit" value="Upload" name="ok" />
</form>
</body>
</html>