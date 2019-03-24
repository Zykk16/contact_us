<?php

namespace common\components;

use Google_Client;
use Google_Service_Drive;
use Google_Service_Drive_DriveFile;
use Yii;
use yii\base\Component;


class GoogleApiComponent extends Component
{

    public function createDocumentGoogleApi()
    {
        $query = (new \yii\db\Query())
            ->select(['*'])
            ->from('settings')->all();
        foreach ($query as $key => $item) {
            $res[$item['key']] = $item['value'];
        }

        $url_array = explode('?', 'http://' . $_SERVER ['HTTP_HOST'] . $_SERVER['REQUEST_URI']);
        $url = $url_array[0];
        $client = new Google_Client();
        $client->setClientId($res['clientId']);
        $client->setClientSecret($res['clientSecret']);
        $client->setRedirectUri($url);
        $client->setAccessType('offline');
        $client->setScopes(array('https://www.googleapis.com/auth/drive.file'));
        $client->setAccessType('offline');

        if (isset($_GET['code'])) {
            $cookies = Yii::$app->response->cookies;

            $cookies->add(new \yii\web\Cookie([
                'name' => 'code',
                'value' => $_GET['code'],
            ]));

            $cookies->add(new \yii\web\Cookie([
                'name' => 'scope',
                'value' => $_GET['scope'],
            ]));

            $response = $client->fetchAccessTokenWithAuthCode( $_GET['code'] );

            $accessToken = $response['access_token']; // access токен
            $expiresIn = $response['expires_in']; // истекает через 3600 (сек.) (1 час)
            $scope = $response['scope']; // области, к которым был получен доступ
            $tokenType = $response['token_type']; // тип токена
            $created = $response['created']; // время создания токена 1537170421

            $client->authenticate($_GET['code']);
            $client->setAccessToken($accessToken);

            $service = new Google_Service_Drive($client);

            $files = scandir($_SERVER["DOCUMENT_ROOT"] . '/pdf');
            rsort($files, SORT_DESC);
            $lastfile = $files[0];

            $file = new Google_Service_Drive_DriveFile();
            $file_path = Yii::getAlias('@frontend') . '/web/pdf/' . $lastfile;
            $file->setName($lastfile);
            $file->setParents(['1XfFxgthV4JcUm_tsL0bjmEyfTLv1ZvI-']);
            $file->setDescription('Сгенерированный pdf');
            $file->setMimeType('application/pdf');
            $service->files->create(
                $file,
                array(
                    'data' => file_get_contents($file_path),
                    'mimeType' => 'application/pdf'
                )
            );
        } else {
            $authUrl = $client->createAuthUrl();
            header('Location: ' . $authUrl);
            exit();
        }
    }
}