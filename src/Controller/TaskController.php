<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Security\Core\User\UserInterface;
use App\Form\TaskType;
use App\Entity\Task;
use App\Entity\User;

class TaskController extends AbstractController
{

    public function index() {

        // Getting ORM manager
        $em = $this -> getDoctrine() -> getManager();
        // Getting repository
        $task_repo = $this -> getDoctrine() -> getRepository(Task::class);

        $tasks = $task_repo -> findBy([], ['id' => 'DESC']);


        // $user_repo = $this -> getDoctrine() -> getRepository(User::class);
        // $users = $user_repo -> findAll();
        //
        // foreach($users as $user) {
        //   echo "<h1>".$user -> getName().' '.$user -> getSurname()."</h1>";
        // }


        return $this->render('task/index.html.twig', [
            'tasks' => $tasks,
        ]);
    }

    public function detail(Task $task) {

      if(!$task) {

        return $this -> redirectToRoute('fonytask_index');

      } else {

        return $this -> render('task/detail.html.twig', [
          'task' => $task
        ]);

      }

    }

    public function create(Request $request, UserInterface $user) {

      $task = new Task();
      $form = $this -> createForm(TaskType::class, $task);

      $form -> handleRequest($request);

      if($form -> isSubmitted() && $form -> isValid()) {

        $task -> setCreatedAt(new \DateTime('now'));
        $task -> setUser($user);

        $em = $this -> getDoctrine() -> getManager();
        $em -> persist($task);
        $em -> flush();

        return $this -> redirect($this -> generateUrl('task_detail', ['id' => $task -> getId()]));

      }

      return $this -> render('task/create.html.twig', [
        'form' => $form -> createView()
      ]);
    }

    public function myTasks(UserInterface $user) {

      $tasks = $user -> getTasks();

      return $this -> render('task/my-tasks.html.twig', [
        'tasks' => $tasks
      ]);
    }

    public function edit(Request $request, Task $task, UserInterface $user) {

      if(!$user || $user -> getId() != $task -> getUser() -> getId()) {

        return $this -> redirectToRoute('fonytask_index');

      }

      $form = $this -> createForm(TaskType::class, $task);

      $form -> handleRequest($request);

      if($form -> isSubmitted() && $form -> isValid()) {

        $em = $this -> getDoctrine() -> getManager();
        $em -> persist($task);
        $em -> flush();

        return $this -> redirect($this -> generateUrl('task_detail', ['id' => $task -> getId()]));

      }

      return $this -> render('task/create.html.twig', [
        'edit' => true,
        'form' => $form -> createView()
      ]);

    }

    public function delete(Task $task, UserInterface $user) {

      if(!$user && $task -> getUser() -> getId()) {

        return $this -> redirectToRoute('fonytask_index');

      }

      $em = $this -> getDoctrine() -> getManager();
      $em -> remove($task);
      $em -> flush();

      return $this -> redirectToRoute('my_task', [
        'message' => 'La tarea se eliminó satisfactoriamente'
      ]);

    }


}
