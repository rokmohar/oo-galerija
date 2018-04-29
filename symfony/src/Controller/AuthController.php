<?php

declare(strict_types=1);

namespace App\Controller;

use App\Entity\User;
use App\EventListener\KernelListener;
use App\Repository\UserRepository;
use Firebase\JWT\JWT;
use JMS\Serializer\Serializer;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class AuthController extends ApiController
{
    /**
     * @return Response
     *
     * @Route("/api/identity", methods={"GET"})
     */
    public function identityAction(): Response
    {
        $identity = $this->getIdentity();

        if (null === $identity) {
            throw $this->createAccessDeniedException();
        }

        /** @var Serializer $serializer */
        $serializer = $this->get('jms_serializer');

        $data = $serializer->serialize($identity, 'json');

        return new Response($data);
    }


    /**
     * @param Request $request
     * @return Response
     *
     * @Route("/api/login", methods={"POST"})
     */
    public function loginAction(Request $request): Response
    {
        /** @var Serializer $serializer */
        $serializer = $this->get('jms_serializer');

        /** @var User $user */
        $user = $serializer->deserialize($request->getContent(), User::class, 'json');

        $username = $user->getUsername();
        $password = $user->getPassword();

        if (null === $username || null === $password) {
            throw new \InvalidArgumentException('Username and password must be provided.');
        }

        $doctrine = $this->getDoctrine();
        $manager  = $doctrine->getManager();

        /** @var UserRepository $userRepository */
        $userRepository = $manager->getRepository(User::class);

        /** @var null|User $user */
        $user = $userRepository->findOneBy(compact('username', 'password'));

        if (null === $user) {
            throw $this->createNotFoundException('User does not exist.');
        }

        $jwt = JWT::encode(['user_id' => (string) $user->getId()], KernelListener::JWT_KEY);

        return new Response($jwt);
    }
}