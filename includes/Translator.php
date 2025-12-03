<?php
/**
 * @file Translator.php
 * @route /includes/Translator.php
 * @description 
 * @author Kevin Mariano
 * @version 1.0.1
 * @since 1.0.0
 * @copyright Banda de Baranoa 2025
 */

class Translator {
    public static function translate($text, $targetLang) {
        if ($targetLang === 'es' || empty($text)) {
            return $text;
        }

        $encodedText = urlencode($text);
        $url = "https://api.mymemory.translated.net/get?q={$encodedText}&langpair=es|{$targetLang}";

        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($ch, CURLOPT_TIMEOUT, 2); // Timeout de 2 segundos
        $response = curl_exec($ch);
        curl_close($ch);

        if ($response) {
            $json = json_decode($response, true);
            if (isset($json['responseData']['translatedText'])) {
                return $json['responseData']['translatedText'];
            }
        }
        return $text;
    }
}
?>