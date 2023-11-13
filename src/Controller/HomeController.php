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
        return $this->render('home/index.html.twig');
    }

    #[Route('/sql', name: 'sql')]
    public function sqlInjection(Request $request): Response
    {
        $name = $request->request->get('name');

        if ($request->request->get('sqlCheckbox') !== null) {
//            $txt = "nije null";

            // this is the SQL injection vulnerable case
            $data = $this->connection->fetchAllAssociative(
                'SELECT * FROM app_user WHERE full_name=$name'
            );
        } else {
//            $txt = "je null";

            // this is the SQL injection protected case
            $data = $this->connection->fetchAllAssociative(
                'SELECT * FROM app_user WHERE full_name=:name', ['name' => $name]
            );
        }

        return $this->render('home/users.html.twig', [
            'user_name' => $name,
            'data' => $data
        ]);
    }
}
