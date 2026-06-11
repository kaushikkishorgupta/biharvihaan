<?php

namespace App\Models;

use App\Core\Database;

class Event {
    protected $db;

    public function __construct() {
        $this->db = Database::getInstance();
    }

    public function getFestivals($season = null) {
        if ($season) {
            $sql = "SELECT * FROM festivals WHERE season = ? ORDER BY date ASC";
            return $this->db->query($sql, [$season]);
        }
        $sql = "SELECT * FROM festivals ORDER BY date ASC";
        return $this->db->query($sql);
    }

    public function getEvents($status = 'active') {
        $sql = "SELECT e.*, u.name as organizer_name 
                FROM events e
                JOIN users u ON e.organizer_id = u.id
                WHERE e.status = ?
                ORDER BY e.date ASC";
        return $this->db->query($sql, [$status]);
    }

    public function findEvent($id) {
        $sql = "SELECT e.*, u.name as organizer_name 
                FROM events e
                JOIN users u ON e.organizer_id = u.id
                WHERE e.id = ?";
        return $this->db->queryRow($sql, [$id]);
    }

    public function registerForEvent($userId, $eventId, $ticketCount, $totalPrice, $paymentStatus = 'pending') {
        // Generate unique ticket code
        $ticketCode = 'VIHAAN-' . strtoupper(bin2hex(random_bytes(4)));

        $sql = "INSERT INTO events_registrations (event_id, user_id, ticket_count, total_price, payment_status, ticket_code) 
                VALUES (?, ?, ?, ?, ?, ?)";
        
        $this->db->execute($sql, [$eventId, $userId, $ticketCount, $totalPrice, $paymentStatus, $ticketCode]);
        $regId = $this->db->lastInsertId();

        // If paid instantly, decrement available tickets
        if ($paymentStatus === 'paid') {
            $this->decrementAvailableTickets($eventId, $ticketCount);
        }

        return [
            'registration_id' => $regId,
            'ticket_code' => $ticketCode
        ];
    }

    public function decrementAvailableTickets($eventId, $count) {
        $sql = "UPDATE events SET available_tickets = available_tickets - ? WHERE id = ? AND available_tickets >= ?";
        return $this->db->execute($sql, [$count, $eventId, $count]);
    }

    public function getUserRegistrations($userId) {
        $sql = "SELECT er.*, e.title as event_title, e.date as event_date, e.time as event_time, e.location as event_location, e.image_url as event_image 
                FROM events_registrations er
                JOIN events e ON er.event_id = e.id
                WHERE er.user_id = ?
                ORDER BY er.id DESC";
        return $this->db->query($sql, [$userId]);
    }

    public function getRegistration($regId) {
        $sql = "SELECT er.*, e.title as event_title, e.date as event_date, e.time as event_time, e.location as event_location, u.name as user_name, u.email as user_email
                FROM events_registrations er
                JOIN events e ON er.event_id = e.id
                JOIN users u ON er.user_id = u.id
                WHERE er.id = ?";
        return $this->db->queryRow($sql, [$regId]);
    }

    public function updateRegistrationPayment($regId, $paymentStatus) {
        $sql = "UPDATE events_registrations SET payment_status = ? WHERE id = ?";
        $this->db->execute($sql, [$paymentStatus, $regId]);

        if ($paymentStatus === 'paid') {
            $reg = $this->getRegistration($regId);
            $this->decrementAvailableTickets($reg['event_id'], $reg['ticket_count']);
        }
    }

    public function createEvent($data) {
        $sql = "INSERT INTO events (title, description, date, time, location, price, max_tickets, available_tickets, organizer_id, image_url, status) 
                VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, 'active')";
        
        $this->db->execute($sql, [
            $data['title'],
            $data['description'],
            $data['date'],
            $data['time'],
            $data['location'],
            $data['price'],
            $data['max_tickets'],
            $data['max_tickets'], // initially available = max
            $data['organizer_id'],
            $data['image_url']
        ]);

        return $this->db->lastInsertId();
    }
}
