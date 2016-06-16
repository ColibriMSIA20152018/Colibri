<?php

namespace AMAPBundle;

use Symfony\Component\HttpKernel\Bundle\Bundle;

class AMAPBundle extends Bundle
{
    public function getParent()
    {
        return 'FOSUserBundle';
    }
}
