<?php

declare(strict_types=1);

namespace MsgPhp\UserBundle\DependencyInjection\Compiler;

use Doctrine\ORM\EntityManagerInterface as DoctrineEntityManager;
use MsgPhp\Domain\Infra\DependencyInjection\ContainerHelper;
use MsgPhp\User\{Command, Repository};
use MsgPhp\User\Infra\{Console as ConsoleInfra, Security as SecurityInfra, Validator as ValidatorInfra};
use MsgPhp\UserBundle\DependencyInjection\Configuration;
use Symfony\Component\DependencyInjection\Compiler\CompilerPassInterface;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\Security\Core\Authentication\Token\Storage\TokenStorageInterface as SecurityTokenStorage;

/**
 * @author Roland Franssen <franssen.roland@gmail.com>
 *
 * @internal
 */
final class CleanupPass implements CompilerPassInterface
{
    public function process(ContainerBuilder $container): void
    {
        $doctrineRepositoryIds = [];
        foreach (glob(Configuration::getPackageDir().'/Infra/Doctrine/Repository/*Repository.php') as $file) {
            $doctrineRepositoryIds[] = 'MsgPhp\\User\\Infra\\Doctrine\\Repository\\'.basename($file, '.php');
        }
        ContainerHelper::removeIf($container, !$container->has(DoctrineEntityManager::class), $doctrineRepositoryIds);

        ContainerHelper::removeIf($container, !$container->has(SecurityTokenStorage::class), [
            SecurityInfra\UserParamConverter::class,
            SecurityInfra\UserArgumentValueResolver::class,
        ]);
        ContainerHelper::removeIf($container, !$container->has(Repository\RoleRepositoryInterface::class), [
            ConsoleInfra\Command\AddUserRoleCommand::class,
            ConsoleInfra\Command\DeleteUserRoleCommand::class,
        ]);
        ContainerHelper::removeIf($container, !$container->has(Repository\UserRepositoryInterface::class), [
            Command\Handler\ChangeUserCredentialHandler::class,
            Command\Handler\ConfirmUserHandler::class,
            Command\Handler\CreateUserHandler::class,
            Command\Handler\DeleteUserHandler::class,
            Command\Handler\DisableUserHandler::class,
            Command\Handler\EnableUserHandler::class,
            Command\Handler\RequestUserPasswordHandler::class,
            ConsoleInfra\Command\AddUserRoleCommand::class,
            ConsoleInfra\Command\ChangeUserCredentialCommand::class,
            ConsoleInfra\Command\ConfirmUserCommand::class,
            ConsoleInfra\Command\CreateUserCommand::class,
            ConsoleInfra\Command\DeleteUserCommand::class,
            ConsoleInfra\Command\DeleteUserRoleCommand::class,
            ConsoleInfra\Command\DisableUserCommand::class,
            ConsoleInfra\Command\EnableUserCommand::class,
            SecurityInfra\Jwt\SecurityUserProvider::class,
            SecurityInfra\SecurityUserProvider::class,
            SecurityInfra\UserParamConverter::class,
            SecurityInfra\UserArgumentValueResolver::class,
            ValidatorInfra\ExistingUsernameValidator::class,
            ValidatorInfra\UniqueUsernameValidator::class,
        ]);
        ContainerHelper::removeIf($container, !$container->has(Repository\UsernameRepositoryInterface::class), [
            ConsoleInfra\Command\SynchronizeUsernamesCommand::class,
        ]);
        ContainerHelper::removeIf($container, !$container->has(Repository\UserAttributeValueRepositoryInterface::class), [
            Command\Handler\AddUserAttributeValueHandler::class,
            Command\Handler\ChangeUserAttributeValueHandler::class,
            Command\Handler\DeleteUserAttributeValueHandler::class,
        ]);
        ContainerHelper::removeIf($container, !$container->has(Repository\UserEmailRepositoryInterface::class), [
            Command\Handler\AddUserEmailHandler::class,
            Command\Handler\DeleteUserEmailHandler::class,
        ]);
        ContainerHelper::removeIf($container, !$container->has(Repository\UserRoleRepositoryInterface::class), [
            Command\Handler\AddUserRoleHandler::class,
            Command\Handler\DeleteUserRoleHandler::class,
        ]);
    }
}
