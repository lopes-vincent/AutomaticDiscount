<?php
/*************************************************************************************/
/*      This file is part of the Thelia package.                                     */
/*                                                                                   */
/*      Copyright (c) OpenStudio                                                     */
/*      email : dev@thelia.net                                                       */
/*      web : http://www.thelia.net                                                  */
/*                                                                                   */
/*      For the full copyright and license information, please view the LICENSE.txt  */
/*      file that was distributed with this source code.                             */
/*************************************************************************************/

namespace AutomaticDiscount;

use AutomaticDiscount\Model\AutomaticDiscountQuery;
use Propel\Runtime\Connection\ConnectionInterface;
use Thelia\Install\Database;
use Thelia\Module\BaseModule;

/**
 * Class AutomaticDiscount
 * @package AutomaticDiscount
 * @author Baixas Alban <abaixas@openstudio.fr>
 */
class AutomaticDiscount extends BaseModule
{
    /** @var string */
    const DOMAIN_NAME = 'automaticdiscount';

    public function postActivation(ConnectionInterface $con = null)
    {
        try {
            AutomaticDiscountQuery::create()->findOne();
        } catch (\Exception $e) {
            (new Database($con))->insertSql(null, [__DIR__ . "/Config/thelia.sql"]);
        }
    }
}
