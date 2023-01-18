<?php

namespace Donchev\Framework\Repository;

use MeekroDB;

class Repository
{
    /**
     * @var MeekroDB
     */
    private $db;

    public function __construct(MeekroDB $db)
    {
        $this->db = $db;
    }

    public function getAllRoutes(): ?array
    {
        return $this->db->query('SELECT * FROM route r ORDER BY r.id DESC');
    }

    public function getRoutePerId(int $id): ?array
    {
        return $this->db->query('SELECT * FROM route r WHERE r.id = %i', $id);
    }

    public function getUserPerUsername(string $username): ?array
    {
        return $this->db->queryFirstRow('SELECT * FROM user u WHERE u.username = %s', $username);
    }
}
