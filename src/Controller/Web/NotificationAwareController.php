<?php

namespace Donchev\Framework\Controller\Web;

use DI\Annotation\Inject;
use Donchev\Framework\Service\NotificationService;

abstract class NotificationAwareController extends BaseController
{
    /**
     * @Inject()
     * @var NotificationService
     */
    private $notificationService;

    public function renderTemplate(string $templateName, array $parameters = [])
    {
        $notifications = $this->notificationService->getNotifications();

        $parameters['notifications'] = $notifications;

        parent::renderTemplate($templateName, $parameters);
    }
}
