<?php

defined('_JEXEC') or die;

/**
 * Responsive Widgets class
 *
 * @package     Joomla.Plugin
 * @subpackage  System.wf-responsive-widgets
 */
class PlgSystemWf_responsive_widgets extends JPlugin {

    private static $media_pattern = '#(dai\.?ly(motion)?|youtu(\.)?be|vimeo\.com)#i';

    public function onAfterDispatch() {
        $app = JFactory::getApplication();

        if ($app->isAdmin()) {
            return;
        }

        $document = JFactory::getDocument();
        $docType = $document->getType();

        // only in html pages
        if ($docType != 'html') {
            return;
        }

        $document->addStyleSheet(JURI::base(true) . '/plugins/system/wf_responsive_widgets/css/responsive.css');
    }

    /**
     * Wrap media elements in a div container.
     *
     * @param   string   $context  The context of the content being passed to the plugin.
     * @param   mixed    &$row     An object with a "text" property.
     * @param   mixed    &$params  Additional parameters. See {@see PlgSystemWf_responsive_widgets()}.
     * @param   integer  $page     Optional page number. Unused. Defaults to zero.
     *
     * @return  void
     */
    public function onContentPrepare($context, &$row, &$params, $page = 0) {
        // Don't run this plugin when the content is being indexed
        if ($context == 'com_finder.indexer') {
            return true;
        }
        
        // don't process if there is not text
        if (empty($row->text)) {
            return true;
        }
        
        // check for previous processing
        if (JString::strpos($row->text, '<span class="wf-responsive-') !== false) {
            return true;
        }

        /*
         * Check for presence of {responsive=off} which is disables this
         * bot for the item.
         */
        if (JString::strpos($row->text, '{responsive=off}') !== false) {
            $row->text = JString::str_ireplace('{responsive=off}', '', $row->text);

            return true;
        }

        jimport('joomla.environment.browser');

        $row->text = preg_replace_callback('#<(iframe|object|video|audio|embed)([^>]+)>([\s\S]*?)<\/\1>#i', array($this, 'wrap'), $row->text);
    }

    private function getAttributes($string) {
        return JUtility::parseAttributes($string);
    }

    private function wrap($matches) {
        $tag = $matches[1];
        $data = $matches[2];
        $html = $matches[3];

        // default return html
        $default = '<' . $tag . $data . '>' . $html . '</' . $tag . '>';

        // get attributes
        $attribs = $this->getAttributes(trim($data));

        if (!empty($attribs['class']) && strpos($attribs['class'], 'wf-responsive-no-container') !== false) {
            return $default;
        }
        
        $class = 'wf-responsive-' . $tag . '-container';

        $browser = JBrowser::getInstance();

        if ($tag === "iframe") {
            if (!preg_match(self::$media_pattern, $attribs['src'])) {
                if (preg_match('#/ip(hone|ad|od)/i#', $browser->getAgentString())) {
                    $class = 'wf-responsive-' . $tag . '-container-ios';
                }
            } else {
                $class = 'wf-responsive-video-container';
            }
        }

        return '<span class="' . $class . '">' . $default . '</span>';
    }

}
