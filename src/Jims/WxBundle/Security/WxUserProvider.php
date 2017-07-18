<?php

/**
 * Created by PhpStorm.
 * User: jims
 * Date: 17-7-17
 * Time: 下午2:34
 */
namespace Jims\WxBundle\Security;

use Doctrine\Common\Persistence\ObjectManager;
use Doctrine\ORM\EntityRepository;
use Jims\WxBundle\Entity\WxUser;
use Jims\WxBundle\Exception\UserNotFoundException;
use Jims\WxBundle\Security\WechatUserToken;
use Symfony\Component\Security\Core\Authentication\Provider\AuthenticationProviderInterface;
use Symfony\Component\Security\Core\Authentication\Token\TokenInterface;

class WxUserProvider implements AuthenticationProviderInterface
{
    /**
     * @var string
     */
    private $userClass;
    /**
     * @var ObjectManager
     */
    private $objectManager;
    /**
     * @var EntityRepository
     */
    private $repository;
    /**
     * WechatProvider constructor.
     * @param string $userClass
     * @param ObjectManager $objectManager
     */
    public function __construct( ObjectManager $objectManager)
    {
        $this->userClass = WxUser::class;
        $this->objectManager = $objectManager;
        $this->repository = $objectManager->getRepository($this->userClass );
    }
    /**
     * @param TokenInterface $token
     * @return WechatUserToken
     * @throws UserNotFoundException
     */
    public function authenticate(TokenInterface $token)
    {
        /** @var WechatUserToken $token */
        $user = $this->repository->findOneBy(array('openid' => $token->getOpenid()));
        if (!$user) {
            throw new UserNotFoundException();
        }
        $token->setUser($user);
        return $token;
    }
    public function supports(TokenInterface $token)
    {
        return $token instanceof WechatUserToken;
    }
}