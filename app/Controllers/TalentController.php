<?php

namespace App\Controllers;

use App\Core\Controller;
use App\Core\Auth;
use App\Core\Session;
use App\Core\Logger;
use App\Models\Artist;

class TalentController extends Controller {

    public function index() {
        $artistModel = new Artist();
        $category = $_GET['category'] ?? null;
        $artists = $artistModel->getArtists($category);

        $this->render('talent', [
            'title' => 'Talent Showcase - Bihar Vihaan',
            'artists' => $artists,
            'selected_category' => $category
        ]);
    }

    public function detail($params) {
        $id = $params['id'] ?? null;
        if (!$id) {
            $this->redirect('/talent');
        }

        $artistModel = new Artist();
        $artist = $artistModel->findArtist($id);

        if (!$artist) {
            die("Artist profile not found.");
        }

        $portfolios = $artistModel->getPortfolios($id);

        $this->render('talent', [
            'title' => $artist['stage_name'] . ' - Artist Portfolio',
            'artist' => $artist,
            'portfolios' => $portfolios,
            'view_mode' => 'detail'
        ]);
    }

    public function handleCollab() {
        if (!Auth::check()) {
            Session::setFlash('error', 'Please log in to send collaboration requests.');
            $this->redirect('/login');
        }

        $senderId = Session::get('user_id');
        $receiverUserId = intval($_POST['receiver_user_id'] ?? 0);
        $message = $_POST['message'] ?? '';

        if ($receiverUserId <= 0 || empty($message)) {
            Session::setFlash('error', 'Collaboration request could not be processed.');
            $this->redirect('/talent');
        }

        $artistModel = new Artist();
        $success = $artistModel->createCollaborationRequest($senderId, $receiverUserId, $message);

        if ($success) {
            Logger::log('Collaboration Requested', "Collab request from user #$senderId to artist user #$receiverUserId");
            
            // Send system notification
            $db = \App\Core\Database::getInstance();
            $db->execute("INSERT INTO notifications (user_id, type, title, message) VALUES (?, 'system', ?, ?)", [
                $receiverUserId,
                'New Collaboration Request',
                Session::get('user_name') . " wants to collaborate with you! Message: $message"
            ]);

            Session::setFlash('success', 'Collaboration request sent successfully!');
        } else {
            Session::setFlash('error', 'Could not send request. Please try again.');
        }

        $this->redirect('/talent');
    }

    public function handleAddPortfolio() {
        if (!Auth::check()) {
            $this->redirect('/login');
        }

        $title = $_POST['title'] ?? '';
        $desc = $_POST['description'] ?? '';
        $mediaType = $_POST['media_type'] ?? 'link';
        $mediaUrl = $_POST['media_url'] ?? '';

        if (empty($title) || empty($mediaUrl)) {
            Session::setFlash('error', 'Portfolio Title and Media link are required.');
            $this->redirect('/dashboard');
        }

        $artistModel = new Artist();
        $artist = $artistModel->findArtistByUserId(Session::get('user_id'));

        if (!$artist) {
            Session::setFlash('error', 'You must register an artist profile to upload portfolios.');
            $this->redirect('/dashboard');
        }

        $success = $artistModel->addPortfolio($artist['id'], [
            'title' => $title,
            'description' => $desc,
            'media_type' => $mediaType,
            'media_url' => $mediaUrl
        ]);

        if ($success) {
            Logger::log('Portfolio Media Added', "Artist #{$artist['id']} uploaded new portfolio item: $title");
            Session::setFlash('success', 'Portfolio item uploaded successfully!');
        } else {
            Session::setFlash('error', 'Upload failed. Try again.');
        }

        $this->redirect('/dashboard');
    }
}
