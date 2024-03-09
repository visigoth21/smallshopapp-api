<?php 
//-----------------------------------------------------------------------------------------------//
class InvoiceLineGateway
{
    private PDO $conn;
//-----------------------------------------------------------------------------------------------//
    public function __construct(Database $database)
    {
        $this->conn = $database->getConnection();
    }
//-----------------------------------------------------------------------------------------------//
    public function getInvoiceFromLine(string $id): int | false
    {
        $sql = "SELECT invoice FROM invoice_lines WHERE id = :id"; 
        $stmt = $this->conn->prepare($sql);
        $stmt->bindValue(":id", $id, PDO::PARAM_STR);        
        $stmt->execute();
        $retVal = $stmt->fetch();
        return $retVal["invoice"];
    }
//-----------------------------------------------------------------------------------------------//
    public function getAll(): array | false
    {
        $sql = "SELECT * FROM invoice_lines ORDER BY id";
        $stmt = $this->conn->query($sql);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
//-----------------------------------------------------------------------------------------------//    
    public function getByID(string $id): array | false
    {
        $sql = "SELECT * FROM invoice_lines WHERE id = :id"; 
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
    public function getByInvoice(string $id): array | false
        {
            $sql = "SELECT * FROM invoice_lines WHERE invoice = :id"; 
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
    public function create(array $data): array | false
    {
        if ($this->is_multi_array($data))
        {
            foreach ($data as $line) {
                $retVal = $this->insert($line);
            }
        }
        else {
            $retVal = $this->insert($data);
        }
        $retInvoice = [];
        $retInvoice = $this->getByInvoice($retVal);
        return $retInvoice;
    }
//-----------------------------------------------------------------------------------------------//
    private function insert(array $data): string
    {

        $sql = "INSERT INTO invoice_lines 
        (invoice, qty, description, price, type, taxable) 
        VALUES 
        (:invoice, :qty, :description, :price, :type, :taxable)"; 
        $stmt = $this->conn->prepare($sql);
        $invoice =$data["invoice"];

        $stmt->bindValue(":invoice", $invoice, PDO::PARAM_INT);

        if (empty($data["qty"])) {
            $stmt->bindValue(":qty", "1", PDO::PARAM_STR);
        } else {
            $stmt->bindValue(":qty", $data["qty"], PDO::PARAM_STR);
        }
        
        $stmt->bindValue(":description", $data["description"], PDO::PARAM_STR);

        if (empty($data["price"])) {
            $stmt->bindValue(":price", "0.00", PDO::PARAM_STR);
        } else {
            $stmt->bindValue(":price", $data["price"], PDO::PARAM_STR);
        }

        if (empty($data["type"])) {
            $stmt->bindValue(":type", "1", PDO::PARAM_STR);
        } else {
            $stmt->bindValue(":type", $data["type"], PDO::PARAM_STR);
        }

        if (empty($data["active"])) {
            $stmt->bindValue(":taxable", $data["taxable"] ?? true, PDO::PARAM_BOOL);    
        }
        $stmt->execute();

        return $invoice;
    }
//-----------------------------------------------------------------------------------------------//
public function update(string $id, array $data): array | false
{
    $fields = [];

    if (! empty($data["invoice"])) {
       $fields["invoice"] = [$data["invoice"], PDO::PARAM_INT ];   
    }

    if (! empty($data["qty"])) {
       $fields["qty"] = [$data["qty"], PDO::PARAM_STR];    
    }

    if (! empty($data["description"])) {   
        $fields["description"] = [$data["description"], PDO::PARAM_STR];  
    }

    if (! empty($data["price"])) {   
        $fields["price"] = [$data["price"], PDO::PARAM_STR];    
    }

    if (! empty($data["type"]))
    {
        $fields["type"] = [
            $data["type"],
            PDO::PARAM_INT
        ];
    }
    if (array_key_exists("taxable", $data))
    {
        $fields["taxable"] = [
            $data["taxable"],
            PDO::PARAM_BOOL
        ];
    }

    if (empty($fields)) {
        return 0;
    } else {

        $sets = array_map(function($value) {
            return "$value = :$value";
        }, array_keys($fields));

        $sql ="UPDATE invoice_lines" . " SET " . implode(", ", $sets) . " WHERE id = :id";

        $stmt = $this->conn->prepare($sql);
        $stmt->bindValue(":id", $id, PDO::PARAM_INT);
        
        foreach ($fields as $name => $values) {
            $stmt->bindValue(":$name", $values[0], $values[1]);
        }

        $stmt->execute();
        $retInvoice = [];
        $retInvoice = $this->getByInvoice($this->getInvoiceFromLine($id)); 

        if (!empty($retInvoice)) {
            return $retInvoice;
        }
        else
        {   
            return false;
        }
        
    }
}
//-----------------------------------------------------------------------------------------------//
    public function delete(string $id): array | false
    {
        $Invoice = $this->getInvoiceFromLine($id);
        $sql = "DELETE FROM invoice_lines WHERE id = :id";
        $stmt = $this->conn->prepare($sql);
        $stmt->bindValue(":id", $id, PDO::PARAM_INT);
        $stmt->execute();
        $retInvoice = [];
        $retInvoice = $this->getByInvoice($Invoice); 

        if (!empty($retInvoice)) {
            return $retInvoice;
        }
        else
        {   
            return false;
        }
    }
//---------------------------------------------------------------------------//
    private function is_multi_array( $arr ): bool
    {
        rsort( $arr );
        return isset( $arr[0] ) && is_array( $arr[0] );
    }
//-----------------------------------------------------------------------------------------------//
}
//-----------------------------------------------------------------------------------------------//
// invoice_lines
// 	1	id Primary	    bigint(20)	
// 	2	invoice	        int(11)
// 	3	qty	            decimal(10,2)	
// 	4	description	    varchar(300)	
// 	5	price	        decimal(10,2)	
// 	6	type	        int(11)	
// 	7	taxable	        tinyint(1)