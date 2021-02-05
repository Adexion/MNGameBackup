<?php

namespace ModernGame\Util;

use Symfony\Component\DependencyInjection\ContainerInterface;
use Symfony\Component\HttpFoundation\Cookie;
use Symfony\Component\HttpFoundation\InputBag;
use Symfony\Component\HttpFoundation\RequestStack;
use Symfony\Component\HttpFoundation\Response;
use Twig\Environment;
use Twig\Loader\LoaderInterface;

class VersionProvider
{
    private InputBag $cookies;
    private LoaderInterface $loader;
    private ContainerInterface $container;

    private const OLD_VERSION = 'base';

    public function __construct(RequestStack $requestStack, Environment $twig, ContainerInterface $container)
    {
        $this->cookies = $requestStack->getCurrentRequest()->cookies;
        $this->loader = $twig->getLoader();
        $this->container = $container;
    }

    public function getVersionOfView(string $view): string
    {
        $version = !$this->cookies->get('new')
            ? $this->container->getParameter('latest')
            : $this->container->getParameter($this->cookies->get('new', 'latest')) ?? $this->container->getParameter('latest');

        return str_replace(self::OLD_VERSION, $version, $view);
    }

    public function isVersionTwigExist(string $view): bool
    {
        return $this->loader->exists($view);
    }

    public function getCookieResponse(): Response
    {
        $response = new Response();
        $response->headers->setCookie(new Cookie('new', $this->cookies->get('new')));

        return $response;
    }
}