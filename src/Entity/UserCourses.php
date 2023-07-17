<?php

namespace App\Entity;

use App\Repository\UserCoursesRepository;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: UserCoursesRepository::class)]
class UserCourses
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\ManyToOne(inversedBy: 'userCourses')]
    #[ORM\JoinColumn(nullable: false)]
    private ?User $user_ref = null;

    #[ORM\ManyToOne(inversedBy: 'userCourses')]
    private ?Course $course_ref = null;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getUserRef(): ?User
    {
        return $this->user_ref;
    }

    public function setUserRef(?User $user_ref): self
    {
        $this->user_ref = $user_ref;

        return $this;
    }

    public function getCourseRef(): ?Course
    {
        return $this->course_ref;
    }

    public function setCourseRef(?Course $course_ref): self
    {
        $this->course_ref = $course_ref;

        return $this;
    }

    public function equals(UserCourses $course): bool
    {
        return $this->getCourseRef() === $course->getCourseRef() && $this->getUserRef() === $course->getUserRef();
    }
}
