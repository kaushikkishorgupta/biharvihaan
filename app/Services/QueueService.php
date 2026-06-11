<?php

namespace App\Services;

use App\Core\Database;
use App\Core\Logger;
use Exception;

class QueueService {
    private $db;

    public function __construct() {
        $this->db = Database::getInstance();
    }

    /**
     * Pushes a new background task to the queue database table
     * @param string $handler e.g. "email_invoice", "sms_otp", "push_notification", "whatsapp_crm"
     * @param array $payload Metadata arrays for the job execution
     * @return int Job insertion ID
     */
    public function pushJob($handler, $payload) {
        $sql = "INSERT INTO queue_jobs (handler, payload, status) VALUES (?, ?, 'pending')";
        $this->db->execute($sql, [
            $handler,
            json_encode($payload)
        ]);
        return $this->db->lastInsertId();
    }

    /**
     * Retrieves the next pending job, marks it as running, and executes it.
     * Simulated cron worker triggers this on HTTP requests or background scripts.
     */
    public function processNextJob() {
        // Find next pending job
        $job = $this->db->queryRow("SELECT * FROM queue_jobs WHERE status = 'pending' ORDER BY id ASC LIMIT 1");
        if (!$job) {
            return false; // No jobs pending
        }

        $jobId = $job['id'];
        
        // Lock job to running
        $this->db->execute("UPDATE queue_jobs SET status = 'running' WHERE id = ?", [$jobId]);

        try {
            $payload = json_decode($job['payload'], true);
            $handler = $job['handler'];

            // Execute Handler loops
            switch ($handler) {
                case 'email':
                    $this->simulateEmail($payload);
                    break;
                case 'push':
                    $this->simulatePush($payload);
                    break;
                case 'whatsapp':
                    $this->simulateWhatsApp($payload);
                    break;
                case 'sms':
                    $this->simulateSms($payload);
                    break;
                default:
                    throw new Exception("Unknown queue job handler class: " . $handler);
            }

            // Mark completed
            $this->db->execute("UPDATE queue_jobs SET status = 'completed' WHERE id = ?", [$jobId]);
            Logger::log('Queue Job Completed', "Job ID: $jobId successfully processed [Handler: $handler]");
            return true;

        } catch (Exception $e) {
            // Mark failed
            $this->db->execute("UPDATE queue_jobs SET status = 'failed' WHERE id = ?", [$jobId]);
            Logger::log('Queue Job Failed', "Job ID: $jobId failed: " . $e->getMessage());
            return false;
        }
    }

    // ===============================================
    // Simulated delivery loops
    // ===============================================

    private function simulateEmail($payload) {
        $to = $payload['to'] ?? 'guest@biharvihaan.com';
        $subject = $payload['subject'] ?? 'Notification Alert';
        $body = $payload['body'] ?? '';

        // Simulates SMTP write-out logs
        $logMsg = "SMTP SIMULATOR: Sending Email to [$to]. Subject: [$subject]. Body snippet: " . substr($body, 0, 80) . "...";
        Logger::log('Email Queue worker', $logMsg);
    }

    private function simulatePush($payload) {
        $userId = $payload['user_id'] ?? 0;
        $title = $payload['title'] ?? 'Bihar Vihaan Alert';
        $message = $payload['message'] ?? '';

        // Insert notification in user notifications feed
        if ($userId > 0) {
            $this->db->execute("INSERT INTO notifications (user_id, type, title, message) VALUES (?, 'push', ?, ?)", [
                $userId,
                $title,
                $message
            ]);
        }
        Logger::log('Push Queue worker', "PUSH SIMULATOR: Dispatching Firebase push token notifications to User ID: $userId. Title: $title");
    }

    private function simulateWhatsApp($payload) {
        $phone = $payload['phone'] ?? '';
        $message = $payload['message'] ?? '';

        Logger::log('WhatsApp Queue worker', "WHATSAPP SIMULATOR: Sending official Business WhatsApp webhook message to [$phone]: $message");
    }

    private function simulateSms($payload) {
        $phone = $payload['phone'] ?? '';
        $message = $payload['message'] ?? '';

        Logger::log('SMS Queue worker', "SMS GATEWAY SIMULATOR: Sending Twilio/Transactional SMS to [$phone]: $message");
    }
}
