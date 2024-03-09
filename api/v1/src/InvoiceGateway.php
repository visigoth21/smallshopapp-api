<?php 
//---------------------------------------------------------------------------//
class InvoiceGateway
{
    private PDO $conn;
    private $_InvoiceLine;
    private $_Store;
    private int $pageno;
    private int $perpage;
    private int $offset;
//---------------------------------------------------------------------------//
    public function __construct(Database $database)
    {
        $this->conn = $database->getConnection();
        $this->_InvoiceLine = new InvoiceLineGateway($database);
        $this->_Store = new Store($database, 1);
        // $this->perpage = $perPage;
        // $this->pageno = $page;
    }
//---------------------------------------------------------------------------//
    public function getInvoiceFromLine(string $id): int | false
    {
        $sql = "SELECT invoice FROM invoice_lines WHERE id = :id"; 
        $stmt = $this->conn->prepare($sql);
        $stmt->bindValue(":id", $id, PDO::PARAM_STR);        
        $stmt->execute();
        $retVal = $stmt->fetch();
        return $retVal["invoice"];
    }
//---------------------------------------------------------------------------//
    public function getAllLines(): array | false
    {
        $sql = "SELECT * FROM invoice_lines ORDER BY id";
        $stmt = $this->conn->query($sql);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
//---------------------------------------------------------------------------//    
    public function getLineByID(string $id): array | false
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
//---------------------------------------------------------------------------//  
    public function getInvoiceLines(string $id): array | false
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

//
// get All Inactive
//
    public function getAllInactive(): array | false
    {
        $sql = "SELECT * FROM invoice where inv_active = 0 ORDER BY inv_num ";
        $stmt = $this->conn->query($sql);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
//
// get All Active
//
    public function getAllActive(): array | false 
    {
        $sql = "SELECT * FROM invoice where inv_active = 1 ORDER BY inv_num ";
        $stmt = $this->conn->query($sql);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
//
// get All
//
    public function getAll(): array | false 
    {
        $sql = "SELECT * FROM invoice ORDER BY inv_num ";
        $stmt = $this->conn->query($sql);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
//
// by invoice ID
//    
    public function getByID(string $id): array | false
    {
        $Qval = $this->invoiceRecalc($id);
        $sql = "SELECT * FROM invoice WHERE id = :id"; 
        $stmt = $this->conn->prepare($sql);
        $stmt->bindValue(":id", $id, PDO::PARAM_STR);        
        $stmt->execute();
        $rowCount = $stmt->rowCount();
        // create array to store returned items
        $data = [];

        if ($rowCount > 0) {
            $data["invoice"] = $stmt->fetchAll(PDO::FETCH_ASSOC);
            $data["lines"][] = $this->_InvoiceLine->getByInvoice($id);
        } else {
            $data = false;
        }
        return $data;
    }
//
// by invoice number
//    
    public function getByNum(string $id): array | false
    {
        $sql = "SELECT * FROM invoice WHERE id_num = :id"; 
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
// Create an Invoice
//
    public function create(array $data): array | false
    {
        $retVal = $this->insert($data);

        $preInv = $this->_Store->getInvoiceNumPrefix();
        $str_length = $this->_Store->getInvoiceNumMax();
        $str = substr("0000{$retVal}", -$str_length);
        $invData = [];
        if ($preInv = "year")
        {
            $retStr = date("y")."-".$str;
            $invData["inv_num"] = $retStr;    
        }
        else
        {
            $retStr = $preInv."-".$str;
            $invData["inv_num"] = $retStr;
        }

        $retNum = $this->update($retVal, $invData);

        if ($retNum > 0)
        {        
            $retInvoice = [];
            $retInvoice = $this->getByID($retVal);
            return  $retInvoice;
        }
        else {
            return false;    
        }
    }
//
//
//
    public function insert(array $data): string
    {
        $sql = "INSERT INTO invoice 
        (inv_tax_rate, inv_company, inv_location, inv_customer, inv_type) 
        VALUES 
        (:inv_tax_rate, :inv_company, :inv_location, :inv_customer, :inv_type)"; 
        $stmt = $this->conn->prepare($sql);

        $stmt->bindValue(":inv_tax_rate", $this->_Store->getTaxRate(), PDO::PARAM_STR);
        $stmt->bindValue(":inv_company", $this->_Store->getCompanyID(), PDO::PARAM_INT);
        $stmt->bindValue(":inv_location", $this->_Store->getStoreID(), PDO::PARAM_INT);
        $stmt->bindValue(":inv_customer", 0, PDO::PARAM_INT);
        $stmt->bindValue(":inv_type", 1, PDO::PARAM_INT);    

        $stmt->execute();

        return $this->conn->lastInsertId();
    }
//
// Update
//
    public function update(string $id, array $data): array | false
    {
        $fields = [];

        if (! empty($data["inv_num"]))
        {
            $fields["inv_num"] = [
                $data["inv_num"], PDO::PARAM_STR];
        }

        if (! empty($data["inv_tax_rate"]))
        {
            $fields["inv_tax_rate"] = [
                $data["inv_tax_rate"], PDO::PARAM_STR];
        }

        if (! empty($data["inv_company"]))
        {
            $fields["inv_company"] = [
                $data["inv_company"], PDO::PARAM_INT];
        }

        if (! empty($data["inv_location"]))
        {
            $fields["inv_location"] = [
                $data["inv_location"], PDO::PARAM_INT];
        }

        if (! empty($data["inv_customer"]))
        {
            $fields["inv_customer"] = [
                $data["inv_customer"], PDO::PARAM_INT];
        }

        if (! empty($data["inv_type"]))
        {
            $fields["inv_type"] = [
                $data["inv_type"], PDO::PARAM_INT];
        }
        
        if (! empty($data["inv_active"]))
        {
            $fields["inv_active"] = [
                $data["inv_active"], PDO::PARAM_BOOL];
        }

        if (empty($fields)) {
            return 0;
        } else {

            $sets = array_map(function($value) {
                return "$value = :$value";
            }, array_keys($fields));

            $sql ="UPDATE invoice"
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
//---------------------------------------------------------------------------//
    public function createLine(array $data): array | false
    {
        if ($this->is_multi_array($data))
        {
            foreach ($data as $line) {
                $retVal = $this->insertLine($line);
            }
        }
        else {
            $retVal = $this->insertLine($data);
        }
        $retInvoice = [];
        $retInvoice = $this->getByInvoice($retVal);
        return $retInvoice;
    }
//---------------------------------------------------------------------------//
    private function insertLine(array $data): string
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
//---------------------------------------------------------------------------//
    public function updateLine(string $id, array $data): array | false
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
            $fields["type"] = [$data["type"], PDO::PARAM_INT];
        }
        if (array_key_exists("taxable", $data))
        {
            $fields["taxable"] = [$data["taxable"], PDO::PARAM_BOOL];
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
//
// Delete
//
    public function delete(string $id): int
    {
        $sql = "UPDATE invoice SET inv_active = :inv_active WHERE id = :id";
        $stmt = $this->conn->prepare($sql);
        $stmt->bindValue(":id", $id, PDO::PARAM_INT);
        $stmt->bindValue(":inv_active", false, PDO::PARAM_BOOL);
        $stmt->execute();
        return $stmt->rowCount();
    }
//---------------------------------------------------------------------------//
    public function deleteLine(string $id): array | false
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
    private function pagnation(string $sql): bool
    {

        $stmt = $this->conn->query($sql);
        //return $stmt->fetchAll(PDO::FETCH_ASSOC);        
        // $total_rows = mysqli_fetch_array($result)[0];
        // $total_pages = ceil($total_rows / $no_of_records_per_page);

        $this->offset = ($this->pageno-1) * $this->perpage;
        return true;
    }
//---------------------------------------------------------------------------//
    private function invoiceRecalc(string $id) : bool
    {
        $sql = "UPDATE invoice SET inv_total_taxable = :inv_total_taxable, 
            inv_total_nontaxable = :inv_total_nontaxable 
            WHERE id = :id";
        $stmt = $this->conn->prepare($sql);
        $stmt->bindValue(":id", $id, PDO::PARAM_INT);
        $stmt->bindValue(":inv_total_taxable", $this->invoiceTaxableTotal($id), PDO::PARAM_STR);
        $stmt->bindValue(":inv_total_nontaxable", $this->invoiceNontaxableTotal($id), PDO::PARAM_STR);
        $stmt->execute();

        return true;
    }
//---------------------------------------------------------------------------//
    private function invoiceTaxableTotal(string $id) : string
    {
        $sql = "SELECT SUM(qty * price) orderTotal FROM invoice_lines 
            WHERE invoice = :id AND taxable = true";
        $stmt = $this->conn->prepare($sql);
        $stmt->bindValue(":id", $id, PDO::PARAM_INT);
        $stmt->execute();
        $retVal = $stmt->fetch();

        return $this->zero_null($retVal["orderTotal"]);

    }
//---------------------------------------------------------------------------//
    private function invoiceNontaxableTotal(string $id) : string
    {
        $sql = "SELECT SUM(qty * price) orderTotal FROM invoice_lines 
            WHERE invoice = :id AND taxable = false";
        $stmt = $this->conn->prepare($sql);
        $stmt->bindValue(":id", $id, PDO::PARAM_INT);
        $stmt->execute();
        $retVal = $stmt->fetch();
        return $this->zero_null($retVal["orderTotal"]);
    }
//---------------------------------------------------------------------------//
    private function is_multi_array( $arr ): bool
    {
        rsort( $arr );
        return isset( $arr[0] ) && is_array( $arr[0] );
    }

    private function zero_null(?string $sentVal) : string
    {
        if (is_null($sentVal))
        {
            $sentVal = 0;
        }
        return $sentVal;
    }
}
//---------------------------------------------------------------------------//
// invoice
// 	1	id Primary	    bigint(20	
// 	2	inv_num	        int(10)	
// 	3	inv_tax_rate	decimal(10,2)	
// 	4	inv_company	    int(11)	
// 	5	inv_location	int(11)	
// 	6	inv_customer	int(11)	
//  7   inv_status      int(11)
// 	8	inv_type	    int(11)	
// 	9	inv_date	    datetime
// 10   inv_active      bool
// invoice_lines
// 	1	id Primary	    bigint(20)	
// 	2	qty	            decimal(10,2)	
// 	3	description	    varchar(300)	
// 	4	price	        decimal(10,2)	
// 	5	type	        int(11)	
// 	6	taxable	        tinyint(1)