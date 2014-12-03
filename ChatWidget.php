<?php
namespace jones\wschat;

use yii\base\Widget;
use yii\web\View;

/**
 * Class ChatWidget
 * @package jones\wschat
 */
class ChatWidget extends Widget
{
    /**
     * @var boolean set to true if widget will be run for auth users
     */
    public $auth = false;
    public $user_id = null;
    public $view = 'index';
    /** @var int $port web socket port */
    public $port = 8080;

    /**
     * @override
     */
    public function run()
    {
        $this->registerJsOptions();
        return $this->render($this->view, ['auth' => $this->auth]);
    }

    /**
     * Register js variables
     *
     * @access protected
     * @return void
     */
    protected function registerJsOptions()
    {
        $opts = [
            'var currentUserId = '.($this->user_id ?: 0).';',
            'var port = '.$this->port.';'
        ];
        $this->getView()->registerJs(implode(' ', $opts), View::POS_BEGIN);
    }
}
 