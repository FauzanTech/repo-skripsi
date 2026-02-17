<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login</title>
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
            border-radius: 10px;
            outline: none;
            background: #fff;
        }
        .input-field input:focus {
            border: none;
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
        .home-panel {
      width:100%!important;
  }
  .left-home-panel{
    width: 50vw;
    height:100vh;
    color:#fff;
    background:#17283b;
    display: grid;
    align-items: center;
  }
  .body {
    margin: 0!important;
  }
  .quote{
    padding: 0.3rem 1rem;
    
    font-weight:500;
    border-left: solid 3px #56b8e6;
    margin-top: 2rem;
    color: color-mix(in srgb, #fff, transparent 20%);
    p {
      font-size:12pt!important;
    }
  }
  h1 {
    font-size: 3rem;
    font-weight: 700;
  }
  .right-home-panel {
    background-image: url('assets/images/ith/ith.jpeg');
    background-position: calc(50% - 20px) calc(50% - 10px);
    background-size: 140%;
    background-color: #17283b;
    display: grid;
    align-items: center;
  }
  .form-search {
    border-radius: 0!important;
    width: 90%;
  }
  form {
    width: 38rem;
  }
  .btn-login {
    color: #fff;
    background: #17283b;
    text-align: center;
    font-size: 12pt;
    padding: 0.6rem 2rem;
    border-radius: 10px;
    outline: none;
    border: none;
    width: 30%;
    float: right;

  }
  .btn-login:hover, .btn-login:active {
    background: #56b8e6;
  }
  .card-login {
    /* background-color: #8a949c; */
    opacity:1;
    border-radius: 25px;
    padding: 20px 2rem;
    margin-top: 10rem;
    opacity: 0.9;
    
  }
  @media (max-width: 480px) {
    body {
      display: block;
    }
    .card-login {
      padding: 5px 1rem;
    }
    .home-panel {
      height: 100vh;
    }
    form {
      width: 100%;
    }
    .left-home-panel {
      display: none;
    }
    .btn-login {
      width: 100%;
    }
}
.is-invalid {
  border: 2px solid red !important;
}
    </style>
</head>
<body>
<div class="home-panel d-flex">
    <div class="left-home-panel col-md-6 col-lg-6 col-xs-0 col-sm-0">
      <div>
        <h1>Selamat Datang di</h1>
        <h1>Sistem Repositori ITH</h1>
        <div class="quote">
          <p>"Pembangunan bangsa Indonesia itu harus seperti dua sayap pesawat terbang. Sayap sebelah kanan adalah iman dan taqwa kepada Tuhan. Sayap sebelah kiri adalah pembangunan teknologi dan ilmu pengetahuan."</p>
          <p>Prof. Dr. Ing. H. Bacharuddin Jusuf Habibie, FREng
          <br/>Presiden Republik Indonesia ke-3 -- Bapak Teknologi Bangsa</p>
        </div>
      </div>
    </div>
    <div class="right-home-panel col-md-6 col-lg-6 col-xs-0 col-sm-0">
      <div class="card-login">

        <form method="POST" action="<?= base_url('/auth-proces') ?>" class="signin">
            <?= csrf_field() ?>
            
            <div class="input-field">
                <i class="fas fa-user"></i>
                <input type="text" name="username" placeholder="Username" value="<?= old('username') ?>" class="<?= (session('errors.username')) ? 'is-invalid' : '' ?>">
                
            </div>
            <?php if (session('errors.username')): ?>
                  <p class="error-message"><?= session('errors.username') ?></p>
                <?php endif; ?>
            

            <div class="input-field">
                <i class="fas fa-lock"></i>
                <input type="password" name="password" placeholder="Password" class="<?= (session('errors.password')) ? 'is-invalid' : '' ?>">
                
            </div>
            <?php if (session('errors.password')): ?>
                  <p class="error-message"><?= session('errors.password') ?></p>
              <?php endif; ?>
            

            <input type="submit" value="Login" class="btn btn-login">
        </form>
      </div>
    </div>
  </div>

</body>
</html>
