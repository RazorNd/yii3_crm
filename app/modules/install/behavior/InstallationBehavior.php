<?php

namespace app\modules\install\behavior;


use app\controllers\LocalesController;
use app\helpers\InstallationHelper;
use app\modules\install\Module;
use app\services\SettingService;
use Yii;
use yii\base\Behavior;
use yii\web\Application;

/**
 * Class InstallationBehavior
 */
class InstallationBehavior extends Behavior
{
    public $ignoreModules = [
        Module::class,
        \yii\debug\Module::class,
        \yii\gii\Module::class,
    ];

    public $ignoredControllers = [
        LocalesController::class,
    ];

    /**
     * @var SettingService
     */
    private $settingService;

    public function __construct(SettingService $settingService, array $config = [])
    {
        parent::__construct($config);
        $this->settingService = $settingService;
    }


    /**
     * @return array
     */
    public function events()
    {
        return [
            Application::EVENT_BEFORE_ACTION => 'checkInstallation',
        ];
    }

    /**
     *
     */
    public function checkInstallation()
    {
        if (in_array(get_class(Yii::$app->controller->module), $this->ignoreModules)) {
            return;
        }
        if (Yii::$app->controller->module instanceof Yii::$app &&
            in_array(get_class(Yii::$app->controller), $this->ignoredControllers)) {
            return;
        }
        if ($this->settingService->get(InstallationHelper::SETTING_APP_INSTALLED) === false) {
            Yii::$app->controller->redirect(['/install/']);
        }
    }
}