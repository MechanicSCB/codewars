<?php

namespace App\Evaluators;


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

    public function __construct(protected Request $request)
    {
        $this->lang = $request['lang'];
        $this->folder = $this->createTempFolder();
        $this->files = json_decode($request['files'], 1);
        $this->commands = json_decode($request['commands'], 1);
    }

    protected function createTempFolder(): string
    {
        if (! file_exists($tmpDir = storage_path("app/tmp"))) {
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
        if($this->commands['compile_cmd']){
            return shell_exec("cd $this->folder && {$this->commands['compile_cmd']}");
        }else{
            return null;
        }
    }

    public function execScriptFile(): string
    {
        if($this->lang === 'clojure'){
            $sourceFolder = Str::beforeLast($this->folder, '/tmp') . '/src/clojure';
            File::copyDirectory($sourceFolder, $this->folder);
        }

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

    public function removeTempFolder(): void
    {
        if (file_exists($this->folder)) {
            (new Filesystem)->cleanDirectory($this->folder);
            rmdir($this->folder);
        }
    }
}
