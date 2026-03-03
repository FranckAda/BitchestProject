<?php

namespace App\Security;

use App\Repository\UserTokenRepository;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Security\Core\Exception\AuthenticationException;
use Symfony\Component\Security\Http\Authenticator\AbstractAuthenticator;
use Symfony\Component\Security\Http\Authenticator\Passport\Badge\UserBadge;
use Symfony\Component\Security\Http\Authenticator\Passport\SelfValidatingPassport;
use Symfony\Component\Security\Http\EntryPoint\AuthenticationEntryPointInterface;

final class CookieTokenAuthenticator extends AbstractAuthenticator implements AuthenticationEntryPointInterface
{
    public function __construct(private readonly UserTokenRepository $tokens) {}

    public function supports(Request $request): ?bool
    {
        $path = $request->getPathInfo();

        // endpoints publics
        if ($path === '/api/login' || $path === '/api/register' || $path === '/api/health') {
            return false;
        }

        // on ne protège que /api/*
        return str_starts_with($path, '/api');
    }

    public function authenticate(Request $request): SelfValidatingPassport
    {
        $rawToken = (string) $request->cookies->get('connect.uid');
        if ($rawToken === '') {
            throw new AuthenticationException('Missing token');
        }

        $hash = hash('sha256', $rawToken);

        $token = $this->tokens->findOneBy(['tokenHash' => $hash]);
        if (!$token) {
            throw new AuthenticationException('Invalid token');
        }

        if ($token->getExpiresAt() && $token->getExpiresAt() < new \DateTimeImmutable()) {
            throw new AuthenticationException('Expired token');
        }

        $user = $token->getUser();

        return new SelfValidatingPassport(
            new UserBadge($user->getUserIdentifier(), fn () => $user)
        );
    }

    public function onAuthenticationSuccess(Request $request, $token, string $firewallName): ?JsonResponse
    {
        return null;
    }

    public function onAuthenticationFailure(Request $request, AuthenticationException $exception): ?JsonResponse
    {
        return new JsonResponse(['error' => 'unauthenticated'], 401);
    }

    public function start(Request $request, ?AuthenticationException $authException = null): JsonResponse
    {
        return new JsonResponse(['error' => 'unauthenticated'], 401);
    }
}