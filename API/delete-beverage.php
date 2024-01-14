<?php
    require_once('../Config/config.php');

    if (isset($_POST['deleteSubmit'])) {
        $db = getDatabase();
        $beverageNameAndCost = $_POST['selectBeverageName'];

        //Lấy dữ liệu dịch vụ thêm
        $beverageName = substr($beverageNameAndCost, 0, strpos($beverageNameAndCost, " - "));

        //Lấy ID dịch vụ thêm
        $getBeverageData = getIdByBeverageName($beverageName);
        $beverageData = $getBeverageData -> fetch_assoc();
        $beverageId = $beverageData['beverage_id'];

        //Xóa khỏi CSDL
        $beverageId = mysqli_escape_string($db, $beverageId);

        $deleteBeverageQuery = "delete from beverages where beverage_id = '$beverageId'";

        $result = $db -> query($deleteBeverageQuery);

        //Thông báo xóa thành công
        $_SESSION['beverage-management-success'] = "Xóa đồ uống thành công!";

        //Quay về trang quản lý dịch vụ thêm
        header("Location: ../management.php?m=beveragemanagement");
    }
?>