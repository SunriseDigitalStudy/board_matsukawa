<?php

class Bd_Acl_Login implements Sdx_Acl_Directory{
    public function isAllowed(\Sdx_Context $context) {
        $user = $context->getUser();
        return $user->hasId();
    }
}
