<?php

namespace BlogBundle\Twig\Extension;

use BlogBundle\Entity\PostComment;

class commentsCascade extends \Twig_Extension
{
  public $defaultDepth = 0;

  public $commentAry = [];

  public $depth;

  public $tpl = '';
    /**
     * {@inheritdoc}
     */
    public function getName()
    {
        return 'comments_cascade';
    }

  public function getFilters()
  {
    return [
      new \Twig_SimpleFilter('cascadeComment', array($this, 'cascadeComment')),
    ];
  }

  public function cascadeComment($comments, $tpl='', $maxDepth=0, $currentDepth=0)
  {
    if ($maxDepth!=0 && $currentDepth>=$maxDepth) {
      return '';
    }

    if (!$this->tpl) {
      $this->tpl = $tpl;
    }

    if (!count($comments) ) {
      return '';
    }

    $html = '';
    foreach ($comments as $comment) {
      if( $comment->getDepth()==0 || $comment->getReferrer()==null  ) {
        $this->commentAry[$comment->getDepth()][0][] = $comment;
      } else {
        $refer = $comment->getReferrer();
        $this->commentAry[$comment->getDepth()][$refer->getId()][] = $comment;
      }
    }

    $this->depth = count($this->commentAry) -1 ;




    foreach ($this->commentAry[$this->defaultDepth][0] as $comment) {

      $html .= $this->cascade($comment, $this->defaultDepth);


    }

    return $html;


  }

  public function cascade($preComments, $currentDepth=0)
  {
    //echo count($preComments);
    //die();

    $combine_str = '';

    if ($currentDepth==0) {
      $comments[] = $preComments;
    } else if ( isset($this->commentAry[$currentDepth][$preComments->getId()]) ) {
      $comments = $this->commentAry[$currentDepth][$preComments->getId()];
    } else {
      return '';
    }

    foreach ($comments as $comment) {
      $tmp_tpl = str_replace('%title%', $comment->getTitle(), $this->tpl);
      $tmp_tpl = str_replace('%nickname%', $comment->getNickname(), $tmp_tpl);
      $tmp_tpl = str_replace('%content%', $comment->getContent(), $tmp_tpl);
      $tmp_tpl = str_replace('%date%', $comment->getCreatedAt()->format("Y-m-d H:i:s"), $tmp_tpl);
      $tmp_tpl = str_replace('%id%', $comment->getId(), $tmp_tpl);


      if ($currentDepth <= $this->depth) {
        $child = $this->cascade($comment, $currentDepth+1);
        $tmp_tpl = str_replace('<child />', $child, $tmp_tpl);
      }else {
        $tmp_tpl = str_replace('<child />', '', $tmp_tpl);
      }
      $combine_str .= $tmp_tpl;

    }
    



    return $combine_str;

  }


}
