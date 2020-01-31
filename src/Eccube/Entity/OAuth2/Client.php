<?php

/*
 * This file is part of EC-CUBE
 *
 * Copyright(c) EC-CUBE CO.,LTD. All Rights Reserved.
 *
 * http://www.ec-cube.co.jp/
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Eccube\Entity\OAuth2;

use Doctrine\ORM\Mapping as ORM;

if (!class_exists('\Eccube\Entity\OAuth2\Client')) {
    /**
     * Client
     *
     * @ORM\Table(name="dtb_oauth2_client", uniqueConstraints={@ORM\UniqueConstraint(
     *     name="client_identifier",
     *     columns={"client_identifier"})}
     * )
     * @ORM\InheritanceType("SINGLE_TABLE")
     * @ORM\DiscriminatorColumn(name="discriminator_type", type="string", length=255)
     * @ORM\HasLifecycleCallbacks()
     * @ORM\Entity(repositoryClass="Eccube\Repository\OAuth2\ClientRepository")
     *
     * @see http://bshaffer.github.io/oauth2-server-php-docs/cookbook/doctrine2/
     */
    class Client extends \Eccube\Entity\AbstractEntity
    {
        /**
         * @var int
         *
         * @ORM\Column(name="id", type="integer", options={"unsigned":true})
         * @ORM\Id
         * @ORM\GeneratedValue(strategy="IDENTITY")
         */
        private $id;

        /**
         * @var string
         *
         * @ORM\Column(name="client_identifier", type="string", length=255)
         */
        private $client_identifier;

        /**
         * @var string
         *
         * @ORM\Column(name="client_secret", type="string", length=255)
         */
        private $client_secret;

        /**
         * @var string
         *
         * @ORM\Column(name="redirect_uri", type="string", length=4000)
         */
        private $redirect_uri;

        /**
         * @var string
         *
         * @ORM\Column(name="app_name", type="string", length=255, options={"default":""})
         */
        private $app_name;

        /**
         * @var \Eccube\Entity\Member
         *
         * @ORM\ManyToOne(targetEntity="Eccube\Entity\Member")
         * @ORM\JoinColumns({
         *   @ORM\JoinColumn(name="member_id", referencedColumnName="id")
         * })
         */
        private $Member;

        /**
         * @var \Doctrine\Common\Collections\Collection
         *
         * @ORM\OneToMany(targetEntity="Eccube\Entity\OAuth2\ClientScope", mappedBy="Client", cascade={"persist","remove"})
         */
        private $ClientScopes;

        /**
         * Constructor
         */
        public function __construct()
        {
            $this->ClientScopes = new \Doctrine\Common\Collections\ArrayCollection();
        }

        /**
         * Get id
         *
         * @return integer
         */
        public function getId()
        {
            return $this->id;
        }

        /**
         * Set client_identifier
         *
         * @param string $clientIdentifier
         *
         * @return Client
         */
        public function setClientIdentifier($clientIdentifier)
        {
            $this->client_identifier = $clientIdentifier;

            return $this;
        }

        /**
         * Get client_identifier
         *
         * @return string
         */
        public function getClientIdentifier()
        {
            return $this->client_identifier;
        }

        /**
         * Set client_secret
         *
         * @param string $clientSecret
         *
         * @return Client
         */
        public function setClientSecret($clientSecret)
        {
            $this->client_secret = $clientSecret;

            return $this;
        }

        /**
         * Get client_secret
         *
         * @return string
         */
        public function getClientSecret()
        {
            return $this->client_secret;
        }

        /**
         * Set redirect_uri
         *
         * @param string $redirectUri
         *
         * @return Client
         */
        public function setRedirectUri($redirectUri)
        {
            $this->redirect_uri = $redirectUri;

            return $this;
        }

        /**
         * Get redirect_uri
         *
         * @return string
         */
        public function getRedirectUri()
        {
            return $this->redirect_uri;
        }

        /**
         * Set Member
         *
         * @param \Eccube\Entity\Member $member
         *
         * @return Client
         */
        public function setMember(\Eccube\Entity\Member $member = null)
        {
            $this->Member = $member;

            return $this;
        }

        /**
         * Get Member
         *
         * @return \Eccube\Entity\Member
         */
        public function getMember()
        {
            return $this->Member;
        }

        /**
         * Set app_name
         *
         * @param string $appName
         *
         * @return Client
         */
        public function setAppName($appName)
        {
            $this->app_name = $appName;

            return $this;
        }

        /**
         * Get app_name
         *
         * @return string
         */
        public function getAppName()
        {
            return $this->app_name;
        }

        /**
         * @var string
         */
        private $scope;

        /**
         * Set scope
         *
         * @param string $scope
         *
         * @return Client
         */
        public function setScope($scope)
        {
            $this->scope = $scope;

            return $this;
        }

        /**
         * Get scope
         *
         * @return string
         */
        public function getScope()
        {
            return $this->scope;
        }

        /**
         * @var string
         */
        private $public_key;

        /**
         * @var string
         */
        private $encryption_algorithm;

        /**
         * Set public_key
         *
         * @param string $publicKey
         *
         * @return Client
         */
        public function setPublicKey($publicKey)
        {
            $this->public_key = $publicKey;

            return $this;
        }

        /**
         * Get public_key
         *
         * @return string
         */
        public function getPublicKey()
        {
            return $this->public_key;
        }

        /**
         * Set encryption_algorithm
         *
         * @param string $encryptionAlgorithm
         *
         * @return Client
         */
        public function setEncryptionAlgorithm($encryptionAlgorithm)
        {
            $this->encryption_algorithm = $encryptionAlgorithm;

            return $this;
        }

        /**
         * Get encryption_algorithm
         *
         * @return string
         */
        public function getEncryptionAlgorithm()
        {
            return $this->encryption_algorithm;
        }

        /**
         * Member を保持しているかどうか.
         *
         * @return boolean Member を保持している場合 true
         */
        public function hasMember()
        {
            if (is_object($this->getMember()) && $this->getMember()->getDelFlg() == Constant::DISABLED) {
                return true;
            }

            return false;
        }

        /**
         * 使用可能な Scope を配列で返します.
         *
         * @return array 使用可能な Scope の配列
         */
        public function getScopes()
        {
            $ClientScopes = $this->getClientScopes();
            $Scopes = [];
            foreach ($ClientScopes as $ClientScope) {
                $Scopes[] = $ClientScope->getScope();
            }

            return $Scopes;
        }

        /**
         * 使用可能な scope の文字列を配列で返します.
         *
         * @return array 使用可能な scope の文字列の配列
         */
        public function getScopeAsArray()
        {
            return array_map(function ($Scope) {
                return $Scope->getScope();
            }, $this->getScopes());
        }

        /**
         * scope が使用可能なチェックします.
         *
         * @param string $scope scope の文字列. スペース区切りで複数指定可能です.
         *
         * @return boolean scope がすべて使用可能な場合 true
         */
        public function checkScope($scope)
        {
            if ($scope) {
                $scopes = explode(' ', $scope);
                if (count(array_diff($scopes, $this->getScopeAsArray())) === 0) {
                    return true;
                }
            }

            return false;
        }

        /**
         * Add ClientScopes
         *
         * @param \Eccube\Entity\OAuth2\ClientScope $clientScopes
         * @return Client
         */
        public function addClientScope(\Eccube\Entity\OAuth2\ClientScope $clientScopes)
        {
            $this->ClientScopes[] = $clientScopes;

            return $this;
        }

        /**
         * Remove ClientScopes
         *
         * @param \Eccube\Entity\OAuth2\ClientScope $clientScopes
         */
        public function removeClientScope(\Eccube\Entity\OAuth2\ClientScope $clientScopes)
        {
            $this->ClientScopes->removeElement($clientScopes);
        }

        /**
         * Get ClientScopes
         *
         * @return \Doctrine\Common\Collections\Collection
         */
        public function getClientScopes()
        {
            return $this->ClientScopes;
        }

        /**
         * Client secret の妥当性を検証します.
         *
         * @param string $client_secret
         *
         * @return boolean Client secret が一致する場合 true
         */
        public function verifyClientSecret($client_secret)
        {
            return $client_secret === $this->getClientSecret();
        }
    }
}
