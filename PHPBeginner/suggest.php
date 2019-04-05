<?php

//Import the PHPMailer class into the global spacename
use PHPMailer\PHPMailer\PHPMailer;
require 'vendor/phpmailer/src/PHPMailer.php';
require 'vendor/phpmailer/src/Exception.php';
require 'vendor/phpmailer/src/SMTP.php';

if ($_SERVER["REQUEST_METHOD"] == "POST"){
  $name = trim(filter_input(INPUT_POST,"name",FILTER_SANITIZE_STRING));
  $email = trim(filter_input(INPUT_POST,"email",FILTER_SANITIZE_EMAIL));
  $category = trim(filter_input(INPUT_POST,"category",FILTER_SANITIZE_STRING));
  $title = trim(filter_input(INPUT_POST,"title",FILTER_SANITIZE_STRING));
  $format = trim(filter_input(INPUT_POST,"format",FILTER_SANITIZE_STRING));
  $genre = trim(filter_input(INPUT_POST,"genre",FILTER_SANITIZE_STRING));
  $year = trim(filter_input(INPUT_POST,"year",FILTER_SANITIZE_NUMBER_INT));
  $details = trim(filter_input(INPUT_POST,"details",FILTER_SANITIZE_SPECIAL_CHARS));
  
  if ($name == "" || $email == "" || $category == "" || $title == ""){
    $error_message = "Please fill in the required fields: Name, Email, Category and Title";
  }
  elseif ($_POST["hack"] != ""){
    $error_message = "Bad form input";
  }
  elseif (!PHPMailer::validateAddress($email)){
    $error_message = "Invalid Email Address";
  }  
  
  if(!isset($error_message)){
    $email_body = "";
    $email_body .= "Name ".$name."\n";
    $email_body .= "Email ".$email."\n";
    $email_body .= "\n\nSuggested Item\n\n";
    $email_body .= "Category ".$details."\n";
    $email_body .= "Title ".$title."\n";
    $email_body .= "Format ".$format."\n";
    $email_body .= "Genre ".$genre."\n";
    $email_body .= "yeay ".$year."\n";
    $email_body .= "Details ".$details."\n";
    
    $mail = new PHPMailer;
    //Tell PHPMailer to use SMTP
      $mail->isSMTP();
      //Enable SMTP debugging
      // 0 = off (for production use)
      // 1 = client messages
      // 2 = client and server messages
      $mail->SMTPDebug = 2;
      //Set the hostname of the mail server
      $mail->Host = 'smtp.gmail.com';
      // use
      // $mail->Host = gethostbyname('smtp.gmail.com');
      // if your network does not support SMTP over IPv6
      //Set the SMTP port number - 587 for authenticated TLS, a.k.a. RFC4409 SMTP submission
      $mail->Port = 587;
      //Set the encryption system to use - ssl (deprecated) or tls
      $mail->SMTPSecure = 'tls';
      //Whether to use SMTP authentication
      $mail->SMTPAuth = true;
      //Username to use for SMTP authentication - use full email address for gmail
      $mail->Username = "galang.pil@gmail.com";
      //Password to use for SMTP authentication
      $mail->Password = "caskdxfiwzeobvxs";
    // end smtp
    //It's important not to use the submitter's address as the from address as it's forgery,
    //which will cause your messages to fail SPF checks.
    //Use an address in your own domain as the from address, put the submitter's address in a reply-to
    $mail->setFrom('galang.pil@gmail.com', (empty($name) ? 'Contact form' : $name));
    $mail->addReplyTo($email, $name);
    $mail->addAddress('galang.pil@gmail.com', 'Galang Piliang');  
    $mail->Subject = 'Library Suggestion from ' . $name;
    $mail->Body = "Contact form submission\n\n" . $email_body;
    if ($mail->send()) {
        header("location:suggest.php?status=thanks");
        exit;
    }
    $error_message = "Mailer Error: " . $mail->ErrorInfo;
  }
  
}

/*// Dropdown Genre //*/
// include file function
include('inc/functions.php');
$genres = get_genre_html();

$pageTitle = "Suggest a Media Item";
$section = "suggest";
include('inc/header.php');

?>

<div class="section page">
  <div class="wrapper">
    <h1>Suggest a Media Item</h1>
    <?php if(isset($_GET["status"]) && $_GET["status"] == "thanks"){
      echo "<p>Thanks for the email I&rsquo;ll check out your suggestion shortly!</p>";
    } else { 
      if (isset($error_message)){
        echo "<p class='message'>$error_message</p>";
      }else{
        echo "<p>If you think there is something I&rsquo;m missing, let me know! Complete the form to send me an email.</p>";
      }
    ?>
    <form method="post" action="suggest.php">
      <table>
        <tr>
          <th><label for="name">Name (required)</label></th>
          <td><input type="text" id="name" name="name" value="<?= isset($name) ? $name : ''; ?>"></td>
        </tr>
        <tr>
          <th><label for="email">Email (required)</label></th>
          <td><input type="email" id="email" name="email" value="<?= isset($email) ? $email : ''; ?>"></td>
        </tr>
        <tr>
          <th><label for="category">Category (required)</label></th>
          <td>
            <select id="category" name="category">
              <option value="">Select One</option>
              <option value="Books">Book</option>
              <option value="Movies">Movie</option>
              <option value="Music">Music</option>
            </select>
          </td>
        </tr>
        <tr>
          <th><label for="title">Title (required)</label></th>
          <td><input type="text" id="title" name="title" value="<?= isset($title) ? $title : ''; ?>"></td>
        </tr>
        <tr>
          <th><label for="format">Format</label></th>
          <td>
            <select id="format" name="format">
              <option value="">Select One</option>
              <optgroup label="Books">
                  <option value="Audio">Audio</option>
                  <option value="Ebook">Ebook</option>
                  <option value="Hardback">Hardback</option>
                  <option value="Paperback">Paperback</option>
              </optgroup>
              <optgroup label="Movies">
                  <option value="Blu-ray">Blu-ray</option>
                  <option value="DVD">DVD</option>
                  <option value="Streaming">Streaming</option>
                  <option value="VHS">VHS</option>
              </optgroup>
              <optgroup label="Music">
                  <option value="Cassette">Cassette</option>
                  <option value="CD">CD</option>
                  <option value="MP3">MP3</option>
                  <option value="Vinyl">Vinyl</option>
              </optgroup>
            </select>
          </td>
        </tr>
        <tr>
          <th>
              <label for="genre">Genre</label>
          </th>
          <td>
              <select name="genre" id="genre">
                  <option value="">Select One</option>
                  <?= $genres ?>
              </select>
          </td>
        </tr>
        <tr>
          <th><label for="year">Year</label></th>
          <td><input type="text" id="year" name="year" value="<?= isset($year) ? $year : ''; ?>"></td>
        </tr>
        <tr>
          <th><label for="details">Suggest Item Details</label></th>
          <td><textarea id="details" name="details"><?= isset($details) ? $details : ''; ?></textarea></td>
        </tr>
        <tr style="display: none;">
          <th><label for="hack">Robot! Leave this field blank</label></th>
          <td><input type="text" id="hack" name="hack"></td>
        </tr>
      </table>
      <input type="submit" value="send">
    </form>
    <?php } ?>
  </div>  
</div>

<?php include('inc/footer.php'); ?>