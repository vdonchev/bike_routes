<?php

namespace Donchev\Framework\Repository;

use DateInterval;
use DateTime;
use MeekroDB;

class Repository
{
    /**
     * @var MeekroDB
     */
    private $db;

    /**
     * @param MeekroDB $db
     */
    public function __construct(MeekroDB $db)
    {
        $this->db = $db;
    }

    /**
     * @return array|null
     */
    public function getAllRoutes(): ?array
    {
        return $this->db->query('SELECT * FROM route r ORDER BY r.id DESC');
    }

    /**
     * @param int $id
     * @return array|null
     */
    public function getRoutePerId(int $id): ?array
    {
        return $this->db->queryFirstRow('SELECT * FROM route r WHERE r.id = %i', $id);
    }

    /**
     * @param string $username
     * @return array|null
     */
    public function getUserPerUsername(string $username): ?array
    {
        return $this->db->queryFirstRow('SELECT * FROM user u WHERE u.username = %s', $username);
    }

    /**
     * @param int $userId
     * @return array|null
     */
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
     * @param int $userId
     * @param string $newPasswordHash
     * @return void
     */
    public function updateUserPassword(int $userId, string $newPasswordHash)
    {
        $this->db->update('user', ['password' => $newPasswordHash], 'id=%i', $userId);
    }

    public function addMedia(string $file, int $userId, int $routeId)
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
            , $routeId
        );
    }

    public function getMediaRowPerId(int $mediaId): ?array
    {
        return $this->db->queryFirstRow(
            'SELECT * FROM media m WHERE m.id = %i'
            , $mediaId
        );
    }

    public function deleteMedia(int $mediaId, int $authorId): bool
    {
        return $this->db->query("DELETE FROM media WHERE id=%i AND user_id=%i", $mediaId, $authorId);
    }

    public function getAllSubscribers(): ?array
    {
        return $this->db->query('SELECT u.email, u.name FROM subscriber s JOIN user u on s.user_id = u.id WHERE s.is_subscribed = 1');
    }

    public function getAllSubscribersButCurrentUser(int $currentUserId): ?array
    {
        return $this->db->query('SELECT u.email, u.name FROM subscriber s JOIN user u on s.user_id = u.id WHERE s.is_subscribed = 1 AND u.id != %i',
            $currentUserId);
    }

    public function storeToken(string $token, int $userId)
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
}
