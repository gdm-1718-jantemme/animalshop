<?php

namespace App\Controller;

use App\Entity\Task;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Form\Extension\Core\Type\DateTimeType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use function Sodium\add;

class TaskController extends AbstractController
{
    /**
     * @Route("/task", name="task")
     */
    public function index()
    {
        return $this->render('task/index.html.twig', [
            'controller_name' => 'TaskController',
        ]);
    }

    /**
     * @Route("/task/create", name="TaskCreate")
     */
    public function addTask(Request $request)
    {
        /**$task = new Task();
        $task->setName("Dierenvoer verkopen");
        $task->setDescription("Zoveel mogelijk");
        $task->setDeadline(new \DateTime('today'));

        $em = $this->getDoctrine()->getManager();
        $em->persist($task);
        $em->flush();

        return $this->json('Taak ingevoerd met id: ' . $task->getId());
         **/

        $task = new Task();

        date_default_timezone_set('Europe/Brussels');
        $form = $this->createFormBuilder($task)
            ->add('name', TextType::class, [
                'label'=>'Give the name of the task',
                'empty_data'=>''
            ])
            ->add('description', TextareaType::class)
            ->add('deadline', DateTimeType::class)
            ->add('save', SubmitType::class)
            ->getForm();

        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $data = $form->getData();

            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist($data);
            $entityManager->flush();

            return $this->redirectToRoute("task");
        }

        return $this->render('task/new.html.twig', [
            'form'=>$form->createView()
        ]);
    }
}
