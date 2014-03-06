<?php

class PutIO
{
    
    private $oauth = null;
    private $url = "https://put.io/v2";
    private $cli = null;

    /**
     * 建構子
     * @param string  $key oauth key
     * @param boolean $cli CLI模式
     * @param string  $url api位置，若無設定則為預設的
     */
    public function __construct($key = null, $cli = true, $url = null)
    {
        $this->setOAuth($key);
        $this->cli = $cli;
        $this->url = $url ? $url : $this->url;
    }

    /**
     * 列出所有檔案
     * @param  integer $parent_id 資料夾id
     * @param  boolean $print_out 印出
     * @return object             
     */
    public function list_file($parent_id = 0, $print_out = false)
    {
        $data = $this->request("$this->url/files/list?parent_id=$parent_id&oauth_token=$this->oauth");
        if ($print_out) {
            var_dump($data);
        } else {
            return $data;
        }
    }

    /**
     * 下載
     * @param  string $item_id 檔案ID
     * @param  boolean $to_local 存到本地端位置
     * @param  string $local 本地端
     * @return object          實際檔案位置
     */
    public function download($item_id = null, $to_local = false, $local = "")
    {           
        $request_uri = "$this->url/files/$item_id/download?oauth_token=$this->oauth";
        if ($to_local) {
            $data = $this->request($request_uri);
            if (!is_array($data)) {
                $dom = new DOMDocument;
                $dom->loadHTML($data);
                $link = $dom->getElementsByTagName("a")->item(0);
                file_put_contents($local, $this->request($link->nodeValue)); 
            } elseif ($this->isJson($data)) {
                var_dump($data);
            }
        } else {
            header("Location: $request_uri");
        }
    }

    /**
     * 刪除
     * @param  string  $file_ids  檔案ID，可以用","分開
     * @param  boolean $print_out 印出
     * @return object             
     */
    public function delete($file_ids = null, $print_out = false)
    {
        $data = $this->request("$this->url/files/delete?oauth_token=$this->oauth", array("file_ids" => $file_ids), "POST");
        if ($print_out) {
            var_dump($data);
        } else {
            return $data;
        }
    }


    /**
     * 建立新資料夾
     * @param  string  $name      新資料夾名稱
     * @param  integer $parent_id 於哪個資料夾底下
     * @return object             
     */
    public function create_folder($name = null, $parent_id = 0)
    {
        $this->request("$this->url/files/create-folder?oauth_token=$this->oauth", array("name" => $name, "parent_id" => $parent_id), "POST");
    }

    /**
     * 移動檔案
     * @param  string  $file_ids  檔案ID
     * @param  integer $parent_id 於哪個資料夾底下
     * @param  boolean $print_out 印出
     * @return object             
     */
    public function move($file_ids = null, $parent_id = 0, $print_out = false)
    {
        $data = $this->request("$this->url/files/move?oauth_token=$this->oauth", array("file_ids" => $file_ids, "parent_id" => $parent_id), "POST");
        if ($print_out) {
            var_dump($data);
        } else {
            return $data;
        }
    }


    /**
     * 重新命名
     * @param  string  $file_id   檔案ID
     * @param  string  $name      檔案新名字
     * @param  boolean $print_out 印出
     * @return object             
     */
    public function rename($file_id = null, $name = null, $print_out = false)
    {
        $data = $this->request("$this->url/files/rename?oauth_token=$this->oauth", array("file_id" => $file_id, "name" => $name), "POST");
        if ($print_out) {
            var_dump($data);
        } else {
            return $data;
        }
    }

    /**
     * 轉換成mp4
     * @param  int  $id        檔案ID
     * @param  boolean $print_out 印出
     * @return object             
     */
    public function convert_mp4($id = null, $print_out = false)
    {
        $data = $this->request("$this->url/files/$id/mp4?oauth_token=$this->oauth", array("id" => $id), "POST");
        if ($print_out) {
            var_dump($data);
        } else {
            return $data;
        }
    }


    /**
     * 取得MP4檔案
     * @param  int $id 檔案ID
     * @return object     
     */
    public function get_mp4($id = null)
    {
        $data = $this->request("$this->url/files/$id/mp4?oauth_token=$this->oauth");
        if ($print_out) {
            var_dump($data);
        } else {
            return $data;
        }
    }

