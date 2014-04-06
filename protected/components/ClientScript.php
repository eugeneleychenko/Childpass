<?php
class ClientScript extends CClientScript
{

    protected $cssPriority = array();
    protected $scriptPriority = array(
        self::POS_HEAD => array(),
        self::POS_BEGIN => array(),
        self::POS_END => array(),
        self::POS_LOAD => array(),
        self::POS_READY => array(),
    );

    public function renderHead(&$output)
    {
        $cssFilesOrdered = array();
        $currentCssFiles = $this->cssFiles;

        foreach ($currentCssFiles as $path => $v){
            if (in_array($path, $this->cssPriority)){
                $cssFilesOrdered[$path] = $v;
                unset($currentCssFiles[$path]);
            }
        }

        $currentCssFiles += $cssFilesOrdered;

        if (!empty($currentCssFiles)){
            $this->cssFiles = $currentCssFiles;
        }

        // Sort scripts

        $currentScriptFiles = $this->scriptFiles;

        foreach ($currentScriptFiles as $position => $scripts){
            $scriptFilesOrdered = array();
            foreach ($currentScriptFiles[$position] as $path => $v) {
                if (in_array($path, $this->scriptPriority[$position])){
                    $scriptFilesOrdered[$path] = $v;
                    unset($currentScriptFiles[$position][$path]);
                }
                $currentScriptFiles[$position] += $scriptFilesOrdered;
            }
        }

        if (!empty($currentScriptFiles)){
            $this->scriptFiles = $currentScriptFiles;
        }

        parent::renderHead($output);
    }

    public function registerCssFile($url, $lowPriority = false, $media = '') {
        if ($lowPriority)
            $this->addToLowPriorityListCSS($url);
        return parent::registerCssFile($url, $media);
    }

    public function registerCss($id, $css, $lowPriority = false, $media = '') {
        if ($lowPriority)
            $this->addToLowPriorityListCSS($id);
        return parent::registerCss($id, $css, $media);
    }

    public function registerScriptFile($url,$position=self::POS_HEAD, $lowPriority = false) {
        if ($lowPriority) {
            $this->addToLowPriorityListScript($url, $position);
        }
        return parent::registerScriptFile($url,$position);
    }

    public function registerScript($id,$script,$position=self::POS_READY, $lowPriority = false) {
        if ($lowPriority)
            $this->addToLowPriorityListScript($id, $position);
        return parent::registerScript($id,$script,$position);
    }

    private function addToLowPriorityListCSS($identifier) {
        $this->cssPriority[] = $identifier;
    }

    private function addToLowPriorityListScript($identifier, $position) {
        $this->scriptPriority[$position][] = $identifier;
    }

}