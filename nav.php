<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Navbar</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.6.0/css/all.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">
    <link rel="stylesheet" href="css/nav.css?v=2">
</head>

<body>
    <nav>
        <div class="logo">
            <a href="index.php" class="logo-text">BlogPoint'S</a>
        </div>
        <div class="menu-toggle">
            <i class="fa fa-bars"></i>
        </div>
        <div class="list">
            <ul class="nav-list">
                <li><a href="index.php" class="l1">Home</a></li>
                <li><a href="posts.php" class="l2">Post's</a></li>
                <form class="d-flex" action="search.php" method="GET">
                    <input class="form-control me-2" type="search" placeholder="Search posts..." name="query" required>
                    <button class="btn btn-outline-success" type="submit"><i class="fa fa-search" style="font-size:24px"></i>
                    </button>
                </form>
                <li><a class="l3" href="contact.php">Contact Us</a></li>
                <?php if (isset($_SESSION['username'])): ?>
                    <li class="profile-menu">
                        <span class="profile-icon"><?php echo htmlspecialchars($_SESSION['username']); ?> &#9660; <i
                                class="fa fa-user-circle-o" style="font-size: 22px;"></i></span>
                        <div class="dropdown-content">
                            <?php if ($_SESSION['role'] === 'Reader'): ?>
                                <a href="reader_profile.php">Profile</a>
                            <?php endif; ?>

                            <?php if ($_SESSION['role'] === 'Blogger'): ?>
                                <a href="blogger_profile.php">Profile</a>
                            <?php endif; ?>

                            <?php if ($_SESSION['role'] === 'Blogger'): ?>
                                <a href="create_post.php">Create Post</a>
                            <?php endif; ?>
                            <?php if ($_SESSION['role'] === 'Admin'): ?>
                                <a href="admin_profile.php">Admin Profile</a>
                                <a href="admin_dashboard.php">Admin Dashboard</a>
                            <?php endif; ?>
                            <a href="logout.php">Logout</a>
                        </div>
                    </li>
                <?php else: ?>
                    <li class="l4"><a href="login.php">Login/Sign up</a></li>
                <?php endif; ?>
            </ul>
        </div>
    </nav>

    <script>
        document.addEventListener('DOMContentLoaded', function () {
            const menuToggle = document.querySelector('.menu-toggle');
            const navList = document.querySelector('.nav-list');

            menuToggle.addEventListener('click', () => {
                navList.classList.toggle('nav-list-active');
            });
        });
    </script>
</body>

</html>
