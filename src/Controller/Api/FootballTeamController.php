<?php

namespace App\Controller\Api;

use App\Entity\FootballTeam;
use App\Form\FootballTeamType;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

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
        if ($data === null) {
            return new JsonResponse(
                'Invalid json',
                Response::HTTP_BAD_REQUEST
            );
        }

        $form->submit($data);
        if ($form->isValid() === false) {
            // TODO: return useful errors
            return new JsonResponse(
                'Invalid form',
                Response::HTTP_BAD_REQUEST
            );
        }

        $em = $this->getDoctrine()->getManager();
        $em->persist($footballTeam);
        $em->flush();

        return new JsonResponse(
            $this->jsonSerializeFootballTeam($footballTeam),
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
        $footballTeam = $this->getDoctrine()
            ->getRepository(FootballTeam::class)
            ->find($id);

        if ($footballTeam === null) {
            return new JsonResponse(
                sprintf('Football Team with id %s not found', $id),
                Response::HTTP_NOT_FOUND
            );
        }

        $form = $this->createForm(FootballTeamType::class, $footballTeam);
        $data = json_decode($request->getContent(), true);
        if ($data === null) {
            // TODO: return useful errors
            return new JsonResponse(
                'Invalid json',
                Response::HTTP_BAD_REQUEST
            );
        }

        $form->submit($data);
        if ($form->isValid() === false) {
            // TODO: return useful errors
            return new JsonResponse(
                'Invalid form',
                Response::HTTP_BAD_REQUEST
            );
        }

        $em = $this->getDoctrine()->getManager();
        $em->flush();

        return new JsonResponse(
            json_encode($footballTeam->toArray()),
            Response::HTTP_OK,
            [],
            true
        );
    }

    /**
     * TODO: could use a more sophisticated serialiser such as the jms serialiser
     * @param FootballTeam $footballTeam
     * @return string
     */
    private function jsonSerializeFootballTeam(FootballTeam $footballTeam): string
    {
        return json_encode($footballTeam->toArray());
    }

}
