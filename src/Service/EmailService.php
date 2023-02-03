<?php

namespace Donchev\Framework\Service;

use DI\Container;
use Nette\Mail\Mailer;
use Nette\Mail\Message;

class EmailService
{
    /**
     * @var Mailer
     */
    private $mailer;
    /**
     * @var Container
     */
    private $container;

    public function __construct(Mailer $mailer, Container $container)
    {
        $this->mailer = $mailer;
        $this->container = $container;
    }

    public function sendHtmlMail(
        string $subject,
        string $html,
        string $to,
        string $fromName = null,
        string $fromEmail = null
    ) {
        $message = new Message();

        if (empty($fromEmail) || empty($fromName)) {
            $fromName = $this->container->get('app.settings')['mail.from.name'];
            $fromEmail = $this->container->get('app.settings')['mail.from.email'];
        }

        $message
            ->setFrom($fromName . ' <' . $fromEmail . '>')
            ->setSubject($subject)
            ->setHtmlBody($html)
            ->setEncoding('UTF8')
            ->addTo($to);

        $this->mailer->send($message);
    }
}
