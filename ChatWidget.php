<?php
namespace jones\wschat;

use yii\base\Widget;

/**
 * Class ChatWidget
 * @package jones\wschat
 */
class ChatWidget extends Widget
{
    /**
     * @override
     */
    public function run()
    {
        return $this->render('views/index');
    }
}
 