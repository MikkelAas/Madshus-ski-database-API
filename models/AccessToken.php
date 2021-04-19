<?php

require_once ('models/Privileges.php');

/**
 * Class AccessToken
 * Model for getting access tokens from database
 */
class AccessToken {

    protected $db;

    /**
     * AccessToken constructor.
     */
    public function __construct() {
        $this->db = require('db/pdo_connection.php');
    }

    /**
     * @param string $token access token to get privileges for
     * @return mixed Privileges if token exists. false otherwise.
     */
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