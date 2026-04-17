<!DOCTYPE html>
<html>

<head>
    <title>Login/Register</title>
    <style>
        body {
            background-color: #f2f1ec;
        }

        .wrapper {
            height: 100vh;
            display: flex;
            justify-content: center;
            align-items: center;
        }

        .container {
            width: 300px;
            display: flex;
            flex-direction: column;
            align-items: center;
        }


        input {
            margin: 10px 0 10px 0;
            border: solid 10px #365b6d;
            border-radius: 2px;
            padding: 5px;
        }

        button {
            margin: 10px 0 10px 0;
            width: 60%;
            border: solid 10px #365b6d;
            border-radius: 4px;
            background-color: #365b6d;
        }

        a {
            color: white;
        }

        #forgot {
            color: blue;
        }

        #loginbtn {
            width: 100%;
            border: solid 10px#53786d;
            border-radius: 4px;
            background-color: #53786d;
            color: white;
        }

        .nav-links a.active {
            border-bottom: 2px solid #4ce0ca;
        }

        h2 {
            border: solid 10px #365b6d;
            border-radius: 20px;
            background-color: #365b6d;
            color: white;
        }

        span {
            color: cyan;
        }
        #back {
            color: white;
            background-color: #365b6d;
            font-weight: bold;
            width: auto;
        }
    </style>
</head>

<body>
    <button id="back"><a href="./index.php">Go back</a></button>
    <div class="wrapper">

        <div class="container">
            <h2>Libra<span>Byte</span></h2>
            <form action="auth/register.php" method="POST">
                <input name="username" placeholder="Username" required><br>
                <input name="password" type="password" placeholder="Password" required><br>
                <button type="submit" id="loginbtn">Sign Up</button>
            </form>
        </div>
    </div>

</body>

</html>