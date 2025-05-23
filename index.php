<?php

require_once "includes/db_connect.php";

//initiate an erroro handler function
function myErrorHandler($errno, $errstr)
{
  echo "<b>Error:</b> [$errno] $errstr";
}
// set error handler function
set_error_handler("myErrorHandler");

if ($_SERVER["REQUEST_METHOD"] == "POST") {

  if (isset($_POST['save'])) {

    $full_name = trim(filter_input(INPUT_POST, 'full_name'));
    $email = trim(filter_input(INPUT_POST, 'email'));
    $phone_number = trim(filter_input(INPUT_POST, 'phone_number'));
    $room_type = trim(filter_input(INPUT_POST, 'room_type'));
    $check_in_date = trim(filter_input(INPUT_POST, 'check_in_date'));
    $check_out_date = trim(filter_input(INPUT_POST, 'check_out_date'));

    if (!empty($full_name) && !empty($email) && !empty($phone_number) && !empty($room_type) && !empty($check_in_date) && !empty($check_out_date)) {

      if ($image_file == '') {
        $image_file = null;
      }

      //connect to the database
      $conn = connectDB();

      // insert the data into the database
      $sql = "INSERT INTO booking_records (full_name, email, phone_number, room_type, check_in_date, check_out_date, image_file)
      VALUES (?, ?, ?, ?, ?, ?, ?)";

      // prepare an SQL statement for execution
      $stmt = mysqli_prepare($conn, $sql);

      if ($stmt === false) {
        echo mysqli_error($conn);
      } else {

        // bind variables for the parameter markers in the SQL statement prepared
        mysqli_stmt_bind_param($stmt, 'sssssss', $full_name, $email, $phone_number, $room_type, $check_in_date, $check_out_date, $image_file);

        //execute the prepared statement
        $results = mysqli_stmt_execute($stmt);

        if ($results === false) {
          echo mysqli_stmt_error($stmt);
        } else {
          header('Location: http://localhost:200/index.php?success=1');
          exit;
        }
      }
    } else {
          header('Location: http://localhost:200/index.php?failure=1');
          exit;
    }
  }
}



?>


<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <title class="btn btn-primary btn-lg">Hotel Booking Form</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.6/dist/css/bootstrap.min.css" rel="stylesheet">
</head>

<body class="bg-light">

  <div class="container py-5">
    <div class="row justify-content-center">
      <div class="col-lg-8">
        <div class="card shadow-lg rounded-4">
          <div class="card-body p-5">
            <h2 class="mb-0 text-center">Hotel Booking Form</h2>

            <!--show success message -->
            <?php if (isset($_GET['success']) && $_GET['success'] == 1): ?>
              <div class="alert alert-success alert-dismissible fade show" role="alert">
                ✅ Booking submitted successfully!
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
              </div>
            <?php endif; ?>

            <!--show failure message -->
            <?php if (isset($_GET['failure']) && $_GET['failure'] == 1): ?>
              <div class="alert alert-danger alert-dismissible fade show" role="alert">
                Booking submission failed!
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
              </div>
            <?php endif; ?>


            <form method="POST" enctype="multipart/form-data">
              <div class="mb-3 form-floating">
                <input type="text" class="form-control" id="fullName" name="full_name" placeholder="Full Name" required>
                <label for="fullName">Full Name</label>
              </div>

              <div class="mb-3 form-floating">
                <input type="email" class="form-control" id="email" name="email" placeholder="Email address" required>
                <label for="email">Email Address</label>
              </div>

              <div class="mb-3">
                <label for="phone" class="form-label">Phone Number</label>
                <input type="tel" class="form-control" id="phone" name="phone_number" placeholder="e.g. 123-456-7890" required>
              </div>

              <div class="mb-3">
                <label for="roomType" class="form-label">Room Type</label>
                <input class="form-control" list="roomOptions" id="roomType" name="room_type" placeholder="Type to search..." required>
                <datalist id="roomOptions">
                  <option value="Single Room">
                  <option value="Double Room">
                  <option value="Suite">
                  <option value="Family Room">
                  <option value="Deluxe">
                  <option value="Executive Room">
                  <option value="Presidential Room">
                </datalist>
              </div>

              <div class="mb-3 form-floating">
                <input type="date" class="form-control" id="checkin" name="check_in_date" placeholder="Check-in Date" required>
                <label for="checkin">Check-in Date</label>
              </div>

              <div class="mb-4 form-floating">
                <input type="date" class="form-control" id="checkout" name="check_out_date" placeholder="Check-out Date" required>
                <label for="checkout">Check-out Date</label>
              </div>

              <div class="mb-4">
                <label for="file">Upload Image:</label>
                <input type="file" id="file" name="image_file">
              </div>

              <div class="text-center">
                <button type="submit" class="btn btn-primary px-5" name="save">Submit Booking</button>
                <a href="/index_records.php" class="btn btn-dark px-5">View Booking Records</a>
              </div>
            </form>

          </div>
        </div>
      </div>
    </div>
  </div>

  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.6/dist/js/bootstrap.bundle.min.js"></script>
</body>

</html>