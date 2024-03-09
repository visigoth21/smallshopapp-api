<?php 

class StoreGateway
{
    private PDO $conn;

    public function __construct(Database $database)
    {
        $this->conn = $database->getConnection();
    }
//
// get All
//
    public function getAll(): array 
    {
        $sql = "SELECT * FROM store ORDER BY id";
        $stmt = $this->conn->query($sql);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
//
// by invoice ID
//    
    public function getByID(string $id): array | false
    {
        $sql = "SELECT * FROM store WHERE id = :id"; 
        $stmt = $this->conn->prepare($sql);
        $stmt->bindValue(":id", $id, PDO::PARAM_STR);        
        $stmt->execute();
        $rowCount = $stmt->rowCount();
        // create array to store returned items
        $data = [];

        if ($rowCount > 0) {
            $data = $stmt->fetchAll(PDO::FETCH_ASSOC);
        } else {
            $data = false;
        }
        return $data;
    }
//
// Create a Store
//
public function create(array $data): string
{
    $sql = "INSERT INTO store 
    (company_id, name, name_2, address, address_2, city, state, zip, tax_rate, inv_num_prefix_add, inv_num_max, inv_num_prefix, inv_num_year_ct, active) 
    VALUES 
    (:company_id, :name, :name_2, :address, :address_2, :city, :state, :zip, :tax_rate, :inv_num_prefix_add, :inv_num_max, :inv_num_prefix, :inv_num_year_ct, :active)"; 

    $stmt = $this->conn->prepare($sql);

    $stmt->bindValue(":company_id", $data["company_id"], PDO::PARAM_INT);
    $stmt->bindValue(":name", $data["name"], PDO::PARAM_INT);
    $stmt->bindValue(":name_2", $data["name_2"], PDO::PARAM_STR);
    $stmt->bindValue(":address", $data["address"], PDO::PARAM_STR);
    $stmt->bindValue(":address_2", $data["address_2"], PDO::PARAM_STR);
    $stmt->bindValue(":city", $data["city"], PDO::PARAM_STR);
    $stmt->bindValue(":state", $data["state"], PDO::PARAM_STR);
    $stmt->bindValue(":zip", $data["zip"], PDO::PARAM_STR);
    $stmt->bindValue(":tax_rate", $data["tax_rate"], PDO::PARAM_STR);
    $stmt->bindValue(":inv_num_prefix_add", $data["inv_num_prefix_add"], PDO::PARAM_BOOL);
    $stmt->bindValue(":inv_num_max", $data["inv_num_max"], PDO::PARAM_INT);
    $stmt->bindValue(":inv_num_prefix", $data["inv_num_prefix"], PDO::PARAM_STR);
    $stmt->bindValue(":inv_num_year_ct", $data["inv_num_year_ct"], PDO::PARAM_INT);   
    $stmt->bindValue(":active", $data["active"], PDO::PARAM_BOOL); 

    $stmt->execute();

    return $this->conn->lastInsertId();
}
//
// Update
//
    public function update(string $id, array $data): int
    {
        $fields = [];

        if (! empty($data["company_id"]))
        {
            $fields["company_id"] = [
                $data["company_id"], PDO::PARAM_INT];
        }

        if (! empty($data["name"]))
        {
            $fields["name"] = [
                $data["name"], PDO::PARAM_STR];
        }

        if (! empty($data["name_2"]))
        {
            $fields["name_2"] = [
                $data["name_2"], PDO::PARAM_STR];
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

        if (! empty($data["tax_rate"]))
        {
            $fields["tax_rate"] = [
                $data["tax_rate"], PDO::PARAM_STR];
        }

        if (! empty($data["inv_num_prefix_add"]))
        {
            $fields["inv_num_prefix_add"] = [
                $data["inv_num_prefix_add"], PDO::PARAM_BOOL];
        }

        if (! empty($data["inv_num_max"]))
        {
            $fields["inv_num_max"] = [
                $data["inv_num_max"], PDO::PARAM_INT];
        }

        if (! empty($data["inv_num_prefix"]))
        {
            $fields["inv_num_prefix"] = [
                $data["inv_num_prefix"], PDO::PARAM_STR];
        }

        if (! empty($data["inv_num_year_ct"]))
        {
            $fields["inv_num_year_ct"] = [
                $data["inv_num_year_ct"], PDO::PARAM_INT];
        }

        if (! empty($data["active"]))
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

            $sql ="UPDATE store"
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
//
// Delete
//
    public function delete(string $id): int
    {
        $sql = "DELETE FROM store WHERE id = :id";
        $stmt = $this->conn->prepare($sql);
        $stmt->bindValue(":id", $id, PDO::PARAM_INT);
        $stmt->execute();
        return $stmt->rowCount();
    }

}
//-----------------------------------------------------------------------------------------------//
// store
//
// id
// company_id
// name
// name_2
// address
// address_2
// city
// state
// zip
// tax_rate
// inv_num_prefix_add
// inv_num_max
// inv_num_prefix
// inv_num_year_ct
// active
//id, company_id, name, name_2, address, address_2, city, state, zip, tax_rate, inv_num_prefix_add, inv_num_max, inv_num_prefix, inv_num_year_ct, active