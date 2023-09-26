<?php

namespace App\Entity;

use App\Entity\Grade;
use Doctrine\ORM\Mapping as ORM;
use App\Repository\SchoolClassRepository;
use Doctrine\Common\Collections\Collection;
use Doctrine\Common\Collections\ArrayCollection;

#[ORM\Entity(repositoryClass: SchoolClassRepository::class)]
class SchoolClass
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 255)]
    private ?string $label = null;

    #[ORM\ManyToOne(inversedBy: 'schoolClasses')]
    #[ORM\JoinColumn(nullable: false)]
    private ?Grade $grade = null;

    #[ORM\OneToMany(mappedBy: 'schoolClass', targetEntity: User::class)]
    private Collection $users;

    #[ORM\ManyToMany(targetEntity: Subject::class, mappedBy: 'schoolClasses')]
    private Collection $subjects;

    #[ORM\ManyToMany(targetEntity: User::class, mappedBy: 'schoolClassTeacher')]
    private Collection $teachers;

    public function __construct()
    {
        $this->users = new ArrayCollection();
        $this->subjects = new ArrayCollection();
        $this->teachers = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getLabel(): ?string
    {
        return $this->label;
    }

    public function setLabel(string $label): static
    {
        $this->label = $label;

        return $this;
    }

    public function getGrade(): ?Grade
    {
        return $this->grade;
    }

    public function setGrade(?Grade $grade): static
    {
        $this->grade = $grade;

        return $this;
    }

    /**
     * @return Collection<int, User>
     */
    public function getUsers(): Collection
    {
        return $this->users;
    }

    public function addUser(User $user): static
    {
        if (!$this->users->contains($user)) {
            $this->users->add($user);
            $user->setSchoolClass($this);
        }

        return $this;
    }

    public function removeUser(User $user): static
    {
        if ($this->users->removeElement($user)) {
            // set the owning side to null (unless already changed)
            if ($user->getSchoolClass() === $this) {
                $user->setSchoolClass(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection<int, Subject>
     */
    public function getSubjects(): Collection
    {
        return $this->subjects;
    }

    public function addSubject(Subject $subject): static
    {
        if (!$this->subjects->contains($subject)) {
            $this->subjects->add($subject);
            $subject->addSchoolClass($this);
        }

        return $this;
    }

    public function removeSubject(Subject $subject): static
    {
        if ($this->subjects->removeElement($subject)) {
            $subject->removeSchoolClass($this);
        }

        return $this;
    }

    /**
     * @return Collection<int, User>
     */
    public function getTeachers(): Collection
    {
        return $this->teachers;
    }

    public function addTeacher(User $teacher): static
    {
        if (!$this->teachers->contains($teacher)) {
            $this->teachers->add($teacher);
            $teacher->addSchoolClassTeacher($this);
        }

        return $this;
    }

    public function removeTeacher(User $teacher): static
    {
        if ($this->teachers->removeElement($teacher)) {
            $teacher->removeSchoolClassTeacher($this);
        }

        return $this;
    }
}
