<?php
session_start();
include('koneksi.php');

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $username = $_POST['username'];
    $password = $_POST['password'];

    $query = "SELECT id, username, password, role FROM users WHERE username = ?"; // Ambil ID dan role
    $stmt = mysqli_prepare($selectdb, $query);
    mysqli_stmt_bind_param($stmt, "s", $username);
    mysqli_stmt_execute($stmt);
    $result = mysqli_stmt_get_result($stmt);

    if ($user = mysqli_fetch_assoc($result)) {
        if (password_verify($password, $user['password'])) {
            $_SESSION['loggedin'] = true;
            $_SESSION['id'] = $user['id']; 
            $_SESSION['username'] = $user['username'];
            $_SESSION['role'] = $user['role'];

            if ($user['role'] === 'admin') {
                header('Location: admin_dashboard.php');
            } elseif ($user['role'] === 'user') { // Redirect to user_dashboard.php for 'user' role
                header('Location: user_dashboard.php');
            } else {
                // Fallback for any other unexpected roles, or simply redirect to a default page
                header('Location: daftar_laptop.php'); 
            }
            exit;
        } else {
            $error = "Username atau password salah.";
        }
    } else {
        $error = "Username atau password salah.";
    }
    mysqli_stmt_close($stmt);
}
?>
<!DOCTYPE html>
<html>

<head>
    <title>Login Sistem Rekomendasi Laptop</title>
    <link href="https://fonts.googleapis.com/icon?family=Material+Icons" rel="stylesheet">
    <link type="text/css" rel="stylesheet"
        href="https://cdnjs.cloudflare.com/ajax/libs/materialize/1.0.0/css/materialize.min.css"
        media="screen,projection" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <style>
    body {
        display: flex;
        min-height: 100vh;
        flex-direction: column;
        background: linear-gradient(to right, #4CAF50, #8BC34A);
        /* Gradient background */
        font-family: 'Roboto', sans-serif;
    }

    main {
        flex: 1 0 auto;
        display: flex;
        align-items: center;
        justify-content: center;
    }

    .login-card {
        background-color: #ffffff;
        padding: 40px;
        border-radius: 8px;
        box-shadow: 0 4px 20px rgba(0, 0, 0, 0.15);
        width: 90%;
        max-width: 400px;
        text-align: center;
    }

    .login-card h4 {
        color: #388e3c;
        /* Darker green for heading */
        margin-bottom: 30px;
        font-weight: 500;
    }

    .input-field input[type=text]:focus+label,
    .input-field input[type=password]:focus+label {
        color: #4CAF50 !important;
    }

    .input-field input[type=text]:focus,
    .input-field input[type=password]:focus {
        border-bottom: 1px solid #4CAF50 !important;
        box-shadow: 0 1px 0 0 #4CAF50 !important;
    }

    .btn {
        background-color: #4CAF50 !important;
        margin-top: 20px;
        width: 100%;
        /* padding: 10px 0; */
        border-radius: 5px;
        font-size: 0.9emem;
        letter-spacing: 0.5px;
    }

    .btn:hover {
        background-color: #689F38 !important;
        /* Slightly darker green on hover */
    }

    .red-text {
        margin-top: 5px;
        font-size: 0.2em;
    }
    nav {
        color: #fff;
        background-color: #055D28 ;
        box-shadow: 0 2px 10px rgba(0, 0, 20, 0.2);
        width: 100%;
        height: 56px;
        line-height: 60px;
}
    </style>
</head>

<body>
    <div class="navbar-fixed">
        <nav>
            <div class="container">
                <div class="nav-wrapper">
                    <ul class="left" style="margin-left: -52px; color: #0f0;">
                        <li><a href="index.php">HOME</a></li>
                        <li><a href="daftar_laptop.php">DAFTAR LAPTOP</a></li>
                        <li><a href="login.php">LOGIN</a></li>
                    </ul>
                </div>
            </div>
        </nav>
    </div> <main>

        <div class="login-card">
            <h4>Login Admin</h4>
            <?php if (isset($error)): ?>
            <p class="red-text"><?php echo $error; ?></p>
            <?php endif; ?>
            <form action="login.php" method="post">
                <div class="input-field">
                    <i class="material-icons prefix">person</i>
                    <input id="username" type="text" name="username" required>
                    <label for="username">Username</label>
                </div>
                <div class="input-field">
                    <i class="material-icons prefix">lock</i>
                    <input id="password" type="password" name="password" required>
                    <label for="password">Password</label>
                </div>
                <button class="btn waves-effect waves-light" type="submit" name="login">Login
                    </button>
            </form>
        </div>
    </main>
    <script type="text/javascript" src="https://cdnjs.cloudflare.com/ajax/libs/materialize/1.0.0/js/materialize.min.js">
    </script>
    <script>
    document.addEventListener('DOMContentLoaded', function() {
        M.updateTextFields(); // Ensure labels are correctly positioned
    });
    </script>
</body>

</html>