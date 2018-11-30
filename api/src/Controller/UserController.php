<?php declare(strict_types=1);

namespace App\Controller;

use App\Entity\Company;
use App\Service\UserManager;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Validator\Validator\ValidatorInterface;

class UserController extends AbstractController
{
    /**
     * User register.
     *
     * @Route("/register", name="api_users_registration")
     * @Method("POST")
     * @throws \libphonenumber\NumberParseException
     */
    public function register(UserManager $userManager, Request $request, ValidatorInterface $validator)
    {
        $user = $userManager->registerUser($request->get('username'), $request->get('password'), $request->get('email'));

        $company = new Company();
        $company->setName($request->get('companyName'));
        $company->setEmail($request->get('email'));
        $company->setAddress($request->get('address'));
        $company->setPhone($request->get('phone'));
        $company->addUser($user);

        // todo: optimize validation
        $errors = $validator->validate($company);
        if (count($errors) > 0) {
            return $this->json($errors);
        }
        $errors = $validator->validate($user);
        if (count($errors) > 0) {
            return $this->json($errors);
        }

        $entityManager = $this->getDoctrine()->getManager();
        $entityManager->persist($user);
        $entityManager->persist($company);
        $entityManager->flush();

        return $this->json($user);
    }
}
