<?php
    require_once('../Config/config.php');

    if (isset($_POST['newSubmit'])) {
        $db = getDatabase();

        $beverageName = $_POST['newBeverageName'];

        $beverageCost = $_POST['newBeverageCost'];
        $beverageCost = (float)$beverageCost;

        $newBeverageId = uniqid();

        $checkNewBeverageName = true;

        $newBeverageName = mysqli_escape_string($db, $beverageName);

        $getNewBeverageName = $db -> query("select beverage_name from beverages where beverage_name = '$newBeverageName'");

        if ($getNewBeverageName -> num_rows > 0) {
            $checkNewBeverageName = false;
        }

        if ($checkNewBeverageName == false) {
            $_SESSION['beverage-management-error'] = "Tên đồ uống đã tồn tại!";
        }

        else {
            $newBeverageId = mysqli_escape_string($db, $newBeverageId);
            $beverageName = mysqli_escape_string($db, $beverageName);
            $beverageCost = mysqli_escape_string($db, $beverageCost);

            $addNewBeverageQuery = "insert into beverages (beverage_id, beverage_name, beverage_cost) values ('$newBeverageId', '$beverageName', '$beverageCost')";
            $res = $db -> query($addNewBeverageQuery);

            $_SESSION['beverage-management-success'] = "Thêm đồ uống thành công!";
        }
    }
    
    header("Location: ../management.php?m=beveragemanagement");
?>