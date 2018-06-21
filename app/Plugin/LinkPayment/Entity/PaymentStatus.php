<?php
/**
 * Created by PhpStorm.
 * User: hideki_okajima
 * Date: 2018/06/21
 * Time: 18:24
 */

namespace Plugin\LinkPayment\Entity;

use Doctrine\ORM\Mapping as ORM;
use Eccube\Entity\Master\AbstractMasterEntity;

/**
 * PaymentStatus
 *
 * TODO プラグインのテーブルで必要なアノテーションを精査
 *
 * @ORM\Table(name="plg_sample_payment_payment_status")
 * @ORM\HasLifecycleCallbacks()
 * @ORM\Entity(repositoryClass="Plugin\LinkPayment\Repository\PaymentStatusRepository")
 * @ORM\Cache(usage="NONSTRICT_READ_WRITE")
 */
class PaymentStatus extends AbstractMasterEntity
{
    // TODO 定数名は要変更

    /**
     * 未決済
     */
    const OUTSTANDING = 1;
    /**
     * 有効性チェック済
     */
    const ENABLED = 2;
    /**
     * 仮売上
     */
    const PROVISIONAL_SALES = 3;
    /**
     * 実売上
     */
    const ACTUAL_SALES = 4;
    /**
     * キャンセル
     */
    const CANCEL = 5;
}
