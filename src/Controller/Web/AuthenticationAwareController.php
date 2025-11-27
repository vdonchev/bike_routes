<?php

namespace Donchev\Framework\Controller\Web;

use DI\Annotation\Inject;
use Donchev\Framework\Security\Authenticator;
use Exception;

abstract class AuthenticationAwareController extends BaseController
{
    private ?Authenticator $authenticator = null;

    /**
     * @throws Exception
     */
    public function __construct(Authenticator $authenticator)
    {
        $this->authenticator = $authenticator;

        $this->authenticator->isUserRemembered();
    }
}
