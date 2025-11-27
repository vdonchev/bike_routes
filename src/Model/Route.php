<?php

namespace Donchev\Framework\Model;

use DateTime;
use Exception;

class Route
{
    /** @var int */
    public int $id;

    /** @var string */
    public string $name;

    /** @var string|null */
    public ?string $map;

    /** @var string */
    public string $gpx_url;

    /** @var ?string */
    public ?string $strava_url;

    /** @var string|null */
    public ?string $gpx_file;

    /** @var string|null */
    public ?string $note;

    /** @var int */
    public int $difficulty;

    /** @var double */
    public float $length;

    /** @var int */
    public int $ascent;

    /** @var int */
    public int $is_race;

    /** @var string */
    public string $created_at;

    /** @var string */
    public string $updated_at;

    public function getId(): int
    {
        return $this->id;
    }

    public function setId(int $id): void
    {
        $this->id = $id;
    }

    public function getName(): string
    {
        return $this->name;
    }

    public function setName(string $name): void
    {
        $this->name = $name;
    }

    public function getMapUrl(): string
    {
        return $this->map;
    }

    public function setMapUrl(?string $map): void
    {
        $this->map = $map;
    }

    public function getGpxUrl(): string
    {
        return $this->gpx_url;
    }

    public function setGpxUrl(string $gpx_url): void
    {
        $this->gpx_url = $gpx_url;
    }

    public function getStravaUrl(): ?string
    {
        return $this->strava_url;
    }

    public function setStravaUrl(?string $strava_url): void
    {
        $this->strava_url = $strava_url;
    }

    public function getGpxFileName(): ?string
    {
        return $this->gpx_file;
    }

    public function setGpxFileName(?string $gpx_file): void
    {
        $this->gpx_file = $gpx_file;
    }

    public function getNote(): ?string
    {
        return $this->note;
    }

    public function setNote(?string $note): void
    {
        $this->note = $note;
    }

    public function getDifficulty(): int
    {
        return $this->difficulty;
    }

    public function setDifficulty(int $difficulty): void
    {
        $this->difficulty = $difficulty;
    }

    public function getLength(): float
    {
        return $this->length;
    }

    public function setLength(float $length): void
    {
        $this->length = $length;
    }

    public function getAscent(): int
    {
        return $this->ascent;
    }

    public function setAscent(int $ascent): void
    {
        $this->ascent = $ascent;
    }

    public function getIsRace(): int
    {
        return $this->is_race;
    }

    public function setIsRace(int $is_race): void
    {
        $this->is_race = $is_race;
    }

    /**
     * @throws Exception
     */
    public function getCreatedAt(): DateTime
    {
        return new DateTime($this->created_at);
    }

    public function setCreatedAt(DateTime $created_at): void
    {
        $this->created_at = $created_at;
    }

    /**
     * @throws Exception
     */
    public function getUpdatedAt(): DateTime
    {
        return new DateTime($this->updated_at);
    }

    public function setUpdatedAt(DateTime $updated_at): void
    {
        $this->updated_at = $updated_at;
    }

    /**
     * @throws Exception
     */
    public function isNew(): bool
    {
        return $this->getCreatedAt() > date_create('7 days ago');
    }

    public function getDifficultyClass(): string
    {
        $bg = 'bg-';
        return match ($this->getDifficulty()) {
            1, 2, 3 => $bg . 'success',
            4, 5 => $bg . 'info',
            6, 7, 8 => $bg . 'warning',
            9, 10 => $bg . 'danger',
            default => '',
        };
    }
}