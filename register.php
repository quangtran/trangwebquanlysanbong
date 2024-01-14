<?php
    //Config
    require_once('./Config/config.php');
?>

<style>
    <?php 
        require_once('./CSS/register.css');
    ?>
</style>

<?php
    //Thiết kế
    require_once('layout.php');

    //Xác thực đăng ké
    require_once('./Validates/auth-validate.php');

    //Tiến hành đằng ký
    if (isset($_POST['submit'])) {
        //Lấy data từ fỏm đki
        $usernameInput = $_POST['username'];
        $passwordInput = $_POST['password'];
        $emailInput = $_POST['email'];
        $phoneInput = $_POST['phone'];
        $realNameInput = $_POST['realname'];

        //Xác thực ttin đăng ký
        $usernameInput = userNameValidate($usernameInput);
        $passwordInput = passwordValidate($passwordInput);

        //Ktra ttin và up lên database
        checkAndUploadRegisterData($usernameInput, $passwordInput, $emailInput, $phoneInput, $realNameInput);

        $_SESSION['register-success'] = "Đăng ký thành công!";
    }

    else if (isset($_POST['usedSubmit'])) {
        $usernameInput = $_POST['username'];
        $passwordInput = $_POST['password'];
        $emailInput = $_POST['email'];
        $phoneInput = $_POST['phone'];

        //Để trường họ tên trống
        $realNameInput = "";

        $usernameInput = userNameValidate($usernameInput);
        $passwordInput = passwordValidate($passwordInput);

        checkAndUploadRegisterData($usernameInput, $passwordInput, $emailInput, $phoneInput, $realNameInput);

        $_SESSION['register-success'] = "Đăng ký thành công!";
    }

    ?>
        <div class="register-background"></div>

        <div class="register-container">
            <img src="./Images/user-logo.png">

            <h1>Đăng ký tại đây!</h1>

            <?php
                if (isset($_GET['r'])) {
                    ?>
                        <form method="POST">
                            <p>Số điện thoại đã đặt sân</p>
                            <input required type="tel" pattern="[0-9]{10}" name="phone" placeholder="Số điện thoại đã được sử dụng" class="register-phone-input">

                            <p>Tên đăng nhập</p>
                            <input required type="text" name="username" placeholder="Tên đăng nhập đã tồn tại" class="register-username-input">

                            <p>Mật khẩu</p>
                            <input required type="password" name="password" class="register-password-input">

                            <p>Email</p>
                            <input required type="email" name="email" placeholder="Email đã được sử dụng" class="register-email-input">

                            <input type="submit" value="Đăng ký" name="usedSubmit">

                            <span>
                                Đã có tài khoản? 
                                <a href="login.php">Đăng nhập.</a>
                            </span>
                        </form>
                    <?php
                }

                else {
                    ?>
                        <form method="POST">
                            <p>Họ tên</p>
                            <input required type="text" name="realname">

                            <p>Tên đăng nhập</p>
                            <input required type="text" name="username" placeholder="Tên đăng nhập đã tồn tại" class="register-username-input">

                            <p>Mật khẩu</p>
                            <input required type="password" name="password" class="register-password-input">

                            <p>Email</p>
                            <input required type="email" name="email" placeholder="Email đã được sử dụng" class="register-email-input">

                            <p>Số điện thoại</p>
                            <input required type="tel" pattern="[0-9]{10}" name="phone" placeholder="Số điện thoại đã được sử dụng" class="register-phone-input">

                            <input type="submit" value="Đăng ký" name="submit">

                            <span>
                                Đã có tài khoản? 
                                <a href="login.php">Đăng nhập.</a>
                            </span>
                        </form>
                    <?php
                }
            ?>
        </div>
    <?php
?>