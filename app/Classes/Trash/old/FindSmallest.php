<?php


namespace App\Classes\Trash;


class FindSmallest
{
    private string $strNumber;
    private ?int $wrongPos = null;
    private ?int $wrongVal = null;
    private ?string $tail = null;

    public function __construct(private $number)
    {
        $this->strNumber = (string)$number;
    }

    public function getSmallest()
    {
        // находим позицию перемещения
        // индекс где нарушается возрастание цифр
        $this->wrongPos = $this->getWrongPos();
        $this->wrongVal = $this->getWrongVal();
        $this->tail = $this->getTail();

        // Определяем тип

        // если входящее число уже минимально и перестановки не требуются
        if ($this->isAlreadySmallest()) {
            return [$this->number, 0, 0];
        }

        // если следующая за текущей цифра равна минимальной из последующих,
        // то перемещаем текущую перед ближайшей равной или большей в хвосте
        // или в самый конец если цифра больше всех в хвосте
        // $fromPos = $wrongPos
        if ($this->isMoveToTail()) {
            return $this->moveToTail();
        }

        // в остальных случаях находим минимальную цифру в хвосте
        // и перемещаем её на текущую позицию
        // $toPos = $wrongPos
        return $this->moveFromTail();
    }

    private function moveToTail(): array
    {
        // get destination pos
        $destPos = $this->getDestinationPos();

        $smallest = +$this->moveDigit($this->wrongPos, $destPos);

        df(tmr(@$this->start),$this->number, $smallest, $this->wrongPos, $this->wrongVal, $destPos);


        return [$smallest, $this->wrongPos, $destPos];
    }

    private function moveFromTail(): array
    {
        // get destination pos
        $targetPos = $this->getTargetPos();
        $smallest = +$this->moveDigit($targetPos, $this->wrongPos);

        return [$smallest, $targetPos, $this->wrongPos];
    }

    private function moveDigit(int $from, int $to): string
    {
        $number = $this->strNumber;
        $insertVal = $number[$from];

        //fetch target
        $number = substr($number, 0, $from) . substr($number, $from + 1);

        // insert digit to destination
        $number = substr($number, 0, $to) . $insertVal . substr($number, $to);

        return $number.'';
    }

    private function getWrongPos(): ?int
    {
        $wrongPos = null;
        $strNumber = $this->strNumber;
        $sort = str_split($strNumber);
        sort($sort);

        foreach ($sort as $key => $digit) {
            if ($digit == $strNumber[$key]) {
                continue;
            }

            $wrongPos = $key;
            break;
        }

        return $wrongPos;
    }

    private function getWrongVal(): ?int
    {
        return @$this->strNumber[$this->wrongPos];
    }

    private function getTail(): string
    {
        return substr($this->strNumber, $this->wrongPos + 1);
    }

    private function isAlreadySmallest(): bool
    {
        return $this->wrongPos === null;
    }

    // если следующая за текущей цифра равна минимальной из последующих,
    // то перемещаем текущую перед ближайшей равной или большей в хвосте
    // $fromPos = $wrongPos
    private function isMoveToTail()
    {
        $next = @$this->strNumber[$this->wrongPos + 1];

        //df(tmr(@$this->start), $this->number, $this->wrongPos, $this->wrongVal, $this->tail, $this->wrongVal, $next, min($this->getTailMinValues()));

        return $next === min($this->getTailMinValues());
    }

    // return array of max values with it's positions (indexes in strNumber)
    private function getTailMaxValues(): array
    {
        $arrTail = str_split($this->tail);
        $tailMaxValues = array_filter($arrTail, fn($v) => $v === max($arrTail));

        return $tailMaxValues;
    }

    // return array of max values with it's positions (indexes in strNumber)
    private function getTailMinValues(): array
    {
        $arrTail = str_split($this->tail);
        $tailMinValues = array_filter($arrTail, fn($v) => $v === min($arrTail));

        return $tailMinValues;
    }

    private function getDestinationPos(): ?int
    {
        $destPos = null;
        $tailBiggerValues = $this->getTailBiggerValues();

        krsort($tailBiggerValues);

        foreach ($tailBiggerValues as $pos => $v) {
            if (! isset($tailBiggerValues[$pos - 1])) {
                $destPos = $pos;
                break;
            }
        }

        //$destPos ??= strlen($this->tail);
        df(tmr(@$this->start), $tailBiggerValues, $destPos);

        return $destPos + $this->wrongPos;
    }

    private function getTargetPos(): ?int
    {
        $tailMinValures = $this->getTailMinValues();
        krsort($tailMinValures);

        foreach ($tailMinValures as $pos => $val) {
            if (! isset($tailMinValures[$pos - 1])) {
                $targetPos = $pos;
                break;
            }
        }

        return $targetPos  + $this->wrongPos + 1;
    }

    private function getTailBiggerValues(): array
    {
        $wrong = $this->wrongVal;
        $arrTail = str_split($this->tail);

        for ($i = $wrong; $i <= 9; $i++) {
            if (count($tailBiggerValues = array_filter($arrTail, fn($v) => +$v === $i))) {
                break;
            }
        }

        return $tailBiggerValues;
    }
}
