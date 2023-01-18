<?php

namespace Donchev\Framework\Model;

use DateTime;
use Exception;

class Route
{
    /** @var int */
    private $id;

    /** @var string */
    private $name;

    /** @var string|null */
    private $mapUrl;

    /** @var string */
    private $gpxUrl;

    /** @var string|null */
    private $gpxFileName;

    /** @var string|null */
    private $note;

    /** @var int */
    private $difficulty;

    /** @var double */
    private $length;

    /** @var int */
    private $ascent;

    /** @var DateTime */
    private $createdAt;

    /** @var DateTime */
    private $updatedAt;

    /**
     * @throws Exception
     */
    public function __construct(array $data)
    {
        $this->setId($data['id']);
        $this->setName($data['name']);
        $this->setMapUrl($data['map']);
        $this->setGpxUrl($data['gpx_url']);
        $this->setGpxFileName($data['gpx_file']);
        $this->setNote($data['note']);
        $this->setDifficulty($data['difficulty']);
        $this->setLength($data['length']);
        $this->setAscent($data['ascent']);
        $this->setCreatedAt(new DateTime($data['created_at']));
        $this->setUpdatedAt(new DateTime($data['updated_at']));
    }

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
        return $this->mapUrl;
    }

    public function setMapUrl(?string $mapUrl)
    {
        $this->mapUrl = $mapUrl;
    }

    public function getGpxUrl(): string
    {
        return $this->gpxUrl;
    }

    public function setGpxUrl(string $gpxUrl)
    {
        $this->gpxUrl = $gpxUrl;
    }

    public function getGpxFileName(): ?string
    {
        return $this->gpxFileName;
    }

    public function setGpxFileName(?string $gpxFileName)
    {
        $this->gpxFileName = $gpxFileName;
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

    public function getCreatedAt(): DateTime
    {
        return $this->createdAt;
    }

    public function setCreatedAt(DateTime $createdAt)
    {
        $this->createdAt = $createdAt;
    }

    public function getUpdatedAt(): DateTime
    {
        return $this->updatedAt;
    }

    public function setUpdatedAt(DateTime $updatedAt)
    {
        $this->updatedAt = $updatedAt;
    }

    public function isNew(): bool
    {
        return $this->createdAt > date_create('7 days ago');
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