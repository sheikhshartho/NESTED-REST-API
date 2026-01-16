<?php
class Addresses
{
    private $conn;
    private $addressesTable = "addresses";

    public function __construct($dbname)
    {
        $this->conn = $dbname;
    }

    public function getAddress()
    {
        $sql = "SELECT * FROM `$this->addressesTable`";
        $resuit = $this->conn->query($sql);

        $data = [];
        while ($row = $resuit->fetch_assoc()) {
            $data[] = $row;
        }
        return $data;
    }
    public function getByUserId($user_id)
    {
        $stmt = $this->conn->prepare(
            "SELECT * FROM `$this->addressesTable` WHERE user_id = ?"
        );
        $stmt->bind_param("i", $user_id);
        $stmt->execute();

        $result = $stmt->get_result();
        $addresses = [];

        while ($row = $result->fetch_assoc()) {
            $addresses[] = $row;
        }

        return $addresses;
    }

    public function postAdress($user_id, $type, $city, $state, $country, $pin, $district)
    {
        $sql = "INSERT INTO `addresses` ( `user_id`, `type`, `city`, `state`, `country`, `pin`, `district`) VALUES ( '$user_id', '$type', '$city', '$state', '$country', '$pin', '$district')";

        return $this->conn->query($sql);
    }

    public function updateAddress($id, $type, $city, $state, $country, $pin, $district)
    {
        $sql = "UPDATE `$this->addressesTable` SET `type`= '$type', `city` = '$city',  `state` = '$state', `country` = '$country', `pin` = '$pin', `district` = '$district' WHERE `$this->addressesTable`.`id` = $id";

        return $this->conn->query($sql);
    }

    public function deletAddress($id)
    {
        $sql = "DELETE FROM `$this->addressesTable` WHERE `$this->addressesTable`.`id` = $id";
    }
}
