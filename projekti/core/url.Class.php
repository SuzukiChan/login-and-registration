<?php
require_once(realpath(dirname(__FILE__) . '/../config.php'));
class URLShortener{
    function generateShortURL($n){
        $db = new Connect;
        $characters = '0123456789abcdefghijklmnopqrstuvwxyz';
        $shortURL = '';
        for ($i = 0; $i < $n; $i++) {
            $index = rand(0, strlen($characters) - 1);
            $shortURL .= $characters[$index];
        }
        $checkURL = $db->prepare('SELECT id FROM shortened_urls WHERE short_url = :new_url');
        $checkURL -> execute(['new_url' => $shortURL]);
        $num = $checkURL->fetchAll(PDO::FETCH_COLUMN);
        $checkExistingURL = count($num);
        if($checkExistingURL != 0){
            $shortURL = $shortURL . count($num);
        }

        return $shortURL;
    }
    function validate(){
        $db = new Connect;
        $long_url = isset($_POST['long_url']) ? $_POST['long_url'] : '';
        $long_url = trim($long_url);
        if(!empty($long_url)){
            $shortURL = $this->generateShortURL(5);
            $insertData = $db->prepare('INSERT INTO shortened_urls (long_url, short_url) VALUES (:long_url, :short_url)');
            $insertData->execute([
                'long_url' => $long_url,
                'short_url' => $shortURL
            ]);
            if($insertData){
                return '<p style="text-align:center;">
                Here is your short URL:<br />' 
                . (isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] === 'on' ? 
                "https" : "http") . "://"
                 . $_SERVER['SERVER_NAME'] . '/' . $shortURL . '
                </p>';
            }
        }else{
            return '<p style="text-align:center;">Please fill it the form!</p>';
        }
        
    }
    function mainForm(){
        $a = isset($_GET['action']) ? $_GET['action'] : '';
        return '
            <div class="form_block">
                <div id="title">
                    URL Shortener
                </div>
                <div class="body">'.
                    ($a == 'validate' ? $this->validate() : '')
                    .'<form action="?action=validate" method="POST">
                        <input type="text" name="long_url" placeholder="Enter URL" required />
                        <input type="submit" value="Submit" />
                    </form>
                </div>
            </div>
        ';
    }
}
?>
