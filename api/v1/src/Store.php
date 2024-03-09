<?php
//---------------------------------------------------------------------------//
class Store
{
    private PDO $conn;
    private int $_store_id;
    private int $_company_id;
    private string $_name;
    private string $_name_2;
    private string $_address;
    private string $_address_2;
    private string $_city;
    private string $_state;
    private string $_zip;
    private string $_tax_rate;
    private bool $_inv_num_prefix_add;
    private int $_inv_num_max;
    private string $_inv_num_prefix;
    private int $_inv_num_year_ct;
    private bool $_active;
    private array $_data;

    public function __construct(Database $database, string $store_id)
    {
        $this->conn = $database->getConnection();
        $this->_data = $this->loadStore($store_id);
    }
//
//
//  
    public function getStoreID(): int
    {
        return $this->_store_id;
    }

    public function getCompanyID(): int
    {
        return $this->_company_id;
    }
 
    public function getName(): string
    {
        return $this->_name;
    }

    public function getName2(): string
    {
        return $this->_name_2;
    }

    public function getAddress(): string
    {
        return $this->_address;
    }

    public function getAddress2(): string
    {
        return $this->_address_2;
    }

    public function getCity(): string
    {
        return $this->_city;
    }

    public function getState(): string
    {
        return $this->_state;
    }

    public function getZip(): string
    {
        return $this->_zip;
    }

    public function getTaxRate(): string
    {
        return $this->_tax_rate;
    }

    public function getAddInvoicePrefix(): bool
    {
        return $this->_inv_num_prefix_add;
    }

    public function getInvoiceNumMax(): int
    {
        return $this->_inv_num_max;
    }

    public function getInvoiceNumPrefix(): string
    {
        return $this->_inv_num_prefix;
    }

    public function getInvoiceYearCount(): int
    {
        return $this->_inv_num_year_ct;
    }

    public function getIsCompanyActive(): bool
    {
        return $this->_active;
    }
//
//
//
    private function loadStore(int $id): array | false
    {
        $sql = "SELECT * FROM store WHERE id = :id"; 
        $stmt = $this->conn->prepare($sql);
        $stmt->bindValue(":id", $id, PDO::PARAM_STR);        
        $stmt->execute();
        //$rowCount = $stmt->rowCount();
        // create array to store returned items
        $this->_data = $stmt->fetch(PDO::FETCH_ASSOC);

        $this->_store_id = $this->_data["id"];
        $this->_company_id = $this->_data["company_id"];
        $this->_name = $this->_data["name"];
        $this->_name_2 = $this->_data["name_2"];
        $this->_address = $this->_data["address"];
        $this->_address_2 = $this->_data["address_2"];
        $this->_city = $this->_data["city"];
        $this->_state = $this->_data["state"];
        $this->_zip = $this->_data["zip"];
        $this->_tax_rate = $this->_data["tax_rate"];
        $this->_inv_num_prefix_add = $this->_data["inv_num_prefix_add"];
        $this->_inv_num_max = $this->_data["inv_num_max"];

        if ($this->_data["inv_num_prefix"] == "year") {
            $currentDate = new DateTime();
            $year = $currentDate->format("y");  
            $this->_inv_num_prefix = $year;
        }
        else
        {   $this->_inv_num_prefix = $this->_data["inv_num_prefix"];    }
        
        $this->_inv_num_year_ct = $this->_data["inv_num_year_ct"];
        $this->_active = $this->_data["active"];

        return $this->_data;
    }
    // function to return task object as an array for json
    public function returnStoreAsArray(): array | false
    {
        return $this->_data;
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
//---------------------------------------------------------------------------//
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










