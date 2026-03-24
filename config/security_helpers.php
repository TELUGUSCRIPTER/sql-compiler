<?php
// config/security_helpers.php

/**
 * Check IP-based registration limit
 * Prevents multiple account registrations from same IP
 */
function checkIPRegistrationLimit($pdo, $ip, $maxRegistrations = 3, $timeWindowHours = 24) {
    $stmt = $pdo->prepare("
        SELECT COUNT(*) FROM users 
        WHERE registration_ip = ? 
        AND created_at > DATE_SUB(NOW(), INTERVAL ? HOUR)
    ");
    $stmt->execute([$ip, $timeWindowHours]);
    $count = $stmt->fetchColumn();
    
    return [
        'allowed' => $count < $maxRegistrations,
        'current_count' => $count,
        'max_allowed' => $maxRegistrations
    ];
}

/**
 * Check registration cooldown period
 * Enforces waiting time between registrations from same IP
 */
function checkRegistrationCooldown($pdo, $ip, $cooldownHours = 24) {
    $stmt = $pdo->prepare("
        SELECT MAX(created_at) as last_reg 
        FROM users 
        WHERE registration_ip = ?
    ");
    $stmt->execute([$ip]);
    $lastReg = $stmt->fetchColumn();
    
    if ($lastReg) {
        $lastRegTime = strtotime($lastReg);
        $currentTime = time();
        $hoursSince = ($currentTime - $lastRegTime) / 3600;
        
        if ($hoursSince < $cooldownHours) {
            $waitHours = ceil($cooldownHours - $hoursSince);
            $waitMinutes = ceil(($cooldownHours - $hoursSince) * 60);
            return [
                'allowed' => false,
                'wait_hours' => $waitHours,
                'wait_minutes' => $waitMinutes % 60,
                'message' => "Please wait {$waitHours} hour(s) before registering again from this network."
            ];
        }
    }
    
    return ['allowed' => true];
}

/**
 * Comprehensive disposable email detection
 * Blocks temporary email services
 */
function isDisposableEmail($email) {
    $domain = strtolower(substr(strrchr($email, "@"), 1));
    
    // Comprehensive list of disposable email domains
    $disposableDomains = [
        // Popular temporary email services
        'tempmail.com', 'temp-mail.org', 'guerrillamail.com', '10minutemail.com',
        'throwaway.email', 'mailinator.com', 'trashmail.com', 'yopmail.com',
        'fakeinbox.com', 'getnada.com', 'maildrop.cc', 'mintemail.com',
        
        // Additional disposable services
        'sharklasers.com', 'guerrillamail.info', 'guerrillamail.biz', 'guerrillamail.de',
        'spam4.me', 'grr.la', 'guerrillamail.org', 'guerrillamail.net',
        'mailcatch.com', 'mohmal.com', 'emailondeck.com', 'tempr.email',
        'jetable.org', 'mytrashmail.com', 'tmpmail.org', 'discard.email',
        
        // 10minutemail variants
        '10minutemail.net', '10minutemail.co.uk', '10minemail.com',
        
        // Mailinator variants
        'mailinator2.com', 'mailinator.net', 'sofimail.com', 'mailinater.com',
        
        // Other common temporary services
        'temp-mail.io', 'tempmail.net', 'tempinbox.com', 'throwawaymail.com',
        'anonbox.net', 'burnermail.io', 'duck.com', 'emailfake.com',
        'mail-temporaire.fr', 'meltmail.com', 'spamgourmet.com', 'mailnesia.com',
        
        // One-time use services
        '33mail.com', 'bugmenot.com', 'deadaddress.com', 'emailsensei.com',
        'getairmail.com', 'hidemail.de', 'incognitomail.com', 'mailexpire.com',
        'mailforspam.com', 'mailmoat.com', 'mailnull.com', 'mailsac.com',
        'mailtemp.net', 'mytempemail.com', 'nospam.ze.tc', 'recipeforfailure.com',
        
        // Recently popular services
        'inboxkitten.com', 'spam.la', 'tmailor.com', 'moakt.com',
        'emailondeck.com', 'filzmail.com', 'anonaddy.me', 'simplelogin.com',
        'disposable.com', 'guerrillamailblock.com', 'spambox.us'
    ];
    
    // Check against disposable list
    if (in_array($domain, $disposableDomains)) {
        return true;
    }
    
    // Additional pattern-based detection
    // Many disposable services use specific patterns
    $suspiciousPatterns = [
        '/^temp.*mail/',
        '/mail.*temp$/',
        '/^throw.*away/',
        '/^fake.*mail/',
        '/^spam.*/',
        '/trash.*mail/',
        '/^guerrilla/',
        '/minute.*mail/',
        '/^disposable/',
        '/^burner/'
    ];
    
    foreach ($suspiciousPatterns as $pattern) {
        if (preg_match($pattern, $domain)) {
            return true;
        }
    }
    
    return false;
}

/**
 * Verify Google reCAPTCHA v3 token
 */
function verifyCaptcha($token, $secretKey) {
    $url = 'https://www.google.com/recaptcha/api/siteverify';
    
    $data = [
        'secret' => $secretKey,
        'response' => $token,
        'remoteip' => $_SERVER['REMOTE_ADDR']
    ];
    
    $options = [
        'http' => [
            'header' => "Content-type: application/x-www-form-urlencoded\r\n",
            'method' => 'POST',
            'content' => http_build_query($data)
        ]
    ];
    
    $context = stream_context_create($options);
    $result = file_get_contents($url, false, $context);
    
    if ($result === FALSE) {
        return ['success' => false, 'error' => 'Failed to verify captcha'];
    }
    
    $responseData = json_decode($result);
    
    // reCAPTCHA v3 uses score (0.0 - 1.0)
    // 0.0 is likely a bot, 1.0 is likely human
    // We'll accept scores >= 0.5
    if ($responseData->success && isset($responseData->score)) {
        return [
            'success' => $responseData->score >= 0.5,
            'score' => $responseData->score,
            'action' => $responseData->action ?? null
        ];
    }
    
    return [
        'success' => false,
        'error_codes' => $responseData->{'error-codes'} ?? []
    ];
}

/**
 * Log registration attempt (for analytics and security)
 */
function logRegistrationAttempt($pdo, $ip, $email, $deviceFp, $success, $reason = null) {
    try {
        $stmt = $pdo->prepare("
            INSERT INTO registration_attempts 
            (ip_address, email, device_fingerprint, success, failure_reason) 
            VALUES (?, ?, ?, ?, ?)
        ");
        $stmt->execute([$ip, $email, $deviceFp, $success ? 1 : 0, $reason]);
    } catch (Exception $e) {
        // Silent fail - don't block registration if logging fails
        error_log("Failed to log registration attempt: " . $e->getMessage());
    }
}

/**
 * Check device fingerprint for suspicious activity
 */
function checkDeviceFingerprint($pdo, $fingerprint, $maxAccountsPerDevice = 5) {
    if (empty($fingerprint)) {
        return ['allowed' => true, 'warning' => 'No fingerprint provided'];
    }
    
    $stmt = $pdo->prepare("
        SELECT COUNT(*) FROM users 
        WHERE device_fingerprint = ? 
        AND created_at > DATE_SUB(NOW(), INTERVAL 30 DAY)
    ");
    $stmt->execute([$fingerprint]);
    $count = $stmt->fetchColumn();
    
    return [
        'allowed' => $count < $maxAccountsPerDevice,
        'current_count' => $count,
        'max_allowed' => $maxAccountsPerDevice
    ];
}
