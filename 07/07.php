<?php

$input = file_get_contents('input.txt');

$lines = explode("\n", $input);

$allChildren = [];
$allParents = [];

foreach ($lines as $line) {
    $parts = explode('->', $line);

    $parentParts = explode('(', $parts[0]);
    $parent = trim($parentParts[0]);

    $allParents[] = $parent;

    if (count($parts) > 1) {
        $children = array_map('trim', explode(',', $parts[1]));

        $allChildren = array_merge($allChildren, $children);
    }
}

$topParent = array_values(array_diff($allParents, $allChildren))[0];

echo 'The parent program is ' . $topParent . PHP_EOL;

class Program
{
    public $name;
    public $weight;
    public $totalWeight;
    public $children = [];

    public function __construct($name = null)
    {
        $this->name = $name;
    }

    public function findChildren($lines)
    {
        foreach ($lines as $line) {
            $parts = explode('->', $line);
            $parentParts = explode('(', $parts[0]);
            $parent = trim($parentParts[0]);

            if ($parent === $this->name) {
                if (count($parts) > 1) {
                    $children = array_map('trim', explode(',', $parts[1]));

                    foreach ($children as $child) {
                        $this->children[] = (new Program($child))->findWeight($lines)->findChildren($lines);
                    }
                }
            }
        }

        return $this;
    }

    public function findWeight($lines)
    {
        foreach ($lines as $line) {
            $parts = explode('->', $line);
            $parentParts = explode('(', $parts[0]);
            $parent = trim($parentParts[0]);

            if ($parent === $this->name) {
                $this->weight = intval(str_replace(')', '', $parentParts[1]));
            }
        }

        return $this;
    }

    public function calculateWeight()
    {
        $weight = $this->weight;

        $childWeights = [];

        foreach ($this->children as $child) {
            if (is_null($child->totalWeight)) {
                $child->calculateWeight();
            }

            $childWeights[] = $child->totalWeight;
        }

        if (count(array_unique($childWeights)) > 1) {
            $weights = array_count_values($childWeights);

            foreach ($weights as $weight => $count) {
                if ($count === 1) {
                    $differentWeight = $weight;
                } else {
                    $correctWeight = $weight;
                }
            }

            $differentChild = array_filter($this->children, function ($child) use ($differentWeight) {
                return $child->totalWeight === $differentWeight;
            });

            $differentChild = array_pop($differentChild);

            $correctWeight = $differentChild->weight - ($differentWeight - $correctWeight);

            echo 'The program weighs ' . $differentChild->weight . ', but should weigh ' . $correctWeight . PHP_EOL;

            die();
        }

        $weight += array_sum($childWeights);

        $this->totalWeight = $weight;

        return $weight;
    }

    public function outputProgram($indent = 0)
    {
        echo str_repeat(' ', $indent * 3);

        echo '- ' . $this->name . ' / ' . $this->weight . ' / ' . $this->totalWeight . PHP_EOL;

        $indent++;

        foreach ($this->children as $child) {
            $child->outputProgram($indent);
        }
    }
}

$topProgram = (new Program($topParent))->findWeight($lines);
$topProgram->findChildren($lines);
$topProgram->calculateWeight();