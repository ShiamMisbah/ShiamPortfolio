<?php
/**
 * Requires the "PHP Email Form" library (BootstrapMade PRO)
 * Upload to: assets/vendor/php-email-form/php-email-form.php (commonly)
 */

// 1) Put your real receiving email here (FIXED)
$receiving_email_address = 'shiam.misbah42@gmail.com';

// 2) Resolve the library path correctly
$php_email_form = __DIR__ . '/../assets/vendor/php-email-form/php-email-form.php';

if (file_exists($php_email_form)) {
  require_once $php_email_form;
} else {
  http_response_code(500);
  exit('Unable to load the "PHP Email Form" Library!');
}

// 3) Only allow POST
if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
  http_response_code(405);
  exit('Method Not Allowed');
}

// 4) Safely read inputs
$name    = isset($_POST['name']) ? trim($_POST['name']) : '';
$email   = isset($_POST['email']) ? trim($_POST['email']) : '';
$subject = isset($_POST['subject']) ? trim($_POST['subject']) : 'Contact Form Submission';
$message = isset($_POST['message']) ? trim($_POST['message']) : '';

// 5) Basic validation
if ($name === '' || $email === '' || $message === '') {
  http_response_code(400);
  exit('Please fill in name, email, and message.');
}

if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
  http_response_code(400);
  exit('Please enter a valid email address.');
}

$contact = new PHP_Email_Form();
$contact->ajax = true;

$contact->to = $receiving_email_address;
$contact->from_name = htmlspecialchars($name, ENT_QUOTES, 'UTF-8');
$contact->from_email = $email;
$contact->subject = htmlspecialchars($subject, ENT_QUOTES, 'UTF-8');

/**
 * OPTIONAL SMTP (recommended on many hosts)
 * If your hosting blocks PHP mail(), SMTP will be required.
 */
/*
$contact->smtp = array(
  'host' => 'smtp.gmail.com',
  'username' => 'your_email@gmail.com',
  'password' => 'your_app_password',
  'port' => '587'
);
*/

$contact->add_message($name, 'From');
$contact->add_message($email, 'Email');
$contact->add_message($message, 'Message', 10);

echo $contact->send();
?>