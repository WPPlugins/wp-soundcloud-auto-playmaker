<?php
  /*
   Plugin Name: Soundcloud Auto Playmaker
   Plugin URI: http://www.fergalmoran.com/code/wp-soundcloud-auto-playmaker
   Description: Automatically generates soundcloud players for your site. 
   Version: 0.6.1
   Author: Fergal Moran
   Author URI: http://fergalmoran.com/
  /* ----------------------------------------------*/
   ###Class: FMSoundCloudWidget

require_once('_scXmlParser.php');
require_once('_scAdmin.php');


class sc_PlaymakerWidget extends WP_Widget {

    private $pluginDir;
    function _getData($url, $profilelink, $tracklink, $color){
        $parser = new scXmlParser();
        return $parser->GetPlayerFeedWithOptions($url, $profilelink, $tracklinki, $color);
    }
    function _getDataCount($url){
        $parser = new scXmlParser();
        return $parser->GetPlayerCount($url);
    }
    function sc_PlaymakerWidget() {
        $widget_ops = array(
            'classname' => 'widget_soundcloud_playmaker', 
            'description' => __('Automatically generate soundcloud players') );
        $control_ops = array(
            'width' => 300, 
            'height' => 300);
        $this->pluginDir = 
             preg_replace('|includes/\z|', '', WP_PLUGIN_URL.'/'.str_replace(basename( __FILE__),"",plugin_basename(__FILE__)));
        $this->WP_Widget('soundcloud-playmaker', __('Soundcloud Auto Playmaker'), $widget_ops, $control_ops);
    }
    function widget($args, $instance){
        extract($args, EXTR_SKIP);
        //need to read the global settings first.
        $globalOptions = sc_PlaymakerAdmin::GetOptions();
        $color = preg_replace('|#|','',strip_tags($globalOptions['color']));
        
        $title = empty($instance['title']) ? '' : apply_filters('widget_title', $instance['title']);
        $user = empty($instance['user']) ? '&nbsp' : apply_filters('widget_user', $instance['user']);
        $maxtracks = empty($instance['maxtracks']) ? '&nbsp' : apply_filters('widget_maxtracks', $instance['maxtracks']);
        $listtype = empty($instance['listtype']) ? '&nbsp' : apply_filters('widget_type', $instance['listtype']);
        $feedtype = empty($instance['feedtype']) ? '&nbsp' : apply_filters('widget_type', $instance['feedtype']);
        $profilelink = empty($instance['profilelink']) ? '0' : apply_filters('widget_type', $instance['profilelink']);
        $tracklink = empty($instance['tracklink']) ? '0' : apply_filters('widget_type', $instance['tracklink']);

        $curOffset = 0;
        $preCountCallAddress = "http://api.soundcloud.com/$listtype/$user/$feedtype";
        $startHtml = "\n" . '<!--Soundcloud Widget by Fergal Moran (http://www.fergalmoran.com) -->' . "\n";
        $pagerScript = "\n" . '<!--Begin pager script (if needed) -->' . "\n";
        $count = round($this->_getDataCount($preCountCallAddress) / $maxtracks);
        if (isset($maxtracks)){
            $preCallAddress = "$preCountCallAddress?limit=$maxtracks&offset=$offset";
            $proxyUrl = $this->pluginDir . '/includes/AjaxProxy.php';
            $pagerScript = <<<END_PAGER
            <script type="text/javascript">
                            //<![CDATA[ 
                              limit         =   $maxtracks;
                              listtype      =   "$listtype";
                              feedtype      =   "$feedtype";
                              user          =   "$user";
                              color         =   "$color";
                              tracklink     =   $tracklink;
                              profilelink   =   $profilelink;
                              proxyUrl      =   "$proxyUrl";
                             //]]> 
                            </script> 
                            <div id="cur-offset" style="display: none">0</div>
                            <div id="pagecount" style="display: none">$count</div>
END_PAGER;
        } 
        //Really need to to a non-ajax pre call  here so we can get
        //all the variables such as number of pages setup for the script
        $data = $this->_getData($preCallAddress, $profilelink, $tracklink, $color);

        echo $before_widget;
        
        echo $pagerScript;
        echo $startHtml; 
        echo $pagerHtml; 

        if (!empty($title)){
            echo $before_title . "\n" . $title . "\n" . $after_title , "\n";
        }
        /*Begin buidling up the actual HTML to be output */

        $html = 
            '<div id="playerarea">' . "\n"
            . $data . "\n"
            . '</div>' . "\n"
            . '<div id="ajax-loader" style="visibility: hidden"><img src="' . "\n"
            . $this->pluginDir . '/images/ajax-loader.gif" alt="Loading..."/></div>'. "\n"
            . '<div id="pager"></div>'. "\n";

        echo $html;
        echo $after_widget;
    }
    function update($new_instance, $old_instance){
        $instance = $old_instance;
        
        $instance['title'] = strip_tags($new_instance['title']);
        $instance['user'] = strip_tags($new_instance['user']);
        $instance['maxtracks'] = strip_tags($new_instance['maxtracks']);
        $instance['listtype'] = strip_tags($new_instance['listtype']);
        $instance['feedtype'] = strip_tags($new_instance['feedtype']);
        $instance['profilelink'] = $new_instance['profilelink'] ? 1 : 0;
        $instance['tracklink'] = $new_instance['tracklink'] ? 1 : 0;

        return $instance;
    }


