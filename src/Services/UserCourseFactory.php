<?php

namespace App\Services;

use App\Entity\Course;
use App\Entity\UserCourses;

class UserCourseFactory {
    public function create(Course $course): UserCourses{
        $courses = new UserCourses();
        $courses->setCourseRef($course);
        return $courses;
    }

    public function createEmpty(): UserCourses{
        return new UserCourses();
    }

}