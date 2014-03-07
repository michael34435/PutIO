<?php

class PutIO
{
    
    private $oauth = null;
    private $url = "https://put.io/v2";
    private $cli = null;
    private $multi = null;

    /**
     * 建構子
     * @param string  $key oauth key
     * @param boolean $cli CLI模式
     * @param string  $url api位置，若無設定則為預設的
     */
    public function __construct($key = null, $cli = true, $url = null)
    {
        ini_set("memory_limit", -1);
        $this->setOAuth($key);
        $this->cli = $cli;
        $this->url = $url ? $url : $this->url;
    }

    /**
     * 列出所有檔案
     * @param  int $parent_id 資料夾id
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
     * @param  int $item_id 檔案ID
     * @param  boolean $to_local 存到本地端位置
     * @param  string $local 本地端
     * @param  boolean $multi 多線程
     * @param  boolean $detail 印出詳細資訊
     * @param  int $thread 線程數
     * @return object   
     */
    public function download($item_id = 0, $to_local = false, $local = "", $multi = false, $detail = false, $thread = 5)
    {           
        $this->from_redirect_uri("$this->url/files/$item_id/download?oauth_token=$this->oauth", $to_local, $local, $multi, $detail = false, $thread);
    }

    /**
     * 刪除
     * @param  string  $file_ids  檔案ID，可以用","分開
     * @param  boolean $print_out 印出
     * @return object             
     */
    public function delete($file_ids = "", $print_out = false)
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
    public function create_folder($name = "default", $parent_id = 0)
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
    public function move($file_ids = "", $parent_id = 0, $print_out = false)
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
     * @param  int  $file_id   檔案ID
     * @param  string  $name      檔案新名字
     * @param  boolean $print_out 印出
     * @return object             
     */
    public function rename($file_id = 0, $name = null, $print_out = false)
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
    public function convert_mp4($id = 0, $print_out = false)
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
    public function get_mp4($id = 0)
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
     * @param  int $file_ids 檔案ID
     * @param  boolean $to_local 存到本地端位置
     * @param  string $local 本地端
     * @param  boolean $multi 多線程
     * @param  boolean $detail 印出詳細資訊
     * @param  int $thread 線程數
     * @return object             
     */
    public function zip_and_download($file_ids = "", $to_local = false, $local = "", $multi = false, $detail = false, $thread = 5)
    {
        $this->from_redirect_uri("$this->url/files/zip?file_ids=$file_ids&oauth_token=$this->oauth", $to_local, $local, $multi, $detail, $thread);
    }


