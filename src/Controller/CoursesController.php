<?php

namespace App\Controller;

use App\Entity\Course;
use App\Entity\UserCourses;
use App\Form\UserCourseType;
use App\Repository\CourseRepository;
use App\Services\UserCourseFactory;
use Doctrine\ORM\EntityManager;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/courses', name: 'courses_')]
class CoursesController extends AbstractController
{
    #[Route('/', name: 'main')]
    public function index(CourseRepository $repository): Response
    {

        return $this->render('courses/index.html.twig', [
            'courses' => $repository->findBy([
                'status' => Course::STATUS_ACTIVE,
            ])
        ]);
    }

    #[Route('/{id}', name: 'detail')]
    public function detail(Course $course, Request $request, EntityManagerInterface $manager) : Response
    {
        $form = $this->createForm(UserCourseType::class);
        $form->handleRequest($request);

        if($form->isSubmitted() && $form->isValid()){
            $user = $this->getUser();
            $userCourse = $form->getData();
            $userCourse->setCourseRef($course);
            $userCourse->setUserRef($user);
            foreach ($user->getUserCourses() as $subscibed){
                if($userCourse->equals($subscibed)){
                    return $this->redirectToRoute('courses_main');
                }
            }
            $manager->persist($userCourse);
            $manager->flush();
            return $this->redirectToRoute('profile_index', ['id'=>$user->getId()]);
        }
        return $this->render('courses/detail.html.twig', [
            'form' => $form->createView(),
            'course' => $course
        ]);
    }
}
