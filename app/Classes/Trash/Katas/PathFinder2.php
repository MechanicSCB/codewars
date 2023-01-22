<?php


namespace App\Classes\Trash\Katas;


class PathFinder2
{
    protected array $curPos = [0, 0];
    protected array $dirs = ['w', 's', 'e', 'n'];
    protected string $curDir = 's';
    protected array $nextPos;
    protected array $exitPos;

    public function __construct(protected array $maze)
    {
        $this->exitPos = [count($this->maze[0]) - 1, count($this->maze) - 1];
    }

    public function run()
    {
        if ($this->maze[0][1] !== '.' && $this->maze[1][0] !== '.') {
            return false;
        }

        $goBackCount = 0;

        while (true) {
            // делаем шаг
            foreach ($this->dirs as $dir) {
                // если клетка свободна -> идём
                if (count($nextCoords = $this->isWay($dir))) {
                    // шагаем
                    $this->curPos = $nextCoords;
                    $this->shiftDirs();

                    if ($this->curPos === [0, 0]) {
                        $goBackCount++;
                    }

                    break;
                }
            }

            // если дважды вернулись в [0,0] return false
            if ($goBackCount > 1) {
                return false;
            }

            // если дошли до выхода возвращаем true
            if ($this->curPos === $this->exitPos) {
                return true;
            }
        }
    }

    function shiftDirs()
    {
        if ($this->curDir === 's') {
            $this->dirs = ['w', 's', 'e', 'n'];
        }
        if ($this->curDir === 'e') {
            $this->dirs = ['s', 'e', 'n', 'w'];
        }
        if ($this->curDir === 'n') {
            $this->dirs = ['e', 'n', 'w', 's'];
        }
        if ($this->curDir === 'w') {
            $this->dirs = ['n', 'w', 's', 'e'];
        }
    }

    function isWay(string $dir): array
    {
        if ($dir === 's') {
            $nextCoords = [$this->curPos[0] + 1, $this->curPos[1]];
        }
        if ($dir === 'w') {
            $nextCoords = [$this->curPos[0], $this->curPos[1] - 1];
        }
        if ($dir === 'n') {
            $nextCoords = [$this->curPos[0] - 1, $this->curPos[1]];
        }
        if ($dir === 'e') {
            $nextCoords = [$this->curPos[0], $this->curPos[1] + 1];
        }

        if (@$this->maze[$nextCoords[0]][$nextCoords[1]] === '.') {
            $this->curDir = $dir;

            return $nextCoords;
        }

        return [];
    }

}













