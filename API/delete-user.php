<?php
    require_once('../Config/config.php');

    if (isset($_POST['deleteSubmit'])) {
        $db = getDatabase();

        //Chọn tên người dùng
        $userRealNameAndPhone = $_POST['selectUserRealName'];

        //Lấy sđt người dùng
        $userRealNameAndPhone = strrev($userRealNameAndPhone);
        $userPhone = strrev(substr($userRealNameAndPhone, 0, strpos($userRealNameAndPhone, " - ")));

        //Lấy ID người dùng qua SĐT
        $getUserData = getIdByUserPhone($userPhone);
        $userData = $getUserData -> fetch_assoc();
        $userId = $userData['user_id'];

        //XÓa khỏi CSDL
        $userId = mysqli_escape_string($db, $userId);

        $deleteUserQuery = "delete from users where user_id = '$userId'";
        $deleteUserBookingQuery = "delete from bookingdetails where user_id = '$userId'";

        $result1 = $db -> query($deleteUserQuery);
        $result2 = $db -> query($deleteUserBookingQuery);

        //Thông báo xóa người dùng khỏi CSDL thành công
        $_SESSION['user-management-success'] = "Xóa người dùng thành công!";

        //Quay lại trang quản lý người dùng
        header("Location: ../management.php?m=usermanagement");
    }
?>