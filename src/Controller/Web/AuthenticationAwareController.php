<?php

namespace Donchev\Framework\Controller\Web;

use DI\Annotation\Inject;
use Donchev\Framework\Security\Authenticator;
use Exception;

abstract class AuthenticationAwareController extends BaseController
{
    /**
     * @var Authenticator
     */
    private $authenticator;

    /**
     * @throws Exception
     */
    public function __construct(Authenticator $authenticator)
    {
        $this->authenticator = $authenticator;

        $this->authenticator->isUserRemembered();
    }
}
