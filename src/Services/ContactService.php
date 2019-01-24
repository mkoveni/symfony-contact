<?php

namespace App\Services;

use App\Repository\ContactRepository;
use App\Entity\Contact;
use Symfony\Component\DependencyInjection\ContainerInterface;
use Doctrine\ORM\EntityManagerInterface;


class ContactService
{
    /**
     * Contact repository instance
     *
     * @var EntityManager
     */
    protected $entityManager;

    /**
     * Mailer instance
     *
     * @var \Swift_Mailer
     */
    protected $mailer;

    /**
     * @var ContainerInterface
     */
    protected $container;

    public function __construct(EntityManagerInterface $entityManager, \Swift_Mailer $mailer, ContainerInterface $container)
    {
        $this->entityManager = $entityManager;
        $this->mailer = $mailer;
        $this->container = $container;
    }

    public function saveMessage(array $data)
    {
        $contact = new Contact;
        $contact->setName($data['name']);
        $contact->setMessage($data['message']);
        $contact->setEmail($data['email']);

        $this->entityManager->persist($contact);

        $this->entityManager->flush();

        $this->sendEmail('Thanks!', $data['email'], 'email/user.twig', $data);

        //$this->sendEmail('New Message', getenv('SITE_ADMIN'), 'email/admin.twig');
    }

    private function sendEmail($subject, $toAddress, $view, $params = null)
    {
        $twig = $this->container->get('twig');

        $message = new \Swift_Message($subject);
        $message->setFrom(getenv('FROM_ADDRESS'));
        $message->setTo($toAddress);
        $message->setBody($twig->render($view, $params), 'text/html');
        
        $this->mailer->send($message);
        
    }

    
}