    /**
     * 搜尋檔案
     * @param  integer $query     資料夾ID
     * @param  integer $page      頁碼
     * @param  string  $syntax    搜尋用參數
     * @param  boolean $print_out false
     * @return object             
     */
    public function search($query = 0, $page = 0, $syntax = "", $print_out = false)
    {
        $data = $this->request("$this->url/files/search/$query/page/$page $syntax?oauth_token=$this->oauth");
        if ($print_out) {
            var_dump($data);
        } else {
            return $data;
        }
    }

    /**
     * 壓縮並下載
     * @param  string  $file_ids  檔案ID，可以用","分開
     * @param  boolean $print_out 印出
     * @return object             
     */
    public function zip_and_download($file_ids = null, $print_out = false)
    {
        $data = $this->request("$this->url/files/zip?file_ids=$file_ids&oauth_token=$this->oauth");
        if ($print_out) {
            var_dump($data);
        } else {
            return $data;
        }
    }


    /**
     * 上傳檔案，Torrent會直接開始
     * @param  object  $file      檔案
     * @param  string  $filename  檔案名稱，無則為預設
     * @param  integer $parent_id 於...資料架下
     * @param  boolean $print_out 印出
     * @return object             
     */
    public function upload($file = null, $filename = null, $parent_id = 0, $print_out = false)
    {
        if (is_file($file) || substr($file, 0, 4) == "http") {
            
            if (substr($file, 0, 4) == "http") {
                $tmp = $file;
                $file = tempnam("/tmp", "put.io-");
                $fn = fopen($file, "w+");
                $part = parse_url($tmp);
                if ($part["scheme"] == "http") {
                    $data_post = file_get_contents($tmp);
                } else {
                    $data_post = $this->request($tmp);
                }
                fwrite($fn, $data_post);
                fclose($fn);
            }
        } else {
            exit("Warning: error occured! Undefined file type!" . PHP_EOL);
        }
        $data = $this->request("$this->url/files/upload?oauth_token=$this->oauth", array("file" => "@" . realpath($file), "filename" => $filename, "parent_id" => $parent_id), "POST");
        
        if ($print_out) {
            var_dump($data);
        } else {
            return $data;
        }
    }

    /**
     * 設定OAuth
     * @param string $key oauth
     */
    public function setOAuth($key)
    {
        $this->oauth = $key;
        $this->checkOAuth();
    }

    private function checkOAuth()
    {
        for($i = 0; $i < 10; $i++) {
            echo ".";
            usleep(100000);
        }

        echo PHP_EOL;

        if ($this->oauth == null) {
            echo "Failed!" . PHP_EOL;
            exit("Warning: Do not find oauth key, leaving ..." . PHP_EOL);         
            
        } else {

            echo "Success!" . PHP_EOL;
            echo "Put.IO OAuth key is established." . PHP_EOL;

            // check if key is set or not
            return true;
        }
    }

    private function request($url, $data = null, $method = null)
    {


        // making request echo 
        if ($this->cli)
            echo PHP_EOL . "Making " . ($method ? $method : "GET") . " request... $url" . PHP_EOL;

        $ch = curl_init();
        curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, false);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_NOPROGRESS, false);
        curl_setopt($ch, CURLOPT_PROGRESSFUNCTION, function($download_size, $downloaded, $upload_size, $uploaded)
        {
            $progress = sprintf("%.2f", (($downloaded == 0 ? $uploaded : $downloaded) / ($download_size == 0 ? $upload_size + 1 : $download_size)) * 100);
            echo "\rRequest status: $progress%";
        });

        if (isset($method)) {
            curl_setopt($ch, CURLOPT_CUSTOMREQUEST, $method); 
            curl_setopt($ch, CURLOPT_POSTFIELDS, $data);
        }

        $result = curl_exec($ch);
        
        curl_close($ch);

        return $this->isJson($result) ? json_decode($result, true) : $result; 
    }   

    private function isJson($string) {
        json_decode($string);
        return (json_last_error() == JSON_ERROR_NONE);
    }
}
?>