<?php declare(strict_types=1);

namespace App\Controller;

use App\Entity\Project;
use App\Entity\Request as RequestEntity;
use App\Repository\ProjectRepository;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;

class ProjectController extends AbstractController
{
    /**
     * Request to project transformation by id.
     *
     * @Route("/projects/create/{id}/request", name="api_projects_make_request")
     * @Method("GET")
     */
    public function makeRequest(RequestEntity $requestEntity)
    {
        $project = new Project();
        $project->setName($requestEntity->getName());
        $project->setProduct($requestEntity->getProduct());
        $project->setPrice($requestEntity->getPrice());
        $project->setDescription($requestEntity->getDescription());
        $project->setStartDate($requestEntity->getStartDate());
        $project->setEndDate($requestEntity->getEndDate());
        $project->setStatus($requestEntity->getStatus());
        $entityManager = $this->getDoctrine()->getManager();
        $entityManager->persist($project);
        $entityManager->flush();

        return $this->json($project);
    }

    /**
     * Project's filters.
     *
     * @Route("/projects/filters", name="api_projects_filters")
     * @Method("GET")
     */
    public function filterProjects(Request $request, ProjectRepository $projectRepository)
    {
        $projects = $projectRepository->filters(
            $request->query->getBoolean('cancelled'),
            $request->query->getBoolean('unpaid'),
            $request->query->getBoolean('overdue'),
            $request->query->getBoolean('fridaySaturday'),
            $request->query->getBoolean('saturdaySunday'),
            $request->query->getInt('clientId'),
            $request->query->get('usersId')
        );

        return $this->json($projects);
    }
}
