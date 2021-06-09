<?php
require_once ($_SERVER['DOCUMENT_ROOT'].'/vendor/autoload.php');

use Google\Cloud\Translate\V3\TranslationServiceClient;

$config = [
    'keyFilePath' => $_SERVER['DOCUMENT_ROOT']."/wms-engine-key.json",
    'projectId' => "wms-engine",
];
$post=json_decode(file_get_contents("php://input"), true);
if (isset($post['target']) && isset($post['text'])){
    putenv('GOOGLE_APPLICATION_CREDENTIALS='.$_SERVER['DOCUMENT_ROOT']."/wms-engine-key.json");

    $config = [
        'keyFilePath' => $_SERVER['DOCUMENT_ROOT']."/wms-engine-key.json",
        'projectId' => "wms-engine",
    ];


    $translationServiceClient = new TranslationServiceClient();

    /** Uncomment and populate these variables in your code */
    $text = $post['text'];
    $targetLanguage = $post['target'];
    $projectId = 'wms-engine';
    $contents = [$text];
    $formattedParent = $translationServiceClient->locationName($projectId, 'global');

    try {
        $response = $translationServiceClient->translateText(
            $contents,
            $targetLanguage,
            $formattedParent
        );
        // Display the translation for each input text provided
        foreach ($response->getTranslations() as $translation) {
            printf('Translated text: %s' . PHP_EOL, );
        }
    } finally {
        $translationServiceClient->close();
    }

    exit(json_encode(array("result"=>$translation->getTranslatedText())));
}
