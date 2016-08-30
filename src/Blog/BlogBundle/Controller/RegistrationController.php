<?php

namespace Blog\BlogBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Blog\BlogBundle\Entity\User;
use Blog\BlogBundle\Form\UserType;


class RegistrationController extends Controller
{

    public function registerAction(Request $request)
    {
        $user = new User();

        $user->setRoles();
        $form = $this->createForm(UserType::class, $user);

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $password = $this->get('security.password_encoder')
                ->encodePassword($user, $user->getPlainPassword());
            $user->setPassword($password);

            $em = $this->getDoctrine()->getManager();
            $em->persist($user);
            $em->flush();

            return $this->redirect('http://localhost:8888/blog/web/app_dev.php/login');
        }

        return $this->render(
            'BlogBlogBundle:Registration:register.html.twig', array(
            'form' => $form->createView()
        ));
    }


}