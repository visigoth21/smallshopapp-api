<?php 
//-----------------------------------------------------------------------------------------------//
class MfgGateway
{
    private PDO $conn;
//-----------------------------------------------------------------------------------------------//
    public function __construct(Database $database)
    {
        $this->conn = $database->getConnection();
    }
//-----------------------------------------------------------------------------------------------//
    public function getAll(): array 
    {
        $sql = "SELECT * FROM mfg ORDER BY mfg_name";
        $stmt = $this->conn->query($sql);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
//-----------------------------------------------------------------------------------------------//    
        public function getByMFG(string $id): array | false
        {
    
            $sql = "SELECT * FROM mfg WHERE mfg = :id"; 
            $stmt = $this->conn->prepare($sql);
            $stmt->bindValue(":id", $id, PDO::PARAM_STR);        
            $stmt->execute();
            $rowCount = $stmt->rowCount();
            // create array to store returned items
            $data = [];
    
            if ($rowCount > 0) {
                $data = $stmt->fetchAll(PDO::FETCH_ASSOC);
            }
            else {
                $data = false;
            }
            return $data;
        }
//-----------------------------------------------------------------------------------------------//    
    public function getByID(string $id): array | false
    {

        $sql = "SELECT * FROM mfg WHERE id = :id"; 
        $stmt = $this->conn->prepare($sql);
        $stmt->bindValue(":id", $id, PDO::PARAM_STR);        
        $stmt->execute();
        $rowCount = $stmt->rowCount();
        // create array to store returned items
        $data = [];

        if ($rowCount > 0) {
            $data = $stmt->fetchAll(PDO::FETCH_ASSOC);
        }
        else {
            $data = false;
        }
        return $data;
    }
//-----------------------------------------------------------------------------------------------//
    public function create(array $data): string
    {
        $sql = "INSERT INTO mfg 
        (mfg_name, mfg, phone_number, website, email_contact, active) 
        VALUES 
        (:mfg_name, :mfg, :phone_number, :website, :email_contact, :active)"; 
        $stmt = $this->conn->prepare($sql);

        $stmt->bindValue(":mfg_name", $data["mfg_name"], PDO::PARAM_STR);
        $stmt->bindValue(":mfg", $data["mfg"], PDO::PARAM_STR);    

        if (empty($data["phone_number"])) {
            $stmt->bindValue(":phone_number", "", PDO::PARAM_STR);
        } else {
            $stmt->bindValue(":phone_number", $data["phone_number"], PDO::PARAM_STR);    
        }

        if (empty($data["website"])) {
            $stmt->bindValue(":website", "", PDO::PARAM_STR);
        } else {
            $stmt->bindValue(":website", $data["website"], PDO::PARAM_STR);    
        }

        if (empty($data["email_contact"])) {
            $stmt->bindValue(":email_contact", "", PDO::PARAM_STR);
        } else {
            $stmt->bindValue(":email_contact", $data["email_contact"], PDO::PARAM_STR);    
        }

        if (empty($data["active"])) {
            $stmt->bindValue(":active", $data["active"] ?? true, PDO::PARAM_BOOL);    
        }
        $stmt->execute();

        return $this->conn->lastInsertId();
    }
//-----------------------------------------------------------------------------------------------//
    public function update(string $id, array $data): int
    {
        $fields = [];

        if (! empty($data["mfg_name"]))
        {
            $fields["mfg_name"] = [
                $data["mfg_name"],
                PDO::PARAM_STR
            ];
        }
        if (! empty($data["mfg"]))
        {
            $fields["mfg"] = [
                $data["mfg"],
                PDO::PARAM_STR
            ];
        }
        if (! empty($data["phone_number"]))
        {
            $fields["phone_number"] = [
                $data["phone_number"],
                PDO::PARAM_STR
            ];
        }
        if (! empty($data["website"]))
        {
            $fields["website"] = [
                $data["website"],
                PDO::PARAM_STR
            ];
        }
        if (! empty($data["email_contact"]))
        {
            $fields["email_contact"] = [
                $data["email_contact"],
                PDO::PARAM_STR
            ];
        }
        if (array_key_exists("active", $data))
        {
            $fields["active"] = [
                $data["active"],
                PDO::PARAM_BOOL
            ];
        }

        if (empty($fields)) {
            return 0;
        } else {

            $sets = array_map(function($value) {
                return "$value = :$value";
            }, array_keys($fields));

            $sql ="UPDATE mfg"
            . " SET " . implode(", ", $sets)
            . " WHERE id = :id";

            $stmt = $this->conn->prepare($sql);
            $stmt->bindValue(":id", $id, PDO::PARAM_INT);
            
            foreach ($fields as $name => $values) {
                $stmt->bindValue(":$name", $values[0], $values[1]);
            }

            $stmt->execute();

            return $stmt->rowCount();
        }
    }
//-----------------------------------------------------------------------------------------------//
    public function delete(string $id): int
    {
        $sql = "DELETE FROM mfg WHERE id = :id";
        $stmt = $this->conn->prepare($sql);
        $stmt->bindValue(":id", $id, PDO::PARAM_INT);
        $stmt->execute();
        return $stmt->rowCount();

    }
}
//-----------------------------------------------------------------------------------------------//
// id
// mfg_name
// mfg
// phone_number
// website
// email_contact
// active
