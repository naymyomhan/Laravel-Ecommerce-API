<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Document</title>
    <style>
        .container{
            width:500px,

        }
    </style>
</head>
<body>
    <!DOCTYPE html>
<html>

<head>
  <meta charset="utf-8">
  <title>Email Verification</title>
  <style>
    /* Reset some default styles */
    body,
    h1,
    p {
      margin: 0;
      padding: 0;
    }

    /* Email body styles */
    body {
      font-family: Arial, sans-serif;
      line-height: 1.6;
      background-color: #f4f4f4;
    }

    /* Container styles */
    .container {
      width: 100%;
      max-width: 600px;
      margin: 0 auto;
      padding: 20px;
      background-color: #fff;
      border-radius: 8px;
      box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
    }

    /* Logo styles */
    .logo {
      text-align: center;
      margin-bottom: 20px;
    }

    /* Logo image styles */
    .logo img {
      max-width: 200px;
      height: auto;
    }

    /* Verification code styles */
    .verification-code {
      text-align: center;
      padding: 20px 0;
      background-color: #f9f9f9;
      border-radius: 5px;
    }

    /* Code text styles */
    .verification-code p {
      font-size: 24px;
      font-weight: bold;
      color: #333;
    }

    /* Instructions styles */
    .instructions {
      text-align: center;
      margin-top: 20px;
    }

    /* Footer styles */
    .footer {
      text-align: center;
      margin-top: 20px;
      color: #777;
    }
  </style>
</head>

<body>
  <div class="container">
    <!-- Logo -->
    <div class="logo">
        <img src="https://assets.materialup.com/uploads/378d2c84-810d-477a-802b-d495646b9c4e/preview.jpg" alt="Company Logo">
    </div>

    <!-- Verification code -->
    <div class="verification-code">
      <p>Your verification code:</p>
      <p style="font-size: 36px; color: #007bff;">{{$verification_token}}</p>
    </div>

    <!-- Instructions -->
    <div class="instructions">
      <p>Please use the above verification code to complete your registration.</p>
    </div>

    <!-- Footer -->
    <div class="footer">
      <p>This is an automated email. Please do not reply.</p>
    </div>
  </div>
</body>

</html>

</body>
</html>
