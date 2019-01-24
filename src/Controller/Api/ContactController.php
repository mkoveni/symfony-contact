<?php

namespace App\Controller\Api;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Request;
use App\Services\ContactService;
use Symfony\Component\Validator\Validator\ValidatorInterface;
use App\Entity\Contact;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Validator\ConstraintViolationList;

class ContactController extends AbstractCOntroller
{
    /**
     * contact service instace
     *
     * @var ContactService
     */
    protected $contactService;
    public function __construct(ContactService $contactService)
    {
        $this->contactService = $contactService;
    }
    /**
     * @Route("/api/contact", methods={"POST"})
     */
    public function create(Request $request, ValidatorInterface $validator)
    {
        $data = json_decode($request->getContent(), true);

        $contact = new Contact();
        $contact->setName($data['name']);
        $contact->setEmail($data['email']);
        $contact->setMessage($data['message']);

        $errors = $validator->validate($contact);

        if($errors->count() > 0) {

            return $this->json(['errors' => $this->formatErrors($errors)], Response::HTTP_UNPROCESSABLE_ENTITY);
        }

        $this->contactService->saveMessage($data);

        return $this->json(['message' => 'Thank you for contact us.'], Response::HTTP_OK);
    }

    protected function formatErrors(ConstraintViolationList $list)
    {
        $errors = [];

        foreach($list as $error)
        {
            if(!isset($errors[$error->getPropertyPath()])) {
                $errors[$error->getPropertyPath()] = [$error->getMessage()];
                continue;
            }

            $errors[$error->getPropertyPath()][] = $error->getMessage();
        }

        return $errors;
    }
}
