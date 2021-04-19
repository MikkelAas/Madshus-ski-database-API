<?php

require_once ('models/Privileges.php');

class AccessToken {

    protected $db;

    public function __construct() {
        $this->db = require('db/pdo_connection.php');
    }

    function getPrivileges (string $token) : mixed {
        $query = "
            SELECT 
                   access_token.company_access, 
                   access_token.customer_access, 
                   access_token.transporter_access 
            FROM access_token
            WHERE token = :token";

        $stmt = $this->db->prepare($query);
        $stmt->bindValue(':token', $token);
        $stmt->execute();

        $row = $stmt->fetch();

        if ($row === false) {
            return false;
        }

        return new Privileges($row['company_access'], $row['customer_access'], $row['transporter_access']);
    }

}