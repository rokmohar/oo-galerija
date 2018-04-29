<?php

declare(strict_types=1);

namespace App\Controller;

use App\Entity\User;
use App\Repository\UserRepository;
use JMS\Serializer\Serializer;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class UserController extends ApiController
{
    /**
     * @return Response
     *
     * @Route("/api/users", methods={"GET"})
     */
    public function usersAction(): Response
    {
        /*$identity = $this->getIdentity();

        if (null === $identity) {
            throw $this->createAccessDeniedException();
        }*/

        $doctrine = $this->getDoctrine();
        $manager  = $doctrine->getManager();

        /** @var UserRepository $userRepository */
        $userRepository = $manager->getRepository(User::class);

        /** @var User[] $users */
        $users = $userRepository->findBy([], ['createdAt' => 'DESC']);

        /** @var Serializer $serializer */
        $serializer = $this->get('jms_serializer');

        $data = $serializer->serialize($users, 'json');

        return new Response($data);
    }

    /**
     * @param Request $request
     * @return Response
     *
     * @Route("/api/user", methods={"POST"})
     */
    public function createUserAction(Request $request): Response
    {
        $identity = $this->getIdentity();

        if (null === $identity) {
            throw $this->createAccessDeniedException();
        }

        /** @var Serializer $serializer */
        $serializer = $this->get('jms_serializer');

        /** @var User $user */
        $user = $serializer->deserialize($request->getContent(), User::class, 'json');

        $doctrine = $this->getDoctrine();
        $manager  = $doctrine->getManager();

        $manager->persist($user);
        $manager->flush();

        $data = $serializer->serialize($user, 'json');

        return new Response($data);
    }

    /**
     * @param string $userId
     * @return Response
     *
     * @Route("/api/user/{userId}", methods={"GET"})
     */
    public function findUserAction(string $userId): Response
    {
        /*$identity = $this->getIdentity();

        if (null === $identity) {
            throw $this->createAccessDeniedException();
        }*/

        $doctrine = $this->getDoctrine();
        $manager  = $doctrine->getManager();

        /** @var UserRepository $userRepository */
        $userRepository = $manager->getRepository(User::class);

        /** @var null|User $user */
        $user = $userRepository->find($userId);

        if (null === $user) {
            throw $this->createNotFoundException();
        }

        /** @var Serializer $serializer */
        $serializer = $this->get('jms_serializer');

        $data = $serializer->serialize($user, 'json');

        return new Response($data);
    }

    /**
     * @param string $userId
     * @param Request $request
     * @return Response
     *
     * @Route("/api/user/{userId}", methods={"POST"})
     */
    public function changeUserAction(string $userId, Request $request): Response
    {
        $identity = $this->getIdentity();

        if (null === $identity) {
            throw $this->createAccessDeniedException();
        }

        $doctrine = $this->getDoctrine();
        $manager  = $doctrine->getManager();

        /** @var UserRepository $userRepository */
        $userRepository = $manager->getRepository(User::class);

        /** @var null|User $user */
        $user = $userRepository->find($userId);

        if (null === $user) {
            throw $this->createNotFoundException();
        }

        /** @var Serializer $serializer */
        $serializer = $this->get('jms_serializer');

        /** @var User $userData */
        $userData = $serializer->deserialize($request->getContent(), User::class, 'json');

        $user->setUsername($userData->getUsername());
        $user->setPassword($userData->getPassword());
        $user->setName($userData->getName());

        $manager->persist($user);
        $manager->flush();

        $data = $serializer->serialize($user, 'json');

        return new Response($data);
    }

    /**
     * @param string $userId
     * @return Response
     *
     * @Route("/api/user/{userId}", methods={"DELETE"})
     */
    public function deleteUserAction(string $userId): Response
    {
        $identity = $this->getIdentity();

        if (null === $identity) {
            throw $this->createAccessDeniedException();
        }

        $doctrine = $this->getDoctrine();
        $manager  = $doctrine->getManager();

        /** @var UserRepository $userRepository */
        $userRepository = $manager->getRepository(User::class);

        /** @var null|User $user */
        $user = $userRepository->find($userId);

        if (null === $user) {
            throw $this->createNotFoundException();
        }

        $manager->remove($user);
        $manager->flush();

        return new Response();
    }
}
