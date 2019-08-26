<?php

namespace AutomaticDiscount\Coupon;

use AutomaticDiscount\Model\AutomaticDiscountQuery;
use Thelia\Coupon\BaseFacade;
use Thelia\Model\Coupon;

/**
 * Class AutomaticDiscountFacade
 * @package AutomaticDiscount\Coupon
 * @author Baixas Alban <abaixas@openstudio.fr>
 */
class AutomaticDiscountFacade extends BaseFacade
{
    /** @var string[] array current enable automatic coupon code */
    protected $automaticDiscountList;

    /**
     * @return array
     * @throws \Propel\Runtime\Exception\PropelException
     */
    public function getCurrentCoupons()
    {
        if ($this->automaticDiscountList === null) {
            $this->automaticDiscountList = AutomaticDiscountQuery::create()->findAllEnabledAutomaticDiscount();
        }

        foreach ($this->automaticDiscountList as $code) {
            $this->pushCouponInSession($code);
        }

        return parent::getCurrentCoupons();
    }

//    /**
//     * Find one Coupon in the database from its code
//     *
//     * @param string $code Coupon code
//     *
//     * @return Coupon
//     */
//    public function findOneCouponByCode($code)
//    {
//        return AutomaticDiscountQuery::create()->findOneCouponByCodeExcludeAutomatic($code);
//    }
}
