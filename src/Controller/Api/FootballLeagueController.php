<?php

namespace App\Controller\Api;

use App\Entity\FootballLeague;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;


class FootballLeagueController extends AbstractController
{
    /**
     * 1. Get a list of football teams in a single league
     * @Route("/api/football-league/{id<\d+>}", name="api_football_league_show", methods={"GET"})
     */
    public function showAction($id): JsonResponse
    {
        $footballLeague = $this->getDoctrine()
            ->getRepository(FootballLeague::class)
            ->find($id);

        if ($footballLeague === null) {
            return new JsonResponse(
                sprintf('Football League with id %s not found', $id),
                Response::HTTP_BAD_REQUEST
            );
        }

        return new JsonResponse(
            $this->jsonSerialiseFootballLeague($footballLeague),
            Response::HTTP_OK,
            [],
            true
        );
    }

    /**
     * 4. Delete a football league
     * @Route("/api/football-league/{id<\d+>}", name="api_football_league_delete", methods={"DELETE"})
     */
    public function deleteAction($id): JsonResponse
    {
        $footballLeague = $this->getDoctrine()
            ->getRepository(FootballLeague::class)
            ->find($id);

        if ($footballLeague === null) {
            return new JsonResponse(
                sprintf('Football League with id %s not found', $id),
                Response::HTTP_BAD_REQUEST
            );
        }

        $footballLeague->removeAllFootballTeams();
        $em = $this->getDoctrine()->getManager();
        $em->remove($footballLeague);
        $em->flush();

        return new JsonResponse(
            null,
            Response::HTTP_NO_CONTENT
        );
    }

    /**
     * TODO: could use a more sophisticated serialiser such as the jms serialiser
     * @param FootballLeague $footballLeague
     * @return string
     */
    protected function jsonSerialiseFootballLeague(FootballLeague $footballLeague): string
    {
        return json_encode($footballLeague->toArray());
    }
}
