<?php

namespace MillenniumFalcon\Core\Service;

use Cocur\Slugify\Slugify;

class UtilsService
{
    /**
     * UtilsService constructor.
     * @param \Doctrine\DBAL\Connection $connection
     */
    public function __construct(\Doctrine\DBAL\Connection $connection)
    {
        $this->connection = $connection;
    }

    /**
     * @param $string
     * @return string
     */
    public function slugify($string)
    {
        $slugify = new Slugify(['trim' => false]);
        return $slugify->slugify($string);
    }

    /**
     * @return FragmentBlock[]
     */
    public function getBlockDropdownOptions()
    {
        /** @var \PDO $pdo */
        $pdo = $this->connection->getWrappedConnection();

        $fullClass = ModelService::fullClass($pdo, 'FragmentBlock');
        $blocks = $fullClass::active($pdo);
        foreach ($blocks as $block) {
            $items = json_decode($block->getItems());
            foreach ($items as &$item) {
                $choices = array();
                if ($item->widget == 9 || $item->widget == 10) {
                    $stmt = $pdo->prepare($item->sql);
                    $stmt->execute();
                    foreach ($stmt->fetchAll() as $key => $val) {
                        $choices[$val['key']] = $val['value'];
                    }
                }
                $item->choices = $choices;
            }
            $block->setItems(json_encode($items));
        }
        return $blocks;
    }

    /**
     * @return array
     */
    public function getBlockWidgets()
    {
        $widgets = array(
            0 => 'Text',
            1 => 'Textarea',
            2 => 'Asset picker',
            3 => 'Asset folder picker',
            4 => 'Checkbox',
            5 => 'Wysiwyg',
            6 => 'Date',
            7 => 'Datetime',
            8 => 'Time',
            9 => 'Choice',
            10 => 'Choice multi json',
            11 => 'Placeholder',
        );
        asort($widgets);
        return array_flip($widgets);
    }

    /**
     * @return array
     */
    public function getFormWidgets()
    {
        return array(
            '\\Symfony\\Component\\Form\\Extension\\Core\\Type\\ChoiceType' => 'Choice',
            '\\Symfony\\Component\\Form\\Extension\\Core\\Type\\CheckboxType' => 'Checkbox',
//            '\\Pz\\Forms\\Types\\DatePicker' => 'Date picker',
//            '\\Pz\\Forms\\Types\\DateTimePicker' => 'Date time picker',
            '\\Symfony\\Component\\Form\\Extension\\Core\\Type\\EmailType' => 'Email',
            '\\Symfony\\Component\\Form\\Extension\\Core\\Type\\HiddenType' => 'Hidden',
            '\\Symfony\\Component\\Form\\Extension\\Core\\Type\\TextType' => 'Text',
            '\\Symfony\\Component\\Form\\Extension\\Core\\Type\\TextareaType' => 'Textarea',
            '\\Symfony\\Component\\Form\\Extension\\Core\\Type\\RepeatedType' => 'Repeated',
//            '\\Pz\\Forms\\Types\\Wysiwyg' => 'Wysiwyg',
            '\\Symfony\\Component\\Form\\Extension\\Core\\Type\\SubmitType' => 'Submit',
        );
    }

    /**
     * @return string
     */
    public function getUniqId()
    {
        return uniqid();
    }

    /**
     * @param $valLength
     * @return bool|string
     */
    static public function generateUniqueHex($valLength, $exists)
    {
        do {
            $uniqeHex = static::generateHex($valLength);
        } while (in_array($uniqeHex, $exists));
        return $uniqeHex;
    }

    /**
     * @param $valLength
     * @return bool|string
     */
    static public function generateHex($valLength)
    {
        $result = '';
        $moduleLength = 40;   // we use sha1, so module is 40 chars
        $steps = round(($valLength / $moduleLength) + 0.5);

        for ($i = 0; $i < $steps; $i++) {
            $result .= sha1(uniqid() . md5(rand() . uniqid()));
        }

        return substr($result, 0, $valLength);
    }

    /**
     * Convert a multi-dimensional array into a single-dimensional array.
     * @author Sean Cannon, LitmusBox.com | seanc@litmusbox.com
     * @param  array $array The multi-dimensional array.
     * @return array
     */
    static public function flattenArray($array)
    {
        if (!is_array($array)) {
            return false;
        }
        $result = array();
        foreach ($array as $key => $value) {
            if (is_array($value)) {
                $result = array_merge($result, static::flattenArray($value));
            } else {
                $result[] = $value;
            }
        }
        return $result;
    }
}