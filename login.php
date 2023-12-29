<?php
session_start();
// Check and initialize login attempts
if (!isset($_SESSION['login_attempts'])) {
    $_SESSION['login_attempts'] = 0;
}

if (isset($_SESSION['expire_time']) && $_SESSION['expire_time']->diff(new DateTime)->format('%R') == '+') {
    unset($_SESSION['expire_time']);
    $_SESSION['login_attempts'] = 0;
}

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    // Validate login credentials
    $email = $_POST["email"];
    $password = $_POST["password"];

    require_once "database.php"; // Include your database connection file

    // Adjust the SQL query to use the correct column names
    $sql = "SELECT * FROM users WHERE User_email = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("s", $email);
    $stmt->execute();
    $result = $stmt->get_result();
    $user = $result->fetch_assoc();

    // Validate the user's existence and password
    if ($user && password_verify($password, $user["User_password"])) {
        $_SESSION["user"] = "yes";
        $_SESSION['login_attempts'] = 0;
        header("Location: generate_otp.php");
        exit;
    } else {
        $error = "<div class='alert alert-danger'>Invalid email or password</div>";

        // Increment login attempts only when the credentials are checked
        $_SESSION['login_attempts']++;

        // Check if login attempts exceed limit
        if ($_SESSION['login_attempts'] >= 3) {
            if (!isset($_SESSION['expire_time'])) {
                $_SESSION['expire_time'] = new DateTime();
                $_SESSION['expire_time']->add(new DateInterval("PT1M"));
            }
            $error = "<div class='alert alert-danger'>Login attempts exceeded. Please try again later.</div>";
        }
    }
}
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login Form</title>
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
            margin-top: 100px;
            /* Adjust as needed */
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

        .button-65 {
            align-items: center;
            background-image: linear-gradient(144deg, #AF40FF, #5B42F3 50%, #00DDEB);
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
            padding: 17px;
            text-decoration: none;
            user-select: none;
            -webkit-user-select: none;
            touch-action: manipulation;
            white-space: nowrap;
            cursor: pointer;
        }

        .button-65:active,
        .button-65:hover {
            outline: 0;
        }

        .button-65 span {
            background-color: rgb(5, 6, 45);
            padding: 16px 60px;
            border-radius: 6px;
            width: 100%;
            height: 100%;
            transition: 300ms;
        }

        .button-65:hover span {
            background: none;
        }

        @media (min-width: 768px) {
            .button-65 {
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

            .image-container {}

            .form-container {
                flex: none;
                width: 100%;
            }
        }
    </style>
</head>

<body>
    <div class="container">
        <div class="form-container">
            <h1 style="font-size: 2em; text-align: center;text-decoration: underline solid; margin-bottom: 20px;">Login Form</h1>
            <?= $error ?? "" ?>
            <form action="login.php" method="post">
                <div class="form-group">
                    <input type="email" placeholder="Enter Email:" name="email" class="form-control">
                </div>
                <div class="form-group">
                    <input type="password" placeholder="Enter Password:" name="password" class="form-control">
                </div>
                <div class="form-btn">
                    <button id="submit-button" class="button-65" type="submit" name="submit"><span class="text">Login</span></button>
                </div>
            </form>
            <div class="centered-text">
                <p>Not registered yet ?</p>
                <p><a href="registration.php">Register Here</a></p>
            </div>
            <?php
            if (isset($_SESSION['expire_time']) && $_SESSION['expire_time']->diff(new DateTime)->format('%R') == '-') {
                $now = new DateTime('now');
            ?>
                <script>
                    const button = document.getElementById('submit-button');
                    button.disabled = true;
                    setInterval(function() {
                        button.disabled = false;
                    }, <?= ($_SESSION['expire_time']->getTimeStamp() - $now->getTimeStamp()) * 1000; ?>);
                </script>
            <?php
            }
            ?>
        </div>
    </div>
</body>

</html>