<?php

declare(strict_types=1);

namespace App\Controller;

use App\Form\SearchEventType;
use App\Models\SearchEvent;
use App\Util\SearchApiEvent;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

class MainController extends AbstractController
{
    #[Route('/', name: 'main_home', methods: ['GET'])]
    public function home(): Response
    {
        return $this->render('main/home.html.twig');
    }

    #[Route('/about-us', name: 'main_about_us', methods: ['GET'])]
    public function aboutUs(): Response
    {
        return $this->render('main/about_us.html.twig');
    }

    #[Route('/events', name: 'main_events', methods: ['GET','POST'])]
    public function events(Request $request, SearchApiEvent $searchApiEvent): Response
    {
        $events=[];
        $searchEvent = new SearchEvent();
//        $searchEvent->dateEvent = new \DateTimeImmutable();
        $eventForm=$this->createForm(SearchEventType::class, $searchEvent);
        $eventForm->handleRequest($request);

        if ($eventForm->isSubmitted() && $eventForm->isValid()) {
            //dd("eventForm");
            $events = $searchApiEvent->search($searchEvent);
            //dd($events);
        }
        //dd($events);
        return $this->render('main/events.html.twig',[
            'events'=>$events,
            'eventForm'=>$eventForm,
        ]);
    }


}
