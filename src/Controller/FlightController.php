<?php

declare(strict_types=1);

namespace App\Controller;

use App\Entity\Flight;
use App\Enum\FlightTypeEnum;
use App\Form\FlightType;
use App\Repository\FlightRepository;
use App\Service\FlightManager;
use Nelmio\ApiDocBundle\Annotation\Model;
use OpenApi\Attributes as OA;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

#[Route('/api/flight')]
class FlightController extends AbstractController
{
    #[OA\Get(
        summary: "Get list of flights",
        tags: ["Flight"],
        responses: [
            new OA\Response(
                response: 200,
                description: "Returns the list of flights",
                content: new OA\JsonContent(
                    type: "array",
                    items: new OA\Items(ref: new Model(type: Flight::class, groups: ["api"])),
                ),
            ),
        ],
    )]
    #[Route('/', methods: ['GET'])]
    public function list(FlightRepository $flightRepository): Response
    {
        return $this->json(data: $flightRepository->findAll(), context: ['groups' => ['api']]);
    }

    #[OA\Post(
        summary: "Create a new flight",
        requestBody: new OA\RequestBody(
            description: "Flight to add",
            required: true,
            content: new OA\JsonContent(
                ref: new Model(type: FlightType::class),
                example: [
                    "number" => "Flight 1",
                    "type" => FlightTypeEnum::arriving,
                    "tasks" => [
                        ["title" => "Task 1", "requiredSkills" => [1, 2]],
                        ["title" => "Task 2", "requiredSkills" => [2, 3]],
                    ],
                ],
            ),
        ),
        tags: ["Flight"],
        responses: [
            new OA\Response(
                response: 200,
                description: "Returns the created flight",
                content: new OA\JsonContent(ref: new Model(type: Flight::class, groups: ["api"])),
            ),
            new OA\Response(
                response: 400,
                description: "Bad request when the form is not valid",
            ),
        ],
    )]
    #[Route('/', methods: ['POST'])]
    public function create(
        FlightManager $flightManager,
        Request $request,
    ): Response {
        $flight = new Flight();
        $form = $this->createForm(FlightType::class, $flight);
        $form->submit($request->getPayload()->all());
        if ($form->isSubmitted() && $form->isValid()) {
            $flightManager->register($flight);

            return $this->json(data: $flight, context: ['groups' => ['api']]);
        }

        return new Response(null, Response::HTTP_BAD_REQUEST);
    }
}
