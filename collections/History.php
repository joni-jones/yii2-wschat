<?php
namespace jones\wschat\collections;

use Yii;
use yii\mongodb\Exception;
use yii\mongodb\Query;
use jones\wschat\components\AbstractStorage;

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
class History extends AbstractStorage
{
    /**
     * Get name of mongo collection
     *
     * @access public
     * @static
     * @return string
     */
    public static function collectionName()
    {
        return 'history';
    }

    /**
     * Get list of attributes
     *
     * @access public
     * @return array
     */
    public function attributes()
    {
        return [
            '_id', 'chat_id', 'chat_title', 'user_id', 'username', 'avatar_16',
            'avatar_32', 'timestamp', 'message'
        ];
    }

    /**
     * @inheritdoc
     */
    public function getHistory($chatId, $limit = 10)
    {
        $query = new Query();
        $query->select(['user_id', 'username', 'message', 'timestamp', 'avatar_16', 'avatar_32'])
            ->from(self::collectionName())
            ->where(['chat_id' => $chatId]);
        $query->orderBy(['timestamp' => SORT_DESC]);
        if ($limit) {
            $query->limit($limit);
        }
        return $query->all();
    }

    /**
     * @inheritdoc
     */
    public function storeMessage(array $params)
    {
        try {
            /** @var \yii\mongodb\Collection $collection */
            $collection = Yii::$app->mongodb->getCollection(self::collectionName());
            $collection->insert($params);
        } catch (Exception $e) {
            Yii::error($e->getMessage());
            return false;
        }
        return true;
    }
}
 