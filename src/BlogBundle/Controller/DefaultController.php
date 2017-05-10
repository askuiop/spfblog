<?php

namespace BlogBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;

class DefaultController extends Controller
{
    public function indexAction(Request $request)
    {
        $spf = $request->query->get('spf', '');
        if ($spf == 'navigate') {
            $data = [
                'title' => '',
                'head'  => '',
                'body'  => [
                    'masthead' => $this->renderBlock('BlogBundle:Default:index.html.twig', 'masthead'),
                    'page-container' => $this->renderBlock('BlogBundle:Default:index.html.twig', 'page_container', [
                        'words' => '<p>此前鸟类之间的“社交互动”研究已经显示了一些有趣的见解</p>',
                    ]),
                ],
                'foot'  => $this->renderBlock('BlogBundle:Default:index.html.twig', 'foot'),
            ];

            return new JsonResponse($data);
        }


        return $this->render('BlogBundle:Default:index.html.twig');
    }

    public function testAction(Request $request)
    {
        $spf = $request->query->get('spf', '');
        if ($spf == 'navigate') {
            $data = [
                'title' =>  $this->renderBlock('BlogBundle:Default:test.html.twig', 'title'),
                'head'  => $this->renderBlock('BlogBundle:Default:test.html.twig', 'head'),
                'body'  => [
                    'masthead' => $this->renderBlock('BlogBundle:Default:test.html.twig', 'masthead'),
                    'page-container' => $this->renderBlock('BlogBundle:Default:test.html.twig', 'page_container', [
                        'words' => '<p>当西红柿成熟时，它们的颜色会从绿色逐渐转变成橙色、红色。评估西红柿何时成熟基本上是用肉眼完成的，会有些主观。</p>',
                    ]),
                ],
                'foot'  => $this->renderBlock('BlogBundle:Default:test.html.twig', 'foot'),
            ];

            return new JsonResponse($data);
        }
        return $this->render('BlogBundle:Default:test.html.twig',[
            'words' => '<p>当西红柿成熟时，它们的颜色会从绿色逐渐转变成橙色、红色。评估西红柿何时成熟基本上是用肉眼完成的，会有些主观。</p>',
        ]);

    }
    public function test3Action(Request $request)
    {
        $spf = $request->query->get('spf', '');
        if ($spf == 'navigate') {
            $data = [
                'title' => '',
                'head'  => '',
                'body'  => [
                    'masthead' => $this->renderBlock('BlogBundle:Default:test3.html.twig', 'masthead'),
                    'page-container' => $this->renderBlock('BlogBundle:Default:test3.html.twig', 'page_container', [
                        'words' => '<p>此前鸟类之间的“社交互动”研究已经显示了一些有趣的见解</p>',
                    ]),
                ],
                'foot'  => $this->renderBlock('BlogBundle:Default:test3.html.twig', 'foot'),
            ];

            sleep(2);
            return new JsonResponse($data);
        }
        return $this->render('BlogBundle:Default:test3.html.twig',[
            'words' => '<p>此前鸟类之间的“社交互动”研究已经显示了一些有趣的见解</p>',
        ]);

    }

    private function renderBlock($template, $block, $params = array())
    {
        /** @var \Twig_Environment $twig */
        $twig = $this->get('twig');
        /** @var \Twig_Template $template */
        $template = $twig->loadTemplate($template);

        return $template->renderBlock($block, $twig->mergeGlobals($params));
    }

}
