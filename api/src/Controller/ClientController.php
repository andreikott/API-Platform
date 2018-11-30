<?php declare(strict_types=1);

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Symfony\Component\HttpFoundation\Request;
use App\Entity\Client;
use App\Repository\CompanyRepository;

class ClientController extends AbstractController
{
    /**
     * @Route("/clients/create", name="api_clients_add")
     * @Method("POST")
     *
     * @throws \libphonenumber\NumberParseException
     */
    public function addClient(Request $request, CompanyRepository $companyRepository)
    {
        $company = $companyRepository->find($this->getUser()->getCompanies()->first());

        $client = new Client();
        $client->setName($request->get('name'));
        $client->setEmail($request->get('email'));
        $client->setPhone($request->get('phone'));
        $client->setDescription($request->get('description'));
        $client->setCompany($company);

        $entityManager = $this->getDoctrine()->getManager();
        $entityManager->persist($client);
        $entityManager->flush();

        return $this->json($client);
    }
}
