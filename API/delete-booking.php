<?php
    require_once('../Config/config.php');

    if (isset($_POST['deleteSubmit'])) {
        $db = getDatabase();

        //Lấy dữ liệu người đặt sân
        $userRealNameAndPhone = $_POST['selectUserRealName'];

        //Lấy tên và SĐT người đặt sân
        $userRealNameAndPhone = strrev($userRealNameAndPhone);
        $userPhone = strrev(substr($userRealNameAndPhone, 0, strpos($userRealNameAndPhone, " - ")));

        //Lấy ID người đặt sân
        $getUserData = getIdByUserPhone($userPhone);
        $userData = $getUserData -> fetch_assoc();
        $userId = $userData['user_id'];

        //Lấy ngày đặt sân
        $bookingDate = $_POST['dateChooseForm'];

        //Xóa lịch đặt sân khỏi CSDL
        $bookingDateDelete = mysqli_escape_string($db, $bookingDate);
        $userId = mysqli_escape_string($db, $userId);

        $sqlQuery = "delete from bookingdetails where user_id = '$userId' and booking_date = '$bookingDateDelete'";

        $result = $db -> query($sqlQuery);

        //Thông báo trên mh đã xóa thành công
        $_SESSION['booking-success'] = "Đã xóa lịch đặt sân!";

        if (isset($_GET['typebooking'])) {
            //Quay lại trang đặt sân
            header("Location: ../index.php?datechoose=$bookingDate&bo=bookingonline");
        }

        else {
            //Quay lại trang quản lý đặt sân
            header("Location: ../management.php?datechoose=$bookingDate&m=bookingground_payment");
        }
    }
?>