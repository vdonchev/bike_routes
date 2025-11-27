<?php

namespace Donchev\Framework\Repository;

use DateInterval;
use DateTime;
use Donchev\Framework\Model\Route;
use MeekroDB;
use MeekroDBException;

class Repository
{
    private ?MeekroDB $db = null;

    public function __construct(MeekroDB $db)
    {
        $this->db = $db;
    }

    /**
     * @throws MeekroDBException
     */
    public function getAllRoutes(): array
    {
        $pdo = $this->db->get();
        $result = $pdo->query('SELECT * FROM route r ORDER BY r.id DESC');

        $all = [];
        while ($staff = $result->fetchObject(Route::class)) {
            $all[] = $staff;
        }

        return $all;
    }

    /**
     * @throws MeekroDBException
     */
    public function getAllRaceRoutes(): array
    {
        $pdo = $this->db->get();
        $result = $pdo->query('SELECT * FROM route r WHERE r.is_race = 1 ORDER BY r.id DESC');

        $all = [];
        while ($staff = $result->fetchObject(Route::class)) {
            $all[] = $staff;
        }

        return $all;
    }

    /**
     * @param int $id
     * @return Route|null
     * @throws MeekroDBException
     */
    public function getRoutePerId(int $id): ?Route
    {
        $pdo = $this->db->get();
        $stmt = $pdo->prepare('SELECT * FROM route r WHERE r.id = :id');
        $stmt->execute(['id' => $id]);

        $obj = $stmt->fetchObject(Route::class);
        return $obj instanceof Route ? $obj : null;
    }

    /**
     * @throws MeekroDBException
     */
    public function getAllLatestRoutes(int $count = 3): array
    {
        $pdo = $this->db->get();
        $result = $pdo->query("SELECT * FROM route r ORDER BY r.created_at DESC LIMIT $count");

        $all = [];
        while ($staff = $result->fetchObject(Route::class)) {
            $all[] = $staff;
        }

        return $all;
    }

    public function getUserPerUsername(string $username): ?array
    {
        return $this->db->queryFirstRow('SELECT * FROM user u WHERE u.username = %s', $username);
    }

    public function getUserPerId(int $userId): ?array
    {
        return $this->db->queryFirstRow('SELECT * FROM user u WHERE u.id = %i', $userId);
    }

    public function removeAllTokensPerUser(int $userId)
    {
        return $this->db->query("DELETE FROM token WHERE user_id=%i", $userId);
    }

    public function removeTokenPerUser(int $userId, string $token)
    {
        return $this->db->query("DELETE FROM token WHERE user_id=%i AND token = %s", $userId, $token);
    }

    /**
     * @throws MeekroDBException
     */
    public function updateUserPassword(int $userId, string $newPasswordHash): void
    {
        $this->db->update('user', ['password' => $newPasswordHash], 'id=%i', $userId);
    }

    public function addMedia(string $file, int $userId, int $routeId): void
    {
        $this->db->insert('media', [
            'file' => $file,
            'user_id' => $userId,
            'route_id' => $routeId,
        ]);
    }

    public function getMediaPerRouteId(int $routeId): ?array
    {
        return $this->db->query(
            'SELECT m.*, u.name FROM media m JOIN user u ON m.user_id = u.id WHERE m.route_id = %i ORDER BY m.created_at DESC'
            ,
            $routeId
        );
    }

    public function getMediaRowPerId(int $mediaId): ?array
    {
        return $this->db->queryFirstRow(
            'SELECT * FROM media m WHERE m.id = %i'
            ,
            $mediaId
        );
    }

    public function deleteMedia(int $mediaId, int $authorId): bool
    {
        return $this->db->query("DELETE FROM media WHERE id=%i AND user_id=%i", $mediaId, $authorId);
    }

    public function getAllSubscribers(): ?array
    {
        return $this->db->query(
            'SELECT u.email, u.name FROM subscriber s JOIN user u on s.user_id = u.id WHERE s.is_subscribed = 1'
        );
    }

    public function getAllSubscribersButCurrentUser(int $currentUserId): ?array
    {
        return $this->db->query(
            'SELECT u.email, u.name FROM subscriber s JOIN user u on s.user_id = u.id WHERE s.is_subscribed = 1 AND u.id != %i',
            $currentUserId
        );
    }

    public function storeToken(string $token, int $userId): void
    {
        $this->db->insert('token', [
            'token' => $token,
            'user_id' => $userId,
            'expiry' => (new DateTime())->add(new DateInterval('P90D')),
        ]);
    }

    public function getToken(string $token): ?array
    {
        return $this->db->queryFirstRow('SELECT * FROM token t WHERE t.token = %s', $token);
    }

    public function addRoute(
        string $name,
        string $map,
        string $gpx,
        string $url,
        int $difficulty,
        float $length,
        int $ascent,
        string $note = null,
        string $strava = null,
        bool $race = false
    ) {
        $this->db->insert('route', [
            'name' => $name,
            'map' => $map,
            'gpx_url' => $url,
            'strava_url' => $strava,
            'gpx_file' => $gpx,
            'note' => $note,
            'difficulty' => $difficulty,
            'length' => $length,
            'ascent' => $ascent,
            'is_race' => $race,
        ]);

        return $this->db->insert_id;
    }
}