    /**
     * 上傳檔案，Torrent會直接開始
     * @param  object  $file      檔案
     * @param  string  $filename  檔案名稱，無則為預設
     * @param  integer $parent_id 於...資料夾下
     * @param  boolean $print_out 印出
     * @return object             
     */
    public function upload($file = "", $filename = null, $parent_id = 0, $print_out = false)
    {
        if (is_file($file) || substr($file, 0, 4) == "http") {
            
            if (substr($file, 0, 4) == "http") {
                $tmp = $file;
                $file = tempnam("/tmp", "put.io-");
                $fn = fopen($file, "w+");
                $part = parse_url($tmp);
                if ($part["scheme"] == "http") {
                    $data_post = file_get_contents($tmp);
                    fwrite($fn, $data_post);
                } else {
                    $data_post = $this->request($tmp, null, null, true, $fn);
                }
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

    private function from_redirect_uri($request_uri, $to_local = false, $local = "", $multi = false, $detail = false, $thread = 5)
    {
        if ($to_local) {
            if (empty($local)) {
                $local = getcwd() . "/" . basename($request_uri);
            }

            $data = $this->request($request_uri);
            if (!is_array($data)) {
                $dom = new DOMDocument;
                $dom->loadHTML($data);
                $link = $dom->getElementsByTagName("a")->item(0);
                $fn = fopen($local, "w+");
                if (!$multi) {
                    $this->request($link->nodeValue, null, null, true, $fn); 
                } else {
                    $tmpfiles = array();
                    $size = $this->getSize($link->nodeValue);
                    $splits = range(0, $size, round($size / $thread));
                    $this->multi = curl_multi_init();
                    $parts = array();
                    for ($i = 0; $i < sizeof($splits); $i ++) {
                        $parts[$i] = tmpfile();
                        $x = ($i == 0 ? 0 : $splits[$i]+1);
                        $y = ($i == sizeof($splits)-1 ? $size : $splits[$i+1]);
                        $range = $x . "-" . $y;
                        $this->request($link->nodeValue, null, null, true, $parts[$i], $range, $detail); 
                        echo "Range from: " . $range . PHP_EOL;
                    }

                    $active = null;
                    do {
                        $status = curl_multi_exec($this->multi, $active);
                    } while ($status === CURLM_CALL_MULTI_PERFORM || $active);

                    curl_multi_close($this->multi);

                    foreach ($parts as $key => $value) {
                        fseek($value, 0, SEEK_SET);
                        $c = fread($value, $size);
                        fwrite($fn, $c);
                        fclose($value);
                        unset($c);
                    }

                    unset($this->multi, $parts);
                    $this->multi = null;
                }
                fclose($fn);
            } else {
                var_dump($data);
            }
        } else {
            header("Location: $request_uri");
        }
    }

    private function request($url, $data = null, $method = null, $files = false, $fn = null, $range = null, $detail= false)
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
        curl_setopt($ch, CURLOPT_BUFFERSIZE, 4096);
        curl_setopt($ch, CURLOPT_PROGRESSFUNCTION, function($download_size, $downloaded, $upload_size, $uploaded)
        {
            $progress = sprintf("%.2f", (($downloaded == 0 ? $uploaded : $downloaded) / ($download_size == 0 ? $upload_size + 1 : $download_size)) * 100);
            echo "\rRequest status: $progress%";
        });

        if (isset($method)) {
            curl_setopt($ch, CURLOPT_CUSTOMREQUEST, $method); 
            curl_setopt($ch, CURLOPT_POSTFIELDS, $data);
        }

        

        if ($files) {
            if (isset($range)) {
                curl_setopt($ch, CURLOPT_RETURNTRANSFER, false);
                curl_setopt($ch, CURLOPT_FOLLOWLOCATION, false); 
                curl_setopt($ch, CURLOPT_HEADER, false);
                if ($detail)
                    curl_setopt($ch, CURLOPT_VERBOSE, true);
                curl_setopt($ch, CURLOPT_FRESH_CONNECT, false);
                curl_setopt($ch, CURLOPT_FILE, $fn);
                curl_setopt($ch, CURLOPT_RANGE, $range);
                curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, 10);
                curl_setopt($ch, CURLOPT_BINARYTRANSFER, true);
                curl_setopt($ch, CURLOPT_USERAGENT, "Mozilla/5.0 (Windows NT 5.1) AppleWebKit/535.1 (KHTML, like Gecko) Chrome/14.0.835.29 Safari/535.1");
                curl_multi_add_handle($this->multi, $ch);
            } else {
                curl_setopt($ch, CURLOPT_FILE, $fn);
                curl_exec($ch);
                curl_close($ch);
            }
        } else {
            $result = curl_exec($ch);
            curl_close($ch);
            return $this->isJson($result) ? json_decode($result, true) : $result; 
        }
    }   

    private function isJson($string) {
        json_decode($string);
        return (json_last_error() == JSON_ERROR_NONE);
    }

    private function getSize($url) {
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, false);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
        curl_setopt($ch, CURLOPT_NOBODY, true);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_HEADER, true);
        curl_setopt($ch, CURLOPT_FOLLOWLOCATION, true);
        $h = fopen('header', "w+");
        curl_setopt($ch, CURLOPT_WRITEHEADER, $h);  

        $data = curl_exec($ch);
        curl_close($ch);    

        if (preg_match('/Content-Length: (\d+)/', $data, $matches)) {
            return $contentLength = (int)$matches[1];
        }
        else 
            return false;
    }
}
?>