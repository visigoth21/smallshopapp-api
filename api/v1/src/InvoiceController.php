<?php 
//-----------------------------------------------------------------------------------------------//
class InvoiceController
{
    private $_returnObject;
    private $_PathHTTP;

    public function __construct(private InvoiceGateway $gateway)
    {
        $this->_returnObject = new Response;
        $this->_PathHTTP = new PathHTTP;
    }
//-----------------------------------------------------------------------------------------------//
public function processRequest(string $method, ?string $id): void
{   
    $id = $this->_PathHTTP->getId() ?? null;
    $secondary = $this->_PathHTTP->getSecondLevel() ?? "";    
    
    //echo $this->_PathHTTP->getMethod();

    if ($id === null)
    {
        if ($method == "GET") {
            if ($secondary == "line")
            {
                $data = $this->gateway->getAllLines();
                $this->respondSuccess($data, "Returned all Invoices");
            }
            else
            {
                $data = $this->gateway->getAll();
                $this->respondSuccess($data, "Returned all Invoices");
            }

        } elseif ($method == "POST") {
            $data = (array) json_decode(file_get_contents("php://input"), true);
            $errors = $this->getValidationErrors($data);

            if (! empty($errors))
            {
                $this->respondUnprocessableEntity($errors);  
                return;  
            }
            if ($secondary == "line")
            {
                $dataRet = $this->gateway->createLine($data);
                $this->respondSuccess($dataRet, "Invoice line created");
            }
            else
            {
                $dataRet = $this->gateway->create($data);
                $this->respondSuccess($dataRet, "Invoice created");
            }
        } else {
            $this->responseMethodNotAllowed("GET, POST");
        }
    }
    else
    {
        if (is_numeric($id)) {
            if ($secondary == "line")
            {
                $item = $this->gateway->getInvoiceLines($id);
            }
            else
            {
                $item = $this->gateway->getByID($id);
            }
            if ($item === false) {
                $this->respondNotFound($id);
                return;
            }

        switch ($method) {
            case "GET":
                $this->respondSuccess($item, "returned invoice");
                break;
            case "PATCH":
                $data = (array) json_decode(file_get_contents("php://input"), true);
                $errors = $this->getValidationErrors($data);
                if (! empty($errors))
                {
                    $this->respondUnprocessableEntity($errors);  
                    return;  
                }
                if ($secondary == "line")
                {
                    $dataRet = $this->gateway->updateLine($id, $data);
                }
                else
                {
                    $dataRet = $this->gateway->update($id, $data);
                }
                if ($dataRet) {
                    $this->respondSuccess($dataRet, "Updated Invoice");
                }
                else
                {
                    $this->respondUnprocessableEntity(["message" => "No line updated"]);
                }
                break;
            case "DELETE":
                if ($secondary == "line")
                {
                    $rows = $this->gateway->deleteLine($id);
                }
                else
                {
                    $rows = $this->gateway->delete($id);
                }
                $this->respondSuccess($item, "Invoice Deleted");
                break;
            default:
                $this->responseMethodNotAllowed("GET, PATCH, DELETE");
            } 
        }
    }
}
//---------------------------------------------------------------------------//
    private function respondSuccess(array $items, string $msg): void
    {
        $this->_returnObject->setHttpStatusCode(200);
        $this->_returnObject->setSuccess(true);
        $this->_returnObject->addMessage($msg);
        $this->_returnObject->setData($items);
        $this->_returnObject->send();
    }
//---------------------------------------------------------------------------//
    private function respondUnprocessableEntity(array $errors): void
    {
        $this->_returnObject->setHttpStatusCode(422);
        $this->_returnObject->setSuccess(false);
        $this->_returnObject->addMessage($errors);
        $this->_returnObject->send();
    }
//---------------------------------------------------------------------------//
    private function responseMethodNotAllowed(string $allowed_methods): void
    {
        $this->_returnObject->setHttpStatusCode(405);
        $this->_returnObject->setSuccess(false);
        $this->_returnObject->allowedMethods($allowed_methods);
        $this->_returnObject->addMessage("Method not allowed");
        $this->_returnObject->send();
    }
//---------------------------------------------------------------------------//
    private function respondNotFound(string $id): void
    {
        $this->_returnObject->setHttpStatusCode(404);
        $this->_returnObject->setSuccess(false);
        $this->_returnObject->addMessage("invoice with $id not found");
        $this->_returnObject->send();
    }
//---------------------------------------------------------------------------//
    private function respondCreated(string $id): void
    {
        $this->_returnObject->setHttpStatusCode(404);
        $this->_returnObject->setSuccess(true);
        $this->_returnObject->addMessage("Invoice created, id = $id");
        $this->_returnObject->send();
    }
//---------------------------------------------------------------------------//
    private function getValidationErrors(array $data, bool $is_new = true): array
    {
        $errors = [];
        // if ($is_new && empty($data["mfg_name"]))
        // {
        //     $errors[] = "Name is required";
        // }
        // if ($is_new && empty($data["mfg"]))
        // {
        //     $errors[] = "Manufacturer code is required";
        // }
        return $errors;
    }
}
//---------------------------------------------------------------------------//
// invoice
// 	1	id Primary	    bigint(20	
// 	2	inv_num	        varchar(10)	
// 	3	inv_tax_rate	decimal(10,2)	
// 	4	inv_store	    int(11)	
// 	5	inv_location	int(11)	
// 	6	inv_customer	int(11)	
//  7   inv_status      int(11)
// 	8	inv_type	    int(11)	
// 	9	inv_date	    datetime
// invoice_lines
// 	1	id Primary	    bigint(20)	
// 	2	invoice	        int(11)	
// 	3	qty	            decimal(10,2)	
// 	4	description	    varchar(300)	
// 	5	price	        decimal(10,2)	
// 	6	type	        int(11)	
// 	7	taxable	        tinyint(1)