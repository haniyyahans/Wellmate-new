<?php

class Model
{
    protected $db;

    public function __construct()
    {
        // Konfigurasi koneksi ke database
        $host = 'localhost:3306'; // Ganti dengan host database Anda
        $user = 'root';
        $pass = '';
        $dbname = 'wellmate';

        // Membuat koneksi ke database
        $this->db = new mysqli($host, $user, $pass, $dbname);

        // Memeriksa koneksi
        if (!$this->db) {
            echo "Database error. ";
            exit;
        }
    }
    protected function escape($value) {
        if ($this->db === null) {
            return htmlspecialchars($value);
        }
        
        return $this->db->real_escape_string($value);
    }

    protected function query($sql) {
        if ($this->db === null) {
            throw new Exception("Database connection is not available.");
        }

        $result = $this->db->query($sql);
        
        if (!$result && $this->db->errno != 0) {
            throw new Exception("Query error: " . $this->db->error);
        }

        return $result;
    }

    protected function isConnected() {
        return $this->db !== null;
    }

}
