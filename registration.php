<?php
session_start();
if (isset($_SESSION["user"])) {
   header("Location: index.php");
   exit; // Ensure to halt further execution after redirecting
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Registration Form</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            margin: 0;
            padding: 0;
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100vh;
            background-color: #f0f0f0;
        }

        .container {
            display: flex;
            align-items: center;
            width: 90%;
            max-width: 800px;
            border-radius: 10px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
            overflow: hidden;
            background-color: #fff;
        }

        .image-container {
            flex: 1;
            padding: 20px;
        }

        .image-container img {
            width: 100%;
            border-radius: 10px;
        }

        .form-container {
            flex: 1;
            padding: 33px;
        }

        .form-group {
            margin-bottom: 20px;
        }

        .form-control {
            width: 100%;
            padding: 10px;
            font-size: 16px;
            border-radius: 5px;
            border: 1px solid #ccc;
        }

        .form-btn {
            text-align: center;
        }

        .btn {
            padding: 10px 20px;
            font-size: 16px;
            border-radius: 5px;
            border: none;
            background-color: #4CAF50;
            color: white;
            cursor: pointer;
        }

        .btn:hover {
            background-color: #45a049;
        }

        .alert {
            padding: 15px;
            margin-bottom: 20px;
            border-radius: 5px;
        }

        .alert-danger {
            background-color: #f44336;
            color: white;
        }

        .alert-success {
            background-color: #4CAF50;
            color: white;
        }

        /* Responsiveness */
        @media screen and (max-width: 768px) {
            .container {
                flex-direction: column;
                width: 100%;
            }

            .image-container,
            .form-container {
                flex: none;
                width: 100%;
            }
        }
    </style>
</head>
<body>
    <div class="container">
        <div class="image-container">
            <img src="https://images.unsplash.com/photo-1580940583249-77175ce5f75a?q=80&w=2070&auto=format&fit=crop&ixlib=rb-4.0.3&ixid=M3wxMjA3fDB8MHxwaG90by1wYWdlfHx8fGVufDB8fHx8fA%3D%3D" alt="Registration Image">
        </div>
        <div class="form-container">
            <?php
            if (isset($_POST["submit"])) {
                $fullName = $_POST["fullname"];
                $email = $_POST["email"];
                $password = $_POST["password"];
                $passwordRepeat = $_POST["repeat_password"];
                
                $passwordHash = password_hash($password, PASSWORD_DEFAULT);
                
                $errors = array();
                
                if (empty($fullName) || empty($email) || empty($password) || empty($passwordRepeat)) {
                    array_push($errors,"All fields are required");
                }
                if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
                    array_push($errors, "Email is not valid");
                }
                if (strlen($password) < 8) {
                    array_push($errors,"Password must be at least 8 characters long");
                }
                if ($password !== $passwordRepeat) {
                    array_push($errors,"Password does not match");
                }
                require_once "database.php";
                $sql = "SELECT * FROM users WHERE email = ?";
                $stmt = mysqli_stmt_init($conn);
                if (!mysqli_stmt_prepare($stmt, $sql)) {
                    die("SQL error");
                } else {
                    mysqli_stmt_bind_param($stmt, "s", $email);
                    mysqli_stmt_execute($stmt);
                    $result = mysqli_stmt_get_result($stmt);
                    $rowCount = mysqli_num_rows($result);
                    if ($rowCount > 0) {
                        array_push($errors,"Email already exists!");
                    }
                }
                if (count($errors) > 0) {
                    foreach ($errors as $error) {
                        echo "<div class='alert alert-danger'>$error</div>";
                    }
                } else {
                    $sql = "INSERT INTO users (full_name, email, password) VALUES (?, ?, ?)";
                    $stmt = mysqli_stmt_init($conn);
                    if (!mysqli_stmt_prepare($stmt, $sql)) {
                        die("SQL error");
                    } else {
                        mysqli_stmt_bind_param($stmt, "sss", $fullName, $email, $passwordHash);
                        mysqli_stmt_execute($stmt);
                        echo "<div class='alert alert-success'>You are registered successfully.</div>";
                    }
                }
            }
            ?>
            <form action="registration.php" method="post">
                <div class="form-group">
                    <input type="text" class="form-control" name="fullname" placeholder="Full Name">
                </div>
                <div class="form-group">
                    <input type="email" class="form-control" name="email" placeholder="Email">
                </div>
                <div class="form-group">
                    <input type="password" class="form-control" name="password" placeholder="Password">
                </div>
                <div class="form-group">
                    <input type="password" class="form-control" name="repeat_password" placeholder="Repeat Password">
                </div>
                <div class="form-btn">
                    <input type="submit" class="btn btn-primary" value="Register" name="submit">
                </div>
            </form>
            <div>
                <p>Already Registered?</p>
                <p><a href="login.php">Login Here</a></p>
            </div>
        </div>
    </div>
</body>
</html>
