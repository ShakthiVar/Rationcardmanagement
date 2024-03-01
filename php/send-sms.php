<?php
require __DIR__ . '/../vendor/autoload.php';
use Twilio\Rest\Client;

function sendSMS($phone_numbers, $message) {
    // Set your Twilio credentials
    $sid = 'AC3bb7ed80ecee408034aa63769b599a36';
    $token = '9a5a4e023a08873fa130e21af1263a5f';
    $twilio_number = '+15738893540';

    // Initialize the Twilio client
    $client = new Client($sid, $token);

    // Format phone numbers to E.164 format
    $formatted_phone_numbers = [];
    foreach ($phone_numbers as $phone_number) {
        // Assume country code is +91 for India
        $formatted_phone_numbers[] = '+91' . $phone_number; // Update country code if needed
    }

    // Send SMS message to each phone number
    foreach ($formatted_phone_numbers as $formatted_phone_number) {
        try {
            // Send SMS message
            $client->messages->create(
                $formatted_phone_number,
                array(
                    'from' => $twilio_number,
                    'body' => $message
                )
            );
        } catch (Exception $e) {
            echo 'Error sending SMS: ' . $e->getMessage();
            exit;
        }
    }

    echo 'Messages sent successfully!';
}

// Logic to send SMS messages
if (isset($_POST['selectedCardholders']) && !empty($_POST['selectedCardholders']) && isset($_POST['message']) && !empty($_POST['message'])) {
    $phone_numbers = $_POST['selectedCardholders'];
    $message = $_POST['message'];

    // Send SMS message
    sendSMS($phone_numbers, $message);
} else {
    echo 'Please select recipients and provide a message.';
}
?>
