<?php

namespace ModernGame\Controller\Front;

use ModernGame\Database\Entity\AdminServerUser;
use ModernGame\Database\Entity\Article;
use ModernGame\Database\Repository\RegulationRepository;
use ModernGame\Exception\ContentException;
use ModernGame\Service\Connection\Minecraft\RCONService;
use ModernGame\Service\ServerProvider;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Cookie;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class HomepageController extends AbstractController
{
    private const PLAYER_AVATAR = 'https://minotar.net/avatar/';

    /**
     * @Route(name="index", path="/")
     *
     * @throws ContentException
     */
    public function index(RCONService $RCONService, ServerProvider $serverProvider, Request $request): Response
    {
        $serverId = $request->request->get('serverId');
        if ($serverId) {
            $request->cookies->set('serverId', $serverId);

            $response = new Response();
            $response->headers->setCookie(Cookie::create('serverId', $serverId));
        }

        if (!$request->cookies->get('serverId')) {
            return $this->render('front/serverSelect.html.twig');
        }

        return $this->render('front/page/index.html.twig', [
            'articleList' => $this->getDoctrine()->getRepository(Article::class)->getLastArticles(),
            'playerListCount' => $RCONService->getServerStatus($serverProvider->getDefaultQueryServerId())['players'] ?? 0,
            'isOnline' => (bool)$RCONService->getServerStatus($serverProvider->getDefaultQueryServerId()),
            'playerList' => $RCONService->getPlayerList(),
            'admins' => $this->getDoctrine()->getRepository(AdminServerUser::class)
                ->findBy(['serverId' => $serverProvider->getCookiesServer()])
        ], $response ?? new Response());
    }

    /**
     * @Route(path="/article/{slug}", name="show-article")
     */
    public function article(string $slug)
    {
        /** @var Article $article */
        $article = $this->getDoctrine()->getRepository(Article::class)->find($slug);

        return $this->render('front/page/article.html.twig', [
            'article' => $article,
            'avatar' => self::PLAYER_AVATAR . $article->getAuthor()->getUsername(),
        ]);
    }

    /**
     * @Route(name="rule", path="/rule")
     */
    public function rule(RegulationRepository $repository)
    {
        return $this->render('front/page/rule.html.twig', [
            'ruleList' => $repository->getRegulationList(),
        ]);
    }
}
