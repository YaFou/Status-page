<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Uid\Uuid;

trait IdTrait
{
    /**
     * @var Uuid
     * @ORM\Column(type="uuid")
     * @ORM\Id()
     * @ORM\GeneratedValue(strategy="UUID")
     */
    private $id;

    public function getId(): Uuid
    {
        return $this->id;
    }
}
