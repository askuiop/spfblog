<?php
/**
 * Created by PhpStorm.
 * User: jimspete
 * Date: 2016/6/10
 * Time: 18:24
 */

namespace BlogBundle\Twig;


class AssetVersionExtension extends \Twig_Extension
{
    /**
     * @var
     */
    private $appDir;

    public function __construct($appDir)
    {

        $this->appDir = $appDir;
    }

    public function getFilters()
    {
        return array(
            new \Twig_SimpleFilter('asset_version', array($this, 'getAssetVersion')),
            new \Twig_SimpleFilter('post_shortcut', array($this, 'getPostShortcut')),

        );
    }

    public function getAssetVersion($filename)
    {
        $filename = trim($filename, '/');
        $manifestPath = $this->appDir.'/../src/BlogBundle/Resources/assets/rev-manifest.json';
        if (!file_exists($manifestPath)) {
            throw new \Exception(sprintf('Cannot find manifest file: "%s"', $manifestPath));
        }
        $paths = json_decode(file_get_contents($manifestPath), true);
        if (!isset($paths[$filename])) {
            throw new \Exception(sprintf('There is no file "%s" in the version manifest!', $filename));
        }
        return '/'.trim($paths[$filename], '/');
    }

    public function getPostShortcut($content, $length=100)
    {
        $length = (int)$length;
        if (!$content || !$length) {
            return $content;

        }

        preg_match('/.{' . $length . '}/u', $content, $mth);
        if(empty($mth[0])){
            return $content;
        } else {
            return $mth[0];
        }
    }


    public function getName()
    {
        return 'asset_version';
    }


}