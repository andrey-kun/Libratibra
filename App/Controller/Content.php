<?php
/**
 * Created by PhpStorm.
 * User: andrey-kun
 * Date: 19.01.19
 * Time: 23:39
 */

namespace App\Controller;


use App\Config;
use App\ReiterationException;
use Core\View;

abstract class Content extends \Core\Controller
{
    public function insert($model_fields)
    {
        $content = null;
        $message = null;
        $name = @$_GET['name'];

        if ($name !== null && $name !== "" && $model_fields !== null) {
            try {
                $content_fields = [
                    'name' => $name
                ];
                $model_fields = array_merge($content_fields, $model_fields);
                $content = static::insertIntoModel($model_fields);
                $message = static::getMessage('addSuccess', $name);
            } catch (ReiterationException $e) {
                $message = static::getMessage('alreadyExists', $name);
            }
        }

        $templateParams = [
            'actionUrl' => static::getActionUrl() . '/add',
            'content' => $content,
            'message' => $message,
            'projectName' => Config::PROJECT_NAME,
            'templateLinks' => static::getTemplateLinks(),
            'title' => static::getMessage('addTitle'),
        ];

        $this->renderTemplate('content/Add.html', $templateParams);
    }


    public function update($model_fields)
    {
        $id = $this->route_params['id'];
        $message = null;
        $newName = @$_GET['newName'];

        $content = static::getModel()::getById($id);

        if ($content === null) {
            $message = static::getMessage('missingId', $id);
        } elseif ($newName !== null && $newName !== "") {
            if (static::getModel()::isExists($newName)) {
                $message = static::getMessage('alreadyExists', $newName);
            } elseif ($content->name === $newName) {
                $message = static::getMessage('matchingNames', $newName);
            } else {
                $message = static::getMessage('renameSuccess', $content->name, $newName);

                $content->name = $newName;
                $content->flush();
            }
        }

        $templateParams = [
            'actionUrl' => static::getActionUrl() . '/edit/' . $id,
            'caption' => @static::getMessage('editCaption', $content->name),
            'content' => $content,
            'message' => $message,
            'projectName' => Config::PROJECT_NAME,
            'templateLinks' => static::getTemplateLinks(),
            'title' => @static::getMessage('editTitle')
        ];

        $this->renderTemplate('content/Edit.html', $templateParams);
    }

    public function delete()
    {
        $id = $this->route_params['id'];
        $isAgree = isset($_GET['agree']);
        $message = null;

        $content = static::getModel()::getById($id);

        if ($content === null) {
            $message = static::getMessage('missingId', $id);
        } elseif ($isAgree) {
            $message = static::getMessage('deleteSuccess', $content->name);
            $content->remove();
        }

        $templateParams = [
            'actionUrl' => static::getActionUrl() . '/delete/' . @$content->id,
            'caption' => @static::getMessage('deleteCaption', $content->name),
            'content' => $content,
            'message' => $message,
            'projectName' => Config::PROJECT_NAME,
            'templateLinks' => static::getTemplateLinks(),
            'title' => @static::getMessage('deleteTitle')
        ];

        $this->renderTemplate('content/Delete.html', $templateParams);
    }

    public abstract function addAction();

    public abstract function deleteAction();

    public abstract function editAction();

    public abstract static function getModel();

    public abstract function getMessage($id, ...$args);

    public abstract static function getActionUrl();

    public abstract static function getTemplateLinks();

    protected function insertIntoModel($model_fields)
    {
        return static::getModel()::insert($model_fields);
    }

    public function renderTemplate($path, $params)
    {
        View::renderTemplate($path, $params);
    }
}