<?php

namespace NullDev\UserBundle\Entity;

use FOS\UserBundle\Entity\User as BaseUser;
use Doctrine\ORM\Mapping as ORM;

/**
 * User
 *
 * @ORM\Table(name="Users")
 * @ORM\Entity(repositoryClass="NullDev\UserBundle\Entity\UserRepository")
 * @ORM\AttributeOverrides({
 *     @ORM\AttributeOverride(name="email",
 *          column=@ORM\Column(
 *              name     = "email",
 *              type     = "string",
 *              length   = 255,
 *              nullable = true,
 *              unique = true,
 *          )
 *      ),
 *     @ORM\AttributeOverride(name="emailCanonical",
 *          column=@ORM\Column(
 *              name     = "email_canonical",
 *              type     = "string",
 *              length   = 255,
 *              nullable = true,
 *              unique = true,
 *          )
 *      )
 * })
 */
class User extends BaseUser
{
    /**
     * @ORM\Id
     * @ORM\Column(type="integer")
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    protected $id;

    /** @ORM\Column(name="profileName", type="string", length=255, nullable=true, unique=true) */
    protected $profileName;

    /** @ORM\Column(name="githubId", type="string", length=255, nullable=true, unique=true) */
    protected $githubId;

    /** @ORM\Column(name="githubAccessToken", type="string", length=255, nullable=true) */
    protected $githubAccessToken;

    /** @ORM\Column(name="trelloId", type="string", length=255, nullable=true, unique=true) */
    protected $trelloId;

    /** @ORM\Column(name="trelloAccessToken", type="string", length=255, nullable=true) */
    protected $trelloAccessToken;

    public function __construct()
    {
        parent::__construct();
        // your own logic
    }

    public function setEmail($email)
    {
        if (empty($email)) {
            $email = null;
        }

        $this->email = $email;

        return $this;
    }

    public function setEmailCanonical($emailCanonical)
    {
        
        if (empty($emailCanonical)) {
            $emailCanonical = null;
        }

        $this->emailCanonical = $emailCanonical;

        return $this;
    }

    /**
     * @return mixed
     */
    public function getProfileName()
    {
        return $this->profileName;
    }

    /**
     * @param mixed $profileName
     */
    public function setProfileName($profileName)
    {
        $this->profileName = $profileName;
    }

    /**
     * @return mixed
     */
    public function getGithubId()
    {
        return $this->githubId;
    }

    /**
     * @param mixed $githubId
     */
    public function setGithubId($githubId)
    {
        $this->githubId = $githubId;
    }

    /**
     * @return mixed
     */
    public function getGithubAccessToken()
    {
        return $this->githubAccessToken;
    }

    /**
     * @param mixed $githubAccessToken
     */
    public function setGithubAccessToken($githubAccessToken)
    {
        $this->githubAccessToken = $githubAccessToken;
    }
}
