<?php declare(strict_types=1);

namespace App\Security;

use Symfony\Component\Security\Core\Encoder\EncoderFactoryInterface;
use App\Entity\User;

class PasswordUpdater
{
    private $encoderFactory;

    public function __construct(EncoderFactoryInterface $encoderFactory)
    {
        $this->encoderFactory = $encoderFactory;
    }

    public function hashPassword(User $user)
    {
        $plainPassword = $user->getPlainPassword();

        if (null !== $plainPassword && 0 === mb_strlen($plainPassword)) {
            return;
        }

        $encoder = $this->encoderFactory->getEncoder($user);

        $hashedPassword = $encoder->encodePassword($plainPassword, $user->getSalt());
        $user->setPassword($hashedPassword);
        $user->eraseCredentials();
    }
}