    function form($instance) {
        $instance = $this->_parseInstance($instance);

        $title = htmlspecialchars($instance['title']);
        $user = htmlspecialchars($instance['user']);
        $maxtracks = htmlspecialchars($instance['maxtracks']);
        $listtype = htmlspecialchars($instance['listtype']);
        $feedtype = htmlspecialchars($instance['feedtype']);
        $profilelink = intval($instance['profilelink']);
        $tracklink = intval($instance['tracklink']);
        //I really dislike this way of doing things php NOWDOC looked kind of better
        //but I read that there were problems with it and not to use it
        //I really think this just looks and feels wrong, if anyone ever sees this code
        //and can suggest a better way then please get in touch
?>
        <p style="padding: 0.5em; background-color: rgb(170, 170, 0); color: rgb(255, 255, 255); font-weight: bold;">
            Feed Properties
        </p>
        <fieldset>
            <p style="align: right">
                    <label for="<?php echo $this->get_field_id('title'); ?>"><?php echo __('Header for tracks:');?></label>
                    <input  style="width: 250px;" 
                            id="<?php echo $this->get_field_id('title'); ?>" 
                            name="<?php echo $this->get_field_name('title'); ?>" 
                            type="text" 
                            value="<?php echo $title; ?>"/>
            </p>
            <p style="align: right">
                    <label for="<?php echo $this->get_field_id('user'); ?>"><?php echo __("User/Group Name:");?></label>
                    <input  style="width: 250px;" 
                            id="<?php echo $this->get_field_id('user'); ?>" 
                            name="<?php echo $this->get_field_name('user'); ?>" 
                            type="text" 
                            value="<?php echo $user; ?>"/>
            </p>
            <p style="align: left">
                    <label for="<?php echo $this->get_field_id('maxtracks'); ?>"><?php echo __("No. Tracks to display:");?></label>
                    <input  style="width: 80px;" 
                            id="<?php echo $this->get_field_id('maxtracks'); ?>" 
                            name="<?php echo $this->get_field_name('maxtracks'); ?>" 
                            type="text" 
                            value="<?php echo $maxtracks; ?>"/>
            </p>
        </fieldset>
        <fieldset>
        <ul>
            <li style="float: left">
                <label for="<?php echo $this->get_field_id('listtype'); ?>"><?php echo __('List Type:');?></label>
                <select id="<?php echo $this->get_field_id('listtype'); ?> " 
                    name=" <?php echo $this->get_field_name('listtype'); ?>">
                <option value="groups"
                        <?php echo $listtype == 'groups' ? 'selected="selected"' : ''; ?>>Group
                </option>
                <option value="users"
                        <?php echo $listtype == 'users' ? 'selected="selected"' : ''; ?>>User
                </option>
                </select>
            </li>
            <li style="float: right">
                <label for="<?php echo $this->get_field_id('feedtype'); ?>"><?php echo __('Feed Type:'); ?></label>
                <select id="<?php echo $this->get_field_id('feedtype'); ?> " 
                        name=" <?php echo $this->get_field_name('feedtype'); ?>">
                    <option value="tracks"
                            <?php echo $feedtype == 'tracks' ? 'selected="selected"' : ''; ?>>Tracks
                    </option>
                    <option value="favourites"
                            <?php echo $feedtype == 'favourites' ? 'selected="selected"' : ''; ?>>Favourites
                    </option>
                </select>
            </li>
        </ul>
        </fieldset>

        <p style="padding: 0.5em; background-color: rgb(170, 170, 0); color: rgb(255, 255, 255); font-weight: bold;">
            Feed Properties
        </p>
        <fieldset>
        <ul>
            <li style="float: left">
                <label for="<?php echo $this->get_field_id('profilelink'); ?>">Profile link:</label>
                <input  class="checkbox"  
                        type="checkbox" 
                        <?php checked($profilelink, true) ?>
                        id="<?php echo $this->get_field_id('profilelink'); ?>"
                        name="<?php echo $this->get_field_name('profilelink'); ?>" />
            </li>
            <li style="float: right">
                <label for="<?php echo $this->get_field_id('tracklink'); ?>">Track link:</label>
                <input  class="checkbox"  
                        type="checkbox" 
                        <?php checked($tracklink, true) ?>
                        id="<?php echo $this->get_field_id('tracklink'); ?>"
                        name="<?php echo $this->get_field_name('tracklink'); ?>" />
            </li>
        </ul>
        </fieldset>
<?php 
    }
    function _parseInstance($instance){
        return wp_parse_args(
            (array)$instance,
            array(
                'title'=>'My new soundcloud list', 
                'user'=>'**username**',
                'maxtracks'=>'5',
                'listtype'=>'users', 
                'feedtype'=>'tracks', 
                'profilelink'=>true, 
                'tracklink'=>true, 
        ));
    }
}
?>
