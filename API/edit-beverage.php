<?php
    require_once('../Config/config.php');

    if (isset($_POST['editSubmit'])) {
        $db = getDatabase();

        //Lấy dữ liệu dịch vụ thêm
        $beverageNameAndCost = $_POST['selectBeverageName'];

        //Lấy tên dịch vụ thêm
        $beverageName = substr($beverageNameAndCost, 0, strpos($beverageNameAndCost, " - "));

        //Lấy giá dịch vụ thêm
        $beverageNameAndCost = strrev($beverageNameAndCost);
        $beverageCost = strrev(substr($beverageNameAndCost, 0, strpos($beverageNameAndCost, " - ")));
        $beverageCost = (float)$beverageCost;

        //Chỉnh sửa tên dịch vụ
        $editBeverageName = $_POST['editBeverageName'];

        //Chỉnh sửa giá dịch vụ
        $editBeverageCost = $_POST['editBeverageCost'];

        //Kiểm tra xem dịch vụ đó đã tồn tại hay chưa
        $checkEditBeverageName = true;

        $newEditBeverageName = mysqli_escape_string($db, $editBeverageName);

        if ($beverageName != $editBeverageName) {
            $getNewEditBeverageName = $db -> query("select beverage_name from beverages where beverage_name = '$newEditBeverageName'");

            if ($getNewEditBeverageName -> num_rows > 0) {
                // Lấy ID dịch vụ thông qua tên
                $getBeverageData = getIdByBeverageName($beverageName);
                $beverageData = $getBeverageData -> fetch_assoc();
                $beverageId = $beverageData['beverage_id'];

                // Sửa ID dịch vụ thồn qua việc sửa tên dịch vụ
                $getBeverageDataEdit = getIdByBeverageName($editBeverageName);
                $beverageDataEdit = $getBeverageDataEdit -> fetch_assoc();
                $beverageIdEdit = $beverageDataEdit['beverage_id'];

                if ($beverageId != $beverageIdEdit) {
                    $checkEditBeverageName = false;
                }
            }
        }

        else {
            //Lấy ID dịch vụ thông qua tên
            $getBeverageData = getIdByBeverageName($beverageName);
            $beverageData = $getBeverageData -> fetch_assoc();
            $beverageId = $beverageData['beverage_id'];
        }

        // Kiểm tra xem có bị trùng hay k, nếu k thì lưu vsof CSDL
        if ($checkEditBeverageName == false) {
            $_SESSION['beverage-management-error'] = "Tên đồ uống đã tồn tại!";
        }

        else {
            $editBeverageName = mysqli_escape_string($db, $editBeverageName);
            $editBeverageCost = mysqli_escape_string($db, $editBeverageCost);

            $editBeverageQuery = "update beverages set
                                beverage_name = '$editBeverageName',
                                beverage_cost = '$editBeverageCost'
                            where beverage_id = '$beverageId'
                            ";
            $res = $db -> query($editBeverageQuery);

            $_SESSION['beverage-management-success'] = "Cập nhật thành công!";
        }
    }
    
    //Quay lại tràn quản lý dịch vụ
    header("Location: ../management.php?m=beveragemanagement");
?>