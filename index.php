<?php
// Handle form submission
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    // Get POST data
    $ps4_ip = $_POST['ps4_ip']; // PS4 IP Address
    $pkg_url = $_POST['pkg_url']; // URL to the .pkg file

    // Check if both fields are filled
    if (!empty($ps4_ip) && !empty($pkg_url)) {
        // Prepare the cURL request to the PS4
        $url = "http://{$ps4_ip}:12800/api/install";
        $data = json_encode([
            "type" => "direct",
            "packages" => [$pkg_url]
        ]);

        // Initialize cURL session
        $ch = curl_init($url);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_POST, true);
        curl_setopt($ch, CURLOPT_POSTFIELDS, $data);
        curl_setopt($ch, CURLOPT_HTTPHEADER, ['Content-Type: application/json']);

        // Execute the cURL request and capture the response
        $response = curl_exec($ch);

        // Check if the request was successful
        if ($response === false) {
            $error = curl_error($ch);
            echo "<div style='color: red;'>Error: {$error}</div>";
        } else {
            echo "<div style='color: green;'>Package sent successfully to PS4 at {$ps4_ip}!</div>";
        }

        // Close the cURL session
        curl_close($ch);
    } else {
        echo "<div style='color: red;'>Please provide both PS4 IP Address and .pkg URL.</div>";
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>PS4 Package Installer</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #f4f4f4;
            margin: 0;
            padding: 20px;
        }
        .container {
            max-width: 600px;
            margin: 0 auto;
            background: white;
            padding: 20px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
        }
        input[type="text"], input[type="url"] {
            width: 100%;
            padding: 10px;
            margin: 10px 0;
            border: 1px solid #ccc;
            border-radius: 5px;
        }
        button {
            padding: 10px 20px;
            background-color: #007BFF;
            color: white;
            border: none;
            border-radius: 5px;
            cursor: pointer;
        }
        button:hover {
            background-color: #0056b3;
        }
    </style>
</head>
<body>
    <div class="container">
        <h1>PS4 Package Installer</h1>
        <form action="index.php" method="POST">
            <label for="ps4_ip">PS4 IP Address:</label>
            <input type="text" name="ps4_ip" id="ps4_ip" placeholder="Enter PS4 IP Address" required>

            <label for="pkg_url">Package URL (Direct Link to .pkg):</label>
            <input type="url" name="pkg_url" id="pkg_url" placeholder="Enter direct .pkg file URL" required>

            <button type="submit">Install Package</button>
        </form>
    </div>
</body>
</html>
