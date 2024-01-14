<?php
    require_once('./Config/config.php');
?>

<style>
    <?php 
        require_once('./CSS/slider-main.css');
        require_once('./CSS/main.css');
    ?>
</style>

<?php
    require_once('layout.php');

    ?>
        <script src="./JS/date-picker.js?v=<?php echo time(); ?>"></script>
    <?php

    ?>
        <div class="wrapper">
            <?php
                require_once('header.php'); 
            ?>

            <div class="container">
                <div class="row">
                    <div class="col-2">

                    </div>

                    <div class="col-8 col-0 col-mobile">
                        <?php
                            //Xử lý ttin người dùng
                            if (isset($_GET['ui'])) {
                                if (isset($_SESSION['user_name'])) {
                                    $sessionUserName = $_SESSION['user_name'];
                                    $db = getDatabase();
                                    $users = getUsers($db);

                                    if ($users != null && $users -> num_rows > 0) {
                                        while ($userData = $users -> fetch_assoc()) {
                                            $userRealName = $userData['user_realname'];
                                            $userName = $userData['user_name'];
                                            $userPhone = $userData['user_phone'];
                                            $userEmail = $userData['user_email'];

                                            if ($userName == $sessionUserName) {
                                                //TTin người dùng
                                                ?>
                                                    <div class="user-info">
                                                        <div class="user-info-title">Thông tin cá nhân</div>

                                                        <br>
                                                        <br>
                                                        <br>
                                                        <label>Họ tên: </label>
                                                        <input disabled value="<?= $userRealName ?>" type="text">

                                                        <br>
                                                        <br>
                                                        <label>Tên người dùng: </label>
                                                        <input disabled value="<?= $userName ?>" type="text">

                                                        <br>
                                                        <br>
                                                        <label>Số điện thoại: </label>
                                                        <input disabled value="<?= $userPhone ?>" type="tel" pattern="[0-9]{10}">

                                                        <br>
                                                        <br>
                                                        <label>Email: </label>
                                                        <input disabled value="<?= $userEmail ?>" type="email">
                                                    </div>
                                                <?php

                                                ?>
                                                    <div class="booking-history">
                                                        <div class="booking-history-title">Lịch sử đặt sân</div>

                                                        <div class="booking-history-list">
                                                            <table>
                                                                <tr>
                                                                    <th>STT</th>
                                                                    <th>Sân đã đặt</th>
                                                                    <th>Thời gian bắt đầu</th>
                                                                    <th>Thời gian kết thúc</th>
                                                                    <th>Ngày đặt</th>
                                                                </tr>

                                                                <?php
                                                                    $bookingDetailsData = getBookingDetails($db);

                                                                    if ($bookingDetailsData != null && $bookingDetailsData -> num_rows > 0) {
                                                                        $number = 0;
                                                                        
                                                                        while ($data = $bookingDetailsData -> fetch_assoc()) {
                                                                            $bookingId = $data['booking_id'];
                                                                            $userId = $data['user_id'];
                                                                            $groundId = $data['ground_id'];
                                                                            $bookingStart = $data['booking_start'];
                                                                            $bookingEnd = $data['booking_end'];
                                                                            $bookingDate = $data['booking_date'];

                                                                            $getUserData = getUserById($userId);
                                                                            $userData = $getUserData -> fetch_assoc();
                                                                            $userNameBooking = $userData['user_name'];

                                                                            $getGroundData = getGroundById($groundId);
                                                                            $groundData = $getGroundData -> fetch_assoc();
                                                                            $groundName = $groundData['ground_name'];

                                                                            if ($userNameBooking == $sessionUserName) {
                                                                                ?>
                                                                                    <tr>
                                                                                        <td><?= $number += 1 ?></td>
                                                                                        <td><?= $groundName ?></td>
                                                                                        <td><?= $bookingStart ?></td>
                                                                                        <td><?= $bookingEnd ?></td>
                                                                                        <td><?= $bookingDate ?></td>
                                                                                    </tr>
                                                                                <?php
                                                                            }
                                                                        }
                                                                    }
                                                                ?>
                                                            </table>
                                                        </div>
                                                    </div>
                                                <?php
                                            }
                                        }
                                    }
                                }

                                else {
                                    ?>
                                        <style>
                                            .footer {
                                                margin-top: 30rem;
                                            }
                                        </style>

                                        <div class='ui-error-message'>Hãy đăng nhập để xem thông tin</div>
                                    <?php
                                }
                            }

                            else if (isset($_GET['bo'])) {
                                if (isset($_SESSION['user_name'])) {
                                    $db = getDatabase();

                                    ?>
                                        <style>
                                            .footer {
                                                margin-top: 20rem;
                                            }
                                        </style>

                                        <div class="booking-online">
                                            <div class="date-picker">
                                                <form action="" method="POST" name="dateChooseInput">
                                                    <label>Chọn ngày:</label>
                                    
                                                    <?php 
                                                        if (isset($_POST['submit'])) {
                                                            ?>
                                                                <input type="text" required placeholder="dd/mm/yyyy" class="date" id="dateChoose" autocomplete="off" name="dateChoose" value="<?= $_POST['dateChoose'] ?>">
                                                            <?php
                                                        }

                                                        else if (isset($_GET['datechoose'])) {
                                                            ?>
                                                                <input type="text" required placeholder="dd/mm/yyyy" class="date" id="dateChoose" autocomplete="off" name="dateChoose" value="<?= $_GET['datechoose'] ?>">
                                                            <?php
                                                        }

                                                        else {
                                                            ?>
                                                                <input type="text" required placeholder="dd/mm/yyyy" class="date" id="dateChoose" autocomplete="off" name="dateChoose">
                                                            <?php
                                                        }
                                                    ?>
                                                    
                                                    <input type="submit" value="Chọn" name="submit">
                                                </form>
                                            </div>

                                            <?php
                                                if (isset($_GET['datechoose'])) {
                                                    ?>
                                                        <div class="booking-online-title">Thông tin đặt sân ngày <?= $_GET['datechoose'] ?></div>

                                                        <div class="booking-list">
                                                            <table>
                                                                <tr>
                                                                    <th>Khách hàng</th>
                                                                    <th>Số điện thoại</th>
                                                                    <th>Sân đã đặt</th>
                                                                    <th>Thời gian bắt đầu</th>
                                                                    <th>Thời gian kết thúc</th>
                                                                    <th>Ngày</th>
                                                                </tr>

                                                                <?php
                                                                    $db = getDatabase();
                                                                    $bookingDetailsData = getBookingDetails($db);

                                                                    if ($bookingDetailsData != null && $bookingDetailsData -> num_rows > 0) {
                                                                        $number = 0;
                                                                        
                                                                        while ($data = $bookingDetailsData -> fetch_assoc()) {
                                                                            $number += 1;

                                                                            $bookingId = $data['booking_id'];
                                                                            $userId = $data['user_id'];
                                                                            $groundId = $data['ground_id'];
                                                                            $bookingStart = $data['booking_start'];
                                                                            $bookingEnd = $data['booking_end'];
                                                                            $bookingDate = $data['booking_date'];

                                                                            $getUserData = getUserById($userId);
                                                                            $userData = $getUserData -> fetch_assoc();
                                                                            $userRealName = $userData['user_realname'];
                                                                            $userName = $userData['user_name'];
                                                                            $userPhone = $userData['user_phone'];

                                                                            $getGroundData = getGroundById($groundId);
                                                                            $groundData = $getGroundData -> fetch_assoc();
                                                                            $groundName = $groundData['ground_name'];

                                                                            if ($bookingDate == $_GET['datechoose'] && $userName == $_SESSION['user_name']) {
                                                                                ?>
                                                                                    <tr>
                                                                                        <td><?= $userRealName ?></td>
                                                                                        <td><?= $userPhone ?></td>
                                                                                        <td><?= $groundName ?></td>
                                                                                        <td><?= $bookingStart ?></td>
                                                                                        <td><?= $bookingEnd ?></td>
                                                                                        <td><?= $_GET['datechoose'] ?></td>

                                                                                        <input type="hidden" id="<?= 'userRealName' . $number ?>" value="<?= $userRealName ?>">
                                                                                        <input type="hidden" id="<?= 'userPhone' . $number ?>" value="<?= $userPhone ?>">
                                                                                        <input type="hidden" id="<?= 'groundName' . $number ?>" value="<?= $groundName ?>">
                                                                                        <input type="hidden" id="<?= 'bookingStart' . $number ?>" value="<?= $bookingStart ?>">
                                                                                        <input type="hidden" id="<?= 'bookingEnd' . $number ?>" value="<?= $bookingEnd ?>">
                                                                                        <input type="hidden" id="<?= 'totalTime' . $number ?>" value="<?= $bookingTotaltime ?>">
                                                                                        <input type="hidden" id="<?= 'groundCost' . $number ?>" value="<?= $bookingCost ?>">
                                                                                    </tr>
                                                                                <?php
                                                                            }
                                                                        }
                                                                    }
                                                                ?>

                                                                <input type="hidden" id="totalBookingUsers" value="<?= $number ?>">
                                                            </table>
                                                        </div>
                                                    <?php
                                                }

                                                else if (isset($_POST['dateChoose'])) {
                                                    ?>
                                                        <div class="booking-online-title">Thông tin đặt sân ngày <?= $_POST['dateChoose'] ?></div>

                                                        <div class="booking-list">
                                                            <table>
                                                                <tr>
                                                                    <th>Khách hàng</th>
                                                                    <th>Số điện thoại</th>
                                                                    <th>Sân đã đặt</th>
                                                                    <th>Thời gian bắt đầu</th>
                                                                    <th>Thời gian kết thúc</th>
                                                                    <th>Ngày</th>
                                                                </tr>

                                                                <?php
                                                                    $db = getDatabase();
                                                                    $bookingDetailsData = getBookingDetails($db);

                                                                    if ($bookingDetailsData != null && $bookingDetailsData -> num_rows > 0) {
                                                                        $number = 0;
                                                                        
                                                                        while ($data = $bookingDetailsData -> fetch_assoc()) {
                                                                            $number += 1;

                                                                            $bookingId = $data['booking_id'];
                                                                            $userId = $data['user_id'];
                                                                            $groundId = $data['ground_id'];
                                                                            $bookingStart = $data['booking_start'];
                                                                            $bookingEnd = $data['booking_end'];
                                                                            $bookingDate = $data['booking_date'];

                                                                            $getUserData = getUserById($userId);
                                                                            $userData = $getUserData -> fetch_assoc();
                                                                            $userRealName = $userData['user_realname'];
                                                                            $userName = $userData['user_name'];
                                                                            $userPhone = $userData['user_phone'];

                                                                            $getGroundData = getGroundById($groundId);
                                                                            $groundData = $getGroundData -> fetch_assoc();
                                                                            $groundName = $groundData['ground_name'];

                                                                            if ($bookingDate == $_POST['dateChoose'] && $userName == $_SESSION['user_name']) {
                                                                                ?>
                                                                                    <tr>
                                                                                        <td><?= $userRealName ?></td>
                                                                                        <td><?= $userPhone ?></td>
                                                                                        <td><?= $groundName ?></td>
                                                                                        <td><?= $bookingStart ?></td>
                                                                                        <td><?= $bookingEnd ?></td>
                                                                                        <td><?= $_POST['dateChoose'] ?></td>

                                                                                        <input type="hidden" id="<?= 'userRealName' . $number ?>" value="<?= $userRealName ?>">
                                                                                        <input type="hidden" id="<?= 'userPhone' . $number ?>" value="<?= $userPhone ?>">
                                                                                        <input type="hidden" id="<?= 'groundName' . $number ?>" value="<?= $groundName ?>">
                                                                                        <input type="hidden" id="<?= 'bookingStart' . $number ?>" value="<?= $bookingStart ?>">
                                                                                        <input type="hidden" id="<?= 'bookingEnd' . $number ?>" value="<?= $bookingEnd ?>">
                                                                                        <input type="hidden" id="<?= 'totalTime' . $number ?>" value="<?= $bookingTotaltime ?>">
                                                                                        <input type="hidden" id="<?= 'groundCost' . $number ?>" value="<?= $bookingCost ?>">
                                                                                    </tr>
                                                                                <?php
                                                                            }
                                                                        }
                                                                    }
                                                                ?>

                                                                <input type="hidden" id="totalBookingUsers" value="<?= $number ?>">
                                                            </table>
                                                        </div>
                                                    <?php
                                                }

                                                else {
                                                    ?>
                                                        <p class="booking-online-empty">Hãy chọn ngày đặt sân...</p>
                                                    <?php
                                                }
                                            ?>

                                            <div class="user-controller">
                                                <a id="addButton" class="controller-button" href="javascript:void(0)">
                                                    <i class="fas fa-user-plus"></i>
                                                </a>

                                                <a id="editButton" class="controller-button" href="javascript:void(0)">
                                                    <i class="fas fa-edit"></i>
                                                </a>
                                                
                                                <a id="deleteButton" class="controller-button" href="javascript:void(0)">
                                                    <i class="far fa-trash-alt"></i>                                                        
                                                </a>
                                            </div>
                                        </div>

                                        <div class="delete-booking-form" title="Xóa lịch đặt sân" id="deleteBookingForm">
                                            <form method="POST" action="API/delete-booking.php?typebooking=online">
                                                <br>
                                                <select required name="selectUserRealName" id="selectUserRealNameDelete" style="width: 100%;">
                                                    <option class="user-realname" selected="true" value="">(Chọn tên)</option>
                                                    
                                                    <?php
                                                        $bookingDetailsEditData = getBookingDetails($db);

                                                        if (isset($_POST['dateChoose'])) {
                                                            if ($bookingDetailsEditData != null && $bookingDetailsEditData -> num_rows > 0) {
                                                                while ($data = $bookingDetailsEditData -> fetch_assoc()) {
                                                                    $userIdentity = $data['user_id'];
                                                                    $bookingDateEdit = $data['booking_date'];

                                                                    $getUserData = getUserById($userIdentity);
                                                                    $userData = $getUserData -> fetch_assoc();
                                                                    $userRealName = $userData['user_realname'];
                                                                    $userPhone = $userData['user_phone'];
                                    
                                                                    if ($bookingDateEdit == $_POST['dateChoose']) {
                                                                        ?>
                                                                            <option class="user-realname"><?= $userRealName . " - " . $userPhone ?></option>
                                                                        <?php
                                                                    }
                                                                }
                                                            }
                                                        }
                                                        
                                                        else if (isset($_GET['datechoose'])) {
                                                            if ($bookingDetailsEditData != null && $bookingDetailsEditData -> num_rows > 0) {
                                                                while ($data = $bookingDetailsEditData -> fetch_assoc()) {
                                                                    $userIdentity = $data['user_id'];
                                                                    $bookingDateEdit = $data['booking_date'];

                                                                    $getUserData = getUserById($userIdentity);
                                                                    $userData = $getUserData -> fetch_assoc();
                                                                    $userRealName = $userData['user_realname'];
                                                                    $userPhone = $userData['user_phone'];
                                    
                                                                    if ($bookingDateEdit == $_GET['datechoose']) {
                                                                        ?>
                                                                            <option class="user-realname"><?= $userRealName . " - " . $userPhone ?></option>
                                                                        <?php
                                                                    }
                                                                }
                                                            }
                                                        }
                                                    ?>
                                                </select>

                                                <br>
                                                <br>
                                                <label>Ngày: </label>
                                                <?php 
                                                    if (isset($_POST['submit'])) {
                                                        ?>
                                                            <input type="text" required placeholder="dd/mm/yyyy" class="date" autocomplete="off" name="dateChooseForm" value="<?= $_POST['dateChoose'] ?>">
                                                        <?php
                                                    }

                                                    else if (isset($_GET['datechoose'])) {
                                                        ?>
                                                            <input type="text" required placeholder="dd/mm/yyyy" class="date" autocomplete="off" name="dateChooseForm" value="<?= $_GET['datechoose'] ?>">
                                                        <?php
                                                    }

                                                    else {
                                                        ?>
                                                            <input type="text" required placeholder="dd/mm/yyyy" class="date" autocomplete="off" name="dateChooseForm">
                                                        <?php
                                                    }
                                                ?>
                                                
                                                <br>
                                                <br>
                                                <input type="submit" onclick="return confirm('Bạn có chắc chắn muốn xóa?');" name="deleteSubmit" id="deleteSubmit" class="delete-submit" value="Xóa">
                                            </form>
                                        </div>

                                        <div class="edit-booking-form" title="Chỉnh sửa lịch đặt sân" id="editBookingForm">
                                            <form method="POST" action="API/edit-booking.php?typebooking=online">
                                                <br>
                                                <select required name="selectUserRealName" id="selectUserRealNameEdit" style="width: 100%;">                                                    
                                                    <option class="user-realname" selected="true" value="">(Chọn tên)</option>
                                                    
                                                    <?php
                                                        $bookingDetailsEditData = getBookingDetails($db);
                                                        
                                                        if (isset($_GET['datechoose'])) {
                                                            if ($bookingDetailsEditData != null && $bookingDetailsEditData -> num_rows > 0) {
                                                                while ($data = $bookingDetailsEditData -> fetch_assoc()) {
                                                                    $userIdentity = $data['user_id'];
                                                                    $bookingDateEdit = $data['booking_date'];
                                    
                                                                    $getUserData = getUserById($userIdentity);
                                                                    $userData = $getUserData -> fetch_assoc();
                                                                    $userRealName = $userData['user_realname'];
                                                                    $userPhone = $userData['user_phone'];
                                    
                                                                    if ($bookingDateEdit == $_GET['datechoose']) {
                                                                        ?>
                                                                            <option class="user-realname"><?= $userRealName . " - " . $userPhone ?></option>
                                                                        <?php
                                                                    }
                                                                }
                                                            }
                                                        }

                                                        else if (isset($_POST['dateChoose'])) {
                                                            if ($bookingDetailsEditData != null && $bookingDetailsEditData -> num_rows > 0) {
                                                                while ($data = $bookingDetailsEditData -> fetch_assoc()) {
                                                                    $userIdentity = $data['user_id'];
                                                                    $bookingDateEdit = $data['booking_date'];
                                    
                                                                    $getUserData = getUserById($userIdentity);
                                                                    $userData = $getUserData -> fetch_assoc();
                                                                    $userRealName = $userData['user_realname'];
                                                                    $userPhone = $userData['user_phone'];
                                    
                                                                    if ($bookingDateEdit == $_POST['dateChoose']) {
                                                                        ?>
                                                                            <option class="user-realname"><?= $userRealName . " - " . $userPhone ?></option>
                                                                        <?php
                                                                    }
                                                                }
                                                            }
                                                        }
                                                    ?>
                                                </select>

                                                <br>
                                                <br>
                                                <label>Họ tên: </label>
                                                <input required type="text" name="editRealName" id="editRealName">

                                                <br>
                                                <br>
                                                <label>Số điện thoại: </label>
                                                <input required type="tel" pattern="[0-9]{10}" name="editPhone" id="editPhone">

                                                <br>
                                                <br>
                                                <label>Ngày: </label>
                                                <?php 
                                                    if (isset($_POST['submit'])) {
                                                        ?>
                                                            <input type="text" required placeholder="dd/mm/yyyy" class="date" autocomplete="off" name="dateChooseForm" value="<?= $_POST['dateChoose'] ?>">
                                                        <?php
                                                    }

                                                    else if (isset($_GET['datechoose'])) {
                                                        ?>
                                                            <input type="text" required placeholder="dd/mm/yyyy" class="date" autocomplete="off" name="dateChooseForm" value="<?= $_GET['datechoose'] ?>">
                                                        <?php
                                                    }

                                                    else {
                                                        ?>
                                                            <input type="text" required placeholder="dd/mm/yyyy" class="date" autocomplete="off" name="dateChooseForm">
                                                        <?php
                                                    }
                                                ?>

                                                <br>
                                                <br>
                                                <label>Sân: </label>
                                                <select name="selectGround" id="selectGround">
                                                    <?php
                                                        $groundsData = getGrounds($db);

                                                        if ($groundsData != null && $groundsData -> num_rows > 0) {
                                                            while ($data = $groundsData -> fetch_assoc()) {
                                                                $groundName = $data['ground_name'];

                                                                if ($groundName) {
                                                                    ?>
                                                                        <option><?= $groundName ?></option>
                                                                    <?php
                                                                }
                                                            }
                                                        }
                                                    ?>
                                                </select>

                                                <br>
                                                <br>
                                                <label>Thời gian bắt đầu: </label>
                                                <select name="selectTimeStart-1" id="selectTimeStart-1">
                                                    <?php
                                                        for ($i = 9; $i <= 21; $i++) { 
                                                            ?>
                                                                <option><?= $i ?></option>
                                                            <?php
                                                        }
                                                    ?>
                                                </select>

                                                :

                                                <select name="selectTimeStart-2" id="selectTimeStart-2">
                                                    <option>00</option>
                                                    <option>15</option>
                                                    <option>30</option>
                                                    <option>45</option>
                                                </select>

                                                <br>
                                                <br>
                                                <label>Thời gian kết thúc: </label>
                                                <select name="selectTimeEnd-1" id="selectTimeEnd-1"> 
                                                    <?php
                                                        for ($i = 9; $i <= 21; $i++) { 
                                                            ?>
                                                                <option><?= $i ?></option>
                                                            <?php
                                                        }
                                                    ?>
                                                </select>

                                                :

                                                <select name="selectTimeEnd-2" id="selectTimeEnd-2">
                                                    <option>00</option>
                                                    <option>15</option>
                                                    <option>30</option>
                                                    <option>45</option>
                                                </select>
                                                
                                                <br>
                                                <br>
                                                <input type="submit" name="editSubmit" onclick="return confirm('Bạn có chắc chắn muốn chỉnh sửa?');" id="editSubmit" class="edit-submit" value="Cập nhật">
                                            </form>
                                        </div>

                                        <div class="add-booking-form" title="Thêm lịch đặt sân" id="addBookingForm">
                                            <form method="POST" action="API/add-booking.php?typeuser=online" id="addOld">
                                                <br>
                                                <select required name="selectUserRealName" id="selectUserRealName" style="width: 100%;">                    
                                                    <option class="user-realname" selected="true" value="">(Chọn tên)</option>

                                                    <?php
                                                        $usersData = getUsers($db);

                                                        if ($usersData != null && $usersData -> num_rows > 0) {
                                                            while ($data = $usersData -> fetch_assoc()) {
                                                                $realName = $data['user_realname'];
                                                                $phone = $data['user_phone'];
                                                                $userName = $data['user_name'];

                                                                if ($userName == $_SESSION['user_name']) {
                                                                    ?>
                                                                        <option selected="true" class="user-realname"><?= $realName . " - " . $phone ?></option>
                                                                    <?php
                                                                }
                                                            }
                                                        }
                                                    ?>
                                                </select>

                                                <br>
                                                <br>
                                                <label>Chọn ngày: </label>
                                                <?php 
                                                    if (isset($_POST['submit'])) {
                                                        ?>
                                                            <input type="text" required placeholder="dd/mm/yyyy" class="date" autocomplete="off" name="dateChooseForm" value="<?= $_POST['dateChoose'] ?>">
                                                        <?php
                                                    }

                                                    else if (isset($_GET['datechoose'])) {
                                                        ?>
                                                            <input type="text" required placeholder="dd/mm/yyyy" class="date" autocomplete="off" name="dateChooseForm" value="<?= $_GET['datechoose'] ?>">
                                                        <?php
                                                    }

                                                    else {
                                                        ?>
                                                            <input type="text" required placeholder="dd/mm/yyyy" class="date" autocomplete="off" name="dateChooseForm">
                                                        <?php
                                                    }
                                                ?>

                                                <br>
                                                <br>
                                                <label>Chọn sân: </label>
                                                <select name="selectGround">
                                                    <?php
                                                        $groundsData = getGrounds($db);

                                                        if ($groundsData != null && $groundsData -> num_rows > 0) {
                                                            while ($data = $groundsData -> fetch_assoc()) {
                                                                $groundName = $data['ground_name'];

                                                                if ($groundName) {
                                                                    ?>
                                                                        <option><?= $groundName ?></option>
                                                                    <?php
                                                                }
                                                            }
                                                        }
                                                    ?>
                                                </select>

                                                <br>
                                                <br>
                                                <label>Thời gian bắt đầu: </label>
                                                <select name="selectTimeStart-1">
                                                    <?php
                                                        for ($i = 9; $i <= 21; $i++) { 
                                                            ?>
                                                                <option><?= $i ?></option>
                                                            <?php
                                                        }
                                                    ?>
                                                </select>

                                                :

                                                <select name="selectTimeStart-2">
                                                    <option>00</option>
                                                    <option>15</option>
                                                    <option>30</option>
                                                    <option>45</option>
                                                </select>

                                                <br>
                                                <br>
                                                <label>Thời gian kết thúc: </label>
                                                <select name="selectTimeEnd-1"> 
                                                    <?php
                                                        for ($i = 9; $i <= 21; $i++) { 
                                                            ?>
                                                                <option><?= $i ?></option>
                                                            <?php
                                                        }
                                                    ?>
                                                </select>

                                                :

                                                <select name="selectTimeEnd-2">
                                                    <option>00</option>
                                                    <option>15</option>
                                                    <option>30</option>
                                                    <option>45</option>
                                                </select>

                                                <br>
                                                <br>
                                                <input type="submit" onclick="return confirm('Thêm lịch đặt?');" name="oldSubmit" id="oldSubmit" class="old-submit" value="Xác nhận">
                                            </form>
                                        </div>

                                        <?php
                                            if (isset($_SESSION['booking-error'])) {
                                                ?>
                                                    <div class="booking-error">
                                                        <p><?= $_SESSION['booking-error'] ?></p>
                                                        <span>&times;</span>
                                                    </div>
                                                <?php

                                                unset($_SESSION['booking-error']);
                                            }

                                            if (isset($_SESSION['booking-success'])) {
                                                ?>
                                                    <div class="booking-success">
                                                        <p><?= $_SESSION['booking-success'] ?></p>
                                                        <span>&times;</span>
                                                    </div>
                                                <?php

                                                unset($_SESSION['booking-success']);
                                            }
                                        ?>

                                        <script>
                                            <?php 
                                                require_once('./JS/close-popup-message.js');
                                                require_once('./JS/add-booking-form.js');
                                                require_once('./JS/edit-booking-form.js');
                                                require_once('./JS/delete-booking-form.js');
                                            ?>
                                        </script>
                                    <?php
                                }

                                else {
                                    ?>
                                        <style>
                                            .footer {
                                                margin-top: 30rem;
                                            }
                                        </style>

                                        <div class='ui-error-message'>Hãy đăng nhập để sử dụng chức năng này</div>
                                    <?php
                                }
                            }

                            else if (isset($_GET['gt'])) {
                                if ($_GET['gt'] == "groundtype5") {
                                    ?>
                                        <style>
                                            .footer {
                                                margin-top: 20rem;
                                            }
                                        </style>

                                        <div class="ground-type-5">
                                            <h2>Sân 5 người</h2>
                                            <p>Số lượng: 4 sân.</p>
                                            <p>Sân hiện có: sân số 1, sân số 2, sân số 3, sân số 4</p>
                                        </div>
                                    <?php
                                }

                                else if ($_GET['gt'] == "groundtype7") {
                                    ?>
                                        <style>
                                            .footer {
                                                margin-top: 20rem;
                                            }
                                        </style>
                                        
                                        <div class="ground-type-5">
                                            <h2>Sân 7 người</h2>
                                            <p>Số lượng: 2 sân.</p>
                                            <p>Sân hiện có: sân số 5, sân số 6</p>
                                        </div>
                                    <?php
                                }

                                else if ($_GET['gt'] == "groundtype11") {
                                    ?>
                                        <style>
                                            .footer {
                                                margin-top: 20rem;
                                            }
                                        </style>
                                        
                                        <div class="ground-type-5">
                                            <h2>Sân 11 người</h2>
                                            <p>Số lượng: 1 sân.</p>
                                            <p>Sân hiện có: sân số 7</p>
                                        </div>
                                    <?php
                                }
                            }

                            else if (isset($_GET['gs'])) {
                                $db = getDatabase();

                                ?>
                                    <style>
                                        .footer {
                                            margin-top: 20rem;
                                        }
                                    </style>

                                    <div class="date-picker">
                                        <form action="" method="POST" name="dateChooseInput">
                                            <label>Chọn ngày:</label>
                            
                                            <?php 
                                                if (isset($_POST['submit'])) {
                                                    ?>
                                                        <input type="text" required placeholder="dd/mm/yyyy" class="date" id="dateChoose" autocomplete="off" name="dateChoose" value="<?= $_POST['dateChoose'] ?>">
                                                    <?php
                                                }

                                                else {
                                                    ?>
                                                        <input type="text" required placeholder="dd/mm/yyyy" class="date" id="dateChoose" autocomplete="off" name="dateChoose">
                                                    <?php
                                                }
                                            ?>
                                            
                                            <input type="submit" value="Chọn" name="submit">
                                        </form>
                                    </div>
                                <?php

                                if (isset($_POST['submit'])) {
                                    ?>
                                        <div class="time-grounds-status">
                                            <div class="time-grounds-status-title">Tình trạng sân ngày <?= $_POST['dateChoose'] ?></div>
                    
                                            <div class="time-grounds-schedule">
                                                <table>
                                                    <tr>
                                                        <th>Tên sân</th>
                                                        <th>Khung giờ đã được đặt</th>
                                                        <th>Tình trạng</th>
                                                    </tr>
                                                    
                                                    <?php 
                                                        $groundsData = getGrounds($db);
                                                        $tempGroundIdCheck = "";
                    
                                                        if ($groundsData != null && $groundsData -> num_rows > 0) {                                        
                                                            while ($data = $groundsData -> fetch_assoc()) {
                                                                $groundId = $data['ground_id'];
                                                                $groundName = $data['ground_name'];
                    
                                                                ?>
                                                                    <tr>
                                                                        <td><?= $groundName ?></td>

                                                                        <?php
                                                                            $combineTimes = "";
                                                                            $timesArray = array();
                                                                            $getBookingDetailsData = getBookingDetailByGroundIdAndDate($groundId, $_POST['dateChoose']);
                    
                                                                            if ($getBookingDetailsData != null && $getBookingDetailsData -> num_rows > 0) {
                                                                                while ($bookingDetailData = $getBookingDetailsData -> fetch_assoc()) {
                                                                                    $bookingStartDetail = $bookingDetailData['booking_start'];
                                                                                    $bookingEndDetail = $bookingDetailData['booking_end'];

                                                                                    $combineTimes = $bookingStartDetail . " - " . $bookingEndDetail;
                    
                                                                                    array_push($timesArray, $combineTimes);
                                                                                }
                                                                            }
                                                                        ?>

                                                                        <?php
                                                                            if (count($timesArray) == 1) {
                                                                                ?>
                                                                                    <td class="is-booking-time"><?= $timesArray[0] ?></td>
                                                                                    <td class="is-using-ground">Đang hoạt động</td>
                                                                                <?php
                                                                            }
                    
                                                                            else if (count($timesArray) > 1) {
                                                                                ?>
                                                                                    <td class="is-booking-time">
                                                                                        <?= implode(", ", $timesArray) ?>
                                                                                    </td>
                    
                                                                                    <td class="is-using-ground">Đang hoạt động</td>
                                                                                <?php
                                                                            }
                                                                        ?>
                                                                    </tr>
                                                                <?php
                                                            }
                                                        }
                                                    ?>
                                                </table>
                                            </div>
                                        </div>
                                    <?php
                                }
                            }

                            else if (isset($_GET['gcat'])) {
                                ?>
                                    <style>
                                        .footer {
                                            margin-top: 20rem;
                                        }
                                    </style>

                                    <div class="ground-cost-and-activity-times">
                                        <h2>Chi phí khi sử dụng sân</h2>
                                        <p>1 phút = 3,000 đồng.</p>

                                        <h2>Khung giờ hoạt động</h2>
                                        <p>Mở cửa từ thứ 2 đến thứ 7 trong tuần, ngoại trừ chủ nhật.</p>
                                        <p>Từ 9h00 sáng đến 21h00 tối.</p>
                                    </div>
                                <?php
                            }

                            else {
                                ?>
                                    <div class="slider-container">
                                        <div class="slider-main" id="sliderMain">
                                            <?php        
                                                $db = getDatabase();
                                                $res = getImages($db);

                                                if ($res != null && $res -> num_rows > 0) {
                                                    while ($data = $res -> fetch_assoc()) {
                                                        $imageSrc = $data['image_src'];
                                                        $imageType = $data['image_type'];

                                                        if ($imageType == "slide") {
                                                            ?>
                                                                <div class="slide fade">
                                                                    <img src="<?= $imageSrc ?>">
                                                                </div>
                                                            <?php
                                                        }
                                                    }
                                                }
                                            ?>

                                            <div class="slider-nav">
                                                <span class="nav-dot"></span>   
                                                <span class="nav-dot"></span>   
                                                <span class="nav-dot"></span>   
                                            </div>
                                        </div>
                                    </div>

                                    <div class="main-content">
                                        <h2>Giới thiệu</h2>
                                        <p>Sân bóng mini, trang cung cấp dịch vụ đặt sân bóng.</p>

                                        <h2 class="quality-content">Chất lượng</h2>
                                        <p>Các sân bóng mini được đảm bảo tiêu chuẩn chất lượng cao.</p>

                                    </div>
                                <?php
                            }
                        ?>
                    </div>

                    <div class="col-2">

                    </div>
                </div>
            </div>
        </div>
    <?php

    require_once('footer.php');

    if (isset($_SESSION['login-success'])) {
        ?>
            <div class="login-success">
                <p><?= $_SESSION['login-success'] ?></p>
                <span>&times;</span>
            </div>
        <?php

        unset($_SESSION['login-success']);
    }
?>

<script>
    <?php 
        require_once('./JS/slider-main.js');
        require_once('./JS/close-popup-message.js');
    ?>
</script>