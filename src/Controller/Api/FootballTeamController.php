<?php

namespace App\Controller\Api;

use App\Entity\FootballLeague;
use App\Entity\FootballTeam;
use App\Form\FootballTeamType;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Serializer\Encoder\JsonEncoder;
use Symfony\Component\Serializer\Normalizer\ObjectNormalizer;
use Symfony\Component\Serializer\Serializer;

class FootballTeamController extends AbstractController
{
    /**
     * 2. Create a football team
     * @Route("/api/football-team", name="api_football_team_new", methods={"POST"})
     * @param Request $request
     * @return JsonResponse
     */
    public function newAction(Request $request): JsonResponse
    {
        $footballTeam = new FootballTeam();
        $form = $this->createForm(FootballTeamType::class, $footballTeam);

        $data = json_decode($request->getContent(), true);
        $form->submit($data);

        $em = $this->getDoctrine()->getManager();
        $em->persist($footballTeam);
        $em->flush();

        // TODO: extract
        $encoder = new JsonEncoder();
        $normalizer = new ObjectNormalizer();
        $serializer = new Serializer([$normalizer], [$encoder]);

        return new JsonResponse(
            $serializer->serialize($footballTeam, 'json'),
            Response::HTTP_CREATED,
            [],
            true
        );
    }

    /**
     * 3. Modify all attributes of a football team
     * @Route("/api/football-team/{id<\d+>}", name="api_football_team_update", methods={"PUT"})
     * @param Request $request
     */
    public function updateAction(int $id, Request $request)
    {
        $footballTeam = $this->getDoctrine()->getRepository(FootballTeam::class)->find($id);


        if ($footballTeam === null) {
            throw $this->createNotFoundException(sprintf(
                'Football Team with id %s not found',
                $id
            ));
        }

        $form = $this->createForm(FootballTeamType::class, $footballTeam);

        $data = json_decode($request->getContent(), true);
        $form->submit($data);

        $em = $this->getDoctrine()->getManager();
        $em->flush();

        // TODO: extract
        $encoder = new JsonEncoder();
        $normalizer = new ObjectNormalizer();
        $serializer = new Serializer([$normalizer], [$encoder]);

        return new JsonResponse(
            $serializer->serialize($footballTeam, 'json'),
            Response::HTTP_OK,
            [],
            true
        );
    }

}
