<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Component\HttpClient\HttpClient;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;

class StationController extends AbstractController
{
    /**
     * @Route("/", name="map")
     */
    public function index(Request $request)
    {
        $client = HttpClient::create();

        $form = $this->createFormBuilder()
            ->add('distance', TextType::class)
            ->add('save', SubmitType::class)
            ->getForm();

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $data = $form->getData();
            $response = $client->request('GET', 'http://localhost:9000/stations/' . $data["distance"]);
        } else {
            $response = $client->request('GET', 'http://localhost:9000/stations/');
        }

        $content = $response->toArray();

        return $this->render('base.html.twig', [
            'stations' => $content,
            'distance' => 0,
            'form' => $form->createView()
        ]);
    }
}
