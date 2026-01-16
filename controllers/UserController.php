<?php
class UserController
{
    private $user;
    public function __construct($dbname)
    {
        $this->user = new User($dbname);
    }

    public function handleRequest($method)
    {
        switch ($method) {
            case "GET":
                if (isset($_GET['id'])) {
                    $user = $this->user->getWithAddresses($_GET['id']);
                    if ($user) {
                        echo json_encode([
                            'status' => true,
                            "data" => $user
                        ]);
                    } else {
                        echo json_encode([
                            'status' => false,
                            "message" => "User not found"
                        ]);
                    };
                    return;
                } else {
                    $users = $this->user->getData();
                    $usersWithAddresses = [];
                    foreach ($users as $u) {
                        $usersWithAddresses[] = $this->user->getWithAddresses($u['id']);
                    }

                    echo json_encode([
                        "status" => true,
                        "data" => $usersWithAddresses
                    ]);
                };
                break;

            case 'POST':
                $data = json_decode(file_get_contents('php://input'), true);
                $this->user->postData($data['name'], $data['email'], $data['profile_pic']);

                json_encode([
                    'status' => true,
                    'data' => "User created successfully"
                ]);
                break;
            case 'PUT':
                $data = json_decode(file_get_contents('php://input'), true);
                $this->user->updateData($data['id'], $data['name'], $data['email'], $data['profile_pic']);

                json_encode([
                    'status' => true,
                    'data' => 'User updated successfully'
                ]);
                break;
            case 'DELETE':
                $data = json_decode(file_get_contents('php://input'), true);
                $this->user->deleteData($data['id']);

                json_encode([
                    'status' => true,
                    "data" => "User deleted successfully"
                ]);
        }
    }
}
