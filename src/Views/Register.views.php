<?php

use RaihanNih\Factory\SessionFactory;
use RaihanNih\Utils\Config;
use RaihanNih\Utils\Mysql;

$config = new Config(RESOURCES . "config.yml");
if (!SessionFactory::hasStarted("username")) {
    if ($_SERVER["REQUEST_METHOD"] == "POST") {
        if (isset($_POST["register"])) {
            $username = $_POST["username"];
            $email = $_POST["email"];
            $phone = $_POST["phone"];
            $password = $_POST["password"];
            $rpassword = $_POST["rpassword"];

            if ($password == $rpassword) {
                $mysql = new Mysql($config->get("MYSQL_HOST") ?? "localhost", $config->get("MYSQL_USER") ?? "root", $config->get("MYSQL_PASSWORD") ?? "", $config->get("MYSQL_DATABASE"));
                $rowAll = $mysql->executeQuery("SELECT * FROM users WHERE username = '$username' AND email = '$email'");
                $rowCount = $rowAll->rowCount();

                if ($rowCount == 0) {
                    $mysql->executeQuery("INSERT INTO users (username, email, phone, password) VALUES (:username, :email, :phone, :password);", [
                        ":username" => $username,
                        ":email" => $email,
                        ":phone" => $phone,
                        ":password" => sha1($password),
                    ]);
                    echo "<script>alert('Berhasil mendaftar');</script>";
                    echo "<script>window.location = '/login';</script>";
                } else {
                    echo "<script>alert('Username atau email telah digunakan');</script>";
                    echo "<script>window.location = '/register';</script>";
                }
            } else {
                echo "<script>alert('Password tidak sesuai');</script>";
                echo "<script>window.location = '/register';</script>";
            }
        }
    }
} else {
    echo "<script>window.location = '/';</script>";
}
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Register</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="//fonts.googleapis.com/css?family=Open+Sans:300,400,600|Raleway:400,700|Karla:400,700|Poppins:400,500,600,700|Montserrat:400|Poppins:100,200,300,400,500,600,700,800,900" rel="stylesheet">
    <style>
        body {
            background-color: #080825;
            display: flex;
            align-items: center;
            justify-content: center;
            min-height: 100vh;
            font-family: 'Open Sans', sans-serif;
        }

        .form-container {
            background-color: #ffffff;
            border-radius: 10px;
            box-shadow: 1px 5px 20px rgba(255, 255, 255, 0.2);
            padding: 40px;
            max-width: 400px;
            max-height: 600px;
            overflow-y: auto;
            margin: 0 auto;
        }

        .form-container h5 {
            text-align: center;
            color: #000;
            font-size: 32px;
            font-weight: bold;
            margin-bottom: 20px;
        }

        .login-form input[type="text"],
        .login-form input[type="password"],
        .login-form input[type="email"] {
            border: 2px solid #ccc;
            border-radius: 5px;
            padding: 10px;
            margin-bottom: 10px;
            box-shadow: 0px 0px 10px rgba(0, 0, 0, 0.1);
            outline: none;
            transition: border-color 0.3s ease;
            width: 100%;
        }

        .login-form input[type="text"]:focus,
        .login-form input[type="password"]:focus,
        .login-form input[type="email"]:focus {
            border-color: #000;
        }

        .login-form input[type="text"]:hover,
        .login-form input[type="password"]:hover,
        .login-form input[type="email"]:hover {
            border-color: #000;
        }

        .login-form .form-group {
            margin-bottom: 20px;
        }

        .login-form label {
            font-weight: bold;
            margin-bottom: 10px;
            display: block;
        }

        .login-form button {
            background-color: #000;
            color: #fff;
            border: 2px solid #ccc;
            border-radius: 5px;
            padding: 15px 30px;
            cursor: pointer;
            transition: background-color 0.3s ease, border-color 0.3s ease, color 0.3s ease;
            width: 100%;
        }

        .login-form button:hover {
            background-color: #222;
            border-color: #333;
            color: #fff;
        }

        .register-divider {
            text-align: center;
            margin-top: 15px;
        }

        .register-divider hr {
            border: none;
            height: 2px;
            background: #ddd;
            margin: 10px 0;
        }

        .register-link {
            text-align: center;
            margin-top: 10px;
        }

        .register-link a {
            text-decoration: none;
            color: #000;
            transition: border-bottom 0.3s ease;
            margin-bottom: -2px;
        }

        .register-link a:hover,
        .register-link a:focus {
            border-bottom: 1px solid #000;
        }
    </style>
</head>

<body>
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-8">
                <div class="form-container" <h5 class="mb-4">Register</h5>
                    <form class="login-form" method="post">
                        <div class="form-group">
                            <label for="username">Username</label>
                            <input type="text" class="form-control" placeholder="Enter Username" id="username" name="username" required>
                        </div>
                        <div class="form-group">
                            <label for="email">Email</label>
                            <input type="email" class="form-control" placeholder="Enter Email" id="email" name="email" required>
                        </div>
                        <div class="form-group">
                            <label for="phone">Phone</label>
                            <input type="text" class="form-control" placeholder="Enter Phone Number" id="phone" name="phone" required>
                        </div>
                        <div class="form-group">
                            <label for="password">Password</label>
                            <input type="password" class="form-control" placeholder="Enter Password" id="password" name="password" required>
                        </div>
                        <div class="form-group">
                            <label for="rpassword">Repeat Password</label>
                            <input type="password" class="form-control" placeholder="Repeat Password" id="rpassword" name="rpassword" required>
                        </div>
                        <div class="text-center">
                            <button type="submit" id="register" name="register" class="btn">Register</button>
                        </div>
                    </form>
                    <div class="register-divider">
                        <hr>
                        <div class="register-link">
                            <p>Already Registered? <a href="/login">Login Here</a></p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.min.js"></script>
</body>

</html>