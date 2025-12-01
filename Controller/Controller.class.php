<?php

class Controller
{
    function model($model)
    {
        require_once 'Model/Model.class.php';
        require_once 'Model/' . $model . '.class.php';
        return new $model();
    }

    public function view($viewName, $data = [])
    {
        foreach ($data as $key => $value) {
            $$key = $value;
        }
        include 'View/' . $viewName . '.php';
    }

    function startSession() {
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }
    }

    function setSession($key, $value) {
        $_SESSION[$key] = $value;
    }

    function getSession($key) {
        return isset($_SESSION[$key]) ? $_SESSION[$key] : null;
    }

    function removeSession($key) {
        if (isset($_SESSION[$key])) {
            unset($_SESSION[$key]);
        }
    }   

    function jsonResponse($data) {
        header('Content-Type: application/json');
        echo json_encode($data);
        exit;
    }


}
