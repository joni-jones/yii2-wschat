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
     * @param array $props array of properties for non auth chat users
     */
    public function __construct($id = null, $modelClassName = null, array $props = [])
    {
        $this->id = $id;
        $this->modelClassName = $modelClassName;
        $this->init($props);
}

    /**
     * Restore user attributes from cache or load it from
     * repository
     *
     * @access private
     * @param array $props
     * @return void
     */
    private function init(array $props = [])
    {
        $cache = Yii::$app->cache;
        $cache->keyPrefix = 'user';
        if ($cache->exists($this->id)) {
            $attrs = $cache->get($this->id);
        } else {
            if ($this->modelClassName) {
                if (!in_array('findOne', (array)get_class_methods($this->modelClassName))) {
                    throw new InvalidParamException(Yii::t('app', 'Model class should implements `findOne()` method'));
                }
                /** @var \yii\db\BaseActiveRecord $model */
                $model = call_user_func_array([$this->modelClassName, 'findOne'], ['id' => $this->id]);
                if (!$model) {
                    throw new InvalidParamException(Yii::t('app', 'User entity not found.'));
                }
                $attrs = $model->attributes;
            } else {
                $attrs = $props;
            }
            $cache->set($this->id, $attrs);
        }
        Yii::configure($this, $attrs);
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
}

 