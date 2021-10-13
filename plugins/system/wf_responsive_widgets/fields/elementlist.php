<?php

defined('JPATH_PLATFORM') or die;

JFormHelper::loadFieldClass('list');

class JFormFieldElementList extends JFormFieldList
{
    /**
     * The form field type.
     *
     * @var string
     *
     * @since  11.1
     */
    protected $type = 'ElementList';

    /**
     * Method to get the field options.
     *
     * @return array The field option objects
     *
     * @since   11.1
     */
    protected function getOptions()
    {
        $fieldname = preg_replace('/[^a-zA-Z0-9_\-]/', '_', $this->fieldname);
        $options = array();

        foreach (explode(',', $this->default) as $option) {
            $value = (string) $option;
            $text = trim((string) $option);

            $tmp = array(
                'value' => $value,
                'text' => JText::alt($text, $fieldname),
                'disable' => false,
                'class' => '',
                'selected' => false,
                'checked' => false,
            );

            // Add the option object to the result set.
            $options[] = (object) $tmp;
        }

        reset($options);

        return $options;
    }
}
