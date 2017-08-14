<?php
class scXmlParser{

    protected $_xmlStream;
    
    protected function _processXmlErrors(){
        $errors = libxml_get_errors();
        foreach ($errors as $error){
            _displayXmlError($error, $xml);
        }
        libxml_clear_errors();
    }

    protected function _displayXmlError($erro, $xml){
        $return = $xml[$error->line -1] . "<br />\n";
        $return .= str_repeat('-', $error->column) . "^\n";

        switch($error->level){
        case LIBXML_ERR_WARNING:
            $return .= "Warning $error->code: ";
            break;
        case LIBXML_ERR_ERROR:
            $return .= "Error $error->code: ";
            break;
        case LIBXML_ERR_FATAL:
            $return .= "Fatal $error->code: ";
            break;
        }
        return $return;
    }

    protected function LoadXmlStream($uri){
        
        $this->pluginDir = dirname(__FILE__); 
        
        $this->_xmlStream = simplexml_load_file($uri);
        #var_dump($xmlStream);
        if ($this->_xmlStream == FALSE){
            echo $this->_processXmlErrors();
            return;
        }
        $tracks = $this->_xmlStream->documentElement;
        $track = $tracks->childNodes;
    }
    public function CreateRssFeed($uri){
        LoadXmlStream($uri);
    }

    public function GetPlayerCount($uri){
        $this->LoadXmlStream($uri);
        return $this->_xmlStream->track->count();
    }

    public function GetPlayerFeed($uri){
        return $this->GetPlayerFeedWithOptions($uri, 0, 0);
    }

    public function GetPlayerFeedWithOptions($uri, $profilelink, $tracklink, $color){
        $this->LoadXmlStream($uri);
        $id = 0;
        $playerTemplate = file_get_contents($this->pluginDir . '/templates/widget_playerDisplay.html');
        $linkTemplate = file_get_contents($this->pluginDir . '/templates/widget_linkDisplay.html');
        $playerCode = '';
        foreach($this->_xmlStream->track as $track){
            $playerHtml = '';
            $encodedUrl = urlencode($track->{'permalink-url'});
            $plainUrl  = ($track->{'permalink-url'});
            $trackTitle = $track->title;
            $userProfile = $track->user->{'permalink-url'};
            $userName = $track->user->username;
            //read the html from the templates and perform the replacements..
            $playerHtml .=  str_replace('%%COLOR%%', $color, str_replace('%%ENCODEDURL%%', $encodedUrl, $playerTemplate));
            if ($tracklink || $profilelink){
                $playerHtml .= '<div id="soundcloud-user-enclosure-%%ID%%">' . "\n";
                if ($tracklink)
                    $playerHtml .= str_replace('%%DESCRIPTION%%', $trackTitle, str_replace('%%URL%%', $plainUrl, $linkTemplate));
                if ($profilelink)
                    $playerHtml .= str_replace('%%DESCRIPTION%%', $userName, str_replace('%%URL%%', $userProfile, $linkTemplate));
                $playerHtml .= '</div>' . "\n";
            }

            $playerCode .= str_replace('%%ID%%', $id, $playerHtml);
            $id++;
        }
        return $playerCode; 
    }
}
