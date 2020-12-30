<?php

namespace ModernGame\Controller\Backend;

use ModernGame\Database\Repository\RegulationRepository;
use ModernGame\Service\Content\Regulation\RegulationService;
use Symfony\Component\HttpFoundation\JsonResponse;
use Swagger\Annotations as SWG;

class RegulationController
{
    /**
     * Get regulation
     *
     * Returns a list of rules which must be follow
     *
     * @SWG\Tag(name="Regulation")
     * @SWG\Response(
     *     response=200,
     *     description="Evertythig works",
     *     examples={
     *                  {
     *                      {
     *                          "name": "string",
     *                          "rules": {
     *                              {
     *                                  "description": "string"
     *                              },
     *                              {
     *                                  "description": "string"
     *                              }
     *                          }
     *                      }
     *                  }
     *           }
     *     )
     * )
     */
    public function getRules(RegulationRepository $repository)
    {
        return new JsonResponse($repository->getRegulationList());
    }
}
