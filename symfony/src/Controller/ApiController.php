<?php

declare(strict_types=1);

namespace App\Controller;

use App\Entity\User;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;

abstract class ApiController extends Controller
{
    /**
     * @var null|User
     */
    private $identity;

    /**
     * @return null|User
     */
    public function getIdentity(): ?User
    {
        return $this->identity;
    }

    /**
     * @param null|User $identity
     */
    public function setIdentity(User $identity = null): void
    {
        $this->identity = $identity;
    }
}
