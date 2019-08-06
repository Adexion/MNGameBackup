<?php

namespace ModernGame\Controller\Admin;

use ModernGame\Database\Entity\RegulationCategory;
use ModernGame\Database\Repository\RegulationCategoryRepository;
use ModernGame\Service\Content\RegulationCategoryService;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

class RegulationCategoryController extends AbstractController
{
    public function postRegulationCategory(Request $request, RegulationCategoryService $regulationService)
    {
        $regulation = $regulationService->getMappedRegulationCategory($request);

        /** @var RegulationCategoryRepository $regulationCategoryRepository */
        $regulationCategoryRepository = $this->getDoctrine()->getRepository(RegulationCategory::class);
        $regulationCategoryRepository->insert($regulation);

        return new JsonResponse(null, Response::HTTP_OK);
    }

    public function putRegulationCategory(Request $request, RegulationCategoryService $regulationService)
    {
        $regulation = $regulationService->getMappedRegulationCategory($request);

        /** @var RegulationCategoryRepository $regulationCategoryRepository */
        $regulationCategoryRepository = $this->getDoctrine()->getRepository(RegulationCategory::class);
        $regulationCategoryRepository->update($regulation);

        return new JsonResponse(null, Response::HTTP_OK);
    }

    public function deleteRegulationCategory(Request $request)
    {
        /** @var RegulationCategoryRepository $regulationCategoryRepository */
        $regulationCategoryRepository = $this->getDoctrine()->getRepository(RegulationCategory::class);
        $regulationCategoryRepository->delete($request->request->getInt('id'));

        return new JsonResponse(null, Response::HTTP_OK);
    }
}
