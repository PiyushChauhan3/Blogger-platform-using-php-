<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>BlogPoint's - Login</title>
    <link rel="stylesheet" href="css/style.css?v=0">
    <link rel="stylesheet" href="css/login.css?v=1">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
</head>

<body>
    <?php include 'nav.php'; ?>
    <div class="login-container">
        <div class="login-form-container">
            <form class="login-form" action="login_process.php" method="POST">
                <h2 class="login-title">Login</h2>
                <br>
                <label for="select-role" class="select-role">Select Role: </label><br>
                <select name="select-role" id="select-role" class="select-rol" required>
                    <option value="" class="sel-r">--Select Role--</option>
                    <option value="Reader">Reader</option>
                    <option value="Admin">Admin</option>
                    <option value="Blogger">Blogger</option>
                </select>
                <br><br>
                <label for="username" class="user">Username: </label><br>
                <input type="text" name="username" class="username" placeholder="Enter Your Username" required>
                <br><br>
                <label for="password" class="pass">Password: </label><br>
                <input type="password" name="password" class="password" placeholder="Enter your password" required>
                <br><br>
                <button type="submit" class="login">Login</button>
                <br><br>
                <h6 class="signup-text">Don't have an Account?<a href="signup.php"> Sign up </a></h6>
            </form>
        </div>
    </div>
    <?php include 'foot.php'; ?>
</body>
</html>
