<?php
    require_once('../Config/config.php');

    if (isset($_POST['paySubmit'])) {
        $db = getDatabase();

        $userRealNameAndPhone = $_POST['selectUserRealName'];

        $userRealNameAndPhone = strrev($userRealNameAndPhone);
        $userPhone = strrev(substr($userRealNameAndPhone, 0, strpos($userRealNameAndPhone, " - ")));

        $bookingDateSelected = $_POST['dateChooseForm'];

        $getUserData = getIdByUserPhone($userPhone);
        $userData = $getUserData -> fetch_assoc();
        $userId = $userData['user_id'];

        $paymentId = uniqid();

        $profitId = uniqid();

        //Lấy ID đặt saan
        $insertBookingId = '';
        $bookingDetailsData = getBookingDetails($db);

        if ($bookingDetailsData != null && $bookingDetailsData -> num_rows > 0) {
            while ($data = $bookingDetailsData -> fetch_assoc()) {
                $bookingId = $data['booking_id'];
                $bookingDate = $data['booking_date'];
                $userIdInDatabase = $data['user_id'];

                if ($bookingDate == $bookingDateSelected && $userIdInDatabase == $userId) {
                    $insertBookingId = $bookingId;
                    break;
                }
            }
        }

        //Lấy thông tin dịch vụ
        $selectBeverage = $_POST['selectBeverage'];

        //Lấy số lượng của từng dịch vụ
        $beverageNumber = $_POST['beverageNumber'];
        $beverageNumber = (int)$beverageNumber;

        //Tính tổng tiền của từng dịch vụ
        $beverageCost = explode(" - ", $selectBeverage)[1];
        $beverageCost = str_replace(",", "", $beverageCost) * $beverageNumber;

        //Lây loại dịch vụ
        $beverageType = explode(" - ", $selectBeverage)[0] . ' x ' . $beverageNumber;

        // Thêm tổng tiền dịch vụ
        $groundCost = $_POST['groundCost'];
        $groundCost = explode(" VNĐ", $groundCost)[0];
        $groundCost = str_replace(",", "", $groundCost);

        // Tính tổng tiền
        $totalCost = $beverageCost + $groundCost;

        // Thẻm thông tin thanh toán vào CSDL
        $paymentId = mysqli_escape_string($db, $paymentId);
        $profitId = mysqli_escape_string($db, $profitId);
        $insertBookingId = mysqli_escape_string($db, $insertBookingId);
        $beverageType = mysqli_escape_string($db, $beverageType);
        $beverageCost = mysqli_escape_string($db, $beverageCost);
        $groundCost = mysqli_escape_string($db, $groundCost);
        $totalCost = mysqli_escape_string($db, $totalCost);
        $paymentStatus = mysqli_escape_string($db, $paymentStatus);
        $paymentDate = mysqli_escape_string($db, $bookingDateSelected);

        $sqlQuery1 = "insert into payments 
                        (payment_id, booking_id, beverage_type, beverage_cost, ground_cost, total_cost, payment_date) 
                        values 
                        ('$paymentId', '$insertBookingId', '$beverageType', '$beverageCost', '$groundCost', '$totalCost', '$paymentDate')";
        $result1 = $db -> query($sqlQuery1);

        $sqlQuery2 = "delete from bookingdetails where booking_id = '$insertBookingId'";
        $result2 = $db -> query($sqlQuery2);

        $sqlQuery3 = "insert into profits (profit_id, payment_id) values ('$profitId', '$paymentId')";
        $result3 = $db -> query($sqlQuery3);

        //Thông báo ttoan thành công và quay lại trang thanh toán đăgtj sân
        $_SESSION['booking-success'] = "Thanh toán thành công!";

        header("Location: ../management.php?datechoose=$bookingDateSelected&m=bookingground_payment");
    }
?>