<?php
namespace jones\wschat\components;

use Yii;
use yii\base\InvalidParamException;

/**
 * Class User
 * @package \jones\wschat\components
 *
 * @property mixed $id
 * @property string $username
 * @property string $avatar_16 url to avatar 16x16 image
 * @property string $avatar_32 url to avatar 32x32 image
 */
class User
{
    public $id;
    public $username;
    public $avatar_16;
    public $avatar_32;
    private $rid;
    /** @var \jones\wschat\components\ChatRoom $chat */
    private $chat;
    /** @var string */
    private $modelClassName = null;

    /**
     * @param $id
     * @param string $modelClassName default null
     */
    public function __construct($id = null, $modelClassName = null)
    {
        $this->id = $id;
        $this->modelClassName = $modelClassName;
        //setup model only if chat will be user for auth users
        if ($this->id && $this->modelClassName) {
            if (!in_array('findOne', (array)get_class_methods($this->modelClassName))) {
                throw new InvalidParamException(Yii::t('app', 'Invalid model class name was specified'));
            }
            $this->init();
        } else {
            /**
             * @TODO added just for testing
             */
            $this->id = uniqid();
            $this->setAvatar();
        }
    }

    private function init()
    {
        $cache = Yii::$app->cache;
        $cache->keyPrefix = 'user';
        /**
         * @TODO remove it after testing
         */
        $cache->delete($this->id);
        if ($cache->exists($this->id)) {
            Yii::configure($this, $cache->get($this->id));
        } else {
            /** @var \yii\db\BaseActiveRecord $model */
            $model = call_user_func_array([$this->modelClassName, 'findOne'], ['id' => $this->id]);
            if (!$model) {
                throw new InvalidParamException(Yii::t('app', 'User entity not found.'));
            }
            /**
             * @TODO set only safe attributes
             */
            $cache->set($this->id, $model->attributes);
        }
    }

    /**
     * Get user id
     *
     * @param string
     * @return string
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * Get user resource id
     *
     * @access public
     * @return string
     */
    public function getRid()
    {
        return $this->rid;
    }

    /**
     * Set user resource id
     *
     * @access public
     * @param $rid
     * @return void
     */
    public function setRid($rid)
    {
        $this->rid = $rid;
    }

    /**
     * Get user chat room
     *
     * @access public
     * @return \jones\wschat\components\ChatRoom
     */
    public function getChat()
    {
        return $this->chat;
    }

    /**
     * Set chat room for user
     *
     * @access public
     * @param \jones\wschat\components\ChatRoom $chat
     * @return void
     */
    public function setChat(ChatRoom $chat)
    {
        $this->chat = $chat;
        $this->chat->addUser($this);
    }

    /**
     * @TODO add real setter
     */
    protected function setAvatar()
    {
        $num = rand(1, 100);
        $this->username = 'name #'.$num;
        $dirPath = Yii::$app->basePath.'/web';
        $image_32 = '/uploads/avatar_'.$num.'_32.png';
        $image_16 = '/uploads/avatar_'.$num.'_16.png';
        if (!file_exists($dirPath.$image_32)) {
            if (!is_dir($dirPath.'/uploads')) {
                mkdir($dirPath.'/uploads');
            }
            $file = file_get_contents('http://www.gravatar.com/avatar/'.$num.'?s=32&d=identicon&r=g');
            file_put_contents($dirPath.$image_32, $file);
            $file = file_get_contents('http://www.gravatar.com/avatar/'.$num.'?s=16&d=identicon&r=g');
            file_put_contents($dirPath.$image_16, $file);
        }
        $this->avatar_32 = $image_32;
        $this->avatar_16 = $image_16;
    }
}
 