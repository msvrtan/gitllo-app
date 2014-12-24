<?php
namespace NullDev\UserBundle\Security\Core\User;

use HWI\Bundle\OAuthBundle\OAuth\Response\UserResponseInterface;
use HWI\Bundle\OAuthBundle\Security\Core\User\FOSUBUserProvider as BaseClass;
use Symfony\Component\Config\Definition\Exception\Exception;
use Symfony\Component\Security\Core\User\UserInterface;
use HWI\Bundle\OAuthBundle\Security\Core\Exception\AccountNotLinkedException;

class FOSUBUserProvider extends BaseClass
{

    /**
     * {@inheritDoc}
     */
    public function notUsedConnect(UserInterface $user, UserResponseInterface $response)
    {
        throw new \Exception('THIS CODE IS ACTUALLY USED???');

        $property = $this->getProperty($response);
        $username = $response->getUsername();

        //on connect - get the access token and the user ID
        $service = $response->getResourceOwner()->getName();

        $setter      = 'set' . ucfirst($service);
        $setterId    = $setter . 'Id';
        $setterToken = $setter . 'AccessToken';

        $previousUser = $this->userManager->findUserBy(array($property => $username));
        //we "disconnect" previously connected users
        if (null !== $previousUser) {
            $previousUser->$setterId(null);
            $previousUser->$setterToken(null);
            $this->userManager->updateUser($previousUser);
        }

        //we connect current user
        $user->$setterId($username);
        //$user->$setterToken($response->getAccessToken());

        $this->userManager->updateUser($user);
    }

    /**
     * {@inheritdoc}
     */
    public function loadUserByoAuthUserResponse(UserResponseInterface $response)
    {
        $username = $response->getUsername();

        $user = $this->userManager->findUserBy(array($this->getProperty($response) => $username));

        if (null === $user) {
            return $this->doRegistration($response);
        }

        //if user exists - go with the HWIOAuth way
        $user = parent::loadUserByOAuthUserResponse($response);

        //$serviceName = $response->getResourceOwner()->getName();

        //$setter = 'set' . ucfirst($serviceName) . 'AccessToken';

        //update access token
        // do not set access token due to security concerns
        //$user->$setter($response->getAccessToken());
        return $user;
    }

    protected function doRegistration($response)
    {
        $identifier = $response->getUsername();
        $email      = $response->getEmail();
        $username   = $response->getNickname();

        // Check that user with same e-mail doesn't already exist
        if (null !== $this->userManager->findUserBy(array('email' => $email))) {
            throw new AccountNotLinkedException(
                "E-mail is already connected to another account. Please login and press connect."
            );
        }

        // Check that this nickname is not already used, if is add timestamp at the end
        if (null !== $this->userManager->findUserBy(array('username' => $username))) {
            $username .= time();
        }

        //when the user is registering.
        $service  = $response->getResourceOwner()->getName();
        $setter   = 'set' . ucfirst($service);
        $setterId = $setter . 'Id';
        //$setterToken = $setter . 'AccessToken';

        // create new user here
        $user = $this->userManager->createUser();

        $user->$setterId($identifier);

        // do not set access token due to security concerns
        //$user->$setterToken($response->getAccessToken());

        //I have set all requested data with the user's username
        //modify here with relevant data
        $user->setUsername($username);
        $user->setEmail($email);
        $user->setPlainPassword(uniqid());
        $user->setEnabled(true);
        $this->userManager->updateUser($user);

        return $user;
    }
}
