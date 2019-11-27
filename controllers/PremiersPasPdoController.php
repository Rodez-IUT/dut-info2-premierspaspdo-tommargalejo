<?php

namespace controllers;

use yasmf\HttpHelper;
use yasmf\View;

class PremiersPasPdoController {

    public function defaultAction($pdo) {
        $lettre = HttpHelper::getParam('lettre') ?: '';
        $status = HttpHelper::getParam('status');
        $status_id = HttpHelper::getParam('status_id');
        $user_id = HttpHelper::getParam('user_id');
        $action = HttpHelper::getParam('action');

        // Traitement formulaire de recherche
        $statusID = 2;

        if ($lettre != null && $status != null) {
            if ($status == "active") {
                $statusID = 2;
            } else if ($status == "waiting") {
                $statusID = 1;
            } else if ($status == "waitingDelete") {
                $statusID = 3;
            }
        }

        // traitement du formulaire de suppressionc
        if ($status_id != null && $user_id != null && $action != null && $action == "askDeletion") {
            try {
                $pdo->beginTransaction();
                // Ajout au log
                $datetime = date("Y-m-d H:i:s");
                $stmt = $pdo->prepare("INSERT INTO action_log (action_date, action_name, user_id) values (?, ?, ?)");
                $stmt->execute([ $datetime, $action, $user_id]);

                // modification du status
                $stmt = $pdo->prepare("UPDATE users SET status_id = ? WHERE id = ?");
                $stmt->execute([ 3, $user_id]);

                $pdo->commit();
            }catch (Exception $e){
                $pdo->rollBack();
                throw $e;
            }
        }

        // Liste des utilisateurs à afficher
        $users = $pdo->prepare("SELECT users.id, username, email, name, status.id as idStatus FROM users JOIN status ON users.status_id = status.id WHERE username LIKE ? AND status.id = ? ORDER BY username");
        $users->execute([$lettre . '%', $statusID]);

        $view = new View("views/all_users");
        $view->setVar('lettre', $lettre);
        $view->setVar('status', $status);
        $view->setVar('users', $users);
        return $view;
    }
}