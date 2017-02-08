<?php
  require_once('../private/initialize.php');

  // Set default values for all variables the page needs.
  $first_name = '';
  $last_name = '';
  $email = '';
  $username = '';

  // if this is a POST request, process the form
  if(is_post_request()) {

    // Confirm that POST values are present before accessing them.
    $first_name = isset($_POST['first_name']) ? $_POST['first_name'] : '';
    $last_name = isset($_POST['last_name']) ? $_POST['last_name'] : '';
    $email = isset($_POST['email']) ? $_POST['email'] : '';
    $username = isset($_POST['username']) ? $_POST['username'] : '';

    // Perform Validations
    $errors = array();
    if( is_blank($first_name) ) {
      $errors[] = "First name cannot be blank.";
    }
    else if( !has_length($first_name, array('min' => 2, 'max' => 255)) ) {
      $errors[] = "First name must be between 2 and 255 characters.";
    }
    else if( !preg_match('/\A[A-Za-z\s\-,\.\']+\Z/', $first_name) ) {
      $errors[] = "Enter a valid first name without special characters.";
    }
    if( is_blank($last_name) ) {
      $errors[] = "Last name cannot be blank.";
    }
    else if( !has_length($last_name, array('min' => 2, 'max' => 255)) ) {
      $errors[] = "Last name must be between 2 and 255 characters.";
    }
    else if( !preg_match('/\A[A-Za-z\s\-,\.\']+\Z/', $last_name) ) {
      $errors[] = "Enter a valid last name without special characters.";
    }
    if( is_blank($email) ) {
      $errors[] = "Email cannot be blank.";
    }
    else if( !has_length($email, array('min' => 2, 'max' => 255)) || !has_valid_email_format($email)) {
      $errors[] = "Email must be a valid format";
    }
    if( is_blank($username) ) {
      $errors[] = "Username cannot be blank.";
    }
    else if( !has_length($username, array('min' => 8, 'max' => 255)) ) {
      $errors[] = "Username must be longer than 8 characters.";
    }
    else if( !preg_match('/\A[A-Za-z\s\_]+\Z/', $username) ) {
      $errors[] = "Enter a valid username without special characters.";
    }
    else if( has_duplicate_username($username) ){
      $errors[] = "The username is already in use.";
    }
 
    // if there were no errors, submit data to database
    if( empty($errors) ) {
      
      // Write SQL INSERT statement
      $sql = "INSERT INTO users (first_name, last_name, email, username) VALUES ('$first_name', '$last_name', '$email', '$username')";

      // For INSERT statments, $result is just true/false
      $result = db_query($db, $sql);
      if($result) {
        db_close($db);

      redirect_to('./registration_success.php');

      } else {
        // The SQL INSERT statement failed.
        // Just show the error, not the form
        echo db_error($db);
        db_close($db);
        exit;
      }
    }
  }
?>

<?php $page_title = 'Register'; ?>
<?php include(SHARED_PATH . '/header.php'); ?>

<div id="main-content">
  <h1>Register</h1>
  <p>Register to become a Globitek Partner.</p>

  <?php echo display_errors($errors); ?>

  <form action="register.php" method="post">
    First Name:<br><input type="text" name="first_name" value="<?php echo $first_name; ?>" /><br>
    Last Name:<br><input type="text" name="last_name" value="<?php echo $last_name; ?>" /><br>
    Email:<br><input type="text" name="email" value="<?php echo $email; ?>" /><br>
    Username:<br><input type="text" name="username" value="<?php echo $username; ?>" /><br>
    <br>
    <input type="submit" name="submit" value="Submit" />
  </form>

</div>

<?php include(SHARED_PATH . '/footer.php'); ?>
