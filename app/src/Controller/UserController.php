<?php

declare(strict_types=1);

namespace App\Controller;

use App\Entity\Role;
use App\Entity\User;
use App\Repository\RoleRepository;
use App\Repository\UserRepository;
use Doctrine\ORM\EntityManagerInterface;
use Exception;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Serializer\SerializerInterface;

#[Route('/user')]
class UserController
{
    public function __construct(
        private UserRepository $userRepository,
        private RoleRepository $roleRepository,
        private SerializerInterface $serializer,
        private EntityManagerInterface $em
    ) {
    }

    /**
     * @return JsonResponse
     */
    #[Route('', name: 'users_get', methods: ['GET'])]
    public function allUsers(): JsonResponse
    {
        $users = $this->userRepository->findAll();

        return new JsonResponse($users, 200);
    }

    /**
     * @param Request $request
     * @return JsonResponse
     */
    #[Route('', name: 'user_create', methods: ['POST'])]
    public function create(Request $request): JsonResponse
    {
        $data = null;

        try {
            $data = $this->serializer->deserialize($request->getContent(), User::class, 'json');

            $username = $data['username'];
            $password = $data['password'];
            $email = $data['email'];
            $userRole = $data['role'];

            $user = new User();
            $user->setUsername($username);
            $user->setPlainPassword($password);
            $user->setEmail($email);

            $user->setRole($this->setUserRole($user, $userRole));

            $this->em->persist($user);
            $this->em->flush();
        } catch (Exception $e) {
            throw new Exception($e->getMessage());
        }

        return new JsonResponse('User created', 201);
    }

    /**
     * @param User $user
     * @param string $userRole
     * @return Role
     */
    public function setUserRole(User $user, string $userRole): Role
    {
        $role = null;

        if ($userRole === 'ROLE_USER' || $userRole === 'ROLE_ADMIN') {
            $role = $this->roleRepository->findOneBy(['code' => $userRole]);

            if (!is_null($role)) {
                $user->setRole($role);
            } else {
                $role = new Role();

                $role->setName('user');
                $role->setCode($userRole);

                $this->em->persist($role);
            }
        } else {
            throw new Exception('Error in user role given');
        }

        return $role;
    }
}
