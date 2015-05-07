<?php
/*
 * This file is part of EC-CUBE
 *
 * Copyright(c) 2000-2015 LOCKON CO.,LTD. All Rights Reserved.
 *
 * http://www.lockon.co.jp/
 *
 * This program is free software; you can redistribute it and/or
 * modify it under the terms of the GNU General Public License
 * as published by the Free Software Foundation; either version 2
 * of the License, or (at your option) any later version.
 *
 * This program is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 * GNU General Public License for more details.
 *
 * You should have received a copy of the GNU General Public License
 * along with this program; if not, write to the Free Software
 * Foundation, Inc., 59 Temple Place - Suite 330, Boston, MA  02111-1307, USA.
 */


namespace Eccube\Page\Entry;

use Eccube\Application;
use Eccube\Page\AbstractPage;
use Eccube\Framework\Display;
use Eccube\Framework\Helper\KiyakuHelper;
use Eccube\Framework\Helper\PurchaseHelper;

/**
 * ご利用規約 のページクラス.
 *
 * @package Page
 * @author LOCKON CO.,LTD.
 */
class Kiyaku extends AbstractPage
{
    /**
     * Page を初期化する.
     *
     * @return void
     */
    public function init()
    {
        parent::init();
        $this->tpl_title = 'ご利用規約';
    }

    /**
     * Page のプロセス.
     *
     * @return void
     */
    public function process()
    {
        parent::process();
        $this->action();
        $this->sendResponse();
    }

    /**
     * Page のアクション.
     *
     * @return void
     */
    public function action()
    {
        //決済処理中ステータスのロールバック
        /* @var $objPurchase PurchaseHelper */
        $objPurchase = Application::alias('eccube.helper.purchase');
        $objPurchase->cancelPendingOrder(PENDING_ORDER_CANCEL_FLAG);

        $arrKiyaku = $this->lfGetKiyakuData();
        $this->max = count($arrKiyaku);

        // mobile時はGETでページ指定
        if (Application::alias('eccube.display')->detectDevice() == DEVICE_TYPE_MOBILE) {
            $this->offset = $this->lfSetOffset($_GET['offset']);
        } else {
            $this->offset = null;
        }

        $this->tpl_kiyaku_text = $this->lfMakeKiyakuText($arrKiyaku, $this->max, $this->offset);
    }

    /**
     * 規約文の作成
     *
     * @param mixed $arrKiyaku
     * @param integer $max
     * @param mixed $offset
     * @access public
     * @return string 規約の内容をテキストエリアで表示するように整形したデータ
     */
    public function lfMakeKiyakuText($arrKiyaku, $max, $offset)
    {
        $tpl_kiyaku_text = '';
        for ($i = 0; $i < $max; $i++) {
            if ($offset !== null && ($offset - 1) <> $i) continue;
            $tpl_kiyaku_text.=$arrKiyaku[$i]['kiyaku_title'] . "\n\n";
            $tpl_kiyaku_text.=$arrKiyaku[$i]['kiyaku_text'] . "\n\n";
        }

        return $tpl_kiyaku_text;
    }

    /**
     * 規約内容の取得
     *
     * @access private
     * @return array $arrKiyaku 規約の配列
     */
    public function lfGetKiyakuData()
    {
        /* @var $objKiyaku KiyakuHelper */
        $objKiyaku = Application::alias('eccube.helper.kiyaku');
        $arrKiyaku = $objKiyaku->getList();

        return $arrKiyaku;
    }

    /**
     *
     * 携帯の場合getで来る次ページのidを適切に処理する
     *
     * @param mixed $offset
     * @access private
     * @return int
     */
    public function lfSetOffset($offset)
    {
        return is_numeric($offset) === true ? intval($offset) : 1;
    }
}
