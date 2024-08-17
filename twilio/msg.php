<!DOCTYPE html>
<html lang="en">
<head>
  <title>Bootstrap Example</title>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.4.1/css/bootstrap.min.css">
  <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.6.3/jquery.min.js"></script>
  <script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.4.1/js/bootstrap.min.js"></script>
</head>
<body>

<div class="container">
  <h2>SMS Form</h2>
  <form action="./tw.php" method="POST" id="smsform">
    <div class="form-group">
      <label for="number">Number:</label>
      <input type="text" class="form-control" id="number" placeholder="Enter number" name="number">
    </div>
    <div class="form-group">
      <label for="msg">Message:</label>
      <input type="textarea" class="form-control" id="msg" placeholder="Enter Message" name="msg">
    </div>
    <button type="submit" class="btn btn-default">Submit</button>
  </form>
</div>

</body>
</html>