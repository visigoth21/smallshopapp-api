<?php 

class CustomerAddressGateway
{
    private PDO $conn;
    private int $pageno;
    private int $perpage;
    private int $offset;

    public function __construct(Database $database, int $page, int $perPage)
    {
        $this->conn = $database->getConnection();
        $this->perpage = $perPage;
        $this->pageno = $page;
        $this->offset = ($page-1) * $perPage;
    }
//
// get All
//
    public function getAll(): array | false
    {
        $sql = "SELECT * FROM customer_address ORDER BY id";
        $stmt = $this->conn->query($sql);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
//
// by invoice ID
//    
    public function getByID(string $id): array | false
    {
        $sql = "SELECT * FROM customer_address WHERE id = :id"; 
        $stmt = $this->conn->prepare($sql);
        $stmt->bindValue(":id", $id, PDO::PARAM_STR);        
        $stmt->execute();
        $rowCount = $stmt->rowCount();
        // create array to store returned items
        $data = [];

        if ($rowCount > 0) {
            $data["customer_address"] = $stmt->fetchAll(PDO::FETCH_ASSOC);
        }
        else {
            $data = false;
        }
        return $data;
    }
//
// Create an Invoice
//
    public function create(array $data): array | false
    {
        $sql = "INSERT INTO customer_address 
        (link, address_name, address, address_2, city, state, zip) 
        VALUES 
        (:link, :address_name, :address, :address_2, :city, :state, :zip)"; 
        $stmt = $this->conn->prepare($sql);

        $stmt->bindValue(":link", $data["link"], PDO::PARAM_INT);
        $stmt->bindValue(":address_name", $data["address_name"], PDO::PARAM_STR);
        $stmt->bindValue(":address", $data["address"], PDO::PARAM_STR);
        $stmt->bindValue(":address_2", $data["address_2"], PDO::PARAM_STR);
        $stmt->bindValue(":city", $data["city"], PDO::PARAM_STR);
        $stmt->bindValue(":state", $data["state"], PDO::PARAM_STR);
        $stmt->bindValue(":zip", $data["zip"], PDO::PARAM_STR); 
        $stmt->execute();

        $RetId = $this->conn->lastInsertId();

        return $this->getByID($RetId);
         
    }
//
// Update
//
    public function update(string $id, array $data): array | false
    {
        $fields = [];

//id	link, address_name, address, address_2, city, state, zip	active

        if (! empty($data["link"]))
        {
            $fields["link"] = [
                $data["link"], PDO::PARAM_INT];
        }

        if (! empty($data["address_name"]))
        {
            $fields["address_name"] = [
                $data["address_name"], PDO::PARAM_STR];
        }

        if (! empty($data["address"]))
        {
            $fields["address"] = [
                $data["address"], PDO::PARAM_STR];
        }

        if (! empty($data["address_2"]))
        {
            $fields["address_2"] = [
                $data["address_2"], PDO::PARAM_STR];
        }

        if (! empty($data["city"]))
        {
            $fields["city"] = [
                $data["city"], PDO::PARAM_STR];
        }

        if (! empty($data["state"]))
        {
            $fields["state"] = [
                $data["state"], PDO::PARAM_STR];
        }

        if (! empty($data["zip"]))
        {
            $fields["zip"] = [
                $data["zip"], PDO::PARAM_STR];
        }

        if (array_key_exists("active", $data))
        {
            $fields["active"] = [
                $data["active"], PDO::PARAM_BOOL];
        }

        if (empty($fields)) {
            return 0;
        } else {

            $sets = array_map(function($value) {
                return "$value = :$value";
            }, array_keys($fields));

            $sql ="UPDATE customer_address"
            . " SET " . implode(", ", $sets)
            . " WHERE id = :id";

            $stmt = $this->conn->prepare($sql);
            $stmt->bindValue(":id", $id, PDO::PARAM_INT);
            
            foreach ($fields as $name => $values) {
                $stmt->bindValue(":$name", $values[0], $values[1]);
            }

            $stmt->execute();

            return $this->getByID($id);
        }
    }
//
// Delete
//
    public function delete(string $id): int
    {
        $sql = "UPDATE customer_address SET active = :active WHERE id = :id";
        $stmt = $this->conn->prepare($sql);
        $stmt->bindValue(":id", $id, PDO::PARAM_INT);
        $stmt->bindValue(":active", false, PDO::PARAM_BOOL);
        $stmt->execute();
        return $stmt->rowCount();
    }

}
// customer_address
//id	link	address_name	address	address_2	city	state	zip	active	
	
