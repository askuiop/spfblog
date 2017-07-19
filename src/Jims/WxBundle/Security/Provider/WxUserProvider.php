<?php

/**
 * Created by PhpStorm.
 * User: jims
 * Date: 17-7-18
 * Time: 上午9:02
 */
namespace Jims\WxBundle\Security\Provider;

use Doctrine\ORM\EntityManager;
use Jims\WxBundle\Entity\WxUser;
use Symfony\Component\Security\Core\Exception\UnsupportedUserException;
use Symfony\Component\Security\Core\Exception\UsernameNotFoundException;
use Symfony\Component\Security\Core\User\UserInterface;
use Symfony\Component\Security\Core\User\UserProviderInterface;

class WxUserProvider implements UserProviderInterface
{
    /**
     * @var $em EntityManager
     */
    private $em ;

    public function __construct(EntityManager $em)
    {
        $this->em = $em;
    }

    public function loadUserByUsername($username)
    {
        // TODO: Implement loadUserByUsername() method.

        $wxUser = $this->em->getRepository('JimsWxBundle:WxUser')->findOneBy([
            'openid' => $username
        ]);

        return $wxUser;

        //throw new UsernameNotFoundException(
        //    sprintf('Username "%s" does not exist.', $username)
        //);

    }

    public function refreshUser(UserInterface $user)
    {
        // TODO: Implement refreshUser() method.
        if (!$user instanceof WxUser) {
            throw new UnsupportedUserException(
                sprintf('Instances of "%s" are not supported.', get_class($user))
            );
        }

        return $this->loadUserByUsername($user->getUsername());
    }

    public function supportsClass($class)
    {
        // TODO: Implement supportsClass() method.
        return $class === WxUser::class;
    }

}