<?php

$USERNAME = "admin";
$PASSWORD = "admin123";

$error = "";

if ($_SERVER["REQUEST_METHOD"] === "POST") {

    $user = $_POST["username"] ?? "";
    $pass = $_POST["password"] ?? "";

    if ($user === $USERNAME && $pass === $PASSWORD) {

        // Vercel-safe redirect (no sessions)
        header("Location: /?auth=1");
        exit;

    } else {
        $error = "Invalid credentials";
    }
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Login</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>

<body class="bg-light">

<div class="container mt-5">
    <div class="row justify-content-center">
        <div class="col-md-4">

            <div class="card p-4 shadow">

                <h4 class="text-center">Login</h4>

                <?php if (!empty($error)): ?>
                    <div class="alert alert-danger">
                        <?= $error ?>
                    </div>
                <?php endif; ?>

                <form method="POST">
                    <input type="text" name="username" class="form-control mb-2" placeholder="Username" required>
                    <input type="password" name="password" class="form-control mb-2" placeholder="Password" required>

                    <button class="btn btn-primary w-100">Login</button>
                </form>

            </div>

        </div>
    </div>
</div>

</body>
</html>