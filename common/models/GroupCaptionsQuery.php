<?php

namespace common\models;

/**
 * This is the ActiveQuery class for [[GroupCaptions]].
 *
 * @see GroupCaptions
 */
class GroupCaptionsQuery extends \common\models\BaseActiveQuery
{
    /*public function active()
    {
        return $this->andWhere('[[status]]=1');
    }*/

    /**
     * {@inheritdoc}
     * @return GroupCaptions[]|array
     */
    public function all($db = null)
    {
        return parent::all($db);
    }

    /**
     * {@inheritdoc}
     * @return GroupCaptions|array|null
     */
    public function one($db = null)
    {
        return parent::one($db);
    }
}
