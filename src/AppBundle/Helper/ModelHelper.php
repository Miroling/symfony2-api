<?php
/**
 * Created by PhpStorm.
 * User: miroling
 * Date: 17.04.15
 * Time: 15:22
 */

namespace AppBundle\Helper;


class ModelHelper {

    /**
     * Get array of values from Array of associative arrays
     *
     * @param array  $data Array of associative arrays
     * @param string $key  Key name
     *
     * @return array Array of valoes
     */
    static public function getArrayValuesByKey($data, $key){

        $items = array();

        foreach($data as $value){
            if(isset($value[$key])){
                $items[] = $value[$key];
            }
        }

        return $items;

    }

    /**
     * Set object public properties from passed array
     *
     * @param mixed $object
     * @param $data
     *
     * @return mixed
     */
    static public function setProperties($object, $data) {

        $has = get_object_vars($object);
        $itemClassNameReflection = new \ReflectionObject($object);

        // Convert array keys to camelCase format
        $dataWithCamelCaseKeys = array();

        foreach ($data as $k=>$v){
            $dataWithCamelCaseKeys[self::toCamelCase($k)] = $v;
        }

        // Find matched between dataset & object properties
        $intersect = array_intersect(array_keys($dataWithCamelCaseKeys), array_keys($has));

        // Fill object by passed data
        foreach ($intersect as $key) {
            if (is_object($object->$key) && is_array($dataWithCamelCaseKeys[$key])){
                $classReflection = new \ReflectionObject($object->$key);
                $className = $classReflection->getName();
                $object->$key = self::setProperties(new $className(), $dataWithCamelCaseKeys[$key]);
            } else {

                $object->$key = isset($dataWithCamelCaseKeys[$key]) ? $dataWithCamelCaseKeys[$key] : NULL;

                $itemClassPropertyInfo = new \phpDocumentor\Reflection\DocBlock($itemClassNameReflection->getProperty($key));
                $tagsVar = $itemClassPropertyInfo->getTagsByName("var");

                if (isset($tagsVar[0]) && $tagsVar[0]->getContent() == "\DateTime" && $object->$key !== null){
                    $object->$key = new \DateTime($object->$key);
                }
            }
        }

        return $object;

    }

    /**
     * Get data from object properties.
     *
     * @param mixed $object
     *
     * @return array
     */
    static public function getProperties($object, $exclude = array()) {

        // Convert array keys to camelCase format
        $data = array();
        $itemClassNameReflection = new \ReflectionObject($object);

        foreach($object as $key=>$value){

            if (!in_array($key, $exclude)){

                $itemClassPropertyInfo = new \phpDocumentor\Reflection\DocBlock($itemClassNameReflection->getProperty($key));
                $tagsVar = $itemClassPropertyInfo->getTagsByName("var");

                if (isset($tagsVar[0]) && $tagsVar[0]->getContent() == "\DateTime" && $object->$key !== null){

                    if ($object->$key instanceof \DateTime){
                        $data[self::toUnderscore($key)] = $object->$key;
                    } else {
                        $data[self::toUnderscore($key)] = new \DateTime($object->$key);
                    }

                } else {
                    $data[self::toUnderscore($key)] = $value;
                }

            }

        }

        return $data;

    }

    /**
     * Fill collection
     *
     * @param $itemClassName Item class name.
     * @param $data          Array or data.
     *
     * @return array
     */
    static function fillCollection($itemClassName, $data){

        $collection = array();

        foreach ($data as $item){
            $itemObj = new $itemClassName();
            $itemObj = static::setProperties($itemObj, $item);
            $collection[] = $itemObj;
        }

        return $collection;

    }

    /**
     * Transform string with delimiter to camelCase
     *
     * @param string $string
     * @param bool   $capitalizeFirstCharacter
     * @param string $delimiter
     *
     * @return mixed
     */
    static function toCamelCase($string, $capitalizeFirstCharacter = false, $delimiter = "_")
    {

        $str = str_replace(' ', '', ucwords(str_replace($delimiter, ' ', $string)));

        if (!$capitalizeFirstCharacter) {
            $str[0] = strtolower($str[0]);
        }

        return $str;
    }

    /**
     * Convert camelCase string to underscore_string
     *
     * @param string $string Camelcase string.
     *
     * @return string underscored string.
     */
    static function toUnderscore($string){
        return strtolower(preg_replace('/([a-z])([A-Z])/', '$1_$2', $string));
    }

    static function arrayOrderBy()
    {
        $args = func_get_args();
        $data = array_shift($args);
        foreach ($args as $n => $field) {
            if (is_string($field)) {
                $tmp = array();
                foreach ($data as $key => $row)
                    $tmp[$key] = $row[$field];
                $args[$n] = $tmp;
            }
        }
        $args[] = &$data;
        call_user_func_array('array_multisort', $args);
        return array_pop($args);
    }

    /**
     * Convert DBAL result to key=>value array
     *
     * @param        $data
     * @param string $valueName
     * @param string $keyName
     *
     * @return array
     */
    static function resultAssocArray($data, $valueNames = array("value"), $keyName = "id"){

        $result = array();

        if (is_string($valueNames)){
            $valueNames = array($valueNames);
        }

        foreach($data as $value){

            foreach($valueNames as $name){

                if(count($valueNames) == 1){
                    $result[$value[$keyName]] = $value[$name];
                } else {
                    $result[$value[$keyName]][$name] = $value[$name];
                }
            }

        }

        return $result;

    }


}