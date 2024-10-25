<?php

declare(strict_types=1);

namespace App\Service\Form;
use App\DTO\FormData;
use Exception;
use PDO;

final class Form implements FormInterface
{
    public function __construct(private PDO $connection) {
    }

    public function saveForm(FormData $formData): bool
    {
        $sql = "INSERT INTO `zadanie`
            (`name`, `surname`, `email`, `phone`, `client_no`, `account_number`, `choose`, `agreement1`, `agreement2`, `agreement3`)
            VALUES (:name, :surname, :email, :phone, :client_no, :account_number, :choose, :agreement1, :agreement2, :agreement3)";

        try {
            $query = $this->connection->prepare($sql);
            $query->bindValue(':name', $formData->name, PDO::PARAM_STR);
            $query->bindValue(':surname', $formData->surname, PDO::PARAM_STR);
            $query->bindValue(':email', $formData->email, PDO::PARAM_STR);
            $query->bindValue(':phone', $formData->phoneNumber, PDO::PARAM_INT);
            $query->bindValue(':client_no', $formData->clientNumber, PDO::PARAM_STR);
            $query->bindValue(':account_number', $formData->accountNumber, PDO::PARAM_STR);
            $query->bindValue(':choose', $formData->choose, PDO::PARAM_INT);
            $query->bindValue(':agreement1', $formData->agreement1, PDO::PARAM_INT);
            $query->bindValue(':agreement2', $formData->agreement2, PDO::PARAM_INT);
            $query->bindValue(':agreement3', $formData->agreement3, PDO::PARAM_INT);

            $query->execute();
        } catch (Exception $e) {
        }
       
        return isset($e) ? false : true;
    }
    public function counter($surname = 'kowalski', $email = 'gmail.com'): string
    {
        $sql = "SELECT 
                    SUM(
                        IF(`surname` = '$surname', 1, 0)
                    ) AS surnameCounter,
                    SUM(
                        IF(`email` LIKE '%@$email', 1, 0)
                    ) AS emailCounter
                FROM `zadanie`";

        try {
            $query = $this->connection->prepare($sql);
            $query->execute();
            $data = $query->fetch(PDO::FETCH_ASSOC);
    
        } catch (Exception $e) {
            $data = [];
        }

        return json_encode($data);
    }

    public function getList(string $orderBy = null): string
    {
        $sql = "SELECT name, surname, email, phone, client_no, account_number, choose, agreement1, agreement2, agreement3
        FROM `zadanie`";

        if ($orderBy) {
            $sql .= "ORDER BY $orderBy DESC";
        }

        try {
            $query = $this->connection->prepare($sql);
            $query->execute();
            $data = $query->fetchAll(PDO::FETCH_ASSOC); 
        } catch (Exception $e) {
            $data = [];
        }

        return json_encode($data);
    }
}