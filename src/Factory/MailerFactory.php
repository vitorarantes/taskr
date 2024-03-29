<?php

declare(strict_types=1);

namespace App\Factory;

use Psr\Container\ContainerInterface;
use Psr\EventDispatcher\EventDispatcherInterface;
use Psr\Log\LoggerInterface;
use Yiisoft\Aliases\Aliases;
use Yiisoft\Mailer\Composer;
use Yiisoft\Mailer\MessageFactory;
use Yiisoft\Mailer\SwiftMailer\Logger;
use Yiisoft\Mailer\SwiftMailer\Mailer;
use Yiisoft\Mailer\SwiftMailer\Message;
use Yiisoft\View\Theme;
use Yiisoft\View\View;

class MailerFactory
{
    public function __invoke(ContainerInterface $container)
    {
        $aliases = $container->get(Aliases::class);
        $logger = $container->get(LoggerInterface::class);
        $eventDispatcher = $container->get(EventDispatcherInterface::class);
        $messageFactory = new MessageFactory(Message::class);
        $view = new View('', new Theme(), $eventDispatcher, $logger);
        $composer = new Composer($view, $aliases->get('@resources/mail'));
        $transport = $container->get(\Swift_Transport::class);

        $mailer = new Mailer($messageFactory, $composer, $eventDispatcher, $logger, $transport);
        $mailer->registerPlugin(new \Swift_Plugins_LoggerPlugin(new Logger($logger)));

        return $mailer;
    }
}
