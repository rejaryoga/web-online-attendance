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
                        $query = "SELECT employees_id, time_in FROM presence WHERE employees_id='$row_user[id]' AND presence_date='$date'";
                        $result = $connection->query($query);
                        $row = $result->fetch_assoc();
                        if ($result->num_rows > 0) {
                            echo '<button class="btn btn-success btn-lg btn-block" onClick="captureimage(0)"><ion-icon name="camera-outline"></ion-icon>Absen Pulang</button>';
                        } else {
                            echo '<button id="capture-btn" class="btn btn-success btn-lg btn-block" onClick="captureimage()" disabled><ion-icon name="camera-outline"></ion-icon>Absen Masuk</button>';
                        }
                        echo '
    <!-- App Capsule -->
    <div id="appCapsule">
        <div class="section full mt-2 mb-2">
            <div class="section-title">Formulir Permohonan Cuti/Izin</div>
            <div class="wide-block p-0">
                <div class="accordion" id="accordion1">
                    <div class="card">
                        <div class="card-header" id="headingTwo">
                            <h2 class="mb-0">
                                <button class="btn btn-link collapsed" type="button" data-toggle="collapse" data-target="#collapseTwo" aria-expanded="false" aria-controls="collapseTwo">
                                    Isi Formulir Permohonan Cuti/Izin
                                </button>
                            </h2>
                        </div>
                        <div id="collapseTwo" class="collapse" aria-labelledby="headingTwo" data-parent="#accordion1">
                            <div class="card-body">
                                <form method="post">
                                    <div class="form-group">
                                        <label for="leave_type">Jenis Cuti/Izin</label>
                                        <select class="form-control" id="leave_type" name="leave_type">
                                            <option value="sakit">Sakit</option>
                                            <option value="izin">Izin</option>
                                            <option value="cuti">Cuti</option>
                                        </select>
                                    </div>
                                    <div class="form-group">
                                        <label for="leave_reason">Alasan</label>
                                        <textarea class="form-control" id="leave_reason" name="leave_reason"></textarea>
                                    </div>
                                    <button class="btn btn-primary btn-lg btn-block" type="submit" name="submit_leave_request">Kirim Permohonan</button>
                                </form>';
                    if (isset($_POST['submit_leave_request'])) {
                        // Get the form data
                        $employee_id = $row_user['id'];
                        $leave_type = $_POST['leave_type'];
                        $leave_reason = $_POST['leave_reason'];

                        // Insert the leave request into the database
                        $query = "INSERT INTO leave_requests (employee_id, leave_type, leave_reason) VALUES ('$employee_id', '$leave_type', '$leave_reason')";
                        $result = $connection->query($query);

                        if ($result) {
                            echo '<div class="alert alert-success">Permohonan cuti/izin berhasil diajukan.</div>';
                        } else {
                            echo '<div class="alert alert-danger">Gagal mengajukan permohonan cuti/izin.</div>';
                        }
                    }
                    echo '
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <!-- * Wallet Footer -->
    </div>
    <!-- * App Capsule -->
  ';
  include_once 'mod/sw-footer.php';
                }

            }