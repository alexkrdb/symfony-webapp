<?php

namespace App\Controller;

use App\Entity\Course;
use App\Entity\User;
use App\Entity\UserCourses;
use App\Form\ConfirmPasswordType;
use App\Form\RegistrationFormType;
use App\Repository\UserCoursesRepository;
use App\Repository\UserRepository;
use App\Services\CourseManager;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/profile/{id}", name="profile_")
 */
class ProfileController extends AbstractController
{
    /**
     * @param User $user
     * @return Response
     */
    #[Route('/',name: 'index')]
    public function index(User $user): Response
    {
        return $this->render('profile/index.html.twig', [
            'user' => $user,
        ]);
    }

    #[Route('/courses', name:'courses')]
    public function showCourses(User $user, CourseManager $manager):Response
    {
        return $this->render('profile/courses.html.twig', [
            'courses' => $manager->getCourses($user->getUserCourses()),
            'active' => Course::STATUS_ACTIVE,
        ]);
    }
    #[Route('/courses/{course_id}', name:'delete_course')]
    public function delete_course(User $user, Request $request, UserPasswordHasherInterface $hasher, EntityManagerInterface $manager, UserCoursesRepository $repository) : Response
    {
        $userCourses = $repository->findOneBy([
            'user_ref' => $user->getId(),
            'course_ref' => $request->attributes->get('course_id')
            ]);
        $form = $this->createForm(ConfirmPasswordType::class);
        $form->handleRequest($request);
        if($form->isSubmitted() && $form->isValid()){
            if($hasher->isPasswordValid($user, $form->get('plainPassword')->getData())){
                $user->removeUserCourse($userCourses);
                $manager->persist($user);
                $manager->flush();
                return $this->redirectToRoute('profile_index', ['id' => $user->getId()]);
            }
        }
        return $this->render('profile/delete_course.html.twig', [
            'form' => $form->createView(),
            'userCourse' => $userCourses,
            'user' => $user
        ]);
    }
    #[Route('/edit', name:'edit')]
    public function edit(User $user, Request $request, UserPasswordHasherInterface $hasher, EntityManagerInterface $manager):Response
    {
        $form = $this->createForm(RegistrationFormType::class, $user);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid() && $hasher->isPasswordValid($user, $form->get('plainPassword')->getData())) {
            // encode the plain password
            $manager->persist($user);
            $manager->flush();
            return $this->redirectToRoute('profile_index', ['id' => $user->getId()]);
        }
        return $this->render('profile/edit.html.twig', [
            'form' => $form->createView(),
            'user' => $user,
        ]);
    }
    #[Route('/delete', name:'delete')]
    public function delete(User $user, Request $request, UserPasswordHasherInterface $hasher, UserRepository $repository):Response
    {
        $form = $this->createForm(ConfirmPasswordType::class);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid() && $hasher->isPasswordValid($user, $form->get('plainPassword')->getData())) {
            $repository->remove($user, true);
            $request->getSession()->invalidate();
            $this->container->get('security.token_storage')->setToken(null);
            return $this->redirectToRoute('app_main');
        }
        return $this->render('profile/delete.html.twig', [
            'form' => $form->createView(),
            'user' => $user,
        ]);
    }
}
