<?php

namespace App\Controller;

use App\Entity\User;
use App\Form\UserType;
use App\Form\AdminType;
use App\Entity\InfoUser;
use App\Form\StudentType;
use App\Form\TeacherType;
use App\Entity\SchoolClass;
use App\Form\StudentClassType;
use App\Repository\UserRepository;
use App\Repository\GradeRepository;
use App\Repository\InfoUserRepository;
use Doctrine\ORM\EntityManagerInterface;
use App\Repository\SchoolClassRepository;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;

#[Route('/admin')]
class AdminController extends AbstractController
{

    // On déclare la propriété pour l'encodage
    private UserPasswordHasherInterface $encoder;

    public function __construct(UserPasswordHasherInterface $encoder)
    {
        // On injecte la dépendance dans le constructeur
        $this->encoder = $encoder;
    }

    // Méthode pour afficher la liste des professeurs
    #[Route('/teacher', name: 'app_teacher_index', methods: ['GET'])]
    public function index(UserRepository $userRepository): Response
    {
        $teachers = $userRepository->getUsersByRole('ROLE_PROF');
        return $this->render('user/teacher/index.html.twig', [
            'teachers' => $teachers,
        ]);
    }

    // Méthode pour ajouter un professeur
    #[Route('/teacher/new', name: 'app_teacher_new', methods: ['GET', 'POST'])]
    public function new(Request $request,  UserRepository $userRepository, InfoUserRepository $infoUserRepository): Response
    {
        $user = new User();
        $form = $this->createForm(TeacherType::class, $user);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            // Ici on instancie un objet de la classe InfoUser
            $infoUser = new InfoUser();

            // On récupere les infos du form pour infoUser
            $infoUser->setLastname($form->get('infoUser')->get('lastname')->getData());
            $infoUser->setFirstname($form->get('infoUser')->get('firstname')->getData());
            $infoUser->setBirthDate($form->get('infoUser')->get('birthDate')->getData());
            $infoUser->setPhone($form->get('infoUser')->get('phone')->getData());
            $infoUser->setAddress($form->get('infoUser')->get('address')->getData());
            $infoUser->setZipCode($form->get('infoUser')->get('zipCode')->getData());
            $infoUser->setCity($form->get('infoUser')->get('city')->getData());
            $infoUser->setCountry($form->get('infoUser')->get('country')->getData());

            // On enregistre les infoUser
            $infoUserRepository->save($infoUser, true);

            // On récupère l'id de infoUser
            $user->setInfoUser($infoUserRepository->find($infoUser->getId()));

            // On donne le role prof à l'utilisateur
            $user->setRoles(['ROLE_PROF']);

            // On encode le mot de passe
            $plainPassword = $form->get('password')->getData();

            if ($plainPassword) {
                $user->setPassword($this->encoder->hashPassword($user, $plainPassword));
            }

            // On enregistre l'utilisateur
            $userRepository->save($user, true);

            return $this->redirectToRoute('app_teacher_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('user/teacher/new.html.twig', [
            'user' => $user,
            'form' => $form,
        ]);
    }

    // Méthode pour afficher un professeur
    #[Route('/teacher/{id}', name: 'app_teacher_show', methods: ['GET'])]
    public function show(User $user): Response
    {
        return $this->render('user/teacher/show.html.twig', [
            'user' => $user,
        ]);
    }

    // Méthode pour modfiier un professeur
    #[Route('/teacher/{id}/edit', name: 'app_teacher_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, User $user, UserRepository $userRepository): Response
    {
        $form = $this->createForm(TeacherType::class, $user);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {


            // On encode le mot de passe
            $plainPassword = $form->get('password')->getData();

            if ($plainPassword) {
                $user->setPassword($this->encoder->hashPassword($user, $plainPassword));
            }

            // On enregistre l'utilisateur
            $userRepository->save($user, true);

            return $this->redirectToRoute('app_teacher_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('user/teacher/edit.html.twig', [
            'user' => $user,
            'form' => $form,
        ]);
    }

    // Méthode pour supprimer un professeur
    #[Route('/teacher/{id}', name: 'app_teacher_delete', methods: ['POST'])]
    public function delete(Request $request, User $user, EntityManagerInterface $entityManager): Response
    {
        if ($this->isCsrfTokenValid('delete' . $user->getId(), $request->request->get('_token'))) {
            $entityManager->remove($user);
            $entityManager->flush();
        }

        return $this->redirectToRoute('app_teacher_index', [], Response::HTTP_SEE_OTHER);
    }

    // PARTIE ELEVE

    // Méthode pour afficher la liste des eleves
    #[Route('/student', name: 'app_student_index', methods: ['GET'])]
    public function indexStudent(UserRepository $userRepository): Response
    {
        $students = $userRepository->getUsersByRole('ROLE_ELEVE');
        return $this->render('user/student/index.html.twig', [
            'students' => $students,
        ]);
    }

    // Méthode pour ajouter un eleve
    #[Route('/student/new', name: 'app_student_new', methods: ['GET', 'POST'])]
    public function newStudent(Request $request,  UserRepository $userRepository, InfoUserRepository $infoUserRepository): Response
    {
        $user = new User();
        $form = $this->createForm(StudentType::class, $user);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            // Ici on instancie un objet de la classe InfoUser
            $infoUser = new InfoUser();

            // On récupere les infos du form pour infoUser
            $infoUser->setLastname($form->get('infoUser')->get('lastname')->getData());
            $infoUser->setFirstname($form->get('infoUser')->get('firstname')->getData());
            $infoUser->setBirthDate($form->get('infoUser')->get('birthDate')->getData());
            $infoUser->setPhone($form->get('infoUser')->get('phone')->getData());
            $infoUser->setAddress($form->get('infoUser')->get('address')->getData());
            $infoUser->setZipCode($form->get('infoUser')->get('zipCode')->getData());
            $infoUser->setCity($form->get('infoUser')->get('city')->getData());
            $infoUser->setCountry($form->get('infoUser')->get('country')->getData());

            // On enregistre les infoUser
            $infoUserRepository->save($infoUser, true);

            // On récupère l'id de infoUser
            $user->setInfoUser($infoUserRepository->find($infoUser->getId()));

            // On donne le role prof à l'utilisateur
            $user->setRoles(['ROLE_ELEVE']);

            // On encode le mot de passe
            $plainPassword = $form->get('password')->getData();

            if ($plainPassword) {
                $user->setPassword($this->encoder->hashPassword($user, $plainPassword));
            }

            // On enregistre l'utilisateur
            $userRepository->save($user, true);

            return $this->redirectToRoute('app_student_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('user/student/new.html.twig', [
            'user' => $user,
            'form' => $form,
        ]);
    }

    // Méthode pour afficher un eleve
    #[Route('/student/{id}', name: 'app_student_show', methods: ['GET'])]
    public function showStudent(User $user): Response
    {
        return $this->render('user/student/show.html.twig', [
            'user' => $user,
        ]);
    }

    // Méthode pour modfier un eleve
    #[Route('/student/{id}/edit', name: 'app_student_edit', methods: ['GET', 'POST'])]
    public function editStudent(Request $request, User $user, UserRepository $userRepository): Response
    {
        $form = $this->createForm(StudentType::class, $user);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {


            // On encode le mot de passe
            $plainPassword = $form->get('password')->getData();

            if ($plainPassword) {
                $user->setPassword($this->encoder->hashPassword($user, $plainPassword));
            }

            // On enregistre l'utilisateur
            $userRepository->save($user, true);

            return $this->redirectToRoute('app_student_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('user/student/edit.html.twig', [
            'user' => $user,
            'form' => $form,
        ]);
    }

    // Méthode pour supprimer un eleve
    #[Route('/student/{id}', name: 'app_student_delete', methods: ['POST'])]
    public function deleteStudent(Request $request, User $user, EntityManagerInterface $entityManager): Response
    {
        if ($this->isCsrfTokenValid('delete' . $user->getId(), $request->request->get('_token'))) {
            $entityManager->remove($user);
            $entityManager->flush();
        }

        return $this->redirectToRoute('app_student_index', [], Response::HTTP_SEE_OTHER);
    }

    // Partie Admin

    // Méthode pour afficher la liste des Admin
    #[Route('/admin', name: 'app_admin_index', methods: ['GET'])]
    public function indexAdmin(UserRepository $userRepository): Response
    {
        $admins = $userRepository->getUsersByRole('ROLE_ADMIN');
        return $this->render('user/admin/index.html.twig', [
            'admins' => $admins,
        ]);
    }

    // Méthode pour ajouter un Admin
    #[Route('/admin/new', name: 'app_admin_new', methods: ['GET', 'POST'])]
    public function newAdmin(Request $request,  UserRepository $userRepository, InfoUserRepository $infoUserRepository): Response
    {
        $user = new User();
        $form = $this->createForm(AdminType::class, $user);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            // Ici on instancie un objet de la classe InfoUser
            $infoUser = new InfoUser();

            // On récupere les infos du form pour infoUser
            $infoUser->setLastname($form->get('infoUser')->get('lastname')->getData());
            $infoUser->setFirstname($form->get('infoUser')->get('firstname')->getData());
            $infoUser->setBirthDate($form->get('infoUser')->get('birthDate')->getData());
            $infoUser->setPhone($form->get('infoUser')->get('phone')->getData());
            $infoUser->setAddress($form->get('infoUser')->get('address')->getData());
            $infoUser->setZipCode($form->get('infoUser')->get('zipCode')->getData());
            $infoUser->setCity($form->get('infoUser')->get('city')->getData());
            $infoUser->setCountry($form->get('infoUser')->get('country')->getData());

            // On enregistre les infoUser
            $infoUserRepository->save($infoUser, true);

            // On récupère l'id de infoUser
            $user->setInfoUser($infoUserRepository->find($infoUser->getId()));

            // On donne le role prof à l'utilisateur
            $user->setRoles(['ROLE_ADMIN']);

            // On encode le mot de passe
            $plainPassword = $form->get('password')->getData();

            if ($plainPassword) {
                $user->setPassword($this->encoder->hashPassword($user, $plainPassword));
            }

            // On enregistre l'utilisateur
            $userRepository->save($user, true);

            return $this->redirectToRoute('app_admin_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('user/admin/new.html.twig', [
            'user' => $user,
            'form' => $form,
        ]);
    }

    // Méthode pour afficher un Admin
    #[Route('/admin/{id}', name: 'app_admin_show', methods: ['GET'])]
    public function showAdmin(User $user): Response
    {
        return $this->render('user/admin/show.html.twig', [
            'user' => $user,
        ]);
    }

    // Méthode pour modfier un Admin
    #[Route('/admin/{id}/edit', name: 'app_admin_edit', methods: ['GET', 'POST'])]
    public function editAdmin(Request $request, User $user, UserRepository $userRepository): Response
    {
        $form = $this->createForm(AdminType::class, $user);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {


            // On encode le mot de passe
            $plainPassword = $form->get('password')->getData();

            if ($plainPassword) {
                $user->setPassword($this->encoder->hashPassword($user, $plainPassword));
            }

            // On enregistre l'utilisateur
            $userRepository->save($user, true);

            return $this->redirectToRoute('app_admin_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('user/admin/edit.html.twig', [
            'user' => $user,
            'form' => $form,
        ]);
    }

    // Méthode pour supprimer un Admin
    #[Route('/admin/{id}', name: 'app_admin_delete', methods: ['POST'])]
    public function deleteAdmin(Request $request, User $user, EntityManagerInterface $entityManager): Response
    {
        if ($this->isCsrfTokenValid('delete' . $user->getId(), $request->request->get('_token'))) {
            $entityManager->remove($user);
            $entityManager->flush();
        }

        return $this->redirectToRoute('app_admin_index', [], Response::HTTP_SEE_OTHER);
    }

    // PARTIES CLASSES


    // Méthode pour afficher la liste des classes par grade
    #[Route('/class', name: 'app_class_index', methods: ['GET'])]
    public function indexClass(SchoolClassRepository $schoolClassRepository, GradeRepository $gradeRepository)
    {
        // On va récupérer la liste des grades
        $grades = $gradeRepository->findAll();

        // On va passer la liste des grades au repo pour récupérer les classes par grade
        $classes = $schoolClassRepository->getClassesByGrade($grades);

        return $this->render('class/index.html.twig', [
            'classes' => $classes,
        ]);
    }

    // Méthode pour afficher une classe
    #[Route('/class/{id}', name: 'app_class_show', methods: ['GET'])]
    public function showClass(int $id, UserRepository $userRepository, SchoolClass $schoolClass): Response
    {

        $students = $userRepository->getUsersByClass($id);
        // On peux récupérer les eleves d'une classe grade à la méthode getUsers() de l'entité schoolClass
        $studentsBis = $schoolClass->getUsers();

        return $this->render('class/show.html.twig', [
            'students' => $studentsBis,
        ]);
    }

    #[Route('/class/{id}/student-add', name: 'app_student_class_add', methods: ['GET', 'POST'])]
    public function addStudentInClass(Request $request,  UserRepository $userRepository, SchoolClassRepository $schoolClassRepository, InfoUserRepository $infoUserRepository, $id)
    {
        $user = new User();
        $form = $this->createForm(StudentClassType::class, $user);
        $form->handleRequest($request);
        $schoolClass = $schoolClassRepository->find($id);


        if ($form->isSubmitted() && $form->isValid()) {
            // Ici on instancie un objet de la classe InfoUser
            $infoUser = new InfoUser();

            // On récupere les infos du form pour infoUser
            $infoUser->setLastname($form->get('infoUser')->get('lastname')->getData());
            $infoUser->setFirstname($form->get('infoUser')->get('firstname')->getData());
            $infoUser->setBirthDate($form->get('infoUser')->get('birthDate')->getData());
            $infoUser->setPhone($form->get('infoUser')->get('phone')->getData());
            $infoUser->setAddress($form->get('infoUser')->get('address')->getData());
            $infoUser->setZipCode($form->get('infoUser')->get('zipCode')->getData());
            $infoUser->setCity($form->get('infoUser')->get('city')->getData());
            $infoUser->setCountry($form->get('infoUser')->get('country')->getData());

            // On enregistre les infoUser
            $infoUserRepository->save($infoUser, true);

            // On récupère l'id de infoUser
            $user->setInfoUser($infoUserRepository->find($infoUser->getId()));

            // On donne le role prof à l'utilisateur
            $user->setRoles(['ROLE_ELEVE']);
            $user->setSchoolClass($schoolClass);

            // On encode le mot de passe
            $plainPassword = $form->get('password')->getData();

            if ($plainPassword) {
                $user->setPassword($this->encoder->hashPassword($user, $plainPassword));
            }

            // On enregistre l'utilisateur
            $userRepository->save($user, true);

            return $this->redirectToRoute('app_class_show', ['id' => $id], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('class/new.html.twig', [
            'user' => $user,
            'form' => $form,
        ]);
    }
}
