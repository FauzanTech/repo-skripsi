<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Register</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css" />
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/css/bootstrap.min.css">
    <style>
        body {
            font-family: 'Poppins', sans-serif;
            background-color: #f5f5f5;
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100vh;
        }
        .container {
            width: 400px;
            padding: 20px;
            background-color: #fff;
            box-shadow: 0 5px 15px rgba(0, 0, 0, 0.1);
            border-radius: 10px;
        }
        .title {
            font-size: 24px;
            font-weight: bold;
            text-align: center;
            margin-bottom: 20px;
        }
        .input-field {
            position: relative;
            margin-bottom: 15px;
        }
        .input-field i {
            position: absolute;
            top: 12px;
            left: 10px;
            color: #333;
        }
        .input-field input {
            width: 100%;
            padding: 10px 10px 10px 30px;
            border: 1px solid #ccc;
            border-radius: 30px;
            outline: none;
        }
        .input-field input:focus {
            border-color: #007bff;
        }
        .btn {
            width: 100%;
            background-color: #007bff;
            color: #fff;
            padding: 10px;
            border: none;
            border-radius: 30px;
            cursor: pointer;
            font-size: 16px;
        }
        .btn:hover {
            background-color: #0056b3;
        }
        .error-message {
            color: red;
            font-size: 12px;
            margin-top: -10px;
            margin-bottom: 10px;
        }
        .choice {
            text-align: center;
            margin-top: 20px;
        }
    </style>
</head>
<body>

<div class="container">
    <h2 class="title">Register</h2>
    <form method="POST" action="<?= base_url('/register-proces') ?>" class="register">
        <?= csrf_field() ?>

        <!-- Username Field -->
        <div class="input-field">
            <i class="fas fa-user"></i>
            <input type="text" name="username" placeholder="Username" value="<?= old('username') ?>">
        </div>
        <?php if (session('errors.username')): ?>
            <small class="error-message"><?= session('errors.username') ?></small>
        <?php endif; ?>

        <!-- Password Field -->
        <div class="input-field">
            <i class="fas fa-lock"></i>
            <input type="password" name="password" placeholder="Password">
        </div>
        <?php if (session('errors.password')): ?>
            <small class="error-message"><?= session('errors.password') ?></small>
        <?php endif; ?>

        <!-- Confirm Password Field -->
        <div class="input-field">
            <i class="fas fa-lock"></i>
            <input type="password" name="confirm_password" placeholder="Confirm Password">
        </div>
        <?php if (session('errors.confirm_password')): ?>
            <small class="error-message"><?= session('errors.confirm_password') ?></small>
        <?php endif; ?>

        <!-- Register Button -->
        <input type="submit" value="Register" class="btn">
    </form>
</div>

</body>
</html>
