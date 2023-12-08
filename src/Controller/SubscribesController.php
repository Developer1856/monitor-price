<?php

namespace App\Controller;

use App\Repository\AdvertRepository;
use App\Repository\SubscriberRepository;
use App\Services\OlxCrawler;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Validator\Constraints as Assert;
use Symfony\Component\Validator\Validator\ValidatorInterface;

class SubscribesController extends AbstractController
{
    #[Route(path: '/subscribe', name: 'subscribe', methods: ['GET'])]
    public function subscribe(Request $request, SubscriberRepository $subscriberRepository, AdvertRepository $advertRepository, EntityManagerInterface $entityManager, ValidatorInterface $validator, OlxCrawler $olxCrawler): Response
    {
        $email = $request->get('email');
        $link = $request->get('link');
        $link = 'https://www.olx.ua/d/uk/obyavlenie/peredniy-bamper-mercedes-ml-w164-dorestayl-peredny-bamper-ml-164-c197-IDPZaWM.html?reason=hp%7Cpromoted';
        if ($olxCrawler->checkLink($link) != 200) {
            return new Response('Check the link - page not found', 400);
        }
        $price = $olxCrawler->getPrice($link);

        if ($price <= 0) {
            return new Response('There is no price on the page', 400);
        }
        $emailConstraint = new Assert\Email();
        $emailConstraint->message = 'Invalid email address';
        $errorList = $validator->validate(
            $email,
            $emailConstraint
        );

        if (count($errorList) >= 1) {
            $message = '';
            foreach ($errorList as $error) {
                $message .= $error->getMessage();
            }
            return new Response($message, 400);
        }
        $subscriber = $subscriberRepository->findOrCreate($email);
        $advert = $advertRepository->findOrCreate($link);
        $advert->setPrice($price);
        $entityManager->persist($advert);
        $entityManager->flush();
        $subscriber->addAd($advert);
        $entityManager->persist($subscriber);
        $entityManager->flush();
        return new Response('Congratulations when the price changes. - you will receive a notification by email');
    }
}
