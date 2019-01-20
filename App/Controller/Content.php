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
    public function addAction()
    {
        $content = null;
        $message = null;
        $name = @$_GET['name'];

        if ($name !== null && $name !== "") {
            try {
                $content = static::getModel()::insert($name);
                $message = static::getMessage('addSuccess', $name);
            } catch (ReiterationException $e) {
                $message = static::getMessage('alreadyExists', $name);
            }
        }

        View::renderTemplate('content/Add.html', [
            'actionUrl' => static::getActionUrl() . '/add',
            'content' => $content,
            'message' => $message,
            'projectName' => Config::PROJECT_NAME,
            'title' => static::getMessage('addTitle'),
        ]);
    }

    public abstract static function getModel();

    public abstract function getMessage($id, ...$args);

    public abstract static function getActionUrl();

    public function editAction()
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

        View::renderTemplate('content/Edit.html', [
            'actionUrl' => static::getActionUrl() . '/edit/' . $id,
            'caption' => @static::getMessage('editCaption', $content->name),
            'content' => $content,
            'message' => $message,
            'projectName' => Config::PROJECT_NAME,
            'title' => @static::getMessage('editTitle'),
        ]);
    }

    public function delAction()
    {
        $id = $this->route_params['id'];
        $isAgree = isset($_GET['agree']);
        $message = null;

        $content = static::getModel()::getById($id);

        if ($content === null) {
            $message = static::getMessage('missingId', $id);
        } elseif ($isAgree) {
            $message = static::getMessage('delSuccess', $content->name);
            $content->remove();
        }

        View::renderTemplate('content/Delete.html', [
            'actionUrl' => static::getActionUrl() . '/del/' . @$content->id,
            'caption' => @static::getMessage('delCaption', @$content->name),
            'content' => $content,
            'message' => $message,
            'projectName' => Config::PROJECT_NAME,
            'title' => @static::getMessage('delTitle'),
        ]);
    }
}