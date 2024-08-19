<?php 
if ($mod ==''){
    header('location:../404');
    echo'kosong';
}else{
    include_once 'mod/sw-header.php';
if(!isset($_COOKIE['COOKIES_MEMBER']) && !isset($_COOKIE['COOKIES_COOKIES'])){
        setcookie('COOKIES_MEMBER', '', 0, '/');
        setcookie('COOKIES_COOKIES', '', 0, '/');
        // Login tidak ditemukan
        setcookie("COOKIES_MEMBER", "", time()-$expired_cookie);
        setcookie("COOKIES_COOKIES", "", time()-$expired_cookie);
        session_destroy();
        header("location:./index");
}else{
  echo'<!-- App Capsule -->
    <div id="appCapsule">
        <!-- Wallet Card -->
        <div class="section wallet-card-section pt-1">
            <div class="wallet-card">
                <!-- Balance -->
                <!--<div class="balance">
                    <div class="left">
                        <span class="title"> Selamat '.$salam.'</span>
                        <h4>'.ucfirst($row_user['employees_name']).'</h4>
                    </div>
                    <div class="right">
                        <span class="title">'.tgl_ind($date).' </span>
                        <h4><span class="clock"></span></h4>
                    </div>

                </div>-->
                <!-- * Balance -->
                <!-- Wallet Footer -->
                <div class="text-center"><h3>'.tgl_ind($date).' - <span class="clock"></span></h3></div>
                <div class="wallet-footer text-center">
                    <div class="webcam-capture-body text-center">
                        <span class="latitude d-none" id="latitude"></span>
                        <span class="longitude d-none" id="longitude"></span>
                        <div class="webcam-capture"></div>
                        <div class="form-group basic">';
                        $query ="SELECT employees_id,time_in FROM presence WHERE employees_id='$row_user[id]' AND presence_date='$date'";
                        $result = $connection->query($query);
                        $row = $result->fetch_assoc();
                        if($result->num_rows > 0){
                        echo'
                        <button class="btn btn-success btn-lg btn-block" onClick="captureimage(0)"><ion-icon name="camera-outline"></ion-icon>Absen Pulang</button>';}
                        else{
                        echo'
                        <label for="attendance_type">Attendance Type:</label>
                        <select id="attendance_type" name="attendance_type">
                            <option value="present">Present</option>
                            <option value="absent_work_visit">Absent (On Work Visit)</option>
                        </select>
                        <div id="work_visit_details" style="display:none;">
                            <label for="location">Location:</label>
                            <input type="text" id="location" name="location">
                            <label for    ="purpose">Purpose:</label>
                            <input type="text" id="purpose" name="purpose">
                        </div>
                        <button class="btn btn-success btn-lg btn-block" onClick="captureimage(1)">Absent for Work Visit</button>
                        <div class="form-group basic">
                            <div class="row">
                                <div class="col-6">
                                    <label class="control-label">Latitude</label>
                                    <input type="text" class="form-control" id="latitude-input" readonly>
                                </div>
                                <div class="col-6">
                                    <label class="control-label">Longitude</label>
                                    <input type="text" class="form-control" id="longitude-input" readonly>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <!-- * Wallet Footer -->
                
            </div>
        </div>
        <!-- Card -->
    </div>
    <!-- * App Capsule -->
    ';

}
include_once 'mod/sw-footer.php';
}
}