<?php

namespace Donchev\Framework\Service;

class SiteNotificationService
{
    public function addInfo(string $text): void
    {
        $this->addNotification($text, 'info');
    }

    private function addNotification(string $text, string $type): void
    {
        $_SESSION['notifications'][$type][] = $text;
    }

    public function addSuccess(string $text): void
    {
        $this->addNotification($text, 'success');
    }

    public function addWarning(string $text): void
    {
        $this->addNotification($text, 'warning');
    }

    public function addError(string $text): void
    {
        $this->addNotification($text, 'danger');
    }

    public function getNotifications()
    {
        if (!isset($_SESSION['notifications'])) {
            return [];
        }

        $notifications = $_SESSION['notifications'];
        unset($_SESSION['notifications']);

        return $notifications;
    }
}
