<?php
    require_once('../Config/config.php');

    if (isset($_GET['typeuser'])) {
        $db = getDatabase();

        if (isset($_POST['oldSubmit']) && $_GET['typeuser'] == "old") {
            //Lấy tên người đã từng đặt sân
            $userRealNameAndPhone = $_POST['selectUserRealName'];

            //Lấy họ tên và sđt người từng đặt sân
            $userRealNameAndPhone = strrev($userRealNameAndPhone);
            $userPhone = strrev(substr($userRealNameAndPhone, 0, strpos($userRealNameAndPhone, " - ")));

            //Lấy tên sân
            $groundNameSelected = $_POST['selectGround'];

            //Lấy ngày đặt sân
            $bookingDateSelected = $_POST['dateChooseForm'];
        
            //Tạo một ID đặt sân ngẫu nhiên
            $bookingId = uniqid();

            //Tạo ID lịch sử đặt sân ngẫu nhiên
            $historyId = uniqid();

            //Lấy ID người dùng
            $getUserData = getIdByUserPhone($userPhone);
            $userData = $getUserData -> fetch_assoc();
            $userId = $userData['user_id'];

            //Lấy ID sân
            $getGroundDataSelected = getIdByGroundName($groundNameSelected);
            $groundDataSelected = $getGroundDataSelected -> fetch_assoc();
            $groundIdSelected = $groundDataSelected['ground_id'];

            //Chọn giờ đá
            $timeStart = $_POST['selectTimeStart-1'] . ":" . $_POST['selectTimeStart-2'];
            $timeEnd = $_POST['selectTimeEnd-1'] . ":" . $_POST['selectTimeEnd-2'];

            //Check xem ngày giờ đặt đã đúng định dạng chưa
            $checkBookingTimes = true;
            $checkBookingPhone = true;
            $bookingDetailsData = getBookingDetails($db);

            if ($bookingDetailsData != null && $bookingDetailsData -> num_rows > 0) {
                while ($data = $bookingDetailsData -> fetch_assoc()) {
                    $bookingIdInDatabase = $data['booking_id'];
                    $groundId = $data['ground_id'];
                    $userIdInDatabase = $data['user_id'];
                    $bookingStart = $data['booking_start'];
                    $bookingEnd = $data['booking_end'];
                    $bookingDate = $data['booking_date'];

                    //Lây dữ liệu sân và check xem có bị trùng lịch đặt với sân khác hay chưa
                    $getGroundData = getGroundById($groundId);
                    $groundData = $getGroundData -> fetch_assoc();
                    $groundName = $groundData['ground_name'];
                    
                    if ($bookingDate == $bookingDateSelected && $groundName == $groundNameSelected && $bookingStart == $timeStart && $bookingEnd == $timeEnd) {
                        $checkBookingTimes = false;
                    }

                    else if ($bookingDate == $bookingDateSelected && $groundName == $groundNameSelected && strtotime($timeStart) >= strtotime($bookingStart) && strtotime($timeStart) <= strtotime($bookingEnd) && strtotime($timeEnd) >= strtotime($bookingStart) && strtotime($timeEnd) >= strtotime($bookingEnd)) {
                        $checkBookingTimes = false;
                    }
    
                    else if ($bookingDate == $bookingDateSelected && $groundName == $groundNameSelected && strtotime($timeEnd) >= strtotime($bookingStart) && strtotime($timeEnd) <= strtotime($bookingEnd) && strtotime($timeStart) <= strtotime($bookingStart) && strtotime($timeStart) <= strtotime($bookingEnd)) {
                        $checkBookingTimes = false;
                    }

                    else if ($bookingDate == $bookingDateSelected && $groundName == $groundNameSelected && strtotime($timeStart) <= strtotime($bookingStart) && strtotime($timeEnd) >= strtotime($bookingEnd)) {
                        $checkBookingTimes = false;
                    }
    
                    else if ($bookingDate == $bookingDateSelected && $groundName == $groundNameSelected && strtotime($timeStart) >= strtotime($bookingStart) && strtotime($timeEnd) <= strtotime($bookingEnd)) {
                        $checkBookingTimes = false;
                    }

                    else if ($userIdInDatabase == $userId && $bookingDate == $bookingDateSelected) {
                        $checkBookingPhone = false;
                    }
                }
            }

            //Check xem thời gian đặt sân đã hợp lệ hay chưa
            if ($timeStart == $timeEnd || strtotime($timeStart) > strtotime($timeEnd)) {
                $checkBookingTimes = false;
            }

            //Kiểm tra lại thông tin đặt sân, nếu đáp ứng tất cả điều kiện thì lưu vào CSDL và ngược lại, báo lỗi cho người đặt sân
            if ($checkBookingTimes == false) {
                $_SESSION['booking-error'] = "Khung giờ đặt sân không hợp lệ!";
            }

            else if ($checkBookingPhone == false) {
                $_SESSION['booking-error'] = "Số điện thoại đã được dùng!";
            }

            else {
                $historyId = mysqli_escape_string($db, $historyId);
                $bookingId = mysqli_escape_string($db, $bookingId);
                $userId = mysqli_escape_string($db, $userId);
                $groundIdSelected = mysqli_escape_string($db, $groundIdSelected);
                $bookingStartSelected = mysqli_escape_string($db, $timeStart);
                $bookingEndSelected = mysqli_escape_string($db, $timeEnd);
                $bookingDateSelected = mysqli_escape_string($db, $bookingDateSelected);

                $sqlQuery1 = "insert into bookingdetails (booking_id, user_id, ground_id, booking_start, booking_end, booking_date) values ('$bookingId', '$userId', '$groundIdSelected', '$bookingStartSelected', '$bookingEndSelected', '$bookingDateSelected')";
                
                $result1 = $db -> query($sqlQuery1);

                $sqlQuery2 = "insert into bookinghistories (history_id, booking_id, user_id, ground_id, booking_start, booking_end, booking_date) values ('$historyId', '$bookingId', '$userId', '$groundIdSelected', '$bookingStartSelected', '$bookingEndSelected', '$bookingDateSelected')";
                
                $result2 = $db -> query($sqlQuery2);

                $_SESSION['booking-success'] = "Đặt sân thành công!";
            }

            //Lưu lại đặt sân vào CSDL và quay trở lại giao diện đặt sân
            header("Location: ../management.php?m=bookingground_payment&datechoose=$bookingDateSelected");
        }

        else if (isset($_POST['oldSubmit']) && $_GET['typeuser'] == "online") {
            $userRealNameAndPhone = $_POST['selectUserRealName'];

            $userRealNameAndPhone = strrev($userRealNameAndPhone);
            $userPhone = strrev(substr($userRealNameAndPhone, 0, strpos($userRealNameAndPhone, " - ")));

            $groundNameSelected = $_POST['selectGround'];

            $bookingDateSelected = $_POST['dateChooseForm'];
        
            $bookingId = uniqid();

            $historyId = uniqid();

            $getUserData = getIdByUserPhone($userPhone);
            $userData = $getUserData -> fetch_assoc();
            $userId = $userData['user_id'];

            $getGroundDataSelected = getIdByGroundName($groundNameSelected);
            $groundDataSelected = $getGroundDataSelected -> fetch_assoc();
            $groundIdSelected = $groundDataSelected['ground_id'];

            $timeStart = $_POST['selectTimeStart-1'] . ":" . $_POST['selectTimeStart-2'];
            $timeEnd = $_POST['selectTimeEnd-1'] . ":" . $_POST['selectTimeEnd-2'];

            $checkBookingTimes = true;
            $checkBookingPhone = true;
            $bookingDetailsData = getBookingDetails($db);

            if ($bookingDetailsData != null && $bookingDetailsData -> num_rows > 0) {
                while ($data = $bookingDetailsData -> fetch_assoc()) {
                    $bookingIdInDatabase = $data['booking_id'];
                    $groundId = $data['ground_id'];
                    $userIdInDatabase = $data['user_id'];
                    $bookingStart = $data['booking_start'];
                    $bookingEnd = $data['booking_end'];
                    $bookingDate = $data['booking_date'];

                    $getGroundData = getGroundById($groundId);
                    $groundData = $getGroundData -> fetch_assoc();
                    $groundName = $groundData['ground_name'];
                    
                    if ($bookingDate == $bookingDateSelected && $groundName == $groundNameSelected && $bookingStart == $timeStart && $bookingEnd == $timeEnd) {
                        $checkBookingTimes = false;
                    }

                    else if ($bookingDate == $bookingDateSelected && $groundName == $groundNameSelected && strtotime($timeStart) >= strtotime($bookingStart) && strtotime($timeStart) <= strtotime($bookingEnd) && strtotime($timeEnd) >= strtotime($bookingStart) && strtotime($timeEnd) >= strtotime($bookingEnd)) {
                        $checkBookingTimes = false;
                    }
    
                    else if ($bookingDate == $bookingDateSelected && $groundName == $groundNameSelected && strtotime($timeEnd) >= strtotime($bookingStart) && strtotime($timeEnd) <= strtotime($bookingEnd) && strtotime($timeStart) <= strtotime($bookingStart) && strtotime($timeStart) <= strtotime($bookingEnd)) {
                        $checkBookingTimes = false;
                    }

                    else if ($bookingDate == $bookingDateSelected && $groundName == $groundNameSelected && strtotime($timeStart) <= strtotime($bookingStart) && strtotime($timeEnd) >= strtotime($bookingEnd)) {
                        $checkBookingTimes = false;
                    }
    
                    else if ($bookingDate == $bookingDateSelected && $groundName == $groundNameSelected && strtotime($timeStart) >= strtotime($bookingStart) && strtotime($timeEnd) <= strtotime($bookingEnd)) {
                        $checkBookingTimes = false;
                    }

                    else if ($userIdInDatabase == $userId && $bookingDate == $bookingDateSelected) {
                        $checkBookingPhone = false;
                    }
                }
            }

            if ($timeStart == $timeEnd || strtotime($timeStart) > strtotime($timeEnd)) {
                $checkBookingTimes = false;
            }

            if ($checkBookingTimes == false) {
                $_SESSION['booking-error'] = "Khung giờ đặt sân không hợp lệ!";
            }

            else if ($checkBookingPhone == false) {
                $_SESSION['booking-error'] = "Số điện thoại đã được dùng!";
            }

            else {
                $historyId = mysqli_escape_string($db, $historyId);
                $bookingId = mysqli_escape_string($db, $bookingId);
                $userId = mysqli_escape_string($db, $userId);
                $groundIdSelected = mysqli_escape_string($db, $groundIdSelected);
                $bookingStartSelected = mysqli_escape_string($db, $timeStart);
                $bookingEndSelected = mysqli_escape_string($db, $timeEnd);
                $bookingDateSelected = mysqli_escape_string($db, $bookingDateSelected);

                $sqlQuery1 = "insert into bookingdetails (booking_id, user_id, ground_id, booking_start, booking_end, booking_date) values ('$bookingId', '$userId', '$groundIdSelected', '$bookingStartSelected', '$bookingEndSelected', '$bookingDateSelected')";
                
                $result1 = $db -> query($sqlQuery1);

                $sqlQuery2 = "insert into bookinghistories (history_id, booking_id, user_id, ground_id, booking_start, booking_end, booking_date) values ('$historyId', '$bookingId', '$userId', '$groundIdSelected', '$bookingStartSelected', '$bookingEndSelected', '$bookingDateSelected')";
                
                $result2 = $db -> query($sqlQuery2);

                $_SESSION['booking-success'] = "Đặt sân thành công!";
            }

            header("Location: ../index.php?bo=bookingonline&datechoose=$bookingDateSelected");
        }

        else if (isset($_POST['newSubmit']) && $_GET['typeuser'] == "new") {
            // Tạo tên người đặt mới    
            $userRealName = $_POST['newRealName'];

            //Số điện thoại người đặt mới
            $userPhone = $_POST['newPhone'];

            //Tạo một ID người dùng ngẫu nhiên
            $newUserId = uniqid();

            // Kiểm tra xem sđt đã tồn tại hay chưa
            $checkUserNewPhone = true;
        
            $newPhone = mysqli_escape_string($db, $userPhone);

            $getUserNewPhone = $db -> query("select user_phone from users where user_phone = '$newPhone'");

            if ($getUserNewPhone -> num_rows > 0) {
                $checkUserNewPhone = false;
            }

            //Chọn sân
            $groundNameSelected = $_POST['selectGround'];

            //Chọn ngày đặt
            $bookingDateSelected = $_POST['dateChooseForm'];
        
            //Tạo một ID đặt sân ngẫu nhiên
            $bookingId = uniqid();

            //Tạo một ID lịch sử đặt sân ngẫu nhiên
            $historyId = uniqid();

            //Lấy ID sân
            $getGroundDataSelected = getIdByGroundName($groundNameSelected);
            $groundDataSelected = $getGroundDataSelected -> fetch_assoc();
            $groundIdSelected = $groundDataSelected['ground_id'];

            //CHọn rhowif gian bắt đầu vào sân và ra sân
            $timeStart = $_POST['selectTimeStart-1'] . ":" . $_POST['selectTimeStart-2'];
            $timeEnd = $_POST['selectTimeEnd-1'] . ":" . $_POST['selectTimeEnd-2'];

            //Kiểm tra giờ và sđt đã đúng chưa
            $checkBookingTimes = true;
            $checkBookingPhone = true;
            $bookingDetailsData = getBookingDetails($db);

            if ($bookingDetailsData != null && $bookingDetailsData -> num_rows > 0) {
                while ($data = $bookingDetailsData -> fetch_assoc()) {
                    $groundId = $data['ground_id'];
                    $userIdInDatabase = $data['user_id'];
                    $bookingStart = $data['booking_start'];
                    $bookingEnd = $data['booking_end'];
                    $bookingDate = $data['booking_date'];

                    //Lấy dữ liệu sân, check xem có bị trùng với lịch đặt sân trước đó chưa
                    $getGroundData = getGroundById($groundId);
                    $groundData = $getGroundData -> fetch_assoc();
                    $groundName = $groundData['ground_name'];
                    
                    if ($bookingDate == $bookingDateSelected && $groundName == $groundNameSelected && $bookingStart == $timeStart && $bookingEnd == $timeEnd) {
                        $checkBookingTimes = false;
                    }

                    else if ($bookingDate == $bookingDateSelected && $groundName == $groundNameSelected && strtotime($timeStart) >= strtotime($bookingStart) && strtotime($timeStart) <= strtotime($bookingEnd) && strtotime($timeEnd) >= strtotime($bookingStart) && strtotime($timeEnd) >= strtotime($bookingEnd)) {
                        $checkBookingTimes = false;
                    }
    
                    else if ($bookingDate == $bookingDateSelected && $groundName == $groundNameSelected && strtotime($timeEnd) >= strtotime($bookingStart) && strtotime($timeEnd) <= strtotime($bookingEnd) && strtotime($timeStart) <= strtotime($bookingStart) && strtotime($timeStart) <= strtotime($bookingEnd)) {
                        $checkBookingTimes = false;
                    }

                    else if ($bookingDate == $bookingDateSelected && $groundName == $groundNameSelected && strtotime($timeStart) <= strtotime($bookingStart) && strtotime($timeEnd) >= strtotime($bookingEnd)) {
                        $checkBookingTimes = false;
                    }
    
                    else if ($bookingDate == $bookingDateSelected && $groundName == $groundNameSelected && strtotime($timeStart) >= strtotime($bookingStart) && strtotime($timeEnd) <= strtotime($bookingEnd)) {
                        $checkBookingTimes = false;
                    }

                    else if ($userIdInDatabase == $userId && $bookingDate == $bookingDateSelected) {
                        $checkBookingPhone = false;
                    }
                }
            }

            //Kiểm tra thời gian vào và ra sân
            if ($timeStart == $timeEnd || strtotime($timeStart) > strtotime($timeEnd)) {
                $checkBookingTimes = false;
            }

            //Kiểm tra lại dữ liêuj, nếu đúng thì lưu vào CSDL, hoặc báo k hợp lệ
            if ($checkBookingTimes == false) {
                $_SESSION['booking-error'] = "Khung giờ đặt sân không hợp lệ!";
            }

            else if ($checkBookingPhone == false) {
                $_SESSION['booking-error'] = "Số điện thoại đã được dùng!";
            }

            else if ($checkUserNewPhone == false) {
                $_SESSION['booking-error'] = "Số điện thoại đã được dùng!";
            }

            else {
                //Thêm người dùng mới vào CSDL
                $userRealNameNew = mysqli_escape_string($db, $userRealName);
                $newUserId = mysqli_escape_string($db, $newUserId);

                $addNewUserQuery = "insert into users (user_id, user_phone, user_realname) values ('$newUserId', '$newPhone', '$userRealNameNew')";
            
                $addNewResult = $db -> query($addNewUserQuery);

                //Thêm dữ liệu đặt sân vào CSDL
                $historyId = mysqli_escape_string($db, $historyId);
                $bookingId = mysqli_escape_string($db, $bookingId);
                $groundIdSelected = mysqli_escape_string($db, $groundIdSelected);
                $bookingStartSelected = mysqli_escape_string($db, $timeStart);
                $bookingEndSelected = mysqli_escape_string($db, $timeEnd);
                $bookingDateSelected = mysqli_escape_string($db, $bookingDateSelected);

                $sqlQuery1 = "insert into bookingdetails (booking_id, user_id, ground_id, booking_start, booking_end, booking_date) values ('$bookingId', '$newUserId', '$groundIdSelected', '$bookingStartSelected', '$bookingEndSelected', '$bookingDateSelected')";
                
                $result1 = $db -> query($sqlQuery1);

                $sqlQuery2 = "insert into bookinghistories (history_id, booking_id, user_id, ground_id, booking_start, booking_end, booking_date) values ('$historyId', '$bookingId', '$newUserId', '$groundIdSelected', '$bookingStartSelected', '$bookingEndSelected', '$bookingDateSelected')";
                
                $result2 = $db -> query($sqlQuery2);

                $_SESSION['booking-success'] = "Đặt sân thành công!";
            }

            //Quay lại trang đặt sân
            header("Location: ../management.php?m=bookingground_payment&datechoose=$bookingDateSelected");
        }
    }
?>