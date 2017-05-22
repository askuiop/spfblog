<?php
/**
 * Created by PhpStorm.
 * User: jims
 * Date: 17-5-15
 * Time: 上午10:47
 */

namespace BlogBundle\Controller;


use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;

abstract class SpfController extends Controller
{
    private $template;
    private $params;

    protected function spfRender($template, $navigates, $params=[])
    {
        $requestStack = $this->get('request_stack');
        $request = $requestStack->getCurrentRequest();

        $this->template = $template?$template:null;
        $this->params = $params?$params:[];

        $spf = $request->query->get('spf', '');
        if ($spf == 'navigate' && $navigates) {

            $data = $this->getNavigateData($navigates);

            return new JsonResponse($data);
        }
        return $this->render($template, $params);
    }

    private function getNavigateData($def_nav)
    {

        if (is_array($def_nav) && count($def_nav)) {
            $data = [];
            foreach ($def_nav as $id => $nav) {
                $data[$id] = $this->getNavigateData( $nav);
            }
            return $data;
        }

        if (strpos($def_nav, '%') === 0) {
            $def_nav = ltrim($def_nav, '%');

            return !empty($def_nav) ?
                $this->renderBlock($this->template, $def_nav, $this->params)
                : null;
        }

        return !empty($def_nav)
            ? $def_nav
            : null;


    }

    protected function renderBlock($template, $block, $params = array())
    {
        /** @var \Twig_Environment $twig */
        $twig = $this->get('twig');
        /** @var \Twig_Template $template */
        $template = $twig->loadTemplate($template);

        return $template->renderBlock($block, $twig->mergeGlobals($params));
    }

}