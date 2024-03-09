<?php 
//-----------------------------------------------------------------------------------------------//
class SearchController
{
//-----------------------------------------------------------------------------------------------//
    private $returnData = array();

//-----------------------------------------------------------------------------------------------//
    public function __construct(private SearchGateway $gateway)
    {
    }
//-----------------------------------------------------------------------------------------------//
    public function processRequest(string $method, ?string $id): void
    {
        if ($id === null)
        {
            if ($method == "GET") {
                echo json_encode($this->gateway->getAll());
            } elseif ($method == "POST") {
                echo "create";
            } else {
                $this->responseMethodNotAllowed("GET, POST");
            }
        }
        else
        {
            $item = $this->gateway->getByID($id);
            
            if ($item === false) {
                $this->respondNotFound($id);
                return;
            }

            switch ($method) {
                case "GET":                    
                    $this->respondSuccess($item);
                    break;
                case "PATCH":
                    echo "Update $id";
                    break;
                case "DELETE":
                    echo "Delete $id";
                    break;
                default:
                    $this->responseMethodNotAllowed("GET, PATCH, DELETE");
            }
        }
    }
//-----------------------------------------------------------------------------------------------//
private function respondSuccess(array $items): void
    {
        $_returnObject = new Response;
        $_returnObject->setHttpStatusCode(200);
        $_returnObject->setSuccess(true);
        $_returnObject->setData($items);
        $_returnObject->send();
    }
    //-----------------------------------------------------------------------------------------------//
private function responseMethodNotAllowed(string $allowed_methods): void
    {
        $_returnObject = new Response;
        $_returnObject->setHttpStatusCode(405);
        $_returnObject->setSuccess(false);
        $_returnObject->allowedMethods($allowed_methods);
        $_returnObject->addMessage("Method not allowed");
        $_returnObject->send();
    }
//-----------------------------------------------------------------------------------------------//
    private function respondNotFound(string $id): void
    {
        http_response_code(404);
        echo json_encode(["message" => "Item with Part Number or UPC code $id not found"]);
    }
}
//-----------------------------------------------------------------------------------------------//