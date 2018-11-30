<?php declare(strict_types=1);

namespace App\Controller;

use App\Repository\ProjectRepository;
use App\Repository\RequestRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;

class CalendarController extends AbstractController
{
    /**
     *
     * @param Request $request
     * @param ProjectRepository $projectRepository
     * @param RequestRepository $requestRepository
     *
     * @return \Symfony\Component\HttpFoundation\JsonResponse
     */
    public function calendar(Request $request, ProjectRepository $projectRepository, RequestRepository $requestRepository)
    {
        $projects = $this->filterProjects($request, $projectRepository);
        $requests = $this->filterRequests($request, $requestRepository);

        return $this->json([
            'request' => $requests,
            'project' => $projects,
        ]);
    }

    /**
     * Project's filters.
     *
     * @param Request $request
     * @param RequestRepository $requestRepository
     *
     * @return mixed
     */
    protected function filterRequests(Request $request, RequestRepository $requestRepository)
    {
        $requests = $requestRepository->filters(
            $request->query->getBoolean('fridaySaturday'),
            $request->query->getBoolean('saturdaySunday'),
            $request->query->getInt('clientId'),
            $request->query->getInt('usersId')
        );

        return $requests;
    }

    /**
     * Project's filters.
     *
     * @param Request $request
     * @param ProjectRepository $projectRepository
     *
     * @return mixed
     */
    protected function filterProjects(Request $request, ProjectRepository $projectRepository)
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

        return $projects;
    }
}
