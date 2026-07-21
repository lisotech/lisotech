<?php
// Set headers for JSON response
header('Content-Type: application/json');

// Only allow POST requests
if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    echo json_encode(['status' => 'error', 'message' => 'Invalid request method.']);
    exit;
}

// Get the JSON input from the frontend fetch request
$inputJSON = file_get_contents('php://input');
$input = json_decode($inputJSON, TRUE);

if (!$input) {
    echo json_encode(['status' => 'error', 'message' => 'Invalid data received.']);
    exit;
}

// Sanitize and assign input data
$name = htmlspecialchars(trim($input['name']));
$email = filter_var(trim($input['email']), FILTER_SANITIZE_EMAIL);
$phone = htmlspecialchars(trim($input['phone']));
$service = htmlspecialchars(trim($input['service']));
$date = htmlspecialchars(trim($input['date']));

// Validate required fields
if (empty($name) || empty($email) || empty($phone) || empty($service) || empty($date)) {
    echo json_encode(['status' => 'error', 'message' => 'Please fill in all required fields.']);
    exit;
}

// Configure your business email settings here
$to = "info@lisotechinnovations.com"; // Replace with your active business email
$subject = "New Service Booking: " . $service;

$emailBody = "You have received a new service booking request from your website:\n\n";
$emailBody .= "Name: " . $name . "\n";
$emailBody .= "Email: " . $email . "\n";
$emailBody .= "Phone: " . $phone . "\n";
$emailBody .= "Service: " . $service . "\n";
$emailBody .= "Preferred Date: " . $date . "\n\n";
$emailBody .= "Please follow up with the client promptly via WhatsApp or phone call.";

$headers = "From: noreply@lisotechinnovations.com\r\n";
$headers .= "Reply-To: " . $email . "\r\n";

// Attempt to send the email notification
$mailSent = @mail($to, $subject, $emailBody, $headers);

// Return success response to the frontend (WhatsApp redirect will trigger regardless)
echo json_encode([
    'status' => 'success',
    'message' => 'Booking processed successfully.'
]);
exit;
