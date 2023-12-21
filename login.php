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
            background-color: #f0f0f0;
        }

        .container {
            display: flex;
            align-items: center;
            justify-content: space-around;
            width: 90%;
            max-width: 518px;
            border-radius: 10px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
            overflow: hidden;
            background-color: #fff;
        }

        .image-container {
            flex: 9;
            padding: -2px;
            display: flex;
            justify-content: center;
            align-items: center;
        }

        .image-container img {
            max-width: 100%;
            max-height: 100%;
            border-radius: 10px;
            object-fit: contain; /* Adjust to contain the image */
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
            <img src="https://cdn-icons-png.flaticon.com/512/2499/2499292.png" >
        </div>
    <div class="container">
        <?php
        if (isset($_POST["login"])) {
           $email = $_POST["email"];
           $password = $_POST["password"];
            require_once "database.php";
            $sql = "SELECT * FROM users WHERE email = '$email'";
            $result = mysqli_query($conn, $sql);
            $user = mysqli_fetch_array($result, MYSQLI_ASSOC);
            if ($user) {
                if (password_verify($password, $user["password"])) {
                    session_start();
                    $_SESSION["user"] = "yes";
                    header("Location: index.php");
                    die();
                }else{
                    echo "<div class='alert alert-danger'>Password does not match</div>";
                }
            }else{
                echo "<div class='alert alert-danger'>Email does not match</div>";
            }
        }
        ?>
         <form action="login.php" method="post">
                <div class="form-group">
                    <input type="email" placeholder="Enter Email:" name="email" class="form-control">
                </div>
                <div class="form-group">
                    <input type="password" placeholder="Enter Password:" name="password" class="form-control">
                </div>
                <div class="form-btn">
                    <input type="submit" value="Login" name="login" class="btn btn-primary">
                </div>
                <div>
                    <p>Not registered yet <a href="registration.php">Register Here</a></p>
                </div>
            </form>
        </div>
    </div>
</body>
</html>