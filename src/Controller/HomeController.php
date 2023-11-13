<?php

namespace App\Controller;

use Doctrine\DBAL\Connection;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class HomeController extends AbstractController
{
    public function __construct(
        private readonly Connection $connection
    ) {
    }

    #[Route('/', name: 'home')]
    public function index(): Response
    {
        return $this->render('home/index.html.twig', [
            'controller_name' => 'HomeController',
        ]);
    }

    #[Route('/sql', name: 'sql')]
    public function sqlInjection(Request $request): Response
    {
        $name = $request->query->get('name');

        if ($request->request->get('sqlCheckbox')) {
            // this is the SQL injection vulnerable case
            $data = $this->connection->fetchAllAssociative(
                'SELECT * FROM user WHERE full_name=$name'
            );
        } else {
            // this is the SQL injection protected case
//            $this->connection->prepare()
            $data = $this->connection->fetchAllAssociative(
                'SELECT * FROM user WHERE full_name=:name', ['name' => $name]
            );
        }

        dump($data);

        return $this->render('home/index.html.twig', [
            'user_name' => $name
        ]);
    }
}
