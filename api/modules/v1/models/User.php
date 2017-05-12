<?php
namespace api\modules\v1\models;
use \yii\db\ActiveRecord;
/**
 * User Model
 *
 * @author Budi Irawan <deerawan@gmail.com>
 */
class User extends ActiveRecord
{
	/**
	 * @inheritdoc
	 */
	public static function tableName()
	{
		return 'user';
	}

    /**
     * @inheritdoc
     */
    public static function primaryKey()
    {
        return ['id'];
    }

    /**
     * Define rules for validation
     */
    public function rules()
    {
        return [
            [['id', 'name'], 'required']
        ];
    }
	public function fields()
    {
        return ['id', 'name','age'];
    }
}
