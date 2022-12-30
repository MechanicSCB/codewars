<?php


abstract class LangRunner
{
    protected string $separator = "|separator|";
    protected string $lang;
    protected string $folder;
    protected string $solutionCode;
    protected array $attempts;
    protected string $ext;
    protected string $solutionFileName = 'solution';

    public function __construct(protected array $request)
    {
        $this->lang = $request['lang'];
        $this->folder = $this->createTempFolder();
        $this->solutionCode = $request['code'];
        $this->attempts = json_decode($request['attempts'], 1);
    }

    public function saveSolutionToFile(string $code)
    {
        file_put_contents("$this->folder/$this->solutionFileName.$this->ext", $code);
    }

    public function saveScriptToFile(string $code)
    {
        file_put_contents("$this->folder/script.$this->ext", $code);
    }

    public function compileScriptFile(): ?string
    {
        return null;
    }

    public function execScriptFile(): string
    {
        return shell_exec("cd $this->folder && ./a.out 2>&1");
    }

    public function handleRawOutput(string $shellOutput): array
    {
        $output = explode($this->separator, $shellOutput);
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

    protected function convertItem(mixed $item, int $itemKey)
    {
        if($item === 'True'){
            $item = 'true';
        }

        if($item === 'False'){
            $item = 'false';
        }

        $decoded = json_decode($item, 1);

        if($decoded === null && is_string($this->attempts[$itemKey]['expected'])) {
            $decoded = json_decode("\"$item\"", 1);
        }

        return $decoded;
    }

    protected function createTempFolder(): string
    {
        $temp = 'temp_'.rand(0,9999999);
        $dir = "/var/www/html/tmp";

        if(! file_exists($dir)){
            mkdir($dir);
        }

        $dir .= "/$temp";

        if (mkdir($dir)) {
            return $dir;
        } else {
            return "error";
        }
    }

    public function removeTempFolder($dir = null): void
    {
        shell_exec("rm -rf $this->folder");
    }

    public function removeTempFolderNativePhp($dir = null): void
    {
        // TODO remove only this folder but not parent tmp
        //$dir ??= $this->folder;
        $dir ??= "/var/www/html/tmp";

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

    public function recurseCopy(
        string $sourceDirectory,
        string $destinationDirectory,
        string $childFolder = ''
    ): void {
        $directory = opendir($sourceDirectory);

        if (is_dir($destinationDirectory) === false) {
            mkdir($destinationDirectory);
        }

        if ($childFolder !== '') {
            if (is_dir("$destinationDirectory/$childFolder") === false) {
                mkdir("$destinationDirectory/$childFolder");
            }

            while (($file = readdir($directory)) !== false) {
                if ($file === '.' || $file === '..') {
                    continue;
                }

                if (is_dir("$sourceDirectory/$file") === true) {
                    $this->recurseCopy("$sourceDirectory/$file", "$destinationDirectory/$childFolder/$file");
                } else {
                    copy("$sourceDirectory/$file", "$destinationDirectory/$childFolder/$file");
                }
            }

            closedir($directory);

            return;
        }

        while (($file = readdir($directory)) !== false) {
            if ($file === '.' || $file === '..') {
                continue;
            }

            if (is_dir("$sourceDirectory/$file") === true) {
                $this->recurseCopy("$sourceDirectory/$file", "$destinationDirectory/$file");
            }
            else {
                copy("$sourceDirectory/$file", "$destinationDirectory/$file");
            }
        }

        closedir($directory);
    }

}
