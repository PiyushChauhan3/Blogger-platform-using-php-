<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>BlogPoint's - Signup</title>
    <link rel="stylesheet" href="css/style.css?v=0">
    <link rel="stylesheet" href="css/login.css?v=0">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
</head>

<body>
    <?php include 'nav.php'; ?>
    <div class="signup-container">
        <div class="signup-form-container">
            <form class="signup-form" action="signup_process.php" method="POST">
                <h2 class="signup-title">Signup</h2>
                <br>
                <label for="select-role" class="select-role">Signup As a : </label><br>
                <select name="select-role" id="select-role" class="select-rol" required>
                    <option value="" class="sel-r">--Select Role--</option>
                    <option value="Reader">Reader</option>
                    <option value="Blogger">Blogger</option>
      
                </select>
                <br><br>
                <label for="username" class="user">Username : </label><br>
                <input type="text" name="username" class="username" placeholder="Create Your Username" required>
                <br><br>
                <label for="email" class="email user">Email : </label><br>
                <input type="email" name="email" class="email username" placeholder="example@example.com" required>
                <br><br>
                <label for="password" class="pass">Password : </label><br>
                <input type="password" name="password" class="password" placeholder="Enter your password" required>
                <br><br>
                <label for="confirm-password" class="conf-pass pass">Confirm Password : </label><br>
                <input type="password" name="confirm-password" class="password" placeholder="Confirm password" required>
                <br><br>
                <button type="submit" class="signup">Signup</button>
                <br><br>
                <h6 class="signup-text">Already Signed Up? <a href="login.php"> Login </a></h6>
            </form>
        </div>
    </div>
    <?php include 'foot.php'; ?>
</body>
</html>
