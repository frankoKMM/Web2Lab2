<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;

//#[ORM\Entity]
#[ORM\Table(name: 'user')]
class User
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private int $id;

    #[ORM\Column(length: 255)]
    private string $fullName;

    #[ORM\Column(length: 255 ,unique: true)]
    private string $email;

    #[ORM\Column(type: 'text')]
    private ?string $somePublicData = null;

    #[ORM\Column(type: 'text')]
    private ?string $somePrivateData = null;
}