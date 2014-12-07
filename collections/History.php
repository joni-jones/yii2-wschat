<?php
namespace jones\wschat\collections;

use yii\mongodb\ActiveRecord;

/**
 * Class History
 * @package jones\wschat\collections
 * @property \MongoId $_id
 * @property string $chat_id
 * @property string $chat_title
 * @property string $user_id
 * @property string $username
 * @property string $avatar_16
 * @property string $avatar_32
 * @property integer $timestamp
 * @property string $message
 */
class History extends ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function collectionName()
    {
        return 'history';
    }

    /**
     * @override
     */
    public function attributes()
    {
        return ['_id', 'chat_id', 'chat_title', 'user_id', 'username', 'avatar_16', 'avatar_32', 'timestamp', 'message'];
    }
}
 