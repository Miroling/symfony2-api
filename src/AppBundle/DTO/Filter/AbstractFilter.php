<?php
/**
 * Created by PhpStorm.
 * User: miroling
 * Date: 17.04.15
 * Time: 16:13
 */

namespace AppBundle\DTO\Filter;


class AbstractFilter {

    function setProperties(array $vars) {

        $has = get_object_vars($this);
        $intersect = array_intersect(array_keys($vars), array_keys($has));

        foreach ($intersect as $key) {
            $this->$key = isset($vars[$key]) ? $vars[$key] : NULL;
        }

    }

}