<?php

namespace App\Security;

use App\Repository\PanierRepository;
use App\Repository\ArticleRepository;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Security\Core\Security;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\RequestStack;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\Security\Http\Util\TargetPathTrait;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;
use Symfony\Component\Security\Http\Authenticator\Passport\Passport;
use Symfony\Component\Security\Core\Authentication\Token\TokenInterface;
use Symfony\Component\Security\Http\Authenticator\Passport\Badge\UserBadge;
use Symfony\Component\Security\Http\Authenticator\Passport\Badge\CsrfTokenBadge;
use Symfony\Component\Security\Http\Authenticator\AbstractLoginFormAuthenticator;
use Symfony\Component\Security\Http\Authenticator\Passport\Credentials\PasswordCredentials;

class EmailAuthenticator extends AbstractLoginFormAuthenticator
{
    use TargetPathTrait;

    public const LOGIN_ROUTE = 'app_login';
    // private $httpUtils;
    private $panierRepository;
    private $requestStack;
    private $articleRepository;
    public function __construct(ArticleRepository $articleRepository, PanierRepository $panierRepository, RequestStack $requestStack, private UrlGeneratorInterface $urlGenerator)
    {
       $this->panierRepository = $panierRepository;
       $this->requestStack = $requestStack;
       $this->articleRepository = $articleRepository;
    }

    public function authenticate(Request $request): Passport
    {
        $email = $request->request->get('email', '');

        $request->getSession()->set(Security::LAST_USERNAME, $email);

        return new Passport(
            new UserBadge($email),
            new PasswordCredentials($request->request->get('password', '')),
            [
                new CsrfTokenBadge('authenticate', $request->request->get('_csrf_token')),
            ]
        );
    }

    public function onAuthenticationSuccess(Request $request, TokenInterface $token, string $firewallName): ?Response
    { 

        if ($targetPath = $this->getTargetPath($request->getSession(), $firewallName)) {
            return new RedirectResponse($targetPath);
        }
        //recuperer le user, verifier si il a un panier en base de donnée et s'il y'en a un le mettre dans la session.
        //Faire une boucle sur le panier pour afiicher chaque produit, ensuite disocié les produit du panier 
       $user = $token->getUser();
       $session = $this->requestStack->getCurrentRequest()->getSession();//permet de recuperer la session de l'utilisateur connecter
       //$panier = $session->get("panier", []);
       $panier = [];
       if($user->getPanier()){
        foreach($user->getPanier()->getArticles() as $article){
            $panier[$article->getProduit()->getId()] = $article->getQuantite();
        }

        
       }
       $session->set("panier", $panier);
        // For example:
        return new RedirectResponse($this->urlGenerator->generate('home'));
        throw new \Exception('TODO: provide a valid redirect inside '.__FILE__);
    }

    protected function getLoginUrl(Request $request): string
    {
        return $this->urlGenerator->generate(self::LOGIN_ROUTE);
    }
}
