<?php

//namespace common\components; no need to use it as it is registered at entry script

use yii\base\Component;

/**
 * CHelper Component
 *
 */
class CHelper extends Component {

    const FROM_API = 1;
    const FROM_WEB = 0;

    /**
     * Function added to display the formatted output on the screen
     * In controller use as $this->debug();
     * In view use as $this->context->debug();
     * @param type $variable
     * @param type $die
     */
    public function init() {
        parent::init();
    }

    /**
     * 
     * @param type $variable
     * @param type $die
     */
    public static function debug($variable, $die = true) {
        echo "<pre>";
        echo "<div class='row' style='display:table;color:white;background:#5C5C5C;'><div style='width: 50%; display: table-cell; vertical-align:top'>";
        echo "<h1 style='color:orange'>PRINT ARRAY</h1>";
        echo "<pre style='padding:10px 20px;border-right:1px solid white'>";
        print_r($variable);
        echo "</pre>";
        echo "</div>";
        echo "<div div style='width: 50%; display: table-cell; vertical-align:top'>";
        echo "<h1 style='color:orange'>VAR DUMP</h1>";
        echo "<pre style='padding:10px 20px;'>";
        var_dump($variable);
        echo "</pre>";
        echo "</div></div>";
        echo "</pre>";
        ( $die ) ? die() : '';
    }

    /** Function Name :      baseUrl()
     *  Description :        This function returns the base url of the web application's root folder
     *  Parameters :         No parameters
     */
    public static function baseUrl() {

        return yii\helpers\BaseUrl::base();
    }

    /**
     * 
     * @param type $key
     */
    public static function setFlashSuccess($key, $message = null) {
        return Yii::$app->session->setFlash('success', \Yii::t('app.success', $key, ['MESSAGE' => $message]));
    }

    /**
     * 
     * @param type $key
     */
    public static function setFlashError($key, $message = null) {
        Yii::$app->session->setFlash('error', \Yii::t('app.error', $key, ['MESSAGE' => $message]));
    }

    /**
     * 
     * @param type $key
     */
    public static function setFlashWarning($key, $message = null) {
        Yii::$app->session->setFlash('warning', \Yii::t('app.warning', $key, ['MESSAGE' => $message]));
    }

    /**
     * 
     * @param type $key
     */
    public static function setFlashNotice($key, $message = null) {
        Yii::$app->session->setFlash('notice', \Yii::t('app.notice', $key, ['MESSAGE' => $message]));
    }

    /**
     * @author Nikhil B <nikhilbhagunde@benchmarkitsolutions.com> 
     * @param type $attr
     * @return type
     */
    public static function getUserIdentityData($attr = '') {
        if (!isset(Yii::$app->user)) {
            return;
        }
        if (Yii::$app->user->getIdentity() === NULL)
            return;
        if ($attr != '')
            return Yii::$app->user->getIdentity()->getAttribute($attr);
        else {
            return Yii::$app->user->getIdentity()->getAttributes();
        }
    }
    
    /**
     * @author Kumar waghmode <kumarwaghmode@benchmarkitsolutions.com>
     * @date : 04-June-2019
     * @param type date
     * @return type
     */
    public static function getFormatedDatetime($date) {
        $formatter = \Yii::$app->formatter;
        $_date = str_replace('-', '/', $date);
        return $date ? $formatter->asDate($_date, 'php:m-d-Y H:i:s') : '';
    }
}
