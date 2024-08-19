<?php
session_start();
if(empty($_SESSION['SESSION_USER']) && empty($_SESSION['SESSION_ID'])){
    header('location:../../login/');
 exit;}
else {
require_once'../../../library/sw-config.php';
require_once'../../login/login_session.php';
include('../../../library/sw-function.php'); 

switch (@$_GET['action']){
/* -------  LOAD DATA ABSENSI----------*/
case 'absensi':
  $error = array();

   if (empty($_GET['id'])) {
      $error[] = 'ID tidak boleh kosong';
    } else {
      $id = mysqli_real_escape_string($connection, $_GET['id']);
  }

  if(isset($_POST['month']) OR isset($_POST['year'])){
      $bulan   = date ($_POST['month']);} 
  else{
      $bulan  = date ("m");
  }

  $hari       = date("d");
  //$bulan      = date ("m");
  $tahun      = date("Y");
  $jumlahhari = date("t",mktime(0,0,0,$bulan,$hari,$tahun));
  $s          = date ("w", mktime (0,0,0,$bulan,1,$tahun));
if (empty($error)) { 
echo'
<div class="table-responsive">
<table class="table table-bordered table-hover" id="swdatatable">
        <thead>
            <tr>
                <th class="align-middle" width="20">No</th>
                <th class="align-middle">Tanggal</th>
                <th class="align-middle text-center"><i class="fa fa-picture-o" aria-hidden="true"></i></th>
                <th class="align-middle text-center">Jam Masuk</th>
                <th class="align-middle text-center"><i class="fa fa-picture-o" aria-hidden="true"></i></th>
                <th class="align-middle text-center">Jam Pulang</th>
                <th class="align-middle text-center">Lokasi</th>
                <th class="align-middle">Status</th>
                <th class="align-middle text-right">Aksi</th>
            </tr>
        </thead>
        <tbody>';
      for ($d=1;$d<=$jumlahhari;$d++) {
            $warna      = '';
            $background = '';
            $status_hadir     = 'Tidak Hadir';
          if (date("l",mktime (0,0,0,$bulan,$d,$tahun)) == "Sunday") {
            $warna='#ffffff';
            $background ='#005CAA';
            $status_hadir ='Libur Akhir Pekan';
        }
      $date_month_year = ''.$year.'-'.$month.'-'.$d.'';

      if(isset($_POST['month']) OR isset($_POST['year'])){
        $month = $_POST['month'];
        $year  = $_POST['year'];
        $filter ="employees_id='$id' AND presence_date='$date_month_year' AND MONTH(presence_date)='$month' AND year(presence_date)='$year' AND employees_id='$id'";
      } 
      else{
        $filter ="employees_id='$id' AND  presence_date='$date_month_year' AND MONTH(presence_date) ='$month' AND employees_id='$id'";
      }

      $query ="SELECT employees.id,shift.shift_id,shift.time_in,shift.time_out FROM employees,shift WHERE employees.shift_id=shift.shift_id AND employees.id='$id'";
      $result = $connection->query($query);
      $row    = $result->fetch_assoc();


      $query_shift ="SELECT time_in,time_out FROM shift WHERE shift_id='$row[shift_id]'";
      $result_shift = $connection->query($query_shift);
      $row_shift = $result_shift->fetch_assoc();
      $shift_time_in = $row_shift['time_in'];
      $newtimestamp = strtotime(''.$shift_time_in.' + 05 minute');
      $newtimestamp = date('H:i:s', $newtimestamp);

      $query_absen ="SELECT presence_id,presence_date,time_in,time_out,picture_in,picture_out,present_id,presence_address,information,TIMEDIFF(TIME(time_in),'$shift_time_in') 
      AS selisih,
      if (time_in>'$shift_time_in','Telat',
      if(time_in='00:00:00','Tidak Masuk','Tepat Waktu')) 
      AS status FROM presence WHERE $filter ORDER BY presence_id DESC";
      $result_absen = $connection->query($query_absen);
      $row_absen = $result_absen->fetch_assoc();

      $querya= "SELECT presence_id, presence_date, time_in, time_out, picture_in, picture_out, present_id, presence_address, information, TIMEDIFF(TIME(time_in), '$shift_time_in') AS selisih, IF (time_in > '$shift_time_in', 'Telat', IF(time_in = '00:00:00', 'Tidak Masuk', 'Tepat Waktu')) AS status, IFNULL(leave_request.status, 'Pending') as leave_status, present_status.present_name as present_name FROM presence LEFT JOIN leave_request ON presence.presence_date = leave_request.leave_date LEFT JOIN present_status ON presence.present_id = present_status.present_id WHERE $filter ORDER BY presence_id DESC";

$resulta = $connection->query($querya);
$rowa= $result_absen->fetch_assoc();

// Status Kehadiran
if (empty($row_absen['time_in'])) {
    if (date("l", mktime(0, 0, 0, $bulan, $d, $tahun)) === "Sunday") {
        $status_hadir = 'Libur Akhir Pekan';
        $background = '#005CAA';
    } else {
        $status_hadir = '<span class="label label-danger">Tidak Hadir</span>';
    }
    $time_in = $row_absen['time_in'];
} else {
    $status_hadir = '<label class="label label-warning">' . $row_absen['present_name'] . '</label>';
    $time_in = $row_absen['time_in'];
}

// Status Absensi Jam Masuk
if($row_absen['status']=='Telat'){
    $status_time_in ='<label class="label label-danger">'.$row_absen['status'].'</label>';
}
elseif ($row_absen['status']=='Tepat Waktu') {
    $status_time_in ='<label class="label label-info">'.$row_absen['status'].'</label>';
}
else{
    $status_time_in ='<label class="label label-danger">'.$row_absen['status'].'</label>';
}

// Status Cuti
if ($row_absen['leave_status'] != 'Pending') {
    $status_cuti = '<label class="label label-success">Cuti</label>';
} else {
    $status_cuti = '';
}
        list($latitude,  $longitude) = explode(',', $row_absen['presence_address']);

        
        echo''.$geo_location.'
         <tr style="background:'.$background.';color:'.$warna.'">
            <td class="text-center">'.$d.'</td>
            <td>'.format_hari_tanggal($date_month_year).'</td>';
            if (date("l",mktime (0,0,0,$bulan,$d,$tahun)) == "Sunday"){
              if($row_absen['time_in'] ==''){
                echo'
                <td class="text-center">Libur Akhir Pekan</td>
                <td class="text-center">Libur Akhir Pekan</td>';
              }
              else{
                echo'
                <td class="text-center picture">';
                  if($row_absen['picture_in'] ==NULL){
                    echo'<img src="../timthumb?src='.$site_url.'/content/avatar.jpg&h=40&w=40">';}
                  else{
                    echo'
                      <a class="image-link" href="'.$site_url.'/content/present/'.$row_absen['picture_in'].'" target="_blank">
                        <img src="../timthumb?src='.$site_url.'/content/present/'.$row_absen['picture_in'].'&h=40&w=40"></a>';}
                    echo'</td>
                    <td>'.$row_absen['time_in'].'</td>';
              }

            }
            else{
            echo'
            <td class="text-center picture">';
              if($row_absen['picture_in'] ==NULL){
                echo'<img src="../timthumb?src='.$site_url.'/content/avatar.jpg&h=40&w=40">';}
              else{
                echo'
                  <a class="image-link" href="'.$site_url.'/content/present/'.$row_absen['picture_in'].'" target="_blank">
                    <img src="../timthumb?src='.$site_url.'/content/present/'.$row_absen['picture_in'].'&h=40&w=40"></a>';}
            echo'</td>
            <td>'.$row_absen['time_in'].'</td>';
            }
          
            if (date("l",mktime (0,0,0,$bulan,$d,$tahun)) == "Sunday"){
              if($row_absen['time_out'] ==''){
                echo'
                <td class="text-center">Libur Akhir Pekan</td>
                <td class="text-center">Libur Akhir Pekan</td>';
              }
              else{
                echo'
                <td class="text-center picture">';
                  if($row_absen['picture_out'] ==NULL){
                    echo'<img src="../timthumb?src='.$site_url.'/content/avatar.jpg&h=40&w=40">';}
                  else{
                    echo'
                      <a class="image-link" href="'.$site_url.'/content/present/'.$row_absen['picture_in'].'" target="_blank">
                        <img src="../timthumb?src='.$site_url.'/content/present/'.$row_absen['picture_out'].'&h=40&w=40"></a>';}
                    echo'</td>
                    <td>'.$row_absen['time_out'].'</td>';
              }

            }
            else{
            echo'
            <td class="text-center picture">';
              if($row_absen['picture_out'] ==NULL){
                echo'<img src="../timthumb?src='.$site_url.'/content/avatar.jpg&h=40&w=40">';}
              else{
                echo'
                  <a class="image-link" href="'.$site_url.'/content/present/'.$row_absen['picture_out'].'" target="_blank">
                    <img src="../timthumb?src='.$site_url.'/content/present/'.$row_absen['picture_out'].'&h=40&w=40"></a>';}
            echo'</td>
            <td>'.$row_absen['time_out'].'</td>';
            }
            echo'
            <td><p class="btn-modal" data-latitude="'.$latitude.'" data-longitude="'.$longitude.'">Lat: '.$latitude.'<br>Long: '.$longitude.'</p></td>
            <td>'.$status_hadir.' '.$status_time_in.'<br>'.$row_absen['information'].'</td>
            <td class="text-right"><button type="button" class="btn btn-warning btn-xs btn-modal enable-tooltip" title="Lokasi" data-latitude="'.$latitude.'" data-longitude="'.$longitude.'"><i class="fa fa-map-marker"></i> Lokasi</button></td>
          </tr>';
        }
        echo'
        </tbody>
      </table>
  </div>';
  if(isset($_POST['leave_id']) && isset($_POST['status'])){
    $leave_id = $_POST['leave_id'];
    $status = $_POST['status'];
    $update_query = "UPDATE leave_request SET status='$status' WHERE id='$leave_id'";
    $result = $connection->query($update_query);
    if($result){
      echo '<div class="alert alert-success">Leave request status updated!</div>';
    } else{
      echo '<div class="alert alert-danger">Failed to update leave request status</div>';
    }
  }
  
  if(isset($_POST['month']) OR isset($_POST['year'])){
    $month = $_POST['month'];
    $year  = $_POST['year'];
    $filter ="employees_id='$id' AND MONTH(presence_date)='$month' AND year(presence_date)='$year' AND employees_id='$id'";
  } 
  else{
    $bulan  = date ("m");
    $filter ="employees_id='$id' AND MONTH(presence_date) ='$bulan' and employees_id='$id'";
  }
  
  $query_hadir="SELECT presence_id FROM presence WHERE $filter AND present_id='1' ORDER BY presence_id DESC";
  $hadir= $connection->query($query_hadir);
  
  $query_sakit="SELECT presence_id FROM presence WHERE $filter AND present_id='2' ORDER BY presence_id";
  $sakit = $connection->query($query_sakit);
  
  $query_izin="SELECT presence_id FROM presence WHERE $filter AND present_id='3' ORDER BY presence_id";
  $izin = $connection->query($query_izin);
  
  $query_telat ="SELECT presence_id FROM presence WHERE $filter AND time_in>'$shift_time_in'";
  $telat = $connection->query($query_telat);
  
  $query_leave = "SELECT presence_id FROM leave_request WHERE employee_id='$id' ORDER BY id DESC";
  $leave_result = $connection->query($query_leave);
  
  $query_cuti = "SELECT presence_id FROM presence WHERE $filter AND present_id='4' ORDER BY presence_id";
  $cuti = $connection->query($query_cuti);

      

  echo'<hr>
  <div class="row">
    <div class="col-md-3">
      <p>Hadir : <span class="label label-success">'.$hadir->num_rows.'</span></p>
    </div>

    <div class="col-md-3">
      <p>Telat : <span class="label label-danger">'.$telat->num_rows.'</span></p>
    </div>

    <div class="col-md-3">
      <p>Sakit : <span class="label label-warning">'.$sakit->num_rows.'</span></p>
    </div>

    <div class="col-md-3">
      <p>Izin : <span class="label label-info">'.$izin->num_rows.'</span></p>
    </div>
    <div class="col-md-3">
      <p>Cuti : <span class="label label-primary">'.$leave_result->num_rows.'</span></p>
    </div>
  </div>';
    echo'
<script>
  $("#swdatatable").dataTable({
      "iDisplayLength":35,
      "aLengthMenu": [[35, 40, 50, -1], [35, 40, 50, "All"]]
  });
 $(".image-link").magnificPopup({type:"image"});
</script>';?>
<script type="text/javascript">
  $(function () {
    $('[data-toggle="tooltip"]').tooltip()
  })
</script>
<?php
}else{
  echo'Data tidak ditemukan';
}

break;

}

}