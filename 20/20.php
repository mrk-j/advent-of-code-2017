<?php

ini_set('memory_limit', '4G');

$input = file_get_contents('input.txt');

$lines = explode(PHP_EOL, $input);

class Coordinate
{
    public $x, $y, $z;

    function __construct(int $x, int $y, int $z)
    {
        $this->x = $x;
        $this->y = $y;
        $this->z = $z;
    }

    function move(Coordinate $move)
    {
        $this->x += $move->x;
        $this->y += $move->y;
        $this->z += $move->z;
    }

    function distance()
    {
        return abs($this->x) + abs($this->y) + abs($this->z);
    }

    function __toString()
    {
        return $this->x . ',' . $this->y . ',' . $this->z;
    }
}

class Particle
{
    public $position, $velocity, $acceleration;
    public $distances = [];

    function __construct(Coordinate $position, Coordinate $velocity, Coordinate $acceleration)
    {
        $this->position = $position;
        $this->velocity = $velocity;
        $this->acceleration = $acceleration;
    }

    function move()
    {
        $this->velocity->move($this->acceleration);
        $this->position->move($this->velocity);

        $this->distances[] = $this->position->distance();
    }

    function meanDistance()
    {
        return array_sum($this->distances) / count($this->distances);
    }
}

$particles = [];
$backupParticles = [];

foreach ($lines as $id => $line) {
    preg_match('/p=<(.+?),(.+?),(.+?)>, v=<(.+?),(.+?),(.+?)>, a=<(.+?),(.+?),(.+?)>/', $line, $matches);

    array_shift($matches);

    $matches = array_map('intval', $matches);

    list($pX, $pY, $pZ, $vX, $vY, $vZ, $aX, $aY, $aZ) = $matches;

    $particles[] = new Particle(
        new Coordinate($pX, $pY, $pZ),
        new Coordinate($vX, $vY, $vZ),
        new Coordinate($aX, $aY, $aZ)
    );

    $backupParticles[] = new Particle(
        new Coordinate($pX, $pY, $pZ),
        new Coordinate($vX, $vY, $vZ),
        new Coordinate($aX, $aY, $aZ)
    );
}

# Part 1

foreach (range(1, 500) as $i) {
    foreach ($particles as $particle) {
        $particle->move();
    }
}

$distances = array_map(function (Particle $particle) {
    return $particle->meanDistance();
}, $particles);

asort($distances);

foreach ($distances as $key => $distance) {
    echo 'The particle that stays the closest is ' . $key . PHP_EOL;

    break;
}

# Part 2

$particles = $backupParticles;

function position(&$particle)
{
    $particle = (string)$particle->position;
}

foreach (range(1, 500) as $i) {
    foreach ($particles as $particle) {
        $particle->move();
    }

    $coordinates = $particles;

    array_walk($coordinates, 'position');

    $coordinateCounts = array_count_values($coordinates);
    $keysToRemove = [];

    foreach ($particles as $key => $particle) {
        if ($coordinateCounts[(string)$particle->position] > 1) {
            $keysToRemove[] = $key;
        }
    }

    foreach($keysToRemove as $key) {
        unset($particles[$key]);
    }
}

echo 'The number of particles left after all collisions are ' . count($particles) . PHP_EOL;