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
            background-color: #bfe1f6;
        }

        .centered-text {
            text-align: center;
            margin-top: 100px; /* Adjust as needed */
        }

        .container {
            display: flex;
            align-items: center;
            width: 90%;
            max-width: 800px;
            border-radius: 10px;
            box-shadow: rgba(0, 0, 0, 0.17) 0px -23px 25px 0px inset,
            rgba(0, 0, 0, 0.15) 0px -36px 30px 0px inset,
            rgba(0, 0, 0, 0.1) 0px -79px 40px 0px inset,
            rgba(0, 0, 0, 0.06) 0px 2px 1px,
            rgba(0, 0, 0, 0.09) 0px 4px 2px,
            rgba(0, 0, 0, 0.09) 0px 8px 4px,
            rgba(0, 0, 0, 0.09) 0px 16px 8px,
            rgba(0, 0, 0, 0.09) 0px 32px 16px;
            overflow: hidden;
            background-color: #fff;
        }

        .button-64 {
            align-items: center;
            background-image: linear-gradient(144deg,#AF40FF, #5B42F3 50%,#00DDEB);
            border: 0;
            border-radius: 8px;
            box-shadow: rgba(151, 65, 252, 0.2) 0 15px 30px -5px;
            box-sizing: border-box;
            color: #FFFFFF;
            display: inline-block;
            font-family: Phantomsans, sans-serif;
            font-size: 20px;
            justify-content: center;
            line-height: 1em;
            max-width: 100%;
            min-width: 140px;
            text-decoration: none;
            user-select: none;
            -webkit-user-select: none;
            touch-action: manipulation;
            white-space: nowrap;
            cursor: pointer;
            }

        .button-64:active,
        .button-64:hover {
            outline: 0;
            }

        .button-64 span {
            background-color: rgb(5, 6, 45);
            padding: 16px 24px;
            border-radius: 6px;
            width: 100%;
            height: 100%;
            transition: 300ms;
            }

        .button-64:hover span {
            background: none;
        }

        @media (min-width: 768px) {
            .button-64 {
            font-size: 24px;
            min-width: 196px;
        }
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
            <h1 style="font-size: 2em; text-align: center;text-decoration: underline solid; margin-bottom: 20px;">Registration Form</h1>
            <?php
$errors = [];
if (isset($_POST["submit"])) {
    $fullName = $_POST["fullname"];
    $email = $_POST["email"];
    $password = $_POST["password"];
    $passwordRepeat = $_POST["repeat_password"];

    $passwordHash = password_hash($password, PASSWORD_DEFAULT);

    require_once "database.php";

    if (empty($fullName) || empty($email) || empty($password) || empty($passwordRepeat)) {
        $errors[] = "All fields are required";
    }
    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $errors[] = "Email is not valid";
    }
    if (strlen($password) < 8) {
        $errors[] = "Password must be at least 8 characters long";
    }
    if ($password !== $passwordRepeat) {
        $errors[] = "Password does not match";
    }

    if (empty($errors)) {
        $sql = "SELECT * FROM users WHERE User_email = ?";
        $stmt = mysqli_stmt_init($conn);
        if (!mysqli_stmt_prepare($stmt, $sql)) {
            die("SQL error");
        } else {
            mysqli_stmt_bind_param($stmt, "s", $email);
            mysqli_stmt_execute($stmt);
            $result = mysqli_stmt_get_result($stmt);
            $rowCount = mysqli_num_rows($result);
            if ($rowCount > 0) {
                $errors[] = "Email already exists!";
            } else {
                $sql = "INSERT INTO users (User_Name, User_email, User_password) VALUES (?, ?, ?)";
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
                
                <button class="button-64" type="submit" name="submit"><span class="text">Register</span></button>
                </div>
            </form>
            <div class="centered-text">
                <p>Already Registered?</p>
                <p><a href="login.php">Login Here</a></p>
            </div>
        </div>
    </div>
</body>
</html>