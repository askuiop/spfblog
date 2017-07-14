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
    private $view;
    private $params;

    /** @var \Twig_Environment $twig */
    private $twig;
    /** @var \Twig_Template $template */
    private $template;

    protected $layoutView = null;

    protected $spfFormat = [ // block name need same to navigate tag
        'title',
        'url',
        'head',
        'attr',
        'foot',
        // special care
        'body', // block name in body need same to dom id
    ];

    protected function spfRender($view, $params = [])
    {
        $requestStack = $this->get('request_stack');
        $request = $requestStack->getCurrentRequest();
        $this->view = $view ? $view : null;
        $this->params = $params ? $params : [];

        $spf = $request->query->get('spf', '');
        if ($spf == 'navigate') { // is xhr ?
            $data = $this->getNavigateData();
            return new JsonResponse($data);
        }
        return $this->render($view, $params);
    }

    private function getNavigateData()
    {
        $view = $this->view;

        $this->twig = $twig = $this->get('twig');
        $this->template = $twig->loadTemplate($view);

        $blocks = $this->getRefinedBlocks($view);
        $blocks = array_unique(array_keys($blocks));

        $navigateData = [];
        foreach ($blocks as $key => $block) {
            if ($block == 'body') continue;
            if (in_array($block, $this->spfFormat)) {
                $navigateData[$block] = $this->renderBlock($block);

            } else {
                $navigateData['body'][$block] = $this->renderBlock($block);
            }
        }

        #dump($navigateData);die();
        return $navigateData;

    }

    private function getRefinedBlocks($view)
    {
        $twig = $this->twig;
        /** @var \Twig_Template $template */
        $template = $twig->loadTemplate($view);

        if ($template->getParent([])) {
            #$blockNames = $template->getBlockNames([]);
            $blockNames = $template->getBlocks([]);  //refined

            $parentTemplateFile = $template->getParent([])->getTemplateName();
            return array_merge($this->getRefinedBlocks($parentTemplateFile), $blockNames);
        } else {
            if ($this->layoutView && $this->layoutView == $view) {
                return [];
            }

            $blockNames = $template->getBlocks([]);
            return $blockNames;

        }
    }

    protected function renderBlock($block)
    {
        return $this->template->renderBlock($block, $this->twig->mergeGlobals($this->params));
    }

}