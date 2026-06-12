<?php

namespace App\Controllers;

use App\Core\Controller;
use App\Core\Auth;

class AdminController extends Controller {

    public function __construct() {
        parent::__construct();
        Auth::requireAdmin();
    }

    public function index() {
        // Fetch stats from AdminModel or individual models
        $stats = [
            'users'        => 150,
            'products'     => 34,
            'orders'       => 12,
            'destinations' => 8,
            'businesses'   => 45
        ];

        $this->renderAdmin('admin/dashboard', [
            'stats' => $stats
        ]);
    }
}
