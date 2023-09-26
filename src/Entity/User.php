<?php

namespace App\Entity;

use App\Entity\SchoolClass;
use Doctrine\ORM\Mapping as ORM;
use App\Repository\UserRepository;
use Doctrine\Common\Collections\Collection;
use Doctrine\Common\Collections\ArrayCollection;
use Symfony\Component\Security\Core\User\UserInterface;
use Symfony\Component\Security\Core\User\PasswordAuthenticatedUserInterface;

#[ORM\Entity(repositoryClass: UserRepository::class)]
class User implements UserInterface, PasswordAuthenticatedUserInterface
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 180, unique: true)]
    private ?string $email = null;

    #[ORM\Column]
    private array $roles = [];

    /**
     * @var string The hashed password
     */
    #[ORM\Column]
    private ?string $password = null;

    #[ORM\ManyToMany(targetEntity: Subject::class, inversedBy: 'users')]
    private Collection $subjects;

    #[ORM\ManyToOne(inversedBy: 'users')]
    private ?SchoolClass $schoolClass = null;

    #[ORM\OneToOne(cascade: ['persist', 'remove'])]
    private ?InfoUser $infoUser = null;

    #[ORM\ManyToMany(targetEntity: SchoolClass::class, inversedBy: 'teachers')]
    private Collection $schoolClassTeacher;

    public function __construct()
    {
        $this->subjects = new ArrayCollection();
        $this->schoolClassTeacher = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getEmail(): ?string
    {
        return $this->email;
    }

    public function setEmail(string $email): static
    {
        $this->email = $email;

        return $this;
    }

    /**
     * A visual identifier that represents this user.
     *
     * @see UserInterface
     */
    public function getUserIdentifier(): string
    {
        return (string) $this->email;
    }

    /**
     * @see UserInterface
     */
    public function getRoles(): array
    {
        $roles = $this->roles;
        // guarantee every user at least has ROLE_USER
        $roles[] = 'ROLE_USER';

        return array_unique($roles);
    }

    public function setRoles(array $roles): static
    {
        $this->roles = $roles;

        return $this;
    }

    /**
     * @see PasswordAuthenticatedUserInterface
     */
    public function getPassword(): string
    {
        return $this->password;
    }

    public function setPassword(string $password): static
    {
        $this->password = $password;

        return $this;
    }

    /**
     * @see UserInterface
     */
    public function eraseCredentials(): void
    {
        // If you store any temporary, sensitive data on the user, clear it here
        // $this->plainPassword = null;
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
        }

        return $this;
    }

    public function removeSubject(Subject $subject): static
    {
        $this->subjects->removeElement($subject);

        return $this;
    }

    public function getSchoolClass(): ?SchoolClass
    {
        return $this->schoolClass;
    }

    public function setSchoolClass(?SchoolClass $schoolClass): static
    {
        $this->schoolClass = $schoolClass;

        return $this;
    }

    public function getInfoUser(): ?InfoUser
    {
        return $this->infoUser;
    }

    public function setInfoUser(?InfoUser $infoUser): static
    {
        $this->infoUser = $infoUser;

        return $this;
    }

    /**
     * @return Collection<int, SchoolClass>
     */
    public function getSchoolClassTeacher(): Collection
    {
        return $this->schoolClassTeacher;
    }

    public function addSchoolClassTeacher(SchoolClass $schoolClassTeacher): static
    {
        if (!$this->schoolClassTeacher->contains($schoolClassTeacher)) {
            $this->schoolClassTeacher->add($schoolClassTeacher);
        }

        return $this;
    }

    public function removeSchoolClassTeacher(SchoolClass $schoolClassTeacher): static
    {
        $this->schoolClassTeacher->removeElement($schoolClassTeacher);

        return $this;
    }
}
