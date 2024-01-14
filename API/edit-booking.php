<?php
    require_once('../Config/config.php');

    if (isset($_POST['editSubmit'])) {
        $db = getDatabase();

        $userRealNameAndPhone = $_POST['selectUserRealName'];

        $userRealName = substr($userRealNameAndPhone, 0, strpos($userRealNameAndPhone, " - "));

        $userRealNameAndPhone = strrev($userRealNameAndPhone);
        $userPhone = strrev(substr($userRealNameAndPhone, 0, strpos($userRealNameAndPhone, " - ")));

        $userEditRealName = $_POST['editRealName'];

        $userEditPhone = $_POST['editPhone'];

        $checkUserEditPhone = true;

        if ($userPhone != $userEditPhone) {
            $userEditPhone = mysqli_escape_string($db, $userEditPhone);
            $getUserEditPhone = $db -> query("select user_phone from users where user_phone = '$userEditPhone'");

            if ($getUserEditPhone -> num_rows > 0) {
                $getUserData = getIdByUserPhone($userPhone);
                $userData = $getUserData -> fetch_assoc();
                $userId = $userData['user_id'];

                $getUserDataEdit = getIdByUserPhone($userEditPhone);
                $userDataEdit = $getUserDataEdit -> fetch_assoc();
                $userIdEdit = $userDataEdit['user_id'];

                if ($userId != $userIdEdit) {
                    $checkUserEditPhone = false;
                }
            }

            else {
                $getUserData = getIdByUserPhone($userPhone);
                $userData = $getUserData -> fetch_assoc();
                $userId = $userData['user_id'];

                $userEditRealName = mysqli_escape_string($db, $userEditRealName);
                $userEditPhone = mysqli_escape_string($db, $userEditPhone);
                $userEditId = mysqli_escape_string($db, $userId);

                $sqlQueryEditPhone = "update users set user_phone = '$userEditPhone' where user_id = '$userEditId'";
                $res = $db -> query($sqlQueryEditPhone);
            }
        }

        else {
            $getUserData = getIdByUserPhone($userPhone);
            $userData = $getUserData -> fetch_assoc();
            $userId = $userData['user_id'];
        }

        if ($userRealName != $userEditRealName) {
            $userEditRealName = mysqli_escape_string($db, $userEditRealName);
            $userEditId = mysqli_escape_string($db, $userId);

            $sqlQueryEditRealName = "update users set user_realname = '$userEditRealName' where user_id = '$userEditId'";
            $res = $db -> query($sqlQueryEditRealName);
        }

        $groundNameSelected = $_POST['selectGround'];

        $getGroundDataSelected = getIdByGroundName($groundNameSelected);
        $groundDataSelected = $getGroundDataSelected -> fetch_assoc();
        $groundIdSelected = $groundDataSelected['ground_id'];

        $bookingDateSelected = $_POST['dateChooseForm'];

        $timeStart = $_POST['selectTimeStart-1'] . ":" . $_POST['selectTimeStart-2'];
        $timeEnd = $_POST['selectTimeEnd-1'] . ":" . $_POST['selectTimeEnd-2'];

        $checkBookingTimes = true;
        $bookingDetailsData = getBookingDetails($db);

        if ($bookingDetailsData != null && $bookingDetailsData -> num_rows > 0) {
            while ($data = $bookingDetailsData -> fetch_assoc()) {
                $groundId = $data['ground_id'];
                $userIdInDatabase = $data['user_id'];
                $bookingStart = $data['booking_start'];
                $bookingEnd = $data['booking_end'];
                $bookingDate = $data['booking_date'];

                $getGroundData = getGroundById($groundId);
                $groundData = $getGroundData -> fetch_assoc();
                $groundName = $groundData['ground_name'];

                if ($bookingDate == $bookingDateSelected && $groundName == $groundNameSelected && strtotime($timeStart) > strtotime($bookingStart) && strtotime($timeStart) < strtotime($bookingEnd) && strtotime($timeEnd) > strtotime($bookingStart) && strtotime($timeEnd) > strtotime($bookingEnd)) {
                    $checkBookingTimes = false;
                }

                else if ($bookingDate == $bookingDateSelected && $groundName == $groundNameSelected && strtotime($timeEnd) > strtotime($bookingStart) && strtotime($timeEnd) < strtotime($bookingEnd) && strtotime($timeStart) < strtotime($bookingStart) && strtotime($timeStart) < strtotime($bookingEnd)) {
                    $checkBookingTimes = false;
                }

                else if ($bookingDate == $bookingDateSelected && $groundName == $groundNameSelected && strtotime($timeStart) < strtotime($bookingStart) && strtotime($timeEnd) > strtotime($bookingEnd)) {
                    $checkBookingTimes = false;
                }

                else if ($bookingDate == $bookingDateSelected && $groundName == $groundNameSelected && strtotime($timeStart) > strtotime($bookingStart) && strtotime($timeEnd) < strtotime($bookingEnd)) {
                    $checkBookingTimes = false;
                }
            }
        }

        if ($timeStart == $timeEnd || strtotime($timeStart) > strtotime($timeEnd)) {
            $checkBookingTimes = false;
        }

        if ($checkBookingTimes == false) {
            $_SESSION['booking-error'] = "Khung giờ đặt sân không hợp lệ!";
        }

        else if ($checkUserEditPhone == false) {
            $_SESSION['booking-error'] = "Số điện thoại đã được dùng!";
        }

        else {
            $groundIdSelected = mysqli_escape_string($db, $groundIdSelected);
            $bookingStartSelected = mysqli_escape_string($db, $timeStart);
            $bookingEndSelected = mysqli_escape_string($db, $timeEnd);

            $bookingDateEdit = mysqli_escape_string($db, $bookingDateSelected);
            $userId = mysqli_escape_string($db, $userId);

            $sqlQuery1 = "update bookingdetails 
                        set
                            ground_id = '$groundIdSelected', 
                            booking_start = '$bookingStartSelected', 
                            booking_end = '$bookingEndSelected'
                        where user_id = '$userId' and booking_date = '$bookingDateEdit'";

            $result1 = $db -> query($sqlQuery1);

            $sqlQuery2 = "update bookinghistories 
                        set
                            ground_id = '$groundIdSelected', 
                            booking_start = '$bookingStartSelected', 
                            booking_end = '$bookingEndSelected'
                        where user_id = '$userId' and booking_date = '$bookingDateEdit'";

            $result2 = $db -> query($sqlQuery2);

            $_SESSION['booking-success'] = "Cập nhật thông tin thành công!";
        }

        if (isset($_GET['typebooking'])) {
            header("Location: ../index.php?datechoose=$bookingDateSelected&bo=bookingonline");
        }

        else {
            header("Location: ../management.php?datechoose=$bookingDateSelected&m=bookingground_payment");
        }
    }
?>