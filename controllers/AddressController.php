<?php
class AddressController
{
    private $addresses;

    public function __construct($dbname)
    {
        $this->addresses = new Addresses($dbname);
    }

    public function handleRequst($method)
    {

        switch ($method) {
            case "GET":
                if (isset($_GET['id'])) {
                    $addresses = $this->addresses->getByUserId($_GET['id']);
                    if ($addresses) {

                        echo json_encode([
                            'status' => true,
                            "data" => $addresses
                        ]);
                    } else {
                        echo json_encode([
                            "status" => false,
                            "message" => "Address not found"
                        ]);
                    }
                } else {
                    echo json_encode([
                        "status" => true,
                        "message" => $this->addresses->getAddress()
                    ]);
                }
                break;

            case "POST":
                $data = json_decode(file_get_contents('php://input'), true);
                $this->addresses->postAdress($data['user_id'], $data['type'], $data['city'], $data['state'], $data['country'], $data['pin'], $data['district']);

                echo json_encode([
                    'status' => true,
                    'message' => 'Address added successfully'
                ]);
                break;

            case "PUT":
                $data = json_decode(file_get_contents('php://input'), true);
                $this->addresses->updateAddress($data['id'], $data['type'], $data['city'], $data['state'], $data['country'], $data['pin'], $data['district']);

                echo json_encode([
                    'status' => true,
                    'message' => 'Address updated successfully'
                ]);
                break;

            case "DELETE":
                $data = json_decode(file_get_contents('php://input'), true);
                $this->addresses->deletAddress($data['id']);

                echo json_encode([
                    'status' => true,
                    'message' => 'Address deleted successfully'
                ]);
                break;
        }
    }
}
