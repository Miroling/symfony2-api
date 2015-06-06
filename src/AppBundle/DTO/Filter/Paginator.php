<?php
/**
 * Created by PhpStorm.
 * User: miroling
 * Date: 17.04.15
 * Time: 16:03
 */

namespace AppBundle\DTO\Filter;


class Paginator extends AbstractFilter {

    /**
     * Page number (Default: 1)
     *
     * @var  integer
     */
    public $pageNumber = 1;

    /**
     * Results count (Default: 3)
     *
     * @var integer
     */
    public $limit = 3;

}