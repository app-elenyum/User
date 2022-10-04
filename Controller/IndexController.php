<?php

namespace Module\User\Controller;

use App\Controller\BaseController;
use Module\User\Entity\User;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/user')]
class IndexController extends BaseController
{
    public const DATABASE = 'users';

    #[Route('/login', name: 'usersLogin')]
    public function login(Request $request): Response
    {
        $user = $this->getUser();
        if ($user instanceof User) {
            return $this->json([
                'code' => Response::HTTP_OK,
                'message' => 'See PHPSESSID in cookie',
                'user' => $user->getEmail(),
            ]);
        }

        return $this->json([
            'message' => 'missing credentials',
        ], Response::HTTP_UNAUTHORIZED);
    }

    /**
     * @param Request $request
     * @param UserPasswordHasherInterface $passwordHasher
     * @return Response
     * @throws \JsonException
     * @throws \Exception
     */
    #[Route('/registration', name: 'usersRegistration')]
    public function registration(
        Request $request,
        UserPasswordHasherInterface $passwordHasher,
    ): Response {

        $user = $this->toEntity(User::class, $request->getContent());

        if ($user instanceof User) {
            $user->setPassword(
                $passwordHasher->hashPassword(
                    $user,
                    $user->getPassword()
                )
            );
        }

        $validator = $this->getValidator();
        if ($validator->isValid($user)) {
            $result['messages'] = $validator->getMessages();
            $result['code'] = Response::HTTP_BAD_REQUEST;
        } else {
            // Получить соединение
            $em = $this->getEntityManager();
            $em->persist($user);
            $em->flush();
            $result['code'] = Response::HTTP_OK;
        }

        return $this->json($result);
    }
}