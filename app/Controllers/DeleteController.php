<?php

namespace App\Controllers;

use App\FrameworkTools\Abstracts\Controllers\AbstractControllers;

class DeleteController extends AbstractControllers {

    public function exec() {
        $requestsVariables = $this->processServerElements->getVariables();
        $response = ['success' => true];

        $missingAttribute;
        $idUser;
    
        try {
            
            foreach ($requestsVariables as $valueVariable) {
                if ($valueVariable["name"] === "id_user") {
                    $idUser = $valueVariable["value"];
                }
            }
            
            if (!$idUser) {
                $missingAttribute = 'userIdIsNull';
                throw new \Exception("You need to inform idUser variable");
            }

            $users = $this
                        ->pdo
                        ->query("SELECT * FROM user WHERE id_user = '{$idUser}';")
                        ->fetchAll();

            if (sizeof($users) === 0) {
                $missingAttribute = 'thisUserNoExist';
                throw new \Exception("There is not record of this user in db");
            }
        
            $sql = "DELETE FROM user WHERE id_user= :id_user";
            
            $stmt = $this->pdo->prepare($sql);
            $stmt->execute(['id_user' => $idUser]);

        } catch (\Exception $e) {
            $response = [
                'success' => false,
                'message' => $e->getMessage(),
                'missingAttribute' => $missingAttribute
            ];
        }
        
        view($response);
    }

}
