<?php
// ── Configuration ──────────────────────────────────────────────
$to      = "theofficialtonytran@gmail.com";   // your email
$subject_prefix = "[Portfolio Contact]";       // prepended to every subject line
// ──────────────────────────────────────────────────────────────

// Only handle POST requests
if ($_SERVER["REQUEST_METHOD"] !== "POST") {
    header("Location: contactme.html");
    exit;
}

// Sanitize inputs
function clean($value) {
    return htmlspecialchars(strip_tags(trim($value)));
}

$name    = clean($_POST["name"]    ?? "");
$email   = clean($_POST["email"]   ?? "");
$subject = clean($_POST["subject"] ?? "No subject");
$message = clean($_POST["message"] ?? "");

// Basic validation
if (empty($name) || empty($email) || empty($message)) {
    header("Location: contactme.html?status=error");
    exit;
}

if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
    header("Location: contactme.html?status=error");
    exit;
}

// Build the email
$email_subject = "$subject_prefix $subject";

$email_body  = "You received a new message from your portfolio contact form.\n\n";
$email_body .= "Name:    $name\n";
$email_body .= "Email:   $email\n";
$email_body .= "Subject: $subject\n\n";
$email_body .= "Message:\n$message\n";

$headers  = "From: Portfolio Contact Form <no-reply@yourdomain.com>\r\n";
$headers .= "Reply-To: $email\r\n";
$headers .= "X-Mailer: PHP/" . phpversion();

// Send and redirect
if (mail($to, $email_subject, $email_body, $headers)) {
    header("Location: contactme.html?status=success");
} else {
    header("Location: contactme.html?status=error");
}
exit;
?>