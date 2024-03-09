<?php 
//---------------------------------------------------------------------------//
declare(strict_types=1);

require __DIR__ . "/bootstrap.php";

$HTTP_Path = new PathHTTP();
$resource = $HTTP_Path->getTopLevel();
$id = $HTTP_Path->getId();

//---------------------------------------------------------------------------//
$database = new Database($_ENV["DB_HOST"], $_ENV["DB_NAME"], $_ENV["DB_USER"], 
    $_ENV["DB_PASS"]);        
$storeDatabase = new Database($_ENV["DB_STORE_HOST"], $_ENV["DB_STORE_NAME"], 
    $_ENV["DB_STORE_USER"], $_ENV["DB_STORE_PASS"]);
//---------------------------------------------------------------------------//

$user_gateway = new UserGateway($database);
$codec = new JWTCodec($_ENV["SECRET_KEY"]);
$auth = new Auth($user_gateway, $codec);

// if (! $auth->authenticateAccessToken()) { exit; }
//$user_id = $auth->getUserID();

//---------------------------------------------------------------------------//

switch ($resource) {
    case "search":
        $search_gateway = new SearchGateway($database);    
        $controller = new SearchController($search_gateway);
        break;
    case "mfgs":
        $mfg_gateway = new MfgGateway($database);    
        $controller = new MfgController($mfg_gateway);
        break;
    case "invoice":
        $invoice_gateway = new InvoiceGateway($storeDatabase);    
        $controller = new InvoiceController($invoice_gateway);
        break;
    case "zip":
        $zip_gateway = new ZipGateway($storeDatabase);    
        $controller = new ZipController($zip_gateway);
        break;
    case "store":
        $store = new Store($storeDatabase, "1"); 
        exit;
        break;
    default:
        sendError("404", "Resource error");
  }

$controller->processRequest($_SERVER["REQUEST_METHOD"], $id);
//---------------------------------------------------------------------------//
function sendError(string $code, string $errorMsg): void
{
    $response = new Response();
    $response->setHttpStatusCode($code);
    $response->setSuccess(false);
    $response->addMessage($errorMsg);
    $response->send();
    exit;
}
//---------------------------------------------------------------------------//
