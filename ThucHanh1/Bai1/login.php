<!DOCTYPE html>
<html lang="vi">
<head>
<meta charset="UTF-8">
<title>PHT Chương 3 - Đăng nhập</title>
</head>
<body>
    <h2>Form Đăng Nhập</h2>
    <form action="" method="POST">
        <div>
            <label>Tên đăng nhập:</label>
            <input type="text" name="username">
        </div>
        <div>
            <label>Mật khẩu:</label>
            <input type="password" name="password">
        </div>
        <button type="submit">Đăng nhập</button>
    </form>

    <?php
    session_start();
    if (isset($_POST['username']) && isset($_POST['password'])){
        $username = $_POST['username'];
        $pass = $_POST['password'];

        if ($username == 'user' && $pass == '123') {
            $_SESSION['user_id'] = $username;
            header('Location: user.php');
            exit;
        } 
        else if ($username == 'admin' && $pass == '123') {
            $_SESSION['user_id'] = $usernames;
            header('Location: admin.php');
            exit;
        }
        else {
            echo "<p style='color:red;'>Tên đăng nhập hoặc mật khẩu không đúng!</p>";
        }
    }
    ?>
</body>
</html>