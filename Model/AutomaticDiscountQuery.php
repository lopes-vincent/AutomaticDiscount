<?php

namespace AutomaticDiscount\Model;

use AutomaticDiscount\Model\Base\AutomaticDiscountQuery as BaseAutomaticDiscountQuery;
use AutomaticDiscount\Model\Map\AutomaticDiscountTableMap;
use Propel\Runtime\ActiveQuery\Criteria;
use Propel\Runtime\ActiveQuery\Join;
use Thelia\Model\CouponQuery;
use Thelia\Model\Map\CouponTableMap;

/**
 * Class AutomaticDiscountQuery
 * @package AutomaticDiscount\Model
 * @author Baixas Alban <abaixas@openstudio.fr>
 */
class AutomaticDiscountQuery extends BaseAutomaticDiscountQuery
{
    /**
     * find an automatic discount with a coupon code
     * @param $code
     * @return \Thelia\Model\Coupon
     */
    public function findAutomaticDiscountByCouponCode($code)
    {
        $query = CouponQuery::create()
            ->filterByCode($code)
        ;

        $join = new Join();
        $join->setJoinType(Criteria::INNER_JOIN);
        $join->addExplicitCondition(
            CouponTableMap::TABLE_NAME,
            'id',
            null,
            AutomaticDiscountTableMap::TABLE_NAME,
            'coupon_id',
            'automatic_discount_join'
        );

        $query->addJoinObject($join, 'automatic_discount_join');

        return $query->findOne();

    }

    /**
     * find all enabled automatic discount
     * @return array
     * @throws \Propel\Runtime\Exception\PropelException
     */
    public function findAllEnabledAutomaticDiscount()
    {
        $query = CouponQuery::create()->filterByIsEnabled(true);

        $query->select('code');

        $join = new Join();
        $join->setJoinType(Criteria::INNER_JOIN);
        $join->addExplicitCondition(
            CouponTableMap::TABLE_NAME,
            'id',
            null,
            AutomaticDiscountTableMap::TABLE_NAME,
            'coupon_id',
            'automatic_discount_join'
        );

        $query->addJoinObject($join, 'automatic_discount_join');

        return $query->find()->toArray();
    }

    /**
     * find a coupon with code is not an automatic discount
     * @param $code
     * @return \Thelia\Model\Coupon
     */
    public function findOneCouponByCodeExcludeAutomatic($code)
    {
        $query = CouponQuery::create();

        $query->filterByCode($code);
        $query->where('automatic_discount_join.coupon_id IS NULL');

        $join = new Join();
        $join->setJoinType(Criteria::LEFT_JOIN);
        $join->addExplicitCondition(
            CouponTableMap::TABLE_NAME,
            'id',
            null,
            AutomaticDiscountTableMap::TABLE_NAME,
            'coupon_id',
            'automatic_discount_join'
        );

        $query->addJoinObject($join, 'automatic_discount_join');

        return $query->findOne();
    }
} // AutomaticDiscountQuery
