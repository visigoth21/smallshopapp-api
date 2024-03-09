<?php 
//-----------------------------------------------------------------------------------------------//
class InvoiceLineController
{
    private $_returnObject;

    public function __construct(private InvoiceLineGateway $gateway)
    {
        $this->_returnObject = new Response;
    }
//-----------------------------------------------------------------------------------------------//
    public function processRequest(string $method, ?string $id): void
    {
        if ($id === null)
        {
            if ($method == "GET") 
            {
                $data = $this->gateway->getAll();
                $this->respondSuccess($data, "Returned all line items");
            } 
            elseif ($method == "POST") 
            {
                $data = (array) json_decode(file_get_contents("php://input"), true);
                $errors = $this->getValidationErrors($data);

                if (! empty($errors))
                {
                    $this->respondUnprocessableEntity($errors);  
                    return;  
                }
                $dataRet = $this->gateway->create($data);
                $this->respondSuccess($dataRet, "Created line item");
            } 
            else 
            {
                $this->responseMethodNotAllowed("GET, POST");
            }
    }
    else
    {
        if (is_numeric($id)) {
            $item = $this->gateway->getByID($id);
        }
        
        if ($item === false) {
            $this->respondNotFound($id);
            return;
        }

        switch ($method) {
            case "GET":
                $this->respondSuccess($item, "returned line item $id");
                break;
            case "PATCH":
                $data = (array) json_decode(file_get_contents("php://input"), true);
                $errors = $this->getValidationErrors($data);
                if (! empty($errors))
                {
                    $this->respondUnprocessableEntity($errors);  
                    return;  
                }
                    $dataRet = $this->gateway->update($id, $data);
                if ($dataRet) {
                    $this->respondSuccess($dataRet, "Updated line item");
                }
                else
                {
                    $this->respondUnprocessableEntity(["message" => "No line updated"]);
                }
                break;
            case "DELETE":
                $dataRet = $this->gateway->delete($id);                
                $this->respondSuccess($dataRet, "Deleted line item $id");
                break;
            default:
                $this->responseMethodNotAllowed("GET, PATCH, DELETE");
        }
    }
}
//-----------------------------------------------------------------------------------------------//
    private function respondSuccess(array $items, string $msg): void
    {
        $this->_returnObject->setHttpStatusCode(200);
        $this->_returnObject->setSuccess(true);
        $this->_returnObject->addMessage($msg);
        $this->_returnObject->setData($items);
        $this->_returnObject->send();
    }
//-----------------------------------------------------------------------------------------------//
    private function respondUnprocessableEntity(array $errors): void
    {
        $this->_returnObject->setHttpStatusCode(422);
        $this->_returnObject->setSuccess(false);
        $this->_returnObject->addMessage($errors);
        $this->_returnObject->send();
    }
//-----------------------------------------------------------------------------------------------//
    private function responseMethodNotAllowed(string $allowed_methods): void
    {
        $this->_returnObject->setHttpStatusCode(405);
        $this->_returnObject->setSuccess(false);
        $this->_returnObject->allowedMethods($allowed_methods);
        $this->_returnObject->addMessage("Method not allowed");
        $this->_returnObject->send();
    }
//-----------------------------------------------------------------------------------------------//
    private function respondNotFound(string $id): void
    {
        $this->_returnObject->setHttpStatusCode(404);
        $this->_returnObject->setSuccess(false);
        $this->_returnObject->addMessage("invoice with $id not found");
        $this->_returnObject->send();
    }
//-----------------------------------------------------------------------------------------------//
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
//-----------------------------------------------------------------------------------------------//// // invoice_lines
// 	#	Name	Type	Collation	Attributes	Null	Default	Comments	Extra	Action
// 	1	id Primary	bigint(20)	
// 	2	qty	        decimal(10,2)	
// 	3	description	varchar(300)	
// 	4	price	    decimal(10,2)	
// 	5	type	    int(11)	
// 	6	taxable	    tinyint(1)
