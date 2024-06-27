<?php

declare(strict_types=1);

namespace App\Controller;

use App\Entity\Certification;
use App\Form\CertificationType;
use App\Repository\CertificationRepository;
use Nelmio\ApiDocBundle\Annotation\Model;
use OpenApi\Attributes as OA;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

#[Route('/api/certification')]
class CertificationController extends AbstractController
{
    #[OA\Get(
        summary: "Get list of certifications",
        tags: ["Certification"],
        responses: [
            new OA\Response(
                response: 200,
                description: "Returns the list of certifications",
                content: new OA\JsonContent(
                    type: "array",
                    items: new OA\Items(ref: new Model(type: Certification::class, groups: ["api"])),
                ),
            ),
        ],
    )]
    #[Route('/', methods: ['GET'])]
    public function list(CertificationRepository $certificationRepository): Response
    {
        return $this->json(data: $certificationRepository->findAll(), context: ['groups' => ['api']]);
    }

    #[OA\Post(
        summary: "Create a new certification",
        requestBody: new OA\RequestBody(
            description: "Certification to add",
            required: true,
            content: new OA\JsonContent(ref: new Model(type: CertificationType::class), example: ["title" => "Certification 1"]),
        ),
        tags: ["Certification"],
        responses: [
            new OA\Response(
                response: 200,
                description: "Returns the created certification",
                content: new OA\JsonContent(ref: new Model(type: Certification::class, groups: ["api"])),
            ),
            new OA\Response(
                response: 400,
                description: "Bad request when the form is not valid",
            ),
        ],
    )]
    #[Route('/', methods: ['POST'])]
    public function create(
        CertificationRepository $certificationRepository,
        Request $request,
    ): Response {
        $certification = new Certification();
        $form = $this->createForm(CertificationType::class, $certification);
        $form->submit($request->getPayload()->all());
        if ($form->isSubmitted() && $form->isValid()) {
            $certificationRepository->save($certification);

            return $this->json(data: $certification, context: ['groups' => ['api']]);
        }

        return new Response(null, Response::HTTP_BAD_REQUEST);
    }
}
