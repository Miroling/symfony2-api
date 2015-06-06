<?php
/**
 * Created by PhpStorm.
 * User: miroling
 * Date: 17.04.15
 * Time: 12:29
 */

namespace AppBundle\Helper;


use JMS\Serializer\SerializationContext;
use FOS\RestBundle\View\View;

trait CreateView
{
    /**
     * Создание объекта View для ответа.
     *
     * @param mixed   $data       Данные для включения в объект.
     * @param array   $groups     Группы сериализации.
     * @param integer $code       Код ответа.
     * @param bool    $checkDepth Проверка макс. глубины вкл/выкл.
     *
     * @return View
     */
    public function createView($data, $groups = [], $code, $checkDepth = true)
    {
        $view = View::create($data, $code);

        $context = SerializationContext::create();
        if (!empty($groups)) {
            $context->setGroups($groups);
        }

        if ($checkDepth) {
            $context->enableMaxDepthChecks();
        }

        $view->setSerializationContext($context);

        return $view;
    }
}
