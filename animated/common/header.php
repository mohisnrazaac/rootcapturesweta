<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-T3c6CoIi6uLrA9TneNEoa7RxnatzjcDSCmG1MXxSR1GAsXEV/Dwwykc2MPK8M2HN" crossorigin="anonymous">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css" integrity="sha512-DTOQO9RWCH3ppGqcWaEA1BIZOC6xxalwEsw9c2QQeAIftl+Vegovlnee1c9QX4TctnWMn13TZye+giMm8e2LwA==" crossorigin="anonymous" referrerpolicy="no-referrer" />
    <link rel="stylesheet" href="../animated/css/style.css">
    <title>Login</title>
</head>
<body>
<div class="popup">
    <div class="d-flex flex-row align-items-center">
        <h5 class="me-3">Animation</h5>
        <div class="toggle-switch" onclick="toggleAnimation()">
        <span class="toggle-text" id="toggleText1">ON</span>
        <div class="slider slider1"></div>
        </div>
    </div> 
    <div class="d-flex flex-row align-items-center">
        <h5 class="me-3">Sound</h5>
        <div class="toggle-switch" onclick="toggleSound()">
        <span class="toggle-text" id="toggleText2">OFF</span>
        <div class="slider slider2"></div>
        </div>
    </div>
</div>