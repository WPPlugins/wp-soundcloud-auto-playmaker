<?php

//Again this is probably not the neatest way of doing this but not sure
//How to have a class method as a POST Handler in PHP so will 
//Stick a handler here to intercept and pass off to class
//

class sc_PlaymakerAdmin{

    //store the options in an array... was looking through the wp_options
    //database and some plugins have a crazy amount of stuff in here
    //this seems a more sensible way of doing it and should contribute in a small
    //way to not slowing down people's sites
    private static $_options;

    private static function _initDefaultOptions(){
        self::$_options = array(
                   'color' => '#FF7700',
                   'allowinpost' => '1',
                   'allowhotlink' => '1',
                   'allowpodcast' => '1');
    }

    public static function GetOptions(){
        self::_readOptions();
        return self::$_options;
    }
    private static function _initAdmin(){
        add_option('sc-playmaker-global-options', $options);
    }
    private static function _readOptions(){
        if (!(self::$_options = get_option('sc-playmaker-global-options'))){
            self::_initDefaultOptions();
            add_option('sc-playmaker-global-options', self::$_options);
        }
    }
    public static function SaveOptions($postArray){
        self::$_options['color'] = isset($postArray['color']) ? $postArray['color'] : '';
        self::$_options['allowinpost'] = isset($postArray['allowinpost']) ? '1' : '0';
        self::$_options['allowhotlink'] = isset($postArray['allowhotlink']) ? '1' : '0';
        self::$_options['allowpodcast'] = isset($postArray['allowpodcast']) ? '1' : '0';
        update_option('sc-playmaker-global-options', self::$_options);
    }
    public static function CreateOptionsMenu(){
        self::_readOptions();
?>
        <div class="wrap">
        <div id="icon-options-general" class="icon32"></div>
            <h2>Configure global options for all default players</h2>
            <form method="post" action="">
                <input type="hidden" name="sc-global-save" value="1" />
                <p style="padding: 0.5em; background-color: rgb(170, 170, 0); color: rgb(255, 255, 255); font-weight: bold;">
                    Player Appearance
                </p>
                <p style="align: left">
                    <label for="color">Player colour: </label>
                    <input type="text" id="color" name="color" value="<?php echo self::$_options['color']; ?>" />
                    <div id="colorpicker"></div>
                </p>
                <p style="padding: 0.5em; background-color: rgb(170, 170, 0); color: rgb(255, 255, 255); font-weight: bold;">
                    Player types allowed
                </p>
                <fieldset class="options">
                <ul>
                    <li>
                        <input  type="checkbox" 
                                value="<?php echo self::$_options['allowinpost']; ?>" 
                                <?php echo self::$_options['allowinpost'] == '1' ? 'CHECKED' : ''; ?>
                                name="allowinpost" />Allow in posts</input>
                    </li>
                    <li>
                        <input  type="checkbox" 
                                value="<?php echo self::$_options['allowhotlink']; ?>" 
                                <?php echo self::$_options['allowhotlink'] == '1' ? 'CHECKED' : ''; ?>
                                name="allowhotlink" />Allow hotlinking</input>
                    </li>
                    <li>
                        <input  type="checkbox" 
                                value="<?php echo self::$_options['allowpodcast']; ?>" 
                                <?php echo self::$_options['allowpodcast'] == '1' ? 'CHECKED' : ''; ?>
                                name="allowpodcast" />Allow podcasting<posts/input>
                    <li>
                </ul>
                </fieldset>
                <p class="submit">
                <input  class="button-primary" 
                        type="submit" 
                        name="Save" 
                        value="<?php _e("Save Options"); ?>" id="submitbutton" />
                </p>
            </form>
        </div>
        
<?php
    }
}


?>
