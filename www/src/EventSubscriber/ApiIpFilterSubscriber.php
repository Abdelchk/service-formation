<?php

namespace App\EventSubscriber;

use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\HttpFoundation\IpUtils;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Event\RequestEvent;
use Symfony\Component\HttpKernel\KernelEvents;

/**
 * Filtre les requêtes vers /api/* et /api/doc en fonction de l'IP source.
 *
 * - /api/doc  → uniquement depuis les plages IP de l'entreprise
 * - /api/*    → uniquement depuis les IPs des serveurs de l'application tierce
 *
 * Les IPs autorisées sont configurées dans config/packages/api_security.yaml
 * (jamais dans .env).
 */
class ApiIpFilterSubscriber implements EventSubscriberInterface
{
    public function __construct(
        private readonly array $apiAllowedIps,
        private readonly array $swaggerAllowedIpRanges
    ) {
    }

    public static function getSubscribedEvents(): array
    {
        return [
            // Priorité haute (10) pour bloquer avant tout traitement métier
            KernelEvents::REQUEST => ['onKernelRequest', 10],
        ];
    }

    public function onKernelRequest(RequestEvent $event): void
    {
        if (!$event->isMainRequest()) {
            return;
        }

        $request = $event->getRequest();
        $path = $request->getPathInfo();
        $clientIp = $request->getClientIp() ?? '';

        // /api/doc → accessible uniquement depuis les IPs de l'entreprise
        if (str_starts_with($path, '/api/doc')) {
            if (!IpUtils::checkIp($clientIp, $this->swaggerAllowedIpRanges)) {
                $event->setResponse(new Response(
                    'Accès non autorisé : votre adresse IP n\'est pas dans la plage autorisée.',
                    Response::HTTP_FORBIDDEN
                ));

                return;
            }
        }

        // /api/* (hors doc) → accessible uniquement depuis les IPs des serveurs tiers
        if (str_starts_with($path, '/api/') && !str_starts_with($path, '/api/doc')) {
            if (!IpUtils::checkIp($clientIp, $this->apiAllowedIps)) {
                $event->setResponse(new JsonResponse(
                    ['error' => 'Accès interdit : votre adresse IP n\'est pas autorisée à utiliser cette API.'],
                    Response::HTTP_FORBIDDEN
                ));

                return;
            }
        }
    }
}
