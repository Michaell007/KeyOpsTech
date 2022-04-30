<?php

namespace App\DataPersister;

use App\Entity\User;
use Doctrine\ORM\EntityManagerInterface;
use ApiPlatform\Core\DataPersister\DataPersisterInterface;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;

class UserDataPersister implements DataPersisterInterface {

    private $em;
    private $encoderPassword;

    public function __construct(EntityManagerInterface $em, UserPasswordHasherInterface $encoderPassword)
    {
        $this->em = $em;
        $this->encoderPassword = $encoderPassword;
    }


    public function supports($data): bool
    {
        return $data instanceof User;
    }

    /**
     * @param User $data
     */
    public function persist($data)
    {
        if ($data->getPassword()) {
            $data->setPassword(
                $this->encoderPassword->hashPassword($data, $data->getPassword())
            );
        }

        $this->em->persist($data);
        $this->em->flush();
    }

    public function remove($data)
    {
        $this->em->remove($data);
        $this->em->flush();
    }

}