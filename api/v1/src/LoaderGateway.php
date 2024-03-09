<?php
//-----------------------------------------------------------------------------------------------//
class LoaderGateway
{
    private PDO $conn;
    //-----------------------------------------------------------------------------------------------//
    public function __construct(Database $database)
    {
        $this->conn = $database->getConnection();
    }
    //-----------------------------------------------------------------------------------------------//    
    public function loadLine(
        string $part,
        string $upc,
        string $desc,
        string $mfg,
        string $list,
        string $cost,
        string $supersedes,
        string $weight
    ) { //  id, part_number, upc, description, mfg, list, cost, supersedes_to, weight, part_html	

        $sql = "INSERT INTO pricelist (part_number, upc, description, mfg, list, cost, supersedes_to, weight)
            VALUES (:vpart, :vupc, :vdesc, :vmfg, :vlist, :vcost, :vsupersedes, :vweight)
            ON DUPLICATE KEY UPDATE description = :vdesc, mfg = :vmfg, list = :vlist, cost = :vcost, supersedes_to = :vsupersedes, weight = :vweight";

        $stmt = $this->conn->prepare($sql);
        $stmt->bindValue(":vpart", $part, PDO::PARAM_STR_CHAR);
        $stmt->bindValue(":vupc", $upc, PDO::PARAM_STR_CHAR);
        $stmt->bindValue(":vdesc", $desc, PDO::PARAM_STR_CHAR);
        $stmt->bindValue(":vmfg", $mfg, PDO::PARAM_STR_CHAR);
        $stmt->bindValue(":vlist", $list, PDO::PARAM_STR_CHAR);
        $stmt->bindValue(":vcost", $cost, PDO::PARAM_STR_CHAR);
        $stmt->bindValue(":vsupersedes", $supersedes, PDO::PARAM_STR_CHAR);
        $stmt->bindValue(":vweight", $weight, PDO::PARAM_STR_CHAR);
        $stmt->execute();
        $rowCount = $stmt->rowCount();

        if ($rowCount > 0) {
            return true;
        } else {
            return false;
        }
    }
    //-----------------------------------------------------------------------------------------------//
    public function itemCount(): int
    {
        $sql = "SELECT COUNT(*) as count_lines FROM pricelist;";
        $stmt = $this->conn->prepare($sql);
        $stmt->execute();
        $retVal = $stmt->fetch();
        return $retVal["count_lines"];
    }
    //-----------------------------------------------------------------------------------------------//
}
//-----------------------------------------------------------------------------------------------//
// id, part_number, upc, description, mfg, list, cost, supersedes_to, weight

// a
// b
// c
// d
// e
// f
// g
// h
// i
// j
// k
// l
// m
// n
// o
// p
// q
// r
// s
// t
// u
// v
// w
// x
// y
// z