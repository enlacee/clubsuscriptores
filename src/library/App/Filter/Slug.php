<?php

/**
 * Description of SlugFilter
 *
 * @author Usuario
 */
class App_Filter_Slug implements Zend_Filter_Interface
{
    /** 
     * Method used to generate slug
     *
     * @param string $value String to generate its slug
     *
     * @return string Generated slug
     */
    public function filter($value)
    {
        $value = str_replace(
            array("á", "é", "í", "ó", "ú", "ä", "ë", "ï", "ö", "ü", "ñ"), 
            array("a", "e", "i", "o", "u", "a", "e", "i", "o", "u", "n"), 
            mb_strtolower($value, 'UTF-8')
        );
        // Generate slug by removing unwanted (other than alphanumeric 
        //and dash [-]) characters from the string
        $value = preg_replace('/[^a-z0-9-]/i', '-', $value);
        $value = preg_replace('/-[-]*/', '-', $value);
        $value = preg_replace('/-$/', '', $value);
        $value = preg_replace('/^-/', '', $value);
        // Return generated slug
        return $value;
    }
}