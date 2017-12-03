<?php

class Memory
{
    protected $grid = [];
    protected $pos = [
        'x' => 0,
        'y' => 0
    ];
    protected $direction = 'r'; // r, u, l, d

    public function __construct($max = 10, $stressTest = false)
    {
        for ($i = 1; $i <= $max; $i++) {
            if ($stressTest) {
                $value = $this->getAdjecentSum();
            } else {
                $value = $i;
            }

            if ($stressTest && $value > $max) {
                echo "The first value higher than {$max} is {$value}" . PHP_EOL;

                break;
            }

            if (isset($this->grid[$this->pos['x']])) {
                $this->grid[$this->pos['x']][$this->pos['y']] = $value;
            } else {
                $this->grid[$this->pos['x']] = [$this->pos['y'] => $value];
            }

            if ($this->direction === 'r') {
                $newPos = $this->getPosition(1, 0);
            } elseif ($this->direction === 'u') {
                $newPos = $this->getPosition(0, -1);
            } elseif ($this->direction === 'l') {
                $newPos = $this->getPosition(-1, 0);
            } elseif ($this->direction === 'd') {
                $newPos = $this->getPosition(0, 1);
            } else {
                $newPos = $this->getPosition();
            }

            $this->pos = $newPos;

            if ($this->direction === 'r' && !$this->isPositionAtOffsetFilled(0, -1)) {
                $this->direction = 'u';
            } elseif ($this->direction === 'u' && !$this->isPositionAtOffsetFilled(-1, 0)) {
                $this->direction = 'l';
            } elseif ($this->direction === 'l' && !$this->isPositionAtOffsetFilled(0, 1)) {
                $this->direction = 'd';
            } elseif ($this->direction === 'd' && !$this->isPositionAtOffsetFilled(1, 0)) {
                $this->direction = 'r';
            }
        }
    }

    protected function isPositionAtOffsetFilled($offsetX = 0, $offsetY = 0)
    {
        return $this->isPositionFilled([
            'x' => $this->pos['x'] + $offsetX,
            'y' => $this->pos['y'] + $offsetY,
        ]);
    }

    protected function isPositionFilled($pos)
    {
        return isset($this->grid[$pos['x']][$pos['y']]);
    }

    protected function getValueForPosition($pos)
    {
        if ($this->isPositionFilled($pos)) {
            return $this->grid[$pos['x']][$pos['y']];
        }

        return false;
    }

    protected function getAdjecentSum()
    {
        $sum = 0;

        foreach (range(-1, 1) as $offsetX) {
            foreach (range(-1, 1) as $offsetY) {
                $sum += $this->getValueForPosition([
                    'x' => $this->pos['x'] + $offsetX,
                    'y' => $this->pos['y'] + $offsetY,
                ]);
            }
        }

        return max(1, $sum);
    }

    protected function getPosition($offsetX = 0, $offsetY = 0)
    {
        return [
            'x' => $this->pos['x'] + $offsetX,
            'y' => $this->pos['y'] + $offsetY,
        ];
    }

    public function getLocationFor($value)
    {
        foreach ($this->grid as $x => $column) {
            foreach ($column as $y => $field) {
                if ($field === $value) {
                    return [
                        'x' => $x,
                        'y' => $y,
                    ];
                }
            }
        }

        return false;
    }
}

$memory = new Memory(312051);

$location = $memory->getLocationFor(312051);

echo 'The number of steps to the location is ' . (abs($location['x']) + abs($location['y'])) . PHP_EOL;

$memory = new Memory(312051, true);