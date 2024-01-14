<?php
    require_once('../Config/config.php');

    if (isset($_POST['newSubmit'])) {
        $db = getDatabase();

        //Thêm tên người dùng mới
        $userRealName = $_POST['newRealName'];

        //SĐT người dùng mới
        $userPhone = $_POST['newPhone'];

        //Mail người dùng mới
        $userEmail = $_POST['newEmail'];

        //Tạo ID ngẫu nhiên cho người dùng mới
        $newUserId = uniqid();

        //Kiểm tra xem bị trùng sđt với người dùng cũ k
        $checkUserNewPhone = true;

        $newPhone = mysqli_escape_string($db, $userPhone);

        $getUserNewPhone = $db -> query("select user_phone from users where user_phone = '$newPhone'");

        if ($getUserNewPhone -> num_rows > 0) {
            $checkUserNewPhone = false;
        }

        //Kiểm tra xem bị trùng mail với người đung cũ k
        $checkUserNewEmail = true;

        $newEmail = mysqli_escape_string($db, $userEmail);

        $getUserNewEmail = $db -> query("select user_email from users where user_email = '$newEmail'");

        if ($getUserNewEmail -> num_rows > 0) {
            $checkUserNewEmail = false;
        }

        //Nếu thỏa cãc điều kiện trên thì ínert vào CSDL, ngược lại sẽ báo lỗi
        if ($checkUserNewPhone == false) {
            $_SESSION['user-management-error'] = "Số điện thoại đã được dùng!";
        }

        else if ($checkUserNewEmail == false) {
            $_SESSION['user-management-error'] = "Email đã được dùng!";
        }

        else {
            $newUserId = mysqli_escape_string($db, $newUserId);
            $userRealName = mysqli_escape_string($db, $userRealName);

            $addNewUserQuery = "insert into users (user_id, user_phone, user_email, user_realname) values ('$newUserId', '$newPhone', '$newEmail', '$userRealName')";
            $res = $db -> query($addNewUserQuery);

            $_SESSION['user-management-success'] = "Thêm người dùng thành công!";
        }
    }
    
    //Quay lại trnag quản lý người dungf
    header("Location: ../management.php?m=usermanagement");
?>