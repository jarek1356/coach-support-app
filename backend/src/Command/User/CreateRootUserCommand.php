<?php

namespace App\Command\User;

use App\Entity\User\User;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Console\Attribute\AsCommand;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;

#[AsCommand(
    name: 'app:create-root-user',
    description: 'Creates root admin user with fixed password.',
)]
class CreateRootUserCommand extends Command
{
    public function __construct(
        private readonly EntityManagerInterface      $entityManager,
        private readonly UserPasswordHasherInterface $passwordHasher,
    ) {
        parent::__construct();
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $username = 'root';
        $plainPassword = 'Root9856!';
        $roles = ['ROLE_ADMIN'];

        $userRepo = $this->entityManager->getRepository(User::class);

        $existing = $userRepo->findOneBy(['username' => $username]);
        if ($existing) {
            $output->writeln(sprintf(
                '<comment>User "%s" already exists.</comment>',
                $username
            ));

            return Command::SUCCESS;
        }

        $user = new User();
        $user->setUsername($username);
        $user->setRoles($roles);

        $hashedPassword = $this->passwordHasher->hashPassword($user, $plainPassword);
        $user->setPassword($hashedPassword);

        $this->entityManager->persist($user);
        $this->entityManager->flush();

        $output->writeln(sprintf(
            '<info>Created admin "%s" with password "%s"</info>',
            $username,
            $plainPassword
        ));

        return Command::SUCCESS;
    }
}
