<?php

namespace Donchev\Framework\Controller\Web;

use DI\Attribute\Inject;
use Donchev\Framework\Service\SiteNotificationService;

abstract class NotificationAwareController extends AuthenticationAwareController
{
    #[Inject]
    private ?SiteNotificationService $notificationService = null;

    public function renderTemplate(string $templateName, array $parameters = []): void
    {
        $notifications = $this->notificationService->getNotifications();

        $parameters['notifications'] = $notifications;

        parent::renderTemplate($templateName, $parameters);
    }

    public function getNotificationService(): SiteNotificationService
    {
        return $this->notificationService;
    }
}
