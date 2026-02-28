<?php

namespace App\Security;

use App\Entity\UserToken;
use App\Repository\UserTokenRepository;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Security\Core\Exception\AuthenticationException;
use Symfony\Component\Security\Http\Authenticator\AbstractAuthenticator;
use Symfony\Component\Security\Http\EntryPoint\AuthenticationEntryPointInterface;
use Symfony\Component\Security\Http\Authenticator\Passport\Badge\UserBadge;
use Symfony\Component\Security\Http\Authenticator\Passport\SelfValidatingPassport;

final class CookieTokenAuthenticator extends AbstractAuthenticator implements AuthenticationEntryPointInterface
{
    public function __construct(private readonly UserTokenRepository $tokens) {}

    public function supports(Request $request): ?bool
    {
        $path = $request->getPathInfo();

        if ($path === '/api/login' || $path === '/api/register') {
            return false;
        }

        if (!str_starts_with($path, '/api')) {
            return false;
        }

        return $request->cookies->has('connect.uid');
    }

    public function authenticate(Request $request): SelfValidatingPassport
    {
        $rawToken = (string) $request->cookies->get('connect.uid');
        $hash = hash('sha256', $rawToken);

        $token = $this->tokens->findOneBy(['tokenHash' => $hash]);
        if (!$token) {
            throw new AuthenticationException('Token invalide');
        }

        if ($token->getExpiresAt() && $token->getExpiresAt() < new \DateTimeImmutable()) {
            throw new AuthenticationException('Token expiré');
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
        return new JsonResponse(['error' => 'Unauthorized'], 401);
    }

    public function start(Request $request, ?AuthenticationException $authException = null): JsonResponse
    {
        return new JsonResponse(['error' => 'Unauthorized'], 401);
    }
}
