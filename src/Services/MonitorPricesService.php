<?php

namespace App\Services;

use App\Repository\AdvertRepository;
use App\Entity\Advert;
use Symfony\Component\Mailer\MailerInterface;
use Symfony\Component\Mime\Email;

class MonitorPricesService
{

    private AdvertRepository $advertRepository;
    private OlxCrawler $olxCrawler;
    private MailerInterface $mailer;

    public function __construct(AdvertRepository $advertRepository, OlxCrawler $olxCrawler, MailerInterface $mailer)
    {
        $this->advertRepository = $advertRepository;
        $this->olxCrawler = $olxCrawler;
        $this->mailer = $mailer;
    }

    public function execute()
    {
        $adverts = $this->advertRepository->findAll();
        foreach ($adverts as $advert) {
            $this->monitorPrice($advert);
        }
    }

    protected function monitorPrice(Advert $advert)
    {
        $newPrice = $this->olxCrawler->getPrice($advert->getLink());
        if ($newPrice != 0 && $newPrice != $advert->getPrice()) {

            $this->updatePrice($advert, $newPrice);
        }
    }

    protected function updatePrice(Advert $advert, $newPrice)
    {
        $advert->setPrice($newPrice);
        $this->advertRepository->save($advert, true);
        $this->sendNotification($advert);
    }

    protected function sendNotification($advert)
    {
        foreach ($advert->getSubscribers() as $subscriber) {
            $advert->getLink();
            $advert->getPrice();
            $body = sprintf('Hello, the price in the  <a href="%s">ad</a> has changed. New price - %s UAH', $advert->getLink(), $advert->getPrice());
            $email = (new Email())
                ->from('hello@example.com')
                ->to($subscriber->getEmail())
                ->subject('Update Price!')
                ->text($body);
            $this->mailer->send($email);
        }
    }
}