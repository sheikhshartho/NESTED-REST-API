<?php
class User
{

    private $conn;
    private $table = "users";
    private $addressTable = "addresses";

    public function __construct($dbname)
    {
        $this->conn = $dbname;
    }

    public function getData()
    {
        $sql = "SELECT * FROM `$this->table`";
        $result = $this->conn->query($sql);

        $data = [];
        while ($row = $result->fetch_assoc()) {
            $data[] = $row;
        }
        return $data;
    }

    public function getDataById($id)
    {
        $stmt = $this->conn->prepare("SELECT * FROM `$this->table` WHERE id=?");
        $stmt->bind_param('i', $id);
        $stmt->execute();

        $result = $stmt->get_result();
        if ($row = $result->fetch_assoc()) {
            return $row;
        } else {
            return null;
        }
    }

    public function getWithAddresses($id)
    {

        $user = $this->getDataById($id);
        if (!$user) {
            return null;
        }
        $stmt = $this->conn->prepare("
            SELECT * FROM `$this->addressTable` 
            WHERE user_id = ? 
            ORDER BY id DESC
        ");
        $stmt->bind_param('i', $id);
        $stmt->execute();

        $result = $stmt->get_result();
        $addresses = [];
        while ($row = $result->fetch_assoc()) {
            $addresses[] = $row;
        }

        $user['addresses'] = $addresses;
        return $user;
    }

    public function postData($name, $email, $profile_pic)
    {
        $sql = "INSERT INTO `users` ( `name`, `email`, `profile_pic`) VALUES ( '$name', '$email', '$profile_pic ')";

        return $this->conn->query($sql);
    }

    public function updateData($id, $name, $email, $profile_pic)
    {
        $sql = "UPDATE `$this->table` SET `name` = ' $name', `email` = '$email', `profile_pic` = '$profile_pic' WHERE `$this->table`.`id` = $id";
        return $this->conn->query($sql);
    }

    public function deleteData($id)
    {
        $sql = "DELETE FROM `users` WHERE `users`.`id` = $id";
        return $this->conn->query($sql);
    }
}
