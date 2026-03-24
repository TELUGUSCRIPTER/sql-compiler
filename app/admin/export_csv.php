<?php
// app/admin/export_csv.php
// CSV Export utility for admin data
require_once __DIR__ . '/../../config/auth_middleware.php';
require_once __DIR__ . '/../../config/db_control.php';
requireAdmin();

$type = $_GET['type'] ?? '';
$pdo = getControlDB();

// Set CSV headers
header('Content-Type: text/csv; charset=utf-8');
header('Content-Disposition: attachment; filename="' . $type . '_export_' . date('Y-m-d_His') . '.csv"');

// Create output stream
$output = fopen('php://output', 'w');

switch ($type) {
    case 'users':
        // CSV Headers
        fputcsv($output, ['ID', 'Name', 'Email', 'Role', 'Status', 'Registration IP', 'Device Fingerprint', 'DB Name', 'Created At', 'Last Login']);
        
        // Get users
        $users = $pdo->query("
            SELECT u.id, u.name, u.email, u.role, u.status, u.registration_ip, u.device_fingerprint, 
                   u.created_at, u.last_login, d.db_name 
            FROM users u 
            LEFT JOIN user_databases d ON u.id = d.user_id 
            ORDER BY u.created_at DESC
        ")->fetchAll();
        
        foreach ($users as $user) {
            fputcsv($output, [
                $user['id'],
                $user['name'],
                $user['email'],
                $user['role'],
                $user['status'],
                $user['registration_ip'] ?? '',
                $user['device_fingerprint'] ?? '',
                $user['db_name'] ?? '',
                $user['created_at'],
                $user['last_login'] ?? ''
            ]);
        }
        break;
        
    case 'logs':
        // CSV Headers
        fputcsv($output, ['ID', 'User ID', 'User Name', 'SQL Query', 'Status', 'Execution Time (ms)', 'Error Message', 'Created At']);
        
        // Get logs
        $logs = $pdo->query("
            SELECT q.id, q.user_id, u.name, q.sql_text, q.status, q.execution_time_ms, q.error_message, q.created_at
            FROM query_logs q 
            JOIN users u ON q.user_id = u.id 
            ORDER BY q.created_at DESC
            LIMIT 1000
        ")->fetchAll();
        
        foreach ($logs as $log) {
            fputcsv($output, [
                $log['id'],
                $log['user_id'],
                $log['name'],
                $log['sql_text'],
                $log['status'],
                $log['execution_time_ms'],
                $log['error_message'] ?? '',
                $log['created_at']
            ]);
        }
        break;
        
    case 'security':
        // CSV Headers
        fputcsv($output, ['ID', 'IP Address', 'Email', 'Device Fingerprint', 'Success', 'Failure Reason', 'Attempted At']);
        
        // Get security attempts
        $attempts = $pdo->query("
            SELECT * FROM registration_attempts 
            ORDER BY attempted_at DESC 
            LIMIT 1000
        ")->fetchAll();
        
        foreach ($attempts as $attempt) {
            fputcsv($output, [
                $attempt['id'],
                $attempt['ip_address'],
                $attempt['email'] ?? '',
                $attempt['device_fingerprint'] ?? '',
                $attempt['success'] ? 'Yes' : 'No',
                $attempt['failure_reason'] ?? '',
                $attempt['attempted_at']
            ]);
        }
        break;
        
    default:
        fputcsv($output, ['Error', 'Invalid export type']);
        break;
}

fclose($output);
exit;
?>
