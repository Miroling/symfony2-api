<?php
/**
 * Created by PhpStorm.
 * User: miroling
 * Date: 02.05.15
 * Time: 12:26
 */

namespace AppBundle\Converter;


use AppBundle\Helper\ModelHelper;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\ParamConverter;
use Sensio\Bundle\FrameworkExtraBundle\Request\ParamConverter\ParamConverterInterface;
use Symfony\Component\HttpFoundation\Request;

class QueryParamConverter implements ParamConverterInterface {

    /**
     * Stores the object in the request.
     *
     * @param Request        $request       The request
     * @param ParamConverter $configuration Contains the name, class and options of the object
     *
     * @return bool True if the object has been successfully set, else false
     */
    public function apply(Request $request, ParamConverter $configuration){

        $class = $configuration->getClass();
        $object = ModelHelper::setProperties(new $class, $request->query->all());
        $request->attributes->set($configuration->getName(), $object);

        return true;
    }

    /**
     * Checks if the object is supported.
     *
     * @param ParamConverter $configuration Should be an instance of ParamConverter
     *
     * @return bool True if the object is supported, else false
     */
    public function supports(ParamConverter $configuration){
        return true;
    }

}