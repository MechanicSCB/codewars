<?php


use Illuminate\Filesystem\Filesystem;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Str;

class LangEvaluator
{
    public string $folder;
    protected string $lang;
    protected array $files;
    protected array $commands;

    public function __construct(protected $request)
    {
        $this->lang = $request['lang'];
        $this->folder = $this->createTempFolder();
        $this->files = json_decode($request['files'], 1);
        $this->commands = json_decode($request['commands'], 1);
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

    public function saveSolutionFile()
    {
        file_put_contents("$this->folder/{$this->files['solution']['filepath']}", $this->files['solution']['code']);
    }

    public function saveScriptFile()
    {
        file_put_contents("$this->folder/{$this->files['script']['filepath']}", $this->files['script']['code']);
    }

    public function compileScriptFile(): ?string
    {
        if($this->lang === 'cpp'){
            $sourceFolder = explode('/tmp', $this->folder )[0] . '/src/cpp';

            $this->recurseCopy($sourceFolder, $this->folder);
        }

        if($this->commands['compile_cmd']){
            return shell_exec("cd $this->folder && {$this->commands['compile_cmd']}");
        }else{
            return null;
        }
    }

    public function execScriptFile(): string
    {
        $execCmd = str_replace('CURRENT_FOLDER', $this->folder,$this->commands['exec_cmd']);

        return shell_exec("cd $this->folder && $execCmd");
    }

    public function getPhpOutput(): string
    {
        include "$this->folder/solution.php";

        $attempts = json_decode($this->files['script']['code'], 1);

        $output = [];

        foreach ($attempts as $attempt) {
            $output[] = $attempt['name'](...$attempt['args']);
        }

        return json_encode($output);
    }

    public function removeTempFolder($dir = null): void
    {
        shell_exec("rm -rf $this->folder");
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
