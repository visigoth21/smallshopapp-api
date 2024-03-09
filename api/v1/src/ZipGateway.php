<?php 
//////
class ZipGateway
{
    private PDO $conn;
//////
    public function __construct(Database $database)
    {
        $this->conn = $database->getConnection();
    }
//////
    public function getAllZipCode(): array | false
    {
        // $sql = "SELECT * FROM zip_city_state ORDER BY zip_code";
        // $stmt = $this->conn->query($sql);
        // return $stmt->fetchAll(PDO::FETCH_ASSOC);
        return false;
    }
//////    
    public function getByZipCode(string $zip_code): array | false
    {
        switch (strlen($zip_code)) {
            case "2":
                $SQLpart = "Left(zip_code, 2)";
                break;
            case "3":
                $SQLpart = "Left(zip_code, 3)";
                break;
            case "4":
                $SQLpart = "Left(zip_code, 4)";
                break;
            case "5":
                $SQLpart = "zip_code";
                break;
        }        
        if (strlen($zip_code) >= 2 ) {


            $sql = "SELECT * FROM zip_city_state 
            WHERE ".$SQLpart." = :zip_code ORDER BY zip_code" ; 
            $stmt = $this->conn->prepare($sql);
            $stmt->bindValue(":zip_code", $zip_code, PDO::PARAM_STR);        
            $stmt->execute();
            $rowCount = $stmt->rowCount();
        }
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
//
}
////
// zip_city_state
// 1	id	        int(11)	
// 2	sid	        int(11)
// 3	state	    varchar(2)	
// 4	city	    varchar(20)	
// 5	type	    varchar(20)	
// 6	zip_code	varchar(20)	
