<?php


namespace App\Runners;


use Illuminate\Filesystem\Filesystem;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

abstract class LangRunner
{
    protected string $folder;
    protected string $lang;
    protected string $compileCmd;
    protected string $cmdName;
    protected string $ext;
    protected array $attempts;
    protected string $solutionCode;
    protected string $solutionFileName = 'solution';
    protected string $scriptFileName = 'script';

    protected string $separator = "|separator|";
    protected bool $hasSolutionFile = true;
    protected bool $needCompile = false;

    public function __construct(protected Request $request)
    {
        $this->lang = $request['lang'];
        $this->folder = $this->createTempFolder();
        $this->solutionCode = $request['code'];
        $this->attempts = json_decode($request['attempts'], 1);
        $this->compileCmd ??= $this->lang . 'c';
        $this->cmdName ??= $this->lang;
    }

    protected function createTempFolder(): string
    {
        if(! file_exists($tmpDir = storage_path("app/tmp"))){
            mkdir($tmpDir);
        }

        $temp = Str::uuid();
        $dir = "$tmpDir/$temp";

        if (mkdir($dir)) {
            return $dir;
        } else {
            return "error";
        }
    }

    public function getSolutionCode(): string
    {
        return $this->request->code;
    }

    public function saveSolutionToFile(string $code)
    {
        if ($this->hasSolutionFile) {
            file_put_contents("$this->folder/$this->solutionFileName.$this->ext", $code);
        }
    }

    public function saveScriptToFile(string $code)
    {
        file_put_contents("$this->folder/$this->scriptFileName.$this->ext", $code);
    }

    public function compileScriptFile(): ?string
    {
        if($this->needCompile){
            return shell_exec("cd $this->folder && $this->compileCmd $this->scriptFileName.$this->ext 2>&1");
        }else{
            return null;
        }
    }

    public function execScriptFile(): string
    {
        return shell_exec("cd $this->folder && $this->cmdName $this->scriptFileName.$this->ext 2>&1");
    }

    public function handleRawOutput(string $shellOutput): array
    {
        $separator = $this->getSeparatorToExplode();
        $output = explode($separator, $shellOutput);
        $output = array_map('trim', $output);

        // clean null array last element after explode if it's not an error
        if (str_contains($shellOutput, $this->separator)) {
            unset($output[count($output) - 1]);
        }

        foreach ($output as $itemKey => &$item) {
            // skip ERROR to json (return null) decoding
            if (! str_contains($shellOutput, $this->separator)) {
                continue;
            }

            $item = $this->convertItem($item, $itemKey);
        }

        return $output;
    }

    protected function getSeparatorToExplode(): string
    {
        return $this->separator;
    }

    public function removeTempFolder(): void
    {
        if(file_exists($this->folder)){
            (new Filesystem)->cleanDirectory($this->folder);
            rmdir($this->folder);
        }
    }

    public function removeTempFolderNativePhp($dir = null): void
    {
        $dir ??= $this->folder;

        if (substr($dir, strlen($dir) - 1, 1) != '/') {
            $dir .= '/';
        }

        $files = glob($dir . '*', GLOB_MARK);

        foreach ($files as $file) {
            if (is_dir($file)) {
                $this->removeTempFolderNativePhp($file);
            } else {
                unlink($file);
            }
        }

        rmdir($dir);
    }

    protected function convertItem(mixed $item, int $itemKey)
    {
        if($item === 'True'){
            $item = 'true';
        }

        if($item === 'False'){
            $item = 'false';
        }

        $decoded = json_decode($item, 1);

        if($decoded === null && is_string(@$this->attempts[$itemKey]['expected'])) {
            $decoded = json_decode("\"$item\"", 1);
        }

        return $decoded;
    }
}
