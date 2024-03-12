<?php
$base_url = 'http://' . $_SERVER['HTTP_HOST'];

ini_set('session.cookie_path', '/reservation');
session_start();

$api_url = $base_url . '/reservation/includes/login.api.php';

$ch = curl_init();
curl_setopt($ch, CURLOPT_URL, $api_url);
curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
curl_setopt($ch, CURLOPT_FOLLOWLOCATION, 1);
curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, 0); // Skip SSL certificate verification
curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, 0); // Skip SSL certificate verification

$api_data_json = curl_exec($ch);

if (curl_errno($ch)) {
    echo 'Error:' . curl_error($ch);
}

curl_close($ch);

$response = json_decode($api_data_json, true);

if (isset($_SESSION['id'])) {
    header("Location: " . $base_url . "/reservation/reservation.php");
    exit();
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <title>Login</title>
    <link rel="icon" href="styles/icon.ico" type="image/x-icon">
    <link rel="stylesheet" href="styles/login.css">
    <script type="module" src="https://unpkg.com/ionicons@7.1.0/dist/ionicons/ionicons.esm.js"></script>
    <script nomodule src="https://unpkg.com/ionicons@7.1.0/dist/ionicons/ionicons.js"></script>
</head>

<body>

    <main>
        <div class="left-side"></div>

        <div class="right-side">
            <form name="loginForm" id='' action='<?php echo $api_url; ?>' onsubmit="return isValid()" method="POST">
                <div class="btn-group">
                    <button class="btn">
                        <img class="logo" src="https://github.com/ecemgo/mini-samples-great-tricks/assets/13468728/d1c98974-c62d-4071-8bd2-ab859fc5f4e9" alt="" />
                        <span>Sign in with Google</span>
                    </button>
                    <button class="btn">
                        <img class="logo" src="https://github.com/ecemgo/mini-samples-great-tricks/assets/13468728/59c1561b-8152-4d05-b617-0680a7629a0e" alt="" />
                        <span>Sign in with Apple</span>
                    </button>
                </div>

                <div class="or">OR</div>

                <label for="email">Email</label>
                <input type="email" placeholder="Enter Email" name="email" required/>

                <label for="password">Password</label>
                <input
                    type="password"
                    placeholder="Enter Password"
                    name="password"
                    required />

                <button type="submit" value="Login" name="submit" class="login-btn">Log In</button>
                <div class="links">
                    <a href="#">Forgot password?</a>
                    <a href="register.php">Do not have an account?</a>
                </div>
            </form>
        </div>

    </main>
</body>

</html>
