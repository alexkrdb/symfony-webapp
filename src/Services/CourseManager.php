<?php

namespace App\Services;

use App\Entity\User;
use App\Entity\UserCourses;
use App\Repository\UserCoursesRepository;
use Doctrine\ORM\EntityManager;
use Doctrine\Common\Collections\Collection;
class CourseManager
{
    public function __construct( UserCourseFactory $factory)
    {
        $this->factory = $factory;
    }

    /**
     * @param Collection $userCourses
     * @return array|null
     */
    public function getCourses(Collection $userCourses): ?array
    {
        $courses = [];
        if(!$userCourses){
            $courses = null;
        }
        else
        {
            foreach( $userCourses as $userCourse){
                array_push($courses, $userCourse->getCourseRef());
            }
        }
        return $courses;
    }

    public function create()
    {

    }

}