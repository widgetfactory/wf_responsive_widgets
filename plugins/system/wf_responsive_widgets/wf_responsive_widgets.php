<?php

defined('_JEXEC') or die;

use Joomla\String\StringHelper;

/**
 * Responsive Widgets class
 *
 * @package     Joomla.Plugin
 * @subpackage  System.wf-responsive-widgets
 */
class PlgSystemWf_responsive_widgets extends JPlugin
{
    public function onAfterDispatch()
    {
        $app = JFactory::getApplication();

        if ($app->getClientId() !== 0) {
            return;
        }

        $document = JFactory::getDocument();
        $docType = $document->getType();

        // only in html pages
        if ($docType != 'html') {
            return;
        }

        // get active menu
        $menus = $app->getMenu();
        $menu = $menus->getActive();

        // get menu items from parameter
        $menuitems_assign = (array) $this->params->get('menu_assign');

        // is there a menu assignment?
        if (!empty($menuitems_assign) && !empty($menuitems_assign[0])) {
            if ($menu && !in_array($menu->id, (array) $menuitems_assign)) {
                return;
            }
        }

        // get excluded menu items from parameter
        $menuitems_exclude = (array) $this->params->get('menu_exclude');

        // is there a menu exclusion?
        if (!empty($menuitems_exclude) && !empty($menuitems_exclude[0])) {
            if ($menu && in_array($menu->id, (array) $menuitems_exclude)) {
                return;
            }
        }

        // Include jQuery
        JHtml::_('jquery.framework');

        $document->addStyleSheet(JURI::base(true) . '/plugins/system/wf_responsive_widgets/css/responsive.min.css');
        $document->addScript(JURI::base(true) . '/plugins/system/wf_responsive_widgets/js/responsive.js', array('version'));
    }

    /**
     * Wrap media elements in a container.
     *
     * @param   string   $context  The context of the content being passed to the plugin.
     * @param   mixed    &$row     An object with a "text" property.
     * @param   mixed    &$params  Additional parameters.
     * @param   integer  $page     Optional page number. Unused. Defaults to zero.
     *
     * @return  void
     */
    public function onContentPrepare($context, &$row, &$params, $page = 0)
    {
        // Don't run this plugin when the content is being indexed
        if ($context == 'com_finder.indexer') {
            return true;
        }

        // don't process if there is not text
        if (empty($row->text)) {
            return true;
        }

        /*
         * Check for presence of {responsive=off} which is disables this
         * bot for the item.
         */
        if (StringHelper::strpos($row->text, '{responsive=off}') !== false) {
            $row->text = StringHelper::str_ireplace('{responsive=off}', '', $row->text);
            return true;
        }

        // check for previous processing
        if (StringHelper::strpos($row->text, '<span class="wf-responsive-') !== false) {
            return true;
        }

        $elements = $this->params->get('elements', 'iframe,object,video,embed');

        if (is_string($elements)) {
            $elements = explode(',', $elements);
        }

        $row->text = preg_replace_callback('#<(' . implode('|', $elements) . ')([^>]+)>([\s\S]*?)<\/\1>#i', array($this, 'wrap'), $row->text);
    }

    private function wrap($matches)
    {
        $tag = $matches[1];
        $data = $matches[2];
        $html = $matches[3];

        // default return html
        $default = '<' . $tag . $data . '>' . $html . '</' . $tag . '>';

        // get attributes
        $attribs = JUtility::parseAttributes(trim($data));

        if (!empty($attribs['class']) && strpos($attribs['class'], 'wf-responsive-no-container') !== false) {
            return $default;
        }

        $class = 'wf-responsive-container';

        if ($this->params->get('full_width_display', 0) == 1) {
            $class .= ' wf-responsive-container-full';
        }

        // add poster flag to container
        if ($tag == 'iframe' && !empty($attribs['data-poster'])) {
            $class .= ' wf-responsive-iframe-poster';
        }

        return '<span class="' . $class . '" role="figure">' . $default . '</span>';
    }
}
