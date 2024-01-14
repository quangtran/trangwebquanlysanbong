<?php
    require_once('../Config/config.php');

    if (isset($_POST['editSubmit'])) {
        $db = getDatabase();

        $userRealNameAndPhone = $_POST['selectUserRealName'];

        $userRealName = substr($userRealNameAndPhone, 0, strpos($userRealNameAndPhone, " - "));

        $userRealNameAndPhone = strrev($userRealNameAndPhone);
        $userPhone = strrev(substr($userRealNameAndPhone, 0, strpos($userRealNameAndPhone, " - ")));

        $getUserEmailData = getEmailByUserPhone($userPhone);
        $userEmailData = $getUserEmailData -> fetch_assoc();
        $userEmail = $userEmailData['user_email'];

        $editUserRealName = $_POST['editRealName'];

        $editUserPhone = $_POST['editPhone'];

        $editUserEmail = $_POST['editEmail'];

        $checkUserNewEditPhone = true;

        $newEditPhone = mysqli_escape_string($db, $editUserPhone);

        if ($userPhone != $editUserPhone) {
            $getUserNewEditPhone = $db -> query("select user_phone from users where user_phone = '$newEditPhone'");

            if ($getUserNewEditPhone -> num_rows > 0) {
                $getUserData = getIdByUserPhone($userPhone);
                $userData = $getUserData -> fetch_assoc();
                $userId = $userData['user_id'];

                $getUserDataEdit = getIdByUserPhone($editUserPhone);
                $userDataEdit = $getUserDataEdit -> fetch_assoc();
                $userIdEdit = $userDataEdit['user_id'];

                if ($userId != $userIdEdit) {
                    $checkUserNewEditPhone = false;
                }
            }
        }

        else {
            $getUserData = getIdByUserPhone($userPhone);
            $userData = $getUserData -> fetch_assoc();
            $userId = $userData['user_id'];
        }

        $checkUserNewEditEmail = true;

        $newEditEmail = mysqli_escape_string($db, $editUserEmail);

        if ($userEmail != $editUserEmail) {
            $getUserNewEditEmail = $db -> query("select user_email from users where user_email = '$newEditEmail'");

            if ($getUserNewEditEmail -> num_rows > 0) {
                $getUserData = getIdByUserEmail($userEmail);
                $userData = $getUserData -> fetch_assoc();
                $userId = $userData['user_id'];

                $getUserDataEdit = getIdByUserEmail($editUserEmail);
                $userDataEdit = $getUserDataEdit -> fetch_assoc();
                $userIdEdit = $userDataEdit['user_id'];

                if ($userId != $userIdEdit) {
                    $checkUserNewEditEmail = false;
                }
            }
        }

        else {
            $getUserData = getIdByUserEmail($userEmail);
            $userData = $getUserData -> fetch_assoc();
            $userId = $userData['user_id'];
        }

        if ($checkUserNewEditPhone == false) {
            $_SESSION['user-management-error'] = "Số điện thoại đã được dùng!";
        }

        else if ($checkUserNewEditEmail == false) {
            $_SESSION['user-management-error'] = "Email đã được dùng!";
        }

        else {
            $editUserRealName = mysqli_escape_string($db, $editUserRealName);

            $editUserQuery = "update users set
                                user_realname = '$editUserRealName',
                                user_phone = '$newEditPhone',
                                user_email = '$newEditEmail'
                            where user_id = '$userId'
                            ";
            $res = $db -> query($editUserQuery);

            $_SESSION['user-management-success'] = "Cập nhật thành công!";
        }
    }

    header("Location: ../management.php?m=usermanagement");
?>