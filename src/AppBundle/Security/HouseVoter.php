<?php
/**
 * Created by PhpStorm.
 * User: nodir
 * Date: 21/05/2018
 * Time: 18:53
 */

namespace AppBundle\Security;


use AppBundle\Entity\Agency;
use AppBundle\Entity\House;
use Symfony\Component\Security\Core\Authentication\Token\TokenInterface;
use Symfony\Component\Security\Core\Authorization\AccessDecisionManagerInterface;
use Symfony\Component\Security\Core\Authorization\Voter\Voter;

/**
 * Class HouseVoter
 * @package AppBundle\Security
 */
class HouseVoter extends Voter
{
    /**
     *
     */
    const EDIT = 'edit';

    /**
     * HouseVoter constructor.
     * @param AccessDecisionManagerInterface $decisionManager
     */
    public function __construct(AccessDecisionManagerInterface $decisionManager)
    {
        $this->decisionManager = $decisionManager;
    }

    /**
     * @var AccessDecisionManagerInterface
     */
    private $decisionManager;
    /**
     * Determines if the attribute and subject are supported by this voter.
     *
     * @param string $attribute An attribute
     * @param mixed $subject The subject to secure, e.g. an object the user wants to access or any other PHP type
     *
     * @return bool True if the attribute and subject are supported, false otherwise
     */
    protected function supports($attribute, $subject)
    {
        if (!in_array($attribute, array(self::EDIT))) {
            return false;
        }

        if (!$subject instanceof House) {
            return false;
        }

        return true;
    }

    /**
     * Perform a single access check operation on a given attribute, subject and token.
     * It is safe to assume that $attribute and $subject already passed the "supports()" method check.
     *
     * @param string $attribute
     * @param mixed $subject
     * @param TokenInterface $token
     *
     * @return bool
     */
    protected function voteOnAttribute($attribute, $subject, TokenInterface $token)
    {
        $agency = $token->getUser();

        if (!$agency instanceof Agency) {
            return false;
        }

        /** @var House $house */
        $house = $subject;

        if ($this->decisionManager->decide($token, array('ROLE_SUPER_ADMIN'))) {
            return true;
        }
        switch ($attribute) {
            case self::EDIT:
                return $this->canEdit($house, $agency);
        }

        throw new \LogicException('This code should not be reached!');
    }

    /**
     * @param House $post
     * @param Agency $agency
     * @return bool
     */
    private function canEdit(House $post, Agency $agency)
    {
        return $agency === $post->getAgency();
    }
}