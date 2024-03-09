<?php 
//-----------------------------------------------------------------------------------------------//
class SearchGateway
{
    private PDO $conn;
    // private int $pageno;
    // private int $perpage;
    // private int $offset;

//-----------------------------------------------------------------------------------------------//
    public function __construct(Database $database)
    {
        $this->conn = $database->getConnection();
        // $this->perpage = $perPage;
        // $this->pageno = $page;
        // $this->offset = ($page-1) * $perPage;
    }
//-----------------------------------------------------------------------------------------------//
    public function getAll(): array 
    {
        $sql = "SELECT * FROM pricelist ORDER BY part_number";
        $stmt = $this->conn->query($sql);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
//-----------------------------------------------------------------------------------------------//    
    public function getByID(string $id): array | false
    {
        $sql = "SELECT DISTINCT * FROM pricelist WHERE pricelist.part_number = :id OR pricelist.upc = :id"; 

        $stmt = $this->conn->prepare($sql);
        $stmt->bindValue(":id", $id, PDO::PARAM_STR_CHAR);        
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
}
//-----------------------------------------------------------------------------------------------//

// id
// part_number
// upc
// description
// mfg
// list
// cost
// supersedes_to
// weight
