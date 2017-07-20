<?php

/**
 * Created by PhpStorm.
 * User: jims
 * Date: 17-7-18
 * Time: 上午9:20
 */
namespace Jims\WxBundle\Security\Authenticator;


use Doctrine\ORM\EntityManager;
use EasyWeChat\Foundation\Application;
use Jims\WxBundle\Entity\WxUser;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Security\Core\Authentication\Token\TokenInterface;
use Symfony\Component\Security\Core\Exception\AuthenticationException;
use Symfony\Component\Security\Core\User\UserInterface;
use Symfony\Component\Security\Core\User\UserProviderInterface;
use Symfony\Component\Security\Guard\AbstractGuardAuthenticator;

class WxTokenAuthenticator extends AbstractGuardAuthenticator
{

    private $em;
    private $sdk;

    public function __construct(EntityManager $em , Application $sdk)
    {
        $this->em = $em;
        $this->sdk = $sdk;
    }


    /**
     * Called when authentication is needed, but it's not sent
     * if getCredentials return null , it will run
     */
    public function start(Request $request, AuthenticationException $authException = null)
    {
        $redirect_uri = $request->getUri();
        $scope = 'snsapi_userinfo';
        $response = $this->sdk->oauth->scopes([$scope])
            ->redirect($redirect_uri);

        return $response;

        /*
        $queries = [
            'appid' => $this->clientId,
            'redirect_uri' => $request->getUri(),
            'response_type' => 'code',
            'scope' => 'snsapi_base',
        ];
        $redirectUrl = sprintf('https://open.weixin.qq.com/connect/oauth2/authorize?%s#wechat_redirect', http_build_query($queries));

        return new RedirectResponse($redirectUrl);*/
    }

    public function getCredentials(Request $request)
    {
        // Implement getCredentials() method.
        //if ($request->query->has('code')) {
        //    return ['code' => $request->query->get('code')];
        //}
        $code = $request->query->get('code', '');
        if (!$code) {
            return null;
        }

        $user = $this->sdk->oauth->user();
        if (!$user) {
            return null;
        }

        $credentials = $user->toArray();

        return $credentials;

    }

    public function getUser($credentials, UserProviderInterface $userProvider)
    {
        // Implement getUser() method.
        // if null, authentication will fail
        // 如果是空，认证失败
        // if a User object, checkCredentials() is called
        // 如果是个User对象，checkCredentials()将被调用

        $username = $credentials['id'];
        dump($credentials);

        $wxDbUser = $userProvider->loadUserByUsername($username);
        if (!$wxDbUser) {
            $wxUser = new WxUser();
            $wxUser->load($credentials);
            $wxUser->save();
            $wxDbUser = $wxUser;
        }

        return $wxDbUser;
    }

    public function checkCredentials($credentials, UserInterface $user)
    {
        // TODO: Implement checkCredentials() method.
        // check credentials - e.g. make sure the password is valid
        // 检查credentials - 比如，确保密码是有效的
        // no credential check is needed in this case
        // 但在本例中并不需要对credential检查
        // return true to cause authentication success
        // 返回true即是认证成功
        return true;
    }

    public function onAuthenticationFailure(Request $request, AuthenticationException $exception)
    {
        // TODO: Implement onAuthenticationFailure() method.
        $data = array(
            'message' => strtr($exception->getMessageKey(), $exception->getMessageData())

            // or to translate this message
            // 或者翻译信息如下
            // $this->translator->trans($exception->getMessageKey(), $exception->getMessageData())
        );

        return new JsonResponse($data, 403);
    }

    public function onAuthenticationSuccess(Request $request, TokenInterface $token, $providerKey)
    {
        // TODO: Implement onAuthenticationSuccess() method.
        // on success, let the request continue
        // 成功之后，让请求继续
        return null;
    }

    public function supportsRememberMe()
    {
        // Implement supportsRememberMe() method.
        return false;
    }

    private function getOpenIdFromCredentials(array $credentials)
    {

        $url = sprintf(
            'https://api.weixin.qq.com/sns/oauth2/access_token?appid=%s&secret=%s&code=%s&grant_type=authorization_code',
            $this->clientId,
            $this->clientSecret,
            $credentials['code']
        );
        try {
            $info = json_decode(file_get_contents($url), true);
        } catch (\Exception $ex) {
            throw new AuthenticationException('OAuth server is down', $ex);
        }
        if (empty($info['openid'])) {
            throw new AuthenticationException('OAuth code is not valid');
        }
        return $info['openid'];

    }

}