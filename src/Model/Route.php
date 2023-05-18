<?php

namespace Donchev\Framework\Model;

use DateTime;

class Route
{
    /** @var int */
    public $id;

    /** @var string */
    public $name;

    /** @var string|null */
    public $map;

    /** @var string */
    public $gpx_url;

    /** @var string */
    public $strava_url;

    /** @var string|null */
    public $gpx_file;

    /** @var string|null */
    public $note;

    /** @var int */
    public $difficulty;

    /** @var double */
    public $length;

    /** @var int */
    public $ascent;

    /** @var int */
    public $is_race;

    /** @var DateTime */
    public $created_at;

    /** @var DateTime */
    public $updated_at;

    public function getId(): int
    {
        return $this->id;
    }

    public function setId(int $id)
    {
        $this->id = $id;
    }

    public function getName(): string
    {
        return $this->name;
    }

    public function setName(string $name)
    {
        $this->name = $name;
    }

    public function getMapUrl(): string
    {
        return $this->map;
    }

    public function setMapUrl(?string $map)
    {
        $this->map = $map;
    }

    public function getGpxUrl(): string
    {
        return $this->gpx_url;
    }

    public function setGpxUrl(string $gpx_url)
    {
        $this->gpx_url = $gpx_url;
    }

    public function getStravaUrl(): ?string
    {
        return $this->strava_url;
    }

    public function setStravaUrl(?string $strava_url)
    {
        $this->strava_url = $strava_url;
    }

    public function getGpxFileName(): ?string
    {
        return $this->gpx_file;
    }

    public function setGpxFileName(?string $gpx_file)
    {
        $this->gpx_file = $gpx_file;
    }

    public function getNote(): ?string
    {
        return $this->note;
    }

    public function setNote(?string $note)
    {
        $this->note = $note;
    }

    public function getDifficulty(): int
    {
        return $this->difficulty;
    }

    public function setDifficulty(int $difficulty)
    {
        $this->difficulty = $difficulty;
    }

    public function getLength(): float
    {
        return $this->length;
    }

    public function setLength(float $length)
    {
        $this->length = $length;
    }

    public function getAscent(): int
    {
        return $this->ascent;
    }

    public function setAscent(int $ascent)
    {
        $this->ascent = $ascent;
    }

    public function getIsRace(): int
    {
        return $this->is_race;
    }

    public function setIsRace(int $is_race)
    {
        $this->is_race = $is_race;
    }

    public function getCreatedAt(): DateTime
    {
        return new DateTime($this->created_at);
    }

    public function setCreatedAt(DateTime $created_at)
    {
        $this->created_at = $created_at;
    }

    public function getUpdatedAt(): DateTime
    {
        return new DateTime($this->updated_at);
    }

    public function setUpdatedAt(DateTime $updated_at)
    {
        $this->updated_at = $updated_at;
    }

    public function isNew(): bool
    {
        return $this->getCreatedAt() > date_create('7 days ago');
    }

    public function getDifficultyClass(): string
    {
        $bg = 'bg-';
        switch ($this->getDifficulty()) {
            case 1:
            case 2:
            case 3:
                return $bg . 'success';

            case 4:
            case 5:
                return $bg . 'info';

            case 6:
            case 7:
            case 8:
                return $bg . 'warning';

            case 9:
            case 10:
                return $bg . 'danger';

            default:
                return '';
        }
    }
}