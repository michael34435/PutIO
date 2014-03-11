<?php

require_once "PutIO.php";

class KTXP
{

    private $url = "http://bt.ktxp.com/sort-1-%s.html";
    private $fn = null;
    private $data = array();
    private $title = array(".*Kill.*BIG5.*");
    private $publisher = array("极影");
    private $prefix = "http://bt.ktxp.com";
    private $filename = "ktxp.csv";
    private $meta = "<meta http-equiv='Content-Type' content='text/html; charset=utf-8'>";
    private $putio = null;
    private $putio_files = array();

    public function __construct()
    {
        $this->putio = new PutIO("");
        $this->fn = fopen($this->filename, "a+");
        $this->get_status();
    }

    public function __destruct()
    {
        fclose($this->fn);
        unset($this->data, $this->putio);
    }

    public function make_search_sentence($title, $publisher)
    {
        $this->title = $title;
        $this->publisher = $publisher;
    }

    public function get_status()
    {
        while ($data = fgetcsv($this->fn)) {
            if (isset($data[0])) {
                $this->data[$data[0]] = array();
                $this->data[$data[0]]["status"] = $data[1];
                $this->data[$data[0]]["name"] = $data[2];
                $this->data[$data[0]]["putio"] = $data[3];
            }
        }

        $this->putio_files = $this->putio->list_file();
    }

    public function parse($page = 1)
    {   

        for ($i = 0; $i < $page; $i++) { 
            $data = file_get_contents(sprintf($this->url, ($i+1)));
            $dom = new DOMDocument;
            @$dom->loadHTML($data);
            $tr = $dom->getElementsByTagName("tr");
            foreach ($tr as $key => $value) {

                if (($publisher = $this->match_publisher($value)) == null) {
                    continue;
                }

                if (($r = $this->match_title($value)) == null) {
                    continue;
                }

                if (!isset($this->data[$r])) {
                    $this->data[$r] = array();
                    $this->data[$r]["status"] = 0;
                }

                if (isset($this->data[$r]) && $this->data[$r]["status"] == 0) {

                    $obj = $this->putio->upload($r);
                    
                    if (isset($obj["status"]) && $obj["status"] == "OK") {
                        $this->data[$r]["name"] = $obj["transfer"]["name"];
                        $this->data[$r]["putio"] = 0;

                        // handle downloading
                        $this->data[$r]["status"] = 1;
                    } else {
                        $this->data[$r]["name"] = "undefined";
                        $this->data[$r]["putio"] = 0;
                        continue;
                    }
                }

                if (isset($this->data[$r]) && $this->data[$r]["status"] == 0) {
                    unset($this->data[$r]);
                }
            }

            $this->renew();
            unset($data, $dom, $tr, $obj);
        }
    }

    public function renew()
    {
        file_put_contents($this->filename, "");
        foreach ($this->data as $key => $value) {
            fputcsv($this->fn, array($key, $value["status"], $value["name"], $value["putio"]));
        }
    }

    public function request_download()
    {
     
        $this->get_status();

        $tmp = array();
        foreach ($this->data as $key => $value) {
            if ($value["putio"] == 0) {
                $id = $this->search_id($value["name"]);
                
                if (!$id)
                    continue;

                $tmp[] = $id;

                // modified
                $this->data[$key]["putio"] = 1;
            }
        }
        
        $this->renew();
        $queues = implode(",", $tmp);

        if (!empty($queues)) {
            $file = "I:\\" . time() . ".zip";
            $this->putio->zip_and_download($queues, true, $file, true, true, 10);
            $this->putio->delete($queues);
            return $file;
        } else {
            return false;
        }
    }

    private function search_id($name)
    {
        foreach ($this->putio_files["files"] as $key => $value) {
            if ($value["name"] == $name) {
                return $value["id"];
            }
        }

        return false;
    }

    private function match_publisher($node)
    {
        $last = $this->get_last_node($node, "td");

        $matchAry = $this->publisher;

        foreach ($matchAry as $key => $value) {
            if (preg_match("/" . $value . "/", isset($last->nodeValue) ? $last->nodeValue : null)) {
                return $last->nodeValue;
            }
        }
        return null;
    }

    private function get_last_node($node, $name, $last = 1)
    {
        $dom = new DOMDocument;
        $nodes = $this->inner_xml($node);
        @$dom->loadHTML($nodes);
        $td = $dom->getElementsByTagName($name);
        $last = $td->item($td->length-$last);
        return $last;
    }

    private function match_title($node)
    {
        $matchAry = $this->title;
        $dom = new DOMDocument;
        $nodes = $this->inner_xml($node);
        @$dom->loadHTML($nodes);
        $td = $dom->getElementsByTagName("td");

        foreach ($td as $key => $value) {
            if ($value->getAttribute("class") == "ltext ttitle") {
                foreach ($matchAry as $key => $value1) {
                    if (preg_match("/" . $value1 . "/", $value->nodeValue)) {
                        $dom1 = new DOMDocument;
                        $a_n = $this->inner_xml($value);
                        @$dom1->loadHTML($a_n);
                        foreach ($dom1->getElementsByTagName("a") as $url) {
                            return $this->prefix . $url->getAttribute("href");
                        }
                    }
                }
            }
        }

        return null;
    }

    private function inner_xml($node) 
    { 
        $doc  = $node->ownerDocument; 
        $frag = $doc->createDocumentFragment(); 
        foreach ($node->childNodes as $child){ 
            $frag->appendChild($child->cloneNode(TRUE)); 
        } 
        return $this->meta . $doc->saveXML($frag); 
    } 
}
?>