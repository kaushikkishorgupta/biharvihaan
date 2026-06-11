<?php

namespace App\Controllers;

use App\Core\Controller;
use App\Models\Event;

class EventController extends Controller {

    public function index() {
        $this->render('events', [
            'title' => 'Cultural Events & Festivals - Bihar Vihaan Enterprise'
        ]);
    }

    public function detail($params) {
        $id = $params['id'] ?? null;
        if (!$id) {
            $this->redirect('/events');
        }

        $eventModel = new Event();
        $event = $eventModel->findEvent($id);

        if (!$event) {
            die("Event not found.");
        }

        $this->render('event-detail', [
            'title' => $event['title'] . ' - Bihar Vihaan',
            'event' => $event
        ]);
    }
}
