<?php
defined('_JEXEC') or die;

/**
 * Responsive Widgets class
 *
 * @package     Joomla.Plugin
 * @subpackage  System.wf-responsive-widgets
 */
class PlgSystemWf_responsive_widgets extends JPlugin
{
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
	 * @param   mixed    &$row     An object with a "text" property or the string to be cloaked.
	 * @param   mixed    &$params  Additional parameters. See {@see PlgSystemWfresponsivewidgets()}.
	 * @param   integer  $page     Optional page number. Unused. Defaults to zero.
	 *
	 * @return  boolean	True on success.
	 */
	public function onContentPrepare($context, &$row, &$params, $page = 0)
	{
		// Don't run this plugin when the content is being indexed
		if ($context == 'com_finder.indexer')
		{
			return true;
		}
		
		jimport('joomla.environment.browser');

		// opening tag
    		$row->text = preg_replace_callback('#<(iframe|object|video|audio|embed)#i', array($this, 'wrap'), $row->text);
    		// cloasing tag
    		$row->text = preg_replace('#<\/(iframe|object|video|audio|embed)>#i', '</$1></div>', $row->text);
	}
	
	private function wrap($matches) {
		$class = 'wf-' . $matches[1] . '-container';
    		
    		$browser = JBrowser::getInstance();
    		
    		if (preg_match('#/ip(hone|ad|od)/i#', $browser->getAgentString())) {
    			$class .= '-ios';
    		}
    		
    		return '<div class="' . $class . '"><' . $matches[1];
	}
}
