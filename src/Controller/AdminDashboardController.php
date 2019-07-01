<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Core\Authentication\Token\Storage\TokenStorage;

class AdminDashboardController extends AbstractController
{
    /**
     * @Route("/admin/dashboard", name="adminDashboard")
     */
    public function dashboard()
    {
        $user = $this->get('security.token_storage')->getToken()->getUser();

        return $this->render('admin_dashboard/dash.html.twig', [
            'user'=>$user
        ]);
    }

    /**
     * @Route("/admin/dashboard/logout", name="dashLogout")
     */
    public function dashLogout()
    {
        $this->get('security.token_storage')->setToken(null);

        return $this->redirectToRoute('appHomepage', []);
    }

}
