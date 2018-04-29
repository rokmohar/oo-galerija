<?php

declare(strict_types=1);

namespace App\EventListener;

use App\Controller\ApiController;
use App\Entity\User;
use App\Repository\UserRepository;
use Doctrine\ORM\EntityManager;
use Firebase\JWT\JWT;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Event\FilterControllerEvent;
use Symfony\Component\HttpKernel\Exception\HttpException;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Symfony\Component\HttpKernel\Exception\UnauthorizedHttpException;

class KernelListener
{
    const CHALLENGE = 'Bearer realm=\"JWT user token for authentication\"';
    const JWT_KEY   = 'hWIKYO0nMAr8xL944bXO';

    /**
     * @var EntityManager
     */
    private $entityManager;

    /**
     * @var UserRepository
     */
    private $userRepository;

    /**
     * @var Response
     */
    private $response;

    /**
     * @param EntityManager $entityManager
     */
    public function __construct(EntityManager $entityManager)
    {
        $this->entityManager  = $entityManager;
        $this->userRepository = $entityManager->getRepository(User::class);
    }

    /**
     * @param FilterControllerEvent $event
     */
    public function onKernelController(FilterControllerEvent $event): void
    {
        $controller = $event->getController();

        if (is_array($controller) === false) {
            return;
        }

        if (!$controller[0] instanceof ApiController) {
            return;
        }

        $event->stopPropagation();

        try {
            $this->response = new Response('', 200, ['Content-Type' => 'application/json']);
            $this->analyzeControllerEvent($event);
        } catch (\Exception $e) {
            $event->setController(function () use ($e) {
                return $this->createErrorResponse($e);
            });
        }
    }

    /**
     * @param FilterControllerEvent $event
     * @throws \Exception
     */
    private function analyzeControllerEvent(FilterControllerEvent $event)
    {
        $request = $event->getRequest();

        try {
            $user = $this->getUserFromRequest($request);

            /** @var ApiController[]|string[] $controller */
            $controller = $event->getController();
            $controller[0]->setIdentity($user);
        } catch (\Exception $e) {
            // Ignore exception
        }
    }

    /**
     * @param Request $request
     * @return null|User
     */
    private function getUserFromRequest(Request $request): ?User
    {
        $authorization = $request->headers->get('Authorization');

        if (null === $authorization) {
            throw new UnauthorizedHttpException(self::CHALLENGE, 'Authentication header is missing.');
        }

        return $this->getUserFromHeader($authorization);
    }

    /**
     * @param string $authorization
     * @return User
     *
     * @throws NotFoundHttpException      When the user is not found.
     * @throws UnauthorizedHttpException  When the authorization header is invalid.
     */
    public function getUserFromHeader(string $authorization)
    {
        $split = explode(' ', $authorization);

        if (count($split) !== 2) {
            throw new UnauthorizedHttpException(self::CHALLENGE, 'Syntax error in authorization header.');
        }

        list($type, $token) = $split;

        if ($type !== 'Bearer') {
            throw new UnauthorizedHttpException(self::CHALLENGE, sprintf('Authorization method expected to be \'Bearer\', but got \'%s\'.', $type));
        }

        try {
            $jwt  = (array) JWT::decode($token, self::JWT_KEY, ['HS256']);
            $uuid = $jwt['user_id'];

            /** @var null|User $user */
            $user = $this->userRepository->find($uuid);

            if (null === $user) {
                throw new NotFoundHttpException(sprintf('Could not find user with UUID \'%s\'.', $uuid));
            }

            return $user;
        } catch (\Exception $exception) {
            throw new UnauthorizedHttpException(self::CHALLENGE, 'Invalid user token.', $exception);
        }
    }

    /**
     * @param \Throwable $throwable
     * @return JsonResponse
     */
    private function createErrorResponse(\Throwable $throwable): JsonResponse
    {
        $statusCode = $throwable instanceof HttpException ? $throwable->getStatusCode() : 500;
        $errorData = [
            'error'      => true,
            'message'    => $throwable->getMessage(),
            'file'       => $throwable->getFile(),
            'line'       => $throwable->getLine(),
            'phpCode'    => $throwable->getCode(),
            'statusCode' => $statusCode,
            'stackTrace' => explode(PHP_EOL, $throwable->getTraceAsString()),
        ];

        return new JsonResponse($errorData, $statusCode);
    }
}
