<?php

declare(strict_types=1);

namespace App\Controller;

use App\Entity\Skill;
use App\Form\SkillType;
use App\Repository\SkillRepository;
use Nelmio\ApiDocBundle\Annotation\Model;
use OpenApi\Attributes as OA;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

#[Route('/api/skill')]
class SkillController extends AbstractController
{
    #[OA\Get(
        summary: "Get list of skills",
        tags: ["Skill"],
        responses: [
            new OA\Response(
                response: 200,
                description: "Returns the list of skills",
                content: new OA\JsonContent(
                    type: "array",
                    items: new OA\Items(ref: new Model(type: Skill::class, groups: ["api"])),
                ),
            ),
        ],
    )]
    #[Route('/', methods: ['GET'])]
    public function list(SkillRepository $skillRepository): Response
    {
        return $this->json(data: $skillRepository->findAll(), context: ['groups' => ['api']]);
    }

    #[OA\Post(
        summary: "Create a new skill",
        requestBody: new OA\RequestBody(
            description: "Skill to add",
            required: true,
            content: new OA\JsonContent(ref: new Model(type: SkillType::class), example: ["name" => "Skill 1"]),
        ),
        tags: ["Skill"],
        responses: [
            new OA\Response(
                response: 200,
                description: "Returns the created skill",
                content: new OA\JsonContent(ref: new Model(type: Skill::class, groups: ["api"])),
            ),
            new OA\Response(
                response: 400,
                description: "Bad request when the form is not valid",
            ),
        ],
    )]
    #[Route('/', methods: ['POST'])]
    public function create(
        SkillRepository $skillRepository,
        Request $request,
    ): Response {
        $skill = new Skill();
        $form = $this->createForm(SkillType::class, $skill);
        $form->submit($request->getPayload()->all());
        if ($form->isSubmitted() && $form->isValid()) {
            $skillRepository->save($skill);

            return $this->json(data: $skill, context: ['groups' => ['api']]);
        }

        return new Response(null, Response::HTTP_BAD_REQUEST);
    }
}
