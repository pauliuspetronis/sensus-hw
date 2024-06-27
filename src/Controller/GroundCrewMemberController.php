<?php

declare(strict_types=1);

namespace App\Controller;

use App\Entity\GroundCrewMember;
use App\Entity\GroundCrewMemberTask;
use App\Form\GroundCrewMemberType;
use App\Repository\GroundCrewMemberRepository;
use App\Service\GroundCrewMemberTaskManager;
use Nelmio\ApiDocBundle\Annotation\Model;
use OpenApi\Attributes as OA;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

#[Route('/api/ground-crew-member')]
class GroundCrewMemberController extends AbstractController
{
    #[OA\Get(
        summary: "Get list of Ground Crew Members",
        tags: ["Ground Crew Member"],
        responses: [
            new OA\Response(
                response: 200,
                description: "Returns the list of ground crew members",
                content: new OA\JsonContent(
                    type: "array",
                    items: new OA\Items(ref: new Model(type: GroundCrewMember::class, groups: ["api"])),
                ),
            ),
        ],
    )]
    #[Route('/', methods: ['GET'])]
    public function list(GroundCrewMemberRepository $repository): Response
    {
        return $this->json(data: $repository->findAll(), context: ['groups' => ['api']]);
    }

    #[OA\Post(
        summary: "Create a new Ground Crew Member",
        requestBody: new OA\RequestBody(
            description: "Ground Crew Member to add",
            required: true,
            content: new OA\JsonContent(
                ref: new Model(type: GroundCrewMemberType::class),
                example: [
                    "fullName" => "John Doe",
                    "skills" => [1, 2],
                    "groundCrewMemberCertifications" => [
                        ["certification" => 1, "expirationDate" => "2022-12-31"],
                        ["certification" => 2, "expirationDate" => "2022-12-31"],
                    ],
                ],
            ),
        ),
        tags: ["Ground Crew Member"],
        responses: [
            new OA\Response(
                response: 200,
                description: "Returns the created ground crew member",
                content: new OA\JsonContent(ref: new Model(type: GroundCrewMember::class, groups: ["api"])),
            ),
            new OA\Response(
                response: 400,
                description: "Bad request when the form is not valid",
            ),
        ],
    )]
    #[Route('/', methods: ['POST'])]
    public function create(
        GroundCrewMemberRepository $repository,
        Request $request,
    ): Response {
        $groundCrewMember = new GroundCrewMember();
        $form = $this->createForm(GroundCrewMemberType::class, $groundCrewMember);
        $form->submit($request->getPayload()->all());
        if ($form->isSubmitted() && $form->isValid()) {
            $repository->save($groundCrewMember);

            return $this->json(data: $groundCrewMember, context: ['groups' => ['api']]);
        }

        return new Response(null, Response::HTTP_BAD_REQUEST);
    }

    #[OA\Put(
        summary: "Complete Ground Crew Member Task",
        tags: ["Ground Crew Member"],
        parameters: [
            new OA\Parameter(
                name: "member",
                description: "Ground Crew Member ID",
                in: "path",
                required: true,
                schema: new OA\Schema(type: "integer"),
            ),
            new OA\Parameter(
                name: "task",
                description: "Task to complete ID",
                in: "path",
                required: true,
                schema: new OA\Schema(type: "integer"),
            ),
        ],
        responses: [
            new OA\Response(
                response: 200,
                description: "Returns the created ground crew member",
                content: new OA\JsonContent(ref: new Model(type: GroundCrewMember::class, groups: ["api"])),
            ),
            new OA\Response(
                response: 400,
                description: "Bad request when the form is not valid",
            ),
        ],
    )]
    #[Route('/{member}/task/{task}/complete', methods: ['PUT'])]
    public function completeTask(
        GroundCrewMember $member,
        GroundCrewMemberTask $task,
        GroundCrewMemberTaskManager $taskManager,
    ): Response {
        if (!$member->getGroundCrewMemberTasks()->contains($task)) {
            throw $this->createNotFoundException();
        }
        $taskManager->complete($task);

        return $this->json(data: $task, context: ['groups' => ['api']]);
    }
}
