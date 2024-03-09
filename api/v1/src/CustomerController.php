<?php 
//-----------------------------------------------------------------------------------------------//
class CustomerController
{
    private $_returnObject;

    public function __construct(private CustomerGateway $gateway)
    {
        $this->_returnObject = new Response;
    }
//-----------------------------------------------------------------------------------------------//
public function processRequest(string $method, ?string $id): void
{    
    if ($id === null)
    {
        if ($method == "GET") {
            $data = $this->gateway->getAll();
            $this->respondSuccess($data, "Returned all Customers");
        } elseif ($method == "POST") {
            $data = (array) json_decode(file_get_contents("php://input"), true);
            $errors = $this->getValidationErrors($data);

            if (! empty($errors))
            {
                $this->respondUnprocessableEntity($errors);  
                return;  
            }
            $dataRet = $this->gateway->create($data);
            $this->respondSuccess($dataRet, "Customer created");

        } else {
            $this->responseMethodNotAllowed("GET, POST");
        }
    }
    else
    {
        if (is_numeric($id)) {
            $item = $this->gateway->getByID($id);
            if ($item === false) {
                $this->respondNotFound($id);
                return;
            }

            switch ($method) {
                case "GET":
                    $this->respondSuccess($item, "Returned Customer");
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
                        $this->respondSuccess($dataRet, "Updated Customer");
                    }
                    else
                    {
                        $this->respondUnprocessableEntity(["message" => "No Customer updated"]);
                    }
                    break;
                case "DELETE":
                    $rows = $this->gateway->delete($id);
                    $this->respondSuccess($item, "Customer Deleted");
                    break;
                default:
                    $this->responseMethodNotAllowed("GET, PATCH, DELETE");
            } 
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
        $this->_returnObject->addMessage("Customer with $id not found");
        $this->_returnObject->send();
    }
//-----------------------------------------------------------------------------------------------//
    private function respondCreated(string $id): void
    {
        $this->_returnObject->setHttpStatusCode(404);
        $this->_returnObject->setSuccess(true);
        $this->_returnObject->addMessage("Customer created, id = $id");
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
//-----------------------------------------------------------------------------------------------//