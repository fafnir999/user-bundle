<?php

declare(strict_types=1);

use MsgPhp\User\Repository\UserRepository;
use MsgPhp\UserBundle\Twig;
use Symfony\Component\DependencyInjection\Loader\Configurator\ContainerConfigurator;
use function Symfony\Component\DependencyInjection\Loader\Configurator\inline_service;
use function Symfony\Component\DependencyInjection\Loader\Configurator\service;
use Symfony\Component\DependencyInjection\ServiceLocator;
use Symfony\Component\Security\Core\Authentication\Token\Storage\TokenStorageInterface;

return static function (ContainerConfigurator $container): void {
    $container->services()
        ->defaults()
            ->private()

        ->set(Twig\GlobalVariable::class)
            ->args([
                inline_service(ServiceLocator::class)
                    ->args([[
                        TokenStorageInterface::class => service(TokenStorageInterface::class)->nullOnInvalid(),
                        UserRepository::class => service(UserRepository::class)->nullOnInvalid(),
                    ]])
                    ->tag('container.service_locator'),
            ])
    ;
};
