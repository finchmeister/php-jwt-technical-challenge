<?php

namespace App\Controller\Api;

use App\Entity\FootballLeague;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Serializer\Encoder\JsonEncoder;
use Symfony\Component\Serializer\Normalizer\ObjectNormalizer;
use Symfony\Component\Serializer\Serializer;


class FootballLeagueController extends AbstractController
{
    /**
     * 1. Get a list of football teams in a single league
     * @Route("/api/football-league/{id<\d+>}", name="api_football_league_show", methods={"GET"})
     */
    public function showAction($id)
    {
        $footballLeague = $this->getDoctrine()->getRepository(FootballLeague::class)
            ->find($id);

        // TODO: return json
        if ($footballLeague === null) {
            throw $this->createNotFoundException(sprintf(
                'Football League with id %s not found',
                $id
            ));
        }

        // TODO: extract
        $encoder = new JsonEncoder();
        $normalizer = new ObjectNormalizer();
        $serializer = new Serializer([$normalizer], [$encoder]);
        $normalizer->setIgnoredAttributes(['footballLeague']);

        return new JsonResponse(
            $serializer->serialize($footballLeague, 'json'),
Response::HTTP_OK,
            [],
            true
        );
    }

    /**
     * 4. Delete a football league
     * @Route("/api/football-league/{id<\d+>}", name="api_football_league_delete", methods={"DELETE"})
     */
    public function deleteAction($id)
    {
        $footballLeague = $this->getDoctrine()->getRepository(FootballLeague::class)
            ->find($id);

        // TODO: return json
        if ($footballLeague === null) {
            throw $this->createNotFoundException(sprintf(
                'Football League with id %s not found',
                $id
            ));
        }

        // TODO: extract
        $footballLeague->removeAllFootballTeams();
        $em = $this->getDoctrine()->getManager();
        $em->remove($footballLeague);
        $em->flush();

        return new JsonResponse(
            null,
            Response::HTTP_NO_CONTENT
        );
    }


}
