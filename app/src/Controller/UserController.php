<?php

declare(strict_types=1);

namespace App\Controller;

use App\Entity\Role;
use App\Entity\User;
use App\Repository\RoleRepository;
use App\Repository\UserRepository;
use Doctrine\ORM\EntityManagerInterface;
use Exception;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Serializer\SerializerInterface;

use function json_decode;

#[Route('/user')]
class UserController extends AbstractController
{
    public function __construct(
        private UserRepository $userRepository,
        private RoleRepository $roleRepository,
        private SerializerInterface $serializer,
        private EntityManagerInterface $em
    ) {
    }

    /**
     * @return Response
     */
    #[Route('', name: 'users_get', methods: ['GET'])]
    public function getAllUsers(): Response
    {
        $this->denyAccessUnlessGranted('ROLE_USER', null, "You're not authorized to see this page");

        $users = $this->userRepository->findAll();

        $usersJson = $this->serializer->serialize($users, 'json', ['groups' => ['get_users']]);

        return new Response($usersJson, 200);
    }

    /**
     *
     * @param User|string $user
     * @return Response
     */
    #[Route('/{id}', name: 'user_get', requirements: ['id' => '\d+'], methods: ['GET'])]
    public function get(
        User|string $user
    ): Response {
        $this->denyAccessUnlessGranted('ROLE_USER', null, "You're not authorized to see this page");

        $userJson = $this->serializer->serialize($user, 'json', ['groups' => ['get_user']]);

        return new Response($userJson, 200);
    }

    /**
     * @param Request $request
     * @return JsonResponse
     */
    #[Route('', name: 'user_create', methods: ['POST'])]
    public function post(
        Request $request
    ): JsonResponse {
        $data = null;

        try {
            $data = json_decode($request->getContent(), true);

            $username = $data['username'];
            $password = $data['password'];
            $email = $data['email'];
            $userRole = $data['role'];

            $user = new User();
            $user->setUsername($username);
            $user->setPlainPassword($password);
            $user->setEmail($email);

            $user->setRole($this->setUserRole($userRole));

            $this->em->persist($user);
            $this->em->flush();
        } catch (Exception $e) {
            throw new Exception($e->getMessage());
        }

        return new JsonResponse('User created', 201);
    }

    /**
     * @param string $userRole
     * @return Role
     */
    public function setUserRole(string $userRole): Role
    {
        $role = null;

        if ($userRole === 'ROLE_USER' || $userRole === 'ROLE_ADMIN') {
            $role = $this->roleRepository->findOneBy(['code' => $userRole]);

            if (is_null($role)) {
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

    /**
     *
     * @param User $user
     * @param Request $request
     * @return Response
     * @throws Exception
     *
     */
    #[Route('/{id}', name: 'user_edit', requirements: ['id' => '\d+'], methods: ['PUT'])]
    public function put(
        User $user,
        Request $request
    ): Response {
        $data = null;
        $decodedData = null;
        $userJson = null;

        $data = $request->getContent();

        if (!empty($data)) {
            $decodedData = json_decode($data);

            $username = $decodedData->username;
            $password = $decodedData->password;
            $email = $decodedData->email;
            $userRole = $decodedData->role;

            try {
                $user->setUsername($username);
                $user->setPassword($password);
                $user->setEmail($email);
                $user->setRole(self::setUserRole($userRole));

                $this->em->flush();

                $userJson = $this->serializer->serialize($user, 'json', ['groups' => ['get_user']]);
            } catch (Exception $e) {
                throw new Exception($e->getMessage());
            }
        }

        return new Response($userJson, 200);
    }

    /**
     * @param User $user
     * @param Request $request
     * @return JsonResponse
     * @throws Exception
     *
     */
    #[Route('/{id}', name: 'user_delete', requirements: ['id' => '\d+'], methods: ['DELETE'])]
    public function delete(
        User $user,
        Request $request
    ): JsonResponse {
        if ($user == null) {
            new JsonResponse('User not found', 404);
        }

        try {
            $this->em->remove($user);
            $this->em->flush();
        } catch (Exception $e) {
            throw new Exception($e->getMessage());
        }

        return new JsonResponse(null, 204);
    }
}
