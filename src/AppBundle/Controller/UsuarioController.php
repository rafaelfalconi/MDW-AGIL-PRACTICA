<?php
/**
 * Created by Juan Pablo Jimenez.
 * User: Chante
 * Date: 19/04/2018
 * Time: 12:37
 */

namespace AppBundle\Controller;

use Doctrine\Common\Collections\Criteria;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use FOS\RestBundle\Controller\Annotations as Rest;
use FOS\RestBundle\Controller\FOSRestController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use FOS\RestBundle\View\View;
use AppBundle\Entity\Usuario;

class UsuarioController extends FOSRestController
{

    /**
     * @Rest\Post("/user")
     */
    public function postAction(Request $request)
    {

        $usuario = new Usuario();
        $username = $request->get('username');
        if(empty($username))
        {
            return new View("NULL VALUES ARE NOT ALLOWED", Response::HTTP_NOT_ACCEPTABLE);
        }
        $em = $this->getDoctrine()->getManager();
        $criteria = new Criteria();
        $criteria
            ->where($criteria::expr()->eq('email', $username ));
        $user_exist = $em
            ->getRepository(Usuario::class)
            ->matching($criteria);

        if (!count($user_exist)) {

            $usuario->setEmail($username);
            $usuario->setClave($username . mt_rand(0, 1000000));
            $em->persist($usuario);
            $em->flush();
            return new View("OK", Response::HTTP_OK);
        }

        return new View("OK", Response::HTTP_OK);

    }

}
