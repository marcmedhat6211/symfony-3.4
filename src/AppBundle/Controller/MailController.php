<?php

namespace AppBundle\Controller;


use ProductBundle\Entity\Category;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\Routing\Annotation\Route;

class MailController extends Controller
{
    /**
     * @Route("/send-mail", name="send-mail")
     */
    public function indexAction(\Swift_Mailer $mailer)
    {
        $em = $this->getDoctrine()->getManager();
        $categories = $em->getRepository(Category::class)->findAll();


        $message = (new \Swift_Message('Categories Email'))
            ->setFrom('marc@gmail.com')
            ->setTo('peter@gmail.com')
            ->setBody(
                $this->renderView(
                // app/Resources/views/Emails/registration.html.twig
                    'email/categories.html.twig', [
                        'categories' => $categories,
                    ]
                ),
                'text/html'
            );

        $mailer->send($message);
    }
}