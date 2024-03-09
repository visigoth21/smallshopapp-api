<?php
//---------------------------------------------------------------------------//
class PathHTTP
{
    private string $path;
    private array $parts;
    private array $bodyData;
    private ?array $queryString;
    private string $method;
    private string $version;
    private string $topLevel;
    private string $secondLevel;
    private string $thirdLevel;
    private ?string $id = null;
    private int $totalLevels;
    //private array $levelList;
    private bool $top = false;
    private bool $second = false;
    private bool $third = false;
    private bool $paging = false;
    private string $pageno;
    private string $perpage;
    private int $totalParts;
//---------------------------------------------------------------------------//
    public function __construct()
    {
        $this->path = parse_url($_SERVER["REQUEST_URI"], PHP_URL_PATH);
        $this->method = $_SERVER["REQUEST_METHOD"];
        $this->parts = explode("/", $this->path);
        $this->bodyData = (array) json_decode(file_get_contents("php://input"), true);
        $this->version = $this->parts[2];  
        parse_str($_SERVER['QUERY_STRING'], $this->queryString);     
        $this->paging = $this->is_paged();
        //$this->levelList = explode(',', $_ENV['LEVEL_LIST']);
        $this->totalParts = sizeof($this->parts);
        $this->load();
    }
//---------------------------------------------------------------------------//

    public function getId(): ?string
    {
        return $this->id;
    }

    public function getMethod(): string
    {
        return $this->method;
    }

    public function getData(): array | false
    {
        return $this->bodyData;
    }

    public function getPath(): string
    {
        return $this->path;
    }

    public function getQueryString(): array | null
    {
        return $this->queryString;
    }

//---------------------------------------------------------------------------//
    public function levelCount(): int
    {
        return $this->totalLevels;
    }
    
    public function TotalParts(): int
    {
        return $this->totalParts;
    }

    public function getParts(): array | false
    {
        return $this->parts;
    }

    public function getVersion(): string
    {
        return $this->version;
    }
//---------------------------------------------------------------------------//   
    public function isTop(): bool
    {
        return $this->top;
    }

    public function isSecond(): bool
    {
        return $this->second;
    }

    public function isThird(): bool
    {
        return $this->third;
    }

    public function getTopLevel(): string
    {
        return $this->topLevel;
    }

    public function getSecondLevel(): string | bool
    {
        return $this->secondLevel;
    }

    public function getThirdLevel(): string | bool
    {
        return $this->thirdLevel;
    }
//---------------------------------------------------------------------------//
        
    public function isPaged(): bool
    {
        return $this->paging;
    }

    public function getPageNumber(): string
    {
        return $this->pageno;
    }

    public function getItemsPerPage(): string
    {
        return $this->perpage;
    }
//---------------------------------------------------------------------------//
    private function load(): void
    {
        
        $partRef = $this->totalParts-1;
        if ($this->is_level($this->parts[$partRef]))
        {
            $this->id = null;
        }
        else
        {
            $this->id = $this->parts[$partRef];
            $this->totalParts = $partRef;
        }

        $this->totalLevels = $this->totalParts -3;
        //$this->totalLevels = $endPart;

        switch ($this->totalLevels) {
            case 1:
                $this->topLevel = $this->parts[3];
                $this->top = true;
                $this->totalLevels = 1;
                $this->secondLevel = "";
                $this->thirdLevel = "";
                break;
            case 2:                
                $this->topLevel = $this->parts[3];
                $this->secondLevel = $this->parts[4];
                $this->top = true;
                $this->second = true;
                $this->totalLevels = 2;
                $this->thirdLevel = "";
                break;
            case 3:
                $this->topLevel = $this->parts[3];
                $this->secondLevel = $this->parts[4];
                $this->thirdLevel = $this->parts[5]; 
                $this->top = true;
                $this->second = true;
                $this->third = true;
                $this->totalLevels = 3;
                break;
        }
    }
//---------------------------------------------------------------------------//
    private function is_paged(): bool
    {
        //$defaultPageSize= 20;

        if (isset($this->queryString['pageno'])) {
            $this->pageno = $this->queryString['pageno'];
            if (isset($this->queryString['perpage'])) {
                $this->perpage = $this->queryString['perpage'];
            } else {
                $this->perpage = $_ENV['DEFAULT_PAGE_SIZE'];
            }
            return true;
        }
        return false;
    }
//---------------------------------------------------------------------------//
    private function is_level(string $level_name): bool
    {
        if (in_array($level_name, explode(',', $_ENV['LEVEL_LIST'])))
        {
            return true;
        }
        else
        {
            return false;
        }
    }
}
//---------------------------------------------------------------------------//