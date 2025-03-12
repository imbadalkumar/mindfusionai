<?php
header("Content-Type: application/json");

// Set API Key
$api_key = "AIzaSyBiKNt0RPftRdmYnxdvNsBpK9zfd7CvT98";  // Replace with your actual API key
$api_url = "https://generativelanguage.googleapis.com/v1beta/models/gemini-2.0-flash:generateContent?key=$api_key";

// Get user input
$data = json_decode(file_get_contents("php://input"), true);
$userMessage = trim($data["userMessage"] ?? "");

// Validate input
if (empty($userMessage)) {
    echo json_encode(["reply" => "Please ask a fitness or diet-related question."]);
    exit;
}

// Prepare API request
$request_body = json_encode([
    "contents" => [
        [
            "parts" => [
                ["text" => "You are an AI assistant specializing in fitness and diet guidance. Provide accurate and helpful advice.\n\nUser: " . $userMessage]
            ]
        ]
    ]
]);

// Initialize cURL session
$ch = curl_init();
curl_setopt($ch, CURLOPT_URL, $api_url);
curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
curl_setopt($ch, CURLOPT_POST, true);
curl_setopt($ch, CURLOPT_HTTPHEADER, ["Content-Type: application/json"]);
curl_setopt($ch, CURLOPT_POSTFIELDS, $request_body);

// Execute API request
$response = curl_exec($ch);

// Handle errors
if (curl_errno($ch)) {
    echo json_encode(["reply" => "Error communicating with AI: " . curl_error($ch)]);
    curl_close($ch);
    exit;
}

curl_close($ch);

// Decode AI response
$response_data = json_decode($response, true);
$aiReply = $response_data["candidates"][0]["content"]["parts"][0]["text"] ?? "I'm here to help. What do you need advice on?";

// Return AI response
echo json_encode(["reply" => nl2br(htmlspecialchars($aiReply))]);
?>
