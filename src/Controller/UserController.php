<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Request;
use App\Entity\User;
use App\Form\RegisterType;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;
use Symfony\Component\Security\Http\Authentication\AuthenticationUtils;

class UserController extends AbstractController
{


    public function register(Request $request, UserPasswordEncoderInterface $encoder) {

      // Create form
      $user = new User();
      $form = $this -> createForm(RegisterType::class, $user);

      // Hydrate user object y form data
      $form -> handleRequest($request);

      // See if form was submitted
      if($form -> isSubmitted() && $form -> isValid()) {
         // Modifying object as to save in database
        $user -> setRole('ROLE_USER');

        $user -> setCreatedAt(new \DateTime('now'));

        // Password encrypting
        $encoded_password = $encoder -> encodePassword($user, $user -> getPassword());
        $user -> setPassword($encoded_password);

        // Save User
        $em = $this -> getDoctrine() -> getManager();
        $em -> persist($user);
        $em -> flush();

        return $this -> redirectToRoute('fonytask_index');
      }

      return $this -> render('user/register.html.twig', [
          'form' => $form -> createView(),
      ]);
    }

    public function login(AuthenticationUtils $authenticationUtils) {

      $error = $authenticationUtils -> getLastAuthenticationError();
      $lastUsername = $authenticationUtils -> getLastUsername();

      return $this -> render('user/login.html.twig', array(
        'error' => $error,
        'last_username' => $lastUsername
      ));
    }
}
