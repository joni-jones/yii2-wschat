<?php
namespace jones\wschat\components;

use yii;
use yii\db\Query;
use yii\db\Exception;

/**
 * Class DbStorage
 *
 * This class is database storage implementation for chat messages storing
 * @package jones\wschat\components
 */
class DbStorage extends AbstractStorage
{
    /**
     * Get name of table
     *
     * @access public
     * @static
     * @return string
     */
    public static function tableName()
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
            'id', 'chat_id', 'chat_title', 'user_id', 'username', 'avatar_16',
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
            ->from(self::tableName())
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
            Yii::$app->getDb()->createCommand()
                ->insert(self::tableName(), $params)
                ->execute();
        } catch (Exception $e) {
            Yii::error($e->getMessage());
            return false;
        }
        return true;
    }
}
 