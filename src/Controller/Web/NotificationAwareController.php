<?php

namespace Donchev\Framework\Controller\Web;

use DI\Annotation\Inject;
use Donchev\Framework\Service\SiteNotificationService;

abstract class NotificationAwareController extends AuthenticationAwareController
{
    /**
     * @Inject()
     * @var SiteNotificationService
     */
    private $notificationService;

    public function renderTemplate(string $templateName, array $parameters = [])
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
