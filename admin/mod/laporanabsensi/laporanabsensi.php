<?php session_start();
if(empty($connection)){
  header('location:../../');
} else {
  include_once 'mod/sw-panel.php';
 
echo'
  <div class="content-wrapper">';
switch(@$_GET['op']){ 
  default:
echo'
<section class="content-header">
  <h1>Data<small> Absensi</small></h1>
    <ol class="breadcrumb">
      <li><a href="./"><i class="fa fa-dashboard"></i> Beranda</a></li>
      <li class="active">Data Absensi</li>
    </ol>
</section>';
echo'
<section class="content">
  <div class="row">
    <div class="col-xs-12 col-sm-12 col-md-12 col-lg-12">
      <div class="box box-solid">
        <div class="box-header with-border">
          <h3 class="box-title"><b>Data Absensi</b></h3>
          <div class="box-tools pull-right">
          
        </div>

        </div>
        <div class="box-body">
          <div class="table-responsive">
          <table id="swdatatable" class="table table-bordered">
          <thead>
            <tr>
              <th style="width: 10px">No</th>
              <th>ID Pengguna</th>
              <th>Nama</th>
              <th>Email</th>
              <th>Absen Masuk</th>
              <th>Absen Keluar</th>
              <th>Tanggal</th>
              <th>Jam Masuk</th>
              <th>Jam Keluar</th>
              <th>Lokasi</th>
              <th>Status</th>
              <th style="width:120px" class="text-right">lokasi</th>
            </tr>
          </thead>
          <tbody>';          
          $query="SELECT employees.*, presence.presence_date,presence.picture_in,presence.picture_out,presence.time_in,presence.time_out, presence.presence_address 
          FROM employees 
          LEFT JOIN presence 
          ON employees.id = presence.employees_id, position, shift, building 
          WHERE employees.position_id=position.position_id 
          AND employees.shift_id=shift.shift_id 
          AND employees.building_id=building.building_id 
          ORDER BY presence.presence_date DESC";

          
          $result = $connection->query($query);
          
          if($result->num_rows > 0){
              $no=0;
              while ($row= $result->fetch_assoc()) {
                  $no++;
                  list($latitude, $longitude) = explode(',', $row['presence_address']);
                  echo'
              <tr>
                <td class="text-center">'.$no.'</td>
                <td>'.$row['employees_code'].'</td>
                <td>'.$row['employees_name'].'</td>
                <td>'.$row['employees_email'].'</td>
                <td class="text-center picture">';
                if($row['picture_in'] ==NULL){
                  echo'<img src="../timthumb?src='.$site_url.'/content/avatar.jpg&h=40&w=40">';}
                else{
                  echo'
                    <a class="image-link" href="'.$site_url.'/content/present/'.$row['picture_in'].'" target="_blank">
                      <img src="../timthumb?src='.$site_url.'/content/present/'.$row['picture_in'].'&h=40&w=40"></a>';}
              echo'</td>
              <td class="text-center picture">';
              if($row['picture_out'] ==NULL){
                echo'<img src="../timthumb?src='.$site_url.'/content/avatar.jpg&h=40&w=40">';}
              else{
                echo'
                  <a class="image-link" href="'.$site_url.'/content/present/'.$row['picture_out'].'" target="_blank">
                    <img src="../timthumb?src='.$site_url.'/content/present/'.$row['picture_out'].'&h=40&w=40"></a>';}
                    echo'</td>
                    <td>'.$row['presence_date'].'</td>
                    <td>'.$row['time_in'].'</td>
                    <td>'.$row['time_out'].'</td>
                    <td><p class="btn-modal" data-latitude="'.$latitude.'" data-longitude="'.$longitude.'">Lat: '.$latitude.'<br>Long: '.$longitude.'</p></td>
                    <td>';
               
                    $status_hadir = '';

// Logic to determine the value of $status_hadir
if (isset($_POST['month']) || isset($_POST['year'])) {
  $month = $_POST['month'];
  $year = $_POST['year'];
  $filter = "employees_id='$id' AND MONTH(presence_date)='$month' AND YEAR(presence_date)='$year'";
} else {
  $filter = "employees_id='$id' AND MONTH(presence_date)='$month'";
}

// Query database to get the presence records
$query_presence = "SELECT present_id, time_in FROM presence WHERE $filter ORDER BY presence_id DESC";
$result_presence = $connection->query($query_presence);

// Check if there are any presence records
if ($result_presence->num_rows > 0) {
  // Iterate over the presence records
  while ($row_presence = $result_presence->fetch_assoc()) {
    $present_id = $row_presence['present_id'];
    $time_in = $row_presence['time_in'];

    // Check if present_id is equal to 1 (hadir)
    if ($present_id == 1) {
      $status_hadir = 'Tidak Hadir';
      break;
    }

    // Check if time_in is later than or equal to 7:00 AM
    $time_in_hour = intval(date('H', strtotime($time_in)));
    $time_in_minute = intval(date('i', strtotime($time_in)));
    if ($time_in_hour > 7 || ($time_in_hour == 7 && $time_in_minute >= 0)) {
      $status_hadir = 'Telat';
    }
  }
} else {
  $status_hadir = 'Hadir';
}


                    
                    echo 'Status Kehadiran: ' . $status_hadir;
                  
                    
               
               echo '</td>
                <td class="text-right">
                  <div class="btn-group">
                  <button class="btn btn-warning btn-xs btn-modal enable-tooltip" title="Lokasi" data-latitude="'.$latitude.'" data-longitude="'.$longitude.'"><i class="fa fa-map-marker"></i> Lokasi</button>
                  <div class="modal fade" id="modal-location">
                    <div class="modal-dialog">
                      <div class="modal-content">
                        <div class="modal-header">
                          <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                          </button>
                          <h4 class="modal-title">Lokasi Absen <span class="modal-title-name"></span></h4>
                        </div>
                        <div class="modal-body">
                          <div id="iframe-map"></div>
                        </div>
                        <div class="modal-footer">
                          <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
                        </div>
                      </div>
                    </div>
                  </div>';}}
            echo'
            </tbody>
          </table>
          </div>
        </div>
    </div>
  </div> 
</section>
</section>

        <div class="modal fade" id="modal-laporan">
          <div class="modal-dialog">
            <div class="modal-content">
              <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                  <span aria-hidden="true">&times;</span></button>
                <h4 class="modal-title">Laporan Absensi Semua Pengguna</h4>
              </div>
              <div class="modal-body">
                <div class="form-group">
                  <label>Bulan</label>
                  <select class="form-control month" required>';
                    if($month ==1){echo'<option value="01" selected>Januari</option>';}else{echo'<option value="01">Januari</option>';}
                    if($month ==2){echo'<option value="02" selected>Februari</option>';}else{echo'<option value="02">Februari</option>';}
                    if($month ==3){echo'<option value="03" selected>Maret</option>';}else{echo'<option value="03">Maret</option>';}
                    if($month ==4){echo'<option value="04" selected>April</option>';}else{echo'<option value="04">April</option>';}
                    if($month ==5){echo'<option value="05" selected>Mei</option>';}else{echo'<option value="05">Mei</option>';}
                    if($month ==6){echo'<option value="06" selected>Juni</option>';}else{echo'<option value="06">Juni</option>';}
                    if($month ==7){echo'<option value="07" selected>Juli</option>';}else{echo'<option value="07">Juli</option>';}
                    if($month ==8){echo'<option value="08" selected>Agustus</option>';}else{echo'<option value="08">Agustus</option>';}
                    if($month ==9){echo'<option value="09" selected>September</option>';}else{echo'<option value="09">September</option>';}
                    if($month ==10){echo'<option value="10" selected>Oktober</option>';}else{echo'<option value="10">Oktober</option>';}
                    if($month ==11){echo'<option value="12" selected>November</option>';}else{echo'<option value="12">November</option>';}
                    if($month ==12){echo'<option value="12" selected>Desember</option>';}else{echo'<option value="12">Desember</option>';}
                  echo'
                  </select>
                </div>

                <div class="form-group">
                  <label>Tahun</label>
                  <select class="form-control year" required>';
                    $mulai= date('Y') - 0;
                    for($i = $mulai;$i<$mulai + 50;$i++){
                        $sel = $i == date('Y') ? ' selected="selected"' : '';
                        echo '<option value="'.$i.'"'.$sel.'>'.$i.'</option>';
                    }
                    echo'
                  </select>
                </div>

                <div class="form-group">
                  <label>Tipe</label>
                  <select class="form-control type" required>
                    <option value="pdf">PDF</option>
                    <option value="excel">EXCEL</option>
                    <option value="print">PRINT</option>
                  </select>
                </div>

              </div>
              <div class="modal-footer">
              <button type="button" class="btn btn-primary pull-left btn-print-all" onclick="printScreen()">Ekspor Semua</button>
              <button type="button" class="btn btn-default pull-right" data-dismiss="modal">Close</button>
            </div>

            </div>
            <!-- /.modal-content -->
          </div>
          <!-- /.modal-dialog -->
        </div>
        <!-- /.modal -->';


}?>

</div>
<?php }?>