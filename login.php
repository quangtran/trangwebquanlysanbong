<?php
    require_once('./Config/config.php');
?>

<style>
    <?php 
        require_once('./CSS/login.css');
    ?>
</style>

<?php
    //Thiết kế wen
    require_once('layout.php');

    // Xác thực
    require_once('./Validates/auth-validate.php');

    // Xử lý đnhap
    if (isset($_POST['submit'])) {
        //Ghi dữ luệu bao gồm username vàg mk
        $usernameInput = $_POST['username'];
        $passwordInput = $_POST['password'];

        // Xác thực dữ liệu đăng nhập
        $usernameInput = userNameValidate($usernameInput);
        $passwordInput = passwordValidate($passwordInput);

        // Submit username và mật khẩu
        login($usernameInput, $passwordInput);

        $_SESSION['login-success'] = "Đăng nhập thành công!";
    }

    ?>
        <div class="login-background"></div>

        <div class="login-container">
            <img src="./Images/user-logo.png">

            <h1>Đăng nhập tại đây!</h1>

            <form method="POST">
                <p>Tên đăng nhập</p>
                <input type="text" name="username" class="username-input" placeholder="Tên đăng nhập không đúng">

                <p>Mật khẩu</p>
                <input type="password" name="password" class="password-input" placeholder="Mật khẩu không đúng">

                <input type="submit" value="Đăng nhập" name="submit">

                <a href="register.php?r=usedtobooking">Đã từng đặt sân</a>
                <br>
                <a href="register.php">Đăng ký mới</a>
            </form>
        </div>

        <?php
            if (isset($_SESSION['register-success'])) {
                ?>
                    <div class="register-success">
                        <p><?= $_SESSION['register-success'] ?></p>
                        <span>&times;</span>
                    </div>
                <?php

                unset($_SESSION['register-success']);
            }
        ?>
    <?php
?>

<script>
    <?php 
        require_once('./JS/close-popup-message.js');
    ?>
</script>