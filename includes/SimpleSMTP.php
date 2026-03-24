<?php
/**
 * SimpleSMTP - A lightweight SMTP email sender
 * Supports SSL/TLS authentication
 */
class SimpleSMTP {
    private $host;
    private $port;
    private $username;
    private $password;
    private $from;
    private $fromName;
    private $socket;
    private $lastError = '';

    public function __construct($host, $port, $username, $password, $from, $fromName = '') {
        $this->host = $host;
        $this->port = $port;
        $this->username = $username;
        $this->password = $password;
        $this->from = $from;
        $this->fromName = $fromName ?: $from;
    }

    /**
     * Send an email
     * @param string $to Recipient email address
     * @param string $subject Email subject
     * @param string $body Email body (HTML supported)
     * @param bool $isHtml Whether the body is HTML
     * @return bool Success status
     */
    public function send($to, $subject, $body, $isHtml = true) {
        try {
            // Connect to SMTP server
            if (!$this->connect()) {
                return false;
            }

            // Perform SMTP handshake and authentication
            if (!$this->authenticate()) {
                $this->disconnect();
                return false;
            }

            // Send email
            $this->sendCommand("MAIL FROM: <{$this->from}>", 250);
            $this->sendCommand("RCPT TO: <{$to}>", 250);
            $this->sendCommand("DATA", 354);

            // Build email headers
            $headers = $this->buildHeaders($to, $subject, $isHtml);
            
            // Send headers and body
            $message = $headers . "\r\n" . $body . "\r\n.";
            $this->sendCommand($message, 250);

            // Quit
            $this->sendCommand("QUIT", 221);
            $this->disconnect();

            return true;

        } catch (Exception $e) {
            $this->lastError = $e->getMessage();
            $this->disconnect();
            return false;
        }
    }

    /**
     * Connect to SMTP server
     */
    private function connect() {
        $context = stream_context_create([
            'ssl' => [
                'verify_peer' => false,
                'verify_peer_name' => false,
                'allow_self_signed' => true
            ]
        ]);

        // Use SSL for port 465
        $protocol = ($this->port == 465) ? 'ssl://' : '';
        $this->socket = @stream_socket_client(
            "{$protocol}{$this->host}:{$this->port}",
            $errno,
            $errstr,
            30,
            STREAM_CLIENT_CONNECT,
            $context
        );

        if (!$this->socket) {
            $this->lastError = "Failed to connect: $errstr ($errno)";
            return false;
        }

        // Read server greeting
        $response = $this->getResponse();
        if (substr($response, 0, 3) !== '220') {
            $this->lastError = "Invalid server greeting: $response";
            return false;
        }

        return true;
    }

    /**
     * Authenticate with SMTP server
     */
    private function authenticate() {
        // Send EHLO
        $this->sendCommand("EHLO {$this->host}", 250);

        // Authenticate
        $this->sendCommand("AUTH LOGIN", 334);
        $this->sendCommand(base64_encode($this->username), 334);
        $this->sendCommand(base64_encode($this->password), 235);

        return true;
    }

    /**
     * Send a command to the SMTP server
     */
    private function sendCommand($command, $expectedCode) {
        fwrite($this->socket, $command . "\r\n");
        $response = $this->getResponse();
        
        $code = substr($response, 0, 3);
        if ($code != $expectedCode) {
            throw new Exception("SMTP Error: Expected $expectedCode, got $response");
        }

        return $response;
    }

    /**
     * Get response from SMTP server
     */
    private function getResponse() {
        $response = '';
        while ($line = fgets($this->socket, 515)) {
            $response .= $line;
            if (substr($line, 3, 1) === ' ') {
                break;
            }
        }
        return $response;
    }

    /**
     * Build email headers
     */
    private function buildHeaders($to, $subject, $isHtml) {
        $boundary = md5(uniqid(time()));
        
        $headers = "From: {$this->fromName} <{$this->from}>\r\n";
        $headers .= "To: <{$to}>\r\n";
        $headers .= "Subject: {$subject}\r\n";
        $headers .= "MIME-Version: 1.0\r\n";
        
        if ($isHtml) {
            $headers .= "Content-Type: multipart/alternative; boundary=\"{$boundary}\"\r\n";
            $headers .= "\r\n";
            $headers .= "--{$boundary}\r\n";
            $headers .= "Content-Type: text/plain; charset=UTF-8\r\n";
            $headers .= "Content-Transfer-Encoding: 7bit\r\n";
            $headers .= "\r\n";
            $headers .= strip_tags($subject) . "\r\n";
            $headers .= "\r\n";
            $headers .= "--{$boundary}\r\n";
            $headers .= "Content-Type: text/html; charset=UTF-8\r\n";
            $headers .= "Content-Transfer-Encoding: 7bit\r\n";
        } else {
            $headers .= "Content-Type: text/plain; charset=UTF-8\r\n";
        }

        return $headers;
    }

    /**
     * Disconnect from SMTP server
     */
    private function disconnect() {
        if ($this->socket) {
            fclose($this->socket);
            $this->socket = null;
        }
    }

    /**
     * Get the last error message
     */
    public function getLastError() {
        return $this->lastError;
    }
}
