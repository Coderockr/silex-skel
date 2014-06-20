<?php

namespace Skel\Service;

/**
 * User Service
 *
 * @category Skel
 * @package Service
 * @author  Elton Minetto<eminetto@coderockr.com>
 */
class User extends Service
{
    /**
     * Function used to get a already saved or a new User Object
     * @param                 array $data
     * @return                Orcamentos\Model\User $user
     */
    public function execute($parameters) 
    {
        $user = $this->em->getRepository("Skel\Model\User")->find($parameters['id']);

        return array('status' => 'success', 'data' => $user);

    }
}
