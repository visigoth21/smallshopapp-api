<?php 

class CustomerGateway
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
    public function getAll(): array 
    {
        $sql = "SELECT * FROM customer ORDER BY id";
        $stmt = $this->conn->query($sql);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
//
// by invoice ID
//    
    public function getByID(string $id): array | false
    {
        $sql = "SELECT * FROM customer WHERE id = :id"; 
        $stmt = $this->conn->prepare($sql);
        $stmt->bindValue(":id", $id, PDO::PARAM_STR);        
        $stmt->execute();
        $rowCount = $stmt->rowCount();
        // create array to store returned items
        $data = [];

        if ($rowCount > 0) {
            $data["invoice"] = $stmt->fetchAll(PDO::FETCH_ASSOC);
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
        $sql = "INSERT INTO customer 
        (first_name, middle_name, last_name, title, suffix, source) 
        VALUES 
        (:first_name, :middle_name, :last_name, :title, :suffix, :source)"; 
        $stmt = $this->conn->prepare($sql);

        $stmt->bindValue(":first_name", $data["first_name"], PDO::PARAM_STR);
        $stmt->bindValue(":middle_name", $data["middle_name"], PDO::PARAM_STR);
        $stmt->bindValue(":last_name", $data["last_name"], PDO::PARAM_STR);
        $stmt->bindValue(":title", $data["title"], PDO::PARAM_STR);
        $stmt->bindValue(":suffix", $data["suffix"], PDO::PARAM_STR);
        $stmt->bindValue(":source", $data["source"], PDO::PARAM_INT); 
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

        //first_name, middle_name, last_name, title, suffix, active

        if (! empty($data["first_name"]))
        {
            $fields["first_name"] = [
                $data["first_name"], PDO::PARAM_STR];
        }

        if (! empty($data["middle_name"]))
        {
            $fields["middle_name"] = [
                $data["middle_name"], PDO::PARAM_STR];
        }

        if (! empty($data["last_name"]))
        {
            $fields["last_name"] = [
                $data["last_name"], PDO::PARAM_STR];
        }

        if (! empty($data["title"]))
        {
            $fields["title"] = [
                $data["title"], PDO::PARAM_STR];
        }

        if (! empty($data["suffix"]))
        {
            $fields["suffix"] = [
                $data["suffix"], PDO::PARAM_STR];
        }

        if (! empty($data["source"]))
        {
            $fields["source"] = [
                $data["source"], PDO::PARAM_INT];
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

            $sql ="UPDATE customer"
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
        $sql = "UPDATE invoice SET active = :active WHERE id = :id";
        $stmt = $this->conn->prepare($sql);
        $stmt->bindValue(":id", $id, PDO::PARAM_INT);
        $stmt->bindValue(":active", false, PDO::PARAM_BOOL);
        $stmt->execute();
        return $stmt->rowCount();
    }

}
// customer
// 	1	id Primary	int(11)
// 	2	first_name	varchar(20)
// 	3	middle_name	varchar(20)	
// 	4	last_name	varchar(20)	
// 	5	title	varchar(10)
// 	6	suffix	varchar(10)	
// 	7	active	tinyint(1)
//id	first_name	middle_name	last_name	title	suffix	source	active	
