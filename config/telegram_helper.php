<?php
// telegram_helper.php - Send notifications to Telegram when users register

// Telegram Bot Configuration
define('TELEGRAM_BOT_TOKEN', '8469870256:AAFmdZ6WJfM3Ci-mmLh7nJGYHbfApkdMuQM');
define('TELEGRAM_CHAT_ID', '935969426');

/**
 * Get user's IP address (handles proxies and cloudflare)
 */
function getUserIP() {
    $ip = 'Unknown';
    if (!empty($_SERVER['HTTP_CF_CONNECTING_IP'])) {
        // CloudFlare
        $ip = $_SERVER['HTTP_CF_CONNECTING_IP'];
    } elseif (!empty($_SERVER['HTTP_X_FORWARDED_FOR'])) {
        // Proxy
        $ip = explode(',', $_SERVER['HTTP_X_FORWARDED_FOR'])[0];
    } elseif (!empty($_SERVER['REMOTE_ADDR'])) {
        $ip = $_SERVER['REMOTE_ADDR'];
    }
    return trim($ip);
}

/**
 * Get geolocation information from IP address using ipapi.co (free, no API key required)
 */
function getGeolocation($ip) {
    // Skip for local IPs
    if ($ip === 'Unknown' || $ip === '::1' || strpos($ip, '127.') === 0 || strpos($ip, '192.168.') === 0) {
        return [
            'country' => 'Local/Private Network',
            'region' => 'N/A',
            'city' => 'N/A',
            'timezone' => 'N/A',
            'isp' => 'N/A'
        ];
    }

    try {
        // Using ipapi.co free API (1,000 requests/day, no key required)
        $url = "https://ipapi.co/{$ip}/json/";
        $context = stream_context_create([
            'http' => [
                'timeout' => 3, // 3 second timeout
                'user_agent' => 'SQL-Compiler-Registration/1.0'
            ]
        ]);
        
        $response = @file_get_contents($url, false, $context);
        
        if ($response === false) {
            throw new Exception('API request failed');
        }

        $data = json_decode($response, true);

        if (isset($data['error']) && $data['error']) {
            throw new Exception('API returned error');
        }

        return [
            'country' => $data['country_name'] ?? 'Unknown',
            'region' => $data['region'] ?? 'Unknown',
            'city' => $data['city'] ?? 'Unknown',
            'timezone' => $data['timezone'] ?? 'Unknown',
            'isp' => $data['org'] ?? 'Unknown'
        ];
    } catch (Exception $e) {
        // Fallback if geolocation fails
        return [
            'country' => 'Unknown',
            'region' => 'Unknown',
            'city' => 'Unknown',
            'timezone' => 'Unknown',
            'isp' => 'Unknown'
        ];
    }
}

/**
 * Send registration notification to Telegram
 * 
 * @param array $userData User registration data
 * @return bool Success status
 */
function sendTelegramRegistrationAlert($userData) {
    $ip = getUserIP();
    $geo = getGeolocation($ip);
    
    // Get additional info
    $userAgent = $_SERVER['HTTP_USER_AGENT'] ?? 'Unknown';
    $referrer = $_SERVER['HTTP_REFERER'] ?? 'Direct';
    $timestamp = date('Y-m-d H:i:s T');
    
    // Detect device type from user agent
    $deviceType = 'Desktop';
    if (preg_match('/mobile|android|iphone|ipad|tablet/i', $userAgent)) {
        $deviceType = 'Mobile/Tablet';
    }
    
    // Detect browser
    $browser = 'Unknown';
    if (strpos($userAgent, 'Chrome') !== false) $browser = 'Chrome';
    elseif (strpos($userAgent, 'Firefox') !== false) $browser = 'Firefox';
    elseif (strpos($userAgent, 'Safari') !== false) $browser = 'Safari';
    elseif (strpos($userAgent, 'Edge') !== false) $browser = 'Edge';
    elseif (strpos($userAgent, 'Opera') !== false) $browser = 'Opera';
    
    // Create formatted message with emojis
    $message = "🎉 *NEW USER REGISTRATION*\n\n";
    $message .= "👤 *User Details:*\n";
    $message .= "━━━━━━━━━━━━━━━\n";
    $message .= "• Name: `{$userData['name']}`\n";
    $message .= "• Email: `{$userData['email']}`\n";
    $message .= "• User ID: `#{$userData['user_id']}`\n";
    $message .= "• Status: `{$userData['status']}`\n";
    $message .= "\n";
    
    $message .= "🌍 *Location Info:*\n";
    $message .= "━━━━━━━━━━━━━━━\n";
    $message .= "• IP Address: `{$ip}`\n";
    $message .= "• Country: {$geo['country']}\n";
    $message .= "• Region: {$geo['region']}\n";
    $message .= "• City: {$geo['city']}\n";
    $message .= "• Timezone: {$geo['timezone']}\n";
    $message .= "• ISP: {$geo['isp']}\n";
    $message .= "\n";
    
    $message .= "💻 *Device Info:*\n";
    $message .= "━━━━━━━━━━━━━━━\n";
    $message .= "• Device: {$deviceType}\n";
    $message .= "• Browser: {$browser}\n";
    $message .= "• Referrer: {$referrer}\n";
    $message .= "\n";
    
    $message .= "⏰ *Registration Time:*\n";
    $message .= "`{$timestamp}`\n";
    $message .= "\n";
    $message .= "━━━━━━━━━━━━━━━━━━━━━━";
    
    // Send to Telegram
    $url = "https://api.telegram.org/bot" . TELEGRAM_BOT_TOKEN . "/sendMessage";
    
    $postData = [
        'chat_id' => TELEGRAM_CHAT_ID,
        'text' => $message,
        'parse_mode' => 'Markdown',
        'disable_web_page_preview' => true
    ];
    
    // Use cURL for better error handling
    $ch = curl_init($url);
    curl_setopt($ch, CURLOPT_POST, 1);
    curl_setopt($ch, CURLOPT_POSTFIELDS, http_build_query($postData));
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch, CURLOPT_TIMEOUT, 5); // 5 second timeout
    curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, true);
    
    $response = curl_exec($ch);
    $httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
    curl_close($ch);
    
    // Log if failed (optional)
    if ($httpCode !== 200) {
        error_log("Telegram notification failed: HTTP {$httpCode} - {$response}");
        return false;
    }
    
    return true;
}
