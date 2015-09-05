<?php

namespace AppBundle\Controller;

use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;

class HomepageController extends Controller
{

    /**
     * Main page
     *
     * @Route("/", name="homepage")
     * @Template()
     *
     * @param Request $request
     * @return array
     */
    public function homepageAction(Request $request)
    {
        return array();
    }

}
