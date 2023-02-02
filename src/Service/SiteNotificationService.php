<?php

namespace Donchev\Framework\Service;

class SiteNotificationService
{
    public function addInfo(string $text)
    {
        $this->addNotification($text, 'info');
    }

    public function addSuccess(string $text)
    {
        $this->addNotification($text, 'success');
    }

    public function addWarning(string $text)
    {
        $this->addNotification($text, 'warning');
    }

    public function addError(string $text)
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

    private function addNotification(string $text, string $type)
    {
        $_SESSION['notifications'][$type][] = $text;
    }
}
