<?php

namespace controllers;

use yasmf\HttpHelper;
use yasmf\View;

class PremiersPAsPdo {

    public function all_users($pdo) {
        $view = new View("views/all_users");
        return $view;
    }
